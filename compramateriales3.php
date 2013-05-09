<?php
include ("./common.php");
include ("./private/globals.php");
session_start();
check_security(1);

$user_id = get_session("cliID");
$jue_id = get_param("id");
$sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
	"t.jue_id as id from tb_juegos t where t.jue_id=$jue_id ".
	"  and t.jue_sw='A' " ;
	$db->query($sSQL);
	$next_record = $db->next_record();
	$per_inicio = $db->f("inicio");
	$per_cantidad = $db->f("cantidad");
	$jue_id = $db->f("id");
	$per_in = $per_inicio;
	
	
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$jue_id and per_compra='A' limit $per_cantidad");
$per_periodo = get_param("per_periodo");
	
$maxPeriodo2 = get_db_value("select max(per_periodo) from tb_periodos where per_jue_id=$jue_id and per_compra='A'"); //modif
    
if (!$per_periodo) $per_periodo=1;
	
$FormAction = get_param("FormAction");
//acá va el periodo
if ($FormAction=='update') update( $jue_id, $user_id, $per_periodo);
$columnas =16;
//acá va el periodo
function update ($jue_id, $user_id, $per_periodo)
{
	global $db;
	$fldCantidadPedido = get_param("cantPedido");
	$fldMesId = get_param("mesa");
	$fldIncotermsTra = get_param("incoterm");
	$fldgasto = get_param("gasto");
	$cantArray = count($fldCantidadPedido);
	if($cantArray>0)
	{
		$sSQL = "delete from tb_compras2 where com_jue_id= ".tosql($jue_id, "Number")." and com_usu_id=". tosql($user_id, "Number")." and com_per_id=". tosql($per_periodo, "Number");
		$db->query($sSQL);
		for($x=0;$x<$cantArray;$x++)
		{
		  if($fldIncotermsTra[$x]){
			$sSQL="insert into tb_compras2 values(null, ". tosql($fldMesId[$x], "Number") .", ". tosql($fldCantidadPedido[$x], "Number") .", ". tosql($fldgasto[$x], "Number") . ", ". tosql($fldIncotermsTra[$x], "Number") .", ". tosql($user_id, "Number") .", ". tosql($jue_id, "Number") .", ". tosql($per_periodo, "Number") .")";
			$db->query($sSQL);
			}
		}
	}
	
}

function fMontoBasico($a,$b,$c)
{
	if(is_number($a) && is_number($b) && is_number($c)) return $a*$b*$c;
	else return 0;
}
function fFactorIncoterms($fldIncoterms, $fldTransporte, $jue_id)
{
	global $db;
	if($fldIncoterms)
	{
		$factorTra = get_db_value("select int_factorTra from tb_incotran where int_inc_id=".tosql($fldIncoterms, "Number") . " and int_tra_id=".tosql($fldTransporte, "Number") . " and int_jue_id=".tosql($jue_id, "Number"));
		//echo "select int_factorTra from tb_incotran where int_inc_id=".tosql($fldIncoterms, "Text") . " and int_jue_id=".tosql($jue_id, "Text");
		return $factorTra;
	}else return 0;
}

//acá va el periodo
function fDescuento($proveedor, $fldMontoBasico, $user_id, $jue_id, $per_periodo)
{
	$porcentajeDesc = get_db_value("select des_porcentaje from tb_descuentos where des_jue_id=". tosql($jue_id, "Numer")." and des_pro_id = ". tosql($proveedor, "Numer"). " and des_usu_id= ". tosql($user_id, "Numer"). " and des_per_id= ". tosql($per_periodo, "Numer"));
	return round($porcentajeDesc * $fldMontoBasico/100);
}

function fMontoTotal($fldMontoBasico, $fldFactorIncoterms, $fldDescuento, $fldgasto)
{
	return (($fldMontoBasico - $fldDescuento) * $fldFactorIncoterms + $fldgasto);
}

function fTiempoLlegada($tiempoMesa, $fldIncoterms,  $jue_id)
{
	global $db;
	if($fldIncoterms)
	{
		$tiempoTra = get_db_value("select int_tiempoTra from tb_incotran where int_id=".tosql($fldIncoterms, "Text"). " and int_jue_id=".tosql($jue_id, "Number"));
		//$tiempoSuministro = get_db_value("select sum_time from tb_suministro where sum_id=".tosql($fldSuministro, "Text"));
		return ($tiempoTra + $tiempoMesa);
	}else return 0;
}
//acá va el periodo
function fArchivoSalida ($SumMontoTotal, $ProductoSumMontoTotal, $DescuentoTotal, $TiempoTotal, $ProductoTiempoMontoTotal, $pro_id, $user_id, $jue_id, $per_periodo, $mes_id )
{
		global $db1;
		
		$sSQL = "delete from tb_totalcompras where tot_jue_id= ".tosql($jue_id, "Number")." and tot_usu_id=". tosql($user_id, "Number")." and tot_per_id=". tosql($per_periodo, "Number")." and tot_mes_id=". tosql($mes_id, "Number");
		$db1->query($sSQL);
		
		$sSQL = "insert into tb_totalcompras values(null, ". tosql($SumMontoTotal,"Number") .", ". tosql($ProductoSumMontoTotal,"Number") . ",". tosql($DescuentoTotal,"Number") .", ". tosql($TiempoTotal,"Number") . ", ". tosql($ProductoTiempoMontoTotal,"Number") . ", ". tosql($pro_id,"Number") . ", ". tosql($user_id, "Number").", ".tosql($jue_id, "Number").", ".tosql($per_periodo, "Number").", ". tosql($mes_id, "Number").")";
		$db1->query($sSQL);

}
?>
<html>
<head>
<title>siges</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
<link href="facebox/facebox.css" type="text/css" rel="stylesheet">
<script src="js/ajaxlib.js" language="javascript" type="text/javascript"></script>
<script src="js/loadMenu.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="facebox/facebox.js" language="javascript" type="text/javascript"></script>
</head>
<body class="PageBODY">
<p>
<table width="100%" border="0">
<tr>
<td width="50%" align="right" >Reporte Salida <a href="comprasReporte.php?jue_id=<?=$jue_id?>&per_id=<?=$per_periodo?>&" title="Reporte Salida"><img src="./image/excel.jpg" alt="Reporte resumen" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="10%" align="center">
        
    </td>
</tr>

</table>


 <form method="POST" action="compramateriales3.php" name="valoresRecord">
    <br>
  <br>
  Seleccionar Gesti&oacute;n: 
  <select name="per_periodo" onChange="submit();">
  <?php
  foreach($arrayPeriodo as $key=>$value)
  {
	  if($key==$per_periodo) $selValue="Selected"; else $selValue="";
  ?>
  <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
  <?
  }
  ?>
  </select>
  <br>
  <br>
  <table class="Grid" cellspacing="0" cellpadding="0" border="1">
     <tr class="Caption2">
      <td>Producto</td>
      <td>Unidades Pedido</td>
      <td>Proveedor</td>
      <td>Incoterm</td>      
      <td>Precio unitario</td>
      <td>Tiempo de entrega</td>      
      <td>Cantidad de pedidos</td>
      <td>Transporte</td>
      <td>Gasto realizado</td>
      <td>Monto B&aacute;sico</td>
      <td>Factor Transporte &amp; Aduana</td>
      <td>Tiempo Transporte &amp; Aduana</td>
      <td>Descuento Negociado</td>
      <td>Monto Total (M)</td>
      <td>Tiempo de llegada</td>
      <td>Unidades de pedido compradas</td>
     </tr>
     <?php
	 	$sSQL = "select * from tb_mesaproveedores where mes_jue_id=". tosql($jue_id,"Number"). " order by mes_id asc";
		$db->query($sSQL);
		if($db->num_rows())
		{
		$SumMontoTotal = 0;
		$ProductoSumMontoTotal = 0;
		
		while($db->next_record())
		{

			$sSQL="select int_tra_id from tb_incotran where int_jue_id=$jue_id and int_inc_id= ".tosql($db->f("mes_inc_id"),"Number");
			$db2->query($sSQL);
			$sTraId ="";
			if($db2->num_rows())
			{
			//echo $db2->num_rows()."num:";
				$sTraId ="";
				while($db2->next_record())
				{
					
					$sTraId .= $db2->f("int_tra_id") .",";
				}
				//echo $sTraId;
			}
			$sTraId = substr($sTraId,0,strlen($sTraId)-1);
			//echo $sTraId. "<br>";
			$arrayIncoterms = null;
			if(strlen($sTraId)>0)
			{
				$arrayIncoterms = db_fill_array("select tra_id, tra_name from tb_transporte where tra_jue_id=$jue_id and tra_id in(".$sTraId.")");
			}
			$fldCompra = get_db_value("select pro_name from tb_productos2 where  pro_id = ".tosql($db->f("mes_com_id"), "Number"));
			$fldProveedores = get_db_value("select pro_name from tb_proveedor where  pro_id = ".tosql($db->f("mes_pro_id"), "Number"));
			$fldcantidadPedido = get_db_value("select com_cantidad from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number")." and com_per_id=".tosql($per_periodo, "Number"));
			$fldcantidadPedido = ($fldcantidadPedido == NULL)?0:$fldcantidadPedido;
			
			$fldincoterms = get_db_value("select inc_name from tb_incoterms where inc_id = ".tosql($db->f("mes_inc_id"), "Number")." and inc_jue_id=".tosql($jue_id, "Number"));
			
			$fldgasto = get_db_value("select com_gasto from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number")." and com_per_id=".tosql($per_periodo, "Number"));
			$fldgasto = ($fldgasto == NULL)?0:$fldgasto;
			
			$fldIncoterms = get_db_value("select com_int_id from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number")." and com_per_id=".tosql($per_periodo, "Number"));
			if($fldIncoterms)
			{
			$fldTiempoTransporte = get_db_value("select int_tiempoTra from tb_incotran where int_inc_id=".tosql($db->f("mes_inc_id"), "Number"). " and int_tra_id=".tosql($fldIncoterms, "Number"). " and int_jue_id=".tosql($jue_id, "Number"));
			
			//echo "select int_tiempoTra from tb_incotran where int_inc_id=".tosql($db->f("mes_inc_id"), "Number"). " and int_tra_id=".tosql($fldIncoterms, "Number"). " and int_jue_id=".tosql($jue_id, "Number");
			$fldTiempoLlegada = $fldTiempoTransporte + $db->f("mes_tiempo");
			}else {
			 $fldTiempoTransporte=0;
			 $fldTiempoLlegada =0;
			 }
			//echo $fldTiempoTransporte;
			//if(!$fldTiempoTransporte) $fldTiempoTransporte = 0;
			$fldMontoBasico= fMontoBasico($fldcantidadPedido, $db->f("mes_pedido"), $db->f("mes_precio") );
			if($fldIncoterms){
		    $fldFactorIncoterms= fFactorIncoterms($db->f("mes_inc_id"), $fldIncoterms, $jue_id);
			}else $fldFactorIncoterms=0;
			//echo $fldFactorIncoterms."--";
			
            $fldDescuento= fDescuento($db->f("mes_pro_id"),  $fldMontoBasico, $user_id, $jue_id, $per_periodo);
            $fldMontoTotal= fMontoTotal($fldMontoBasico, $fldFactorIncoterms,  $fldDescuento, $fldgasto);
            //$fldTiempoLlegada= fTiempoLlegada($db->f("mes_tiempo"), $fldIncoterms, $jue_id);
			
			$SumMontoTotal = $fldMontoTotal;
			
			$DescuentoTotal = $fldDescuento;
			$TiempoTotal = $fldTiempoLlegada;
			
			$ProductoTiempoMontoTotal = $fldTiempoLlegada * $fldMontoTotal;
					
			$ProductoSumMontoTotal = ($fldcantidadPedido * $db->f("mes_pedido"));	
			
			fArchivoSalida ($SumMontoTotal, $ProductoSumMontoTotal,  $DescuentoTotal, $TiempoTotal, $ProductoTiempoMontoTotal, $db->f("mes_com_id"),  $user_id, $jue_id, $per_periodo, $db->f("mes_id"));
			
			$fldUnidadesCompradas= $db->f("mes_pedido") * $fldcantidadPedido;
			 ?>
			 <tr class="Row">
				  <td><?= $fldCompra?></td>
				  <td><?= number_format($db->f("mes_pedido"),0,".",",")?></td>
				  <td><?= $fldProveedores?></td>
                  <td><?= $fldincoterms?></td>
				  <td><?= number_format($db->f("mes_precio"),0,".",",")?></td>
                  <td><?= $db->f("mes_tiempo")?></td>
				  <td><input name="cantPedido[]" value="<?=$fldcantidadPedido?>" type="text" size="4" class="textoCaja"/> </td>
                  <td><select name="incoterm[]" onChange="loadOption(<?=$jue_id?>,<?=$db->f("mes_inc_id")?>, this.value);">

                                      	<option value="">Seleccione</option>
 										  <?php
										  	foreach($arrayIncoterms as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldIncoterms) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select><input name="mesa[]" value="<?=$db->f("mes_id")?>" type="hidden"/>
                  </td>
                  
                 
                 <td><input name="gasto[]" value="<?=$fldgasto?>" type="text" size="3"></td>
                 <td><?=number_format($fldMontoBasico,0,".",",")?></td>
                 <td><?=$fldFactorIncoterms?></td>
                 <td><?=$fldTiempoTransporte?></td>
                 <td><?=number_format($fldDescuento,0,".",",")?></td>
                 <td><?=number_format($fldMontoTotal,0,".",",")?></td>
                 <td><?=$fldTiempoLlegada?></td>
                 <td><?=number_format($fldUnidadesCompradas,0,".",",")?></td>
			 </tr>
			 <?php
	 	}
		
	 }else{
	 ?>
     <tr class="Row">
      <td class="ClearFieldCaptionTD" colspan="<?=$columnas?>">No hay registros</td>
     </tr>
     <?php
	 }
	 ?>
     
     <tr class="Row">
     <td colspan="<?=$columnas?>" align="right">
       <input type="hidden" value="" name="FormAction"/>
      <input type="hidden" value="<?=$jue_id?>" name="id"/>
      
      <input type="hidden" name="FormName" value="valoresRecord"/>
      <?php
	  $valCheckedCompras = get_db_value("select coh_activo from tb_comprashabilita where coh_jue_id=".tosql($jue_id, "Number")." and coh_per_id=". tosql($per_periodo, "Number"));
	  if($valCheckedCompras==1)
	  {
	  ?>
      <input class="ClearButton" type="submit" value="Calcular" onClick="document.valoresRecord.FormAction.value = 'update';"/>
      <?php
	  }
	  ?>
      &nbsp;&nbsp;
      
      </td>
     </tr>
 </table>
  </form>
<br>
</body>
</html>
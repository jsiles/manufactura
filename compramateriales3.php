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

if ($FormAction=='update') update( $jue_id, $user_id);
$columnas =14;

function update ($jue_id, $user_id)
{
	global $db;
	$fldCantidadPedido = get_param("cantPedido");
	$fldMesId = get_param("mesa");
	$fldIncotermsTra = get_param("incoterm");
	$fldSuministro = get_param("suministro");
	$cantArray = count($fldCantidadPedido);
	if($cantArray>0)
	{
		$sSQL = "delete from tb_compras2 where com_jue_id= ".tosql($jue_id, "Number")." and com_usu_id=". tosql($user_id, "Number");
		$db->query($sSQL);
		for($x=0;$x<$cantArray;$x++)
		{
		  if($fldIncotermsTra[$x]&&$fldSuministro[$x]){
			$sSQL="insert into tb_compras2 values(null, ". tosql($fldMesId[$x], "Number") .", ". tosql($fldCantidadPedido[$x], "Number") .", ". tosql($fldIncotermsTra[$x], "Number") .", ". tosql($fldSuministro[$x], "Number") .", ". tosql($user_id, "Number") .", ". tosql($jue_id, "Number") .")";
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
function fFactorIncoterms($fldIncoterms)
{
	global $db;
	if($fldIncoterms)
	{
		$factorInc = get_db_value("select int_factorInc from tb_incotran where int_id=".tosql($fldIncoterms, "Text"));
		$factorTra = get_db_value("select int_factorTra from tb_incotran where int_id=".tosql($fldIncoterms, "Text"));
		return ($factorInc + $factorTra - 1);
	}else return 0;
}

function fTipoSuministro($fldSuministro)
{
	global $db;
	if($fldSuministro)
	{
		$montoSuministro = get_db_value("select sum_cost from tb_suministro where sum_id=".tosql($fldSuministro, "Text"));
		return $montoSuministro;
	}else return 0;

}

function fDescuento($proveedor, $fldMontoBasico, $user_id, $jue_id)
{
	$porcentajeDesc = get_db_value("select des_porcentaje from tb_descuentos where des_jue_id=". tosql($jue_id, "Numer")." and des_pro_id = ". tosql($proveedor, "Numer"). " and des_usu_id= ". tosql($user_id, "Numer"));
	return round($porcentajeDesc * $fldMontoBasico/100);
}

function fMontoTotal($fldMontoBasico, $fldFactorIncoterms, $fldImporteSuministro, $fldDescuento)
{
	return ($fldMontoBasico * $fldFactorIncoterms + $fldImporteSuministro - $fldDescuento);
}

function fTiempoLlegada($fldSuministro, $fldIncoterms)
{
	global $db;
	if($fldIncoterms&&$fldSuministro)
	{
		$tiempoInc = get_db_value("select int_tiempoInc from tb_incotran where int_id=".tosql($fldIncoterms, "Text"));
		$tiempoTra = get_db_value("select int_tiempoTra from tb_incotran where int_id=".tosql($fldIncoterms, "Text"));
		$tiempoSuministro = get_db_value("select sum_time from tb_suministro where sum_id=".tosql($fldSuministro, "Text"));
		return ($tiempoInc + $tiempoTra + $tiempoSuministro);
	}else return 0;
}
function fArchivoSalida ($SumMontoTotal, $ProductoSumMontoTotal, $pro_id, $user_id, $jue_id)
{
		global $db;
		
		$sSQL = "delete from tb_totalcompras where tot_jue_id= ".tosql($jue_id, "Number")." and tot_usu_id=". tosql($user_id, "Number");
		$db->query($sSQL);
		
		$sSQL = "insert into tb_totalcompras values(null, ". tosql($SumMontoTotal,"Number") .", ". tosql($ProductoSumMontoTotal,"Number") . ", ". tosql($pro_id,"Number") . ", ". tosql($user_id, "Number").", ".tosql($jue_id, "Number").")";
		$db->query($sSQL);

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
<script src="./js/ajaxlib.js" language="javascript" type="text/javascript"></script>
</head>
<body class="PageBODY">
<p>
<table width="100%" border="0">
<tr>
<td width="50%" align="right" >Reporte Salida <a href="comprasReporte.php?jue_id=<?=$jue_id?>&" title="Reporte Salida"><img src="./image/excel.jpg" alt="Reporte resumen" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="10%" align="center">
        
    </td>
</tr>

</table>


 <form method="POST" action="compramateriales3.php" name="valoresRecord">
  
  <table class="Grid" cellspacing="0" cellpadding="0" border="1">
     <tr class="Caption2">
      <td>Producto</td>
      <td>Unidades Pedido</td>
      <td>Proveedor</td>
      <td>Precio</td>
      <td>Cantidad de pedidos</td>
      <td>Incoterms &amp; Transporte</td>
      <td>Tipo Suministro</td>
      <td>Monto B&aacute;sico</td>
      <td>Factor Incoterms &amp; Transporte</td>
      <td>Importe por tipo suministro</td>
      <td>Descuento Negociado</td>
      <td>Monto Total (M)</td>
      <td>Tiempo de llegada</td>
     </tr>
     <?php
	 	$sSQL = "select * from tb_mesaproveedores where mes_jue_id=". tosql($jue_id,"Number"). " order by mes_id asc";
		$db->query($sSQL);
		if($db->num_rows())
		{
		$arrayIncoterms = db_fill_array("select int_id, int_id from tb_incotran where int_jue_id=$jue_id");
		$arraySuministro = db_fill_array("select sum_id, sum_name from tb_suministro where sum_jue_id=$jue_id");
		$SumMontoTotal = 0;
		$ProductoSumMontoTotal = 0;
		while($db->next_record())
		{
			$fldCompra = get_db_value("select pro_name from tb_productos2 where  pro_id = ".tosql($db->f("mes_com_id"), "Number"));
			$fldProveedores = get_db_value("select pro_name from tb_proveedor where  pro_id = ".tosql($db->f("mes_pro_id"), "Number"));
			$fldcantidadPedido = get_db_value("select com_cantidad from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));
			$fldcantidadPedido = ($fldcantidadPedido == NULL)?0:$fldcantidadPedido;
			$fldIncoterms = get_db_value("select com_int_id from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));

			$fldSuministro = get_db_value("select com_sum_id from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));
			
			$fldMontoBasico= fMontoBasico($fldcantidadPedido, $db->f("mes_pedido"), $db->f("mes_precio") );
            $fldFactorIncoterms= fFactorIncoterms($fldIncoterms);
            $fldImporteSuministro= fTipoSuministro($fldSuministro);
            $fldDescuento= fDescuento($db->f("mes_pro_id"), $fldMontoBasico, $user_id, $jue_id);
            $fldMontoTotal= fMontoTotal($fldMontoBasico, $fldFactorIncoterms, $fldImporteSuministro, $fldDescuento);
            $fldTiempoLlegada= fTiempoLlegada($fldSuministro, $fldIncoterms);
			
			$SumMontoTotal = $fldMontoTotal;
			$ProductoSumMontoTotal = (	$fldcantidadPedido	* $db->f("mes_pedido"));	
			
			fArchivoSalida ($SumMontoTotal, $ProductoSumMontoTotal, $db->f("mes_com_id"),  $user_id, $jue_id);
			 ?>
			 <tr class="Row">
				  <td><?= $fldCompra?></td>
				  <td><?= $db->f("mes_pedido")?></td>
				  <td><?= $fldProveedores?></td>
				  <td><?= $db->f("mes_precio")?></td>
				  <td><input name="cantPedido[]" value="<?=$fldcantidadPedido?>" type="text" size="4" class="textoCaja"/> </td>
                  <td><select name="incoterm[]">
                                      	<option value="">Seleccione</option>
 										  <?php
										  	foreach($arrayIncoterms as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldIncoterms) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select>
                  </td>
                  <td><select name="suministro[]">
                                      	<option value="">Seleccione </option>
 										  <?php
										  	foreach($arraySuministro as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldSuministro) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select><input name="mesa[]" value="<?=$db->f("mes_id")?>" type="hidden"/>
                 </td>
                 <td><?=$fldMontoBasico?></td>
                 <td><?=$fldFactorIncoterms?></td>
                 <td><?=$fldImporteSuministro?></td>
                 <td><?=$fldDescuento?></td>
                 <td><?=$fldMontoTotal?></td>
                 <td><?=$fldTiempoLlegada?></td>
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
      <input class="ClearButton" type="submit" value="Calcular" onClick="document.valoresRecord.FormAction.value = 'update';"/>&nbsp;&nbsp;
      </td>
     </tr>
 </table>
  </form>
<br>
</body>
</html>

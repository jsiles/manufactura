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

if ($FormAction=='update') update();
$columnas =14;

function insert ()
{
	global $db;
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
 <form method="POST" action="compramateriales3.php" name="valoresRecord">
  <br>
  <br>
  Seleccionar gesti&oacute;n: 
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
  <table cellspacing="0" cellpadding="0" border="0">

    <td valign="top">
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

		while($db->next_record())
		{
			$fldCompra = get_db_value("select pro_name from tb_productos2 where  pro_id = ".tosql($db->f("mes_com_id"), "Number"));
			$fldProveedores = get_db_value("select pro_name from tb_proveedor where  pro_id = ".tosql($db->f("mes_pro_id"), "Number"));
			$fldcantidadPedido = get_db_value("select com_cantidad from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));
			$fldcantidadPedido = ($fldcantidadPedido == NULL)?0:$fldcantidadPedido;
			$fldIncoterms = get_db_value("select com_int_id from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));
			$fldSuministro = get_db_value("select com_sum_id from tb_compras2 where com_jue_id=".tosql($jue_id, "Number")." and com_mes_id=".tosql($db->f("mes_id"), "Number")." and com_usu_id=".tosql($user_id, "Number"));			
			 ?>
			 <tr class="Row">
				  <td><?= $fldCompra?></td>
				  <td><?= $db->f("mes_pedido")?></td>
				  <td><?= $fldProveedores?></td>
				  <td><?= $db->f("mes_precio")?></td>
				  <td><input name="cantPedido[]" value="<?=$fldcantidadPedido?>" type="text" size="4" class="textoCaja"/> </td>
                  <td><select name="incoterm[]">
                                      	<option value="">Seleccione valor</option>
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
                                      	<option value="">Seleccione valor</option>
 										  <?php
										  	foreach($arraySuministro as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldSuministro) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select>
                 </td>
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
      <input class="ClearButton" type="submit" value="Aceptar" onClick="document.valoresRecord.FormAction.value = 'update';"/>&nbsp;&nbsp;
      </td>
     </tr>
 </table>
  </form>
<br>
</body>
</html>

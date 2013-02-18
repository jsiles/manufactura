<?php
include ("./common2.php");
include ("./globals.php");
session_start();
$jue_id= get_param("jue_id");
$sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
	"t.jue_id as id from tb_juegos t where t.jue_id=$jue_id ".
	"  and t.jue_sw='A' " ;
    //echo $sSQL;die;
	$db->query($sSQL);
	$next_record = $db->next_record();
	$per_inicio = $db->f("inicio");
	$per_cantidad = $db->f("cantidad");
	$jue_id = $db->f("id");
	$per_in = $per_inicio;
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=".get_param("jue_id")." limit $per_cantidad");
$per_periodo = get_param("per_periodo");
$jue_id= get_param("jue_id");
if (!$per_periodo) $per_periodo=1;
//print_r($arrayPeriodo);
	$material = "";
	$calidad = "";
	$unidad = "";	
	$pedido_cero[]= "";	
	$pedido_treinta[]= "";	
	$pedido_sesenta[]= "";	
$FormAction = get_param("FormAction");

if ($FormAction=='update') insert();
/*
$sSQL="select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo order by mat_pedido asc";
//echo "select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo order by mat_pedido asc";
$db->query($sSQL);
$j=0;
while($result=$db->next_record())
{
	$material = $db->f('mat_descripcion');
	$calidad = $db->f('mat_calidad');
	$unidad = $db->f('mat_unidad');	
	$pedido_cero[$j]= $db->f('mat_diascero');	
	$pedido_treinta[$j]= $db->f('mat_diastreinta');	
	$pedido_sesenta[$j]= $db->f('mat_diassesenta');
	//echo $material."#".$result['mat_descripcion']."//";
$j++;
}
*/
//print_r($arrayMateriales);

$arrayCalidad = db_fill_array("select pro_id, pro_nombre from tb_productos where pro_jue_id=".get_param("jue_id"));
function insert ()
{
	global $db;
	$fldmaterial = get_param("material");
	$fldcalidad = get_param("calidad");
	$fldunidad = get_param("unidad");	
	$fldpedido_0 = get_param("pedido_0");
	$fldpedido_30 = get_param("pedido_30");
	$fldpedido_60 = get_param("pedido_60");
	$fldjue_id = get_param("jue_id");
	$fldper_periodo = get_param("per_periodo");
	for($i=0;$i<6;$i++)
	{
		if(!$fldpedido_0[$i]) $fldpedido_0[$i]=0;
		if(!$fldpedido_30[$i]) $fldpedido_30[$i]=0;
		if(!$fldpedido_60[$i]) $fldpedido_60[$i]=0;
		$valCantidad= get_db_value("select count(*) from tb_materiales where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i and mat_descripcion=$fldmaterial and mat_calidad=$fldcalidad and mat_unidad=$fldunidad");
		if ($valCantidad==0)
		$db->query("insert into tb_materiales values(null,$fldjue_id, $fldper_periodo, $fldmaterial, $fldcalidad, $fldunidad, ".$fldpedido_0[$i].",".$fldpedido_30[$i].",".$fldpedido_60[$i].",$i,'ACTIVO',now())");
		else $db->query("update tb_materiales set mat_diascero=".$fldpedido_0[$i].", mat_diastreinta=".$fldpedido_30[$i].", mat_diassesenta=".$fldpedido_60[$i].", mat_datetime=now() where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i and mat_descripcion=$fldmaterial and mat_calidad=$fldcalidad and mat_unidad=$fldunidad");
	}
}
?>
<html>
<head>
<title>siges</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><link rel="stylesheet" href="Site.css" type="text/css"></head>
<link href="Themes/Clear/Style.css" type="text/css" rel="stylesheet">
<script src="js/ajaxlib.js" language="javascript" type="text/javascript"></script>
<body class="PageBODY">
<p>
 <form method="POST" action="compras3.php" name="valoresRecord">
 <font class="ClearFormHeaderFont">Agregar/Editar Parámetros&nbsp; </font> 
  <br>
  <br>
  Seleccionar periodo: <select name="per_periodo" id="per_periodo" onChange="submit();">
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
  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
     <tr>
      <td class="ClearFieldCaptionTD">MATERIAL</td>
      <td class="ClearDataTD" colspan="3"><select name="material" id="material" onChange="changeMaterial(this,<?=$per_periodo?>,<?=$jue_id?>);"><option value="">Seleccionar Valor</option>
      <?php
	  foreach($arrayMateriales as $key=>$value)
	  {
	  ?>
      <option value="<?=$key?>"><?=$value?></option>
      <?php
	  }
	  ?>
      </select></td>
     </tr>
    <tr>
      <td class="ClearFieldCaptionTD">CALIDAD</td>
      <td class="ClearDataTD" colspan="3"><select name="calidad" id="calidad"><option value="">Seleccionar Valor</option>
      <?php
	  foreach($arrayCalidad as $key=>$value)
	  {
	  ?>
      <option value="<?=$key?>"><?=$value?></option>
      <?php
	  }
	  ?>
      </select></td>
     </tr>
     <tr>
      <td class="ClearFieldCaptionTD">UNIDAD DE PEDIDO</td>
      <td class="ClearDataTD" colspan="3">
      <div id="listPedido">
      <select name="unidad" onChange="changeData(this,<?=$per_periodo?>,<?=$jue_id?>);"><option value="">Seleccionar Valor</option>
      <?php
	  foreach($arrayUnidades as $arrayUnidad)
	  {
		foreach($arrayUnidad as $key=>$value) 
		{
	  ?>
      <option value="<?=$key?>"><?=$value?></option>
      <?php
		}
	  }
	  ?>
      </select>
      </div></td>
     </tr>
     </table>
     <br>
     <input type="hidden" name="jue_id" value="<?=$jue_id?>">
<div id="tablaData">
   </div>  
  </form>
<!--EndFormvaloresRecord-->
   <!--BeginFormvaloresRecordFooter-->
   <!--EndFormvaloresRecordFooter-->
</body>
</html>
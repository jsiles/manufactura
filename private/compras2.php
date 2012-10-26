<?php
include ("./common2.php");
session_start();
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=".get_param("jue_id")."");
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
		$valCantidad= get_db_value("select count(*) from tb_materiales where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i");
		if ($valCantidad==0)
		$db->query("insert into tb_materiales values(null,$fldjue_id, $fldper_periodo, '$fldmaterial', '$fldcalidad', '$fldunidad', ".$fldpedido_0[$i].",".$fldpedido_30[$i].",".$fldpedido_60[$i].",$i,'ACTIVO',now())");
		else $db->query("update tb_materiales set mat_jue_id=$fldjue_id, mat_per_id=$fldper_periodo, mat_descripcion='$fldmaterial', mat_calidad='$fldcalidad', mat_unidad='$fldunidad', mat_diascero=".$fldpedido_0[$i].", mat_diastreinta=".$fldpedido_30[$i].", mat_diassesenta=".$fldpedido_60[$i].", mat_datetime=now() where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i");
	}
}
?>
<html>
<head>
<title>siges</title>
<meta name="GENERATOR" content="YesSoftware CodeCharge v.2.0.5 / Templates.ccp build 11/01/2001">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><link rel="stylesheet" href="Site.css" type="text/css"></head>
<link href="Themes/Clear/Style.css" type="text/css" rel="stylesheet">
<body class="PageBODY">
<p>
 <form method="POST" action="compras.php" name="valoresRecord">
  <font class="ClearFormHeaderFont">Agregar/Editar Parámetros&nbsp; </font> 
  <br>
  <br>
  Seleccionar periodo: <select name="per_periodo" onChange="submit();">
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
     <!--BeginvaloresRecordErr-->
   <!--  <tr>
      <td class="ClearErrorDataTD" colspan="4">{svaloresRecordErr}</td>
     </tr>-->
     <!--EndvaloresRecordErr-->
     <tr>
      <td class="ClearFieldCaptionTD">MATERIAL</td>
      <td class="ClearDataTD" colspan="3"><input name="material" type="text" value="<?=$material?>"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD">CALIDAD</td>
      <td class="ClearDataTD" colspan="3"><input name="calidad" type="text" value="<?=$calidad?>"></td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD">UNIDAD DE PEDIDO</td>
      <td class="ClearDataTD" colspan="3"><input name="unidad" type="text" value="<?=$unidad?>"></td>
     </tr>
     <tr>
      <td class="ClearFieldCaptionTD" align="center"  colspan="4" >PRECIO $M/PEDIDO</td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD">&nbsp;</td>
      <td class="ClearFieldCaptionTD">A 0 DIAS</td>
      <td class="ClearFieldCaptionTD">A 30 DIAS</td>
      <td class="ClearFieldCaptionTD">A 60 DIAS</td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD">1 PEDIDO</td>
      <td class="ClearDataTD"><input name="pedido_0[0]" size="2" value="<?=$pedido_cero[0]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[0]" size="2" value="<?=$pedido_treinta[0]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[0]" size="2" value="<?=$pedido_sesenta[0]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD">2 PEDIDOS</td>
      <td class="ClearDataTD"><input name="pedido_0[1]" size="2" value="<?=$pedido_cero[1]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[1]" size="2" value="<?=$pedido_treinta[1]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[1]" size="2" value="<?=$pedido_sesenta[1]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD">3 PEDIDOS</td>
      <td class="ClearDataTD"><input name="pedido_0[2]" size="2" value="<?=$pedido_cero[2]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[2]" size="2" value="<?=$pedido_treinta[2]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[2]" size="2" value="<?=$pedido_sesenta[2]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD">4 PEDIDOS</td>
      <td class="ClearDataTD"><input name="pedido_0[3]" size="2" value="<?=$pedido_cero[3]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[3]" size="2" value="<?=$pedido_treinta[3]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[3]" size="2" value="<?=$pedido_sesenta[3]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD">5 PEDIDOS</td>
      <td class="ClearDataTD"><input name="pedido_0[4]" size="2" value="<?=$pedido_cero[4]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[4]" size="2" value="<?=$pedido_treinta[4]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[4]" size="2" value="<?=$pedido_sesenta[4]?>" type="text"></td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD">M&Aacute;S DE 6 PEDIDOS</td>
      <td class="ClearDataTD"><input name="pedido_0[5]" size="2" value="<?=$pedido_cero[5]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[5]" size="2" value="<?=$pedido_treinta[5]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[5]" size="2" value="<?=$pedido_sesenta[5]?>" type="text"></td>
     </tr>
     
     <tr>
      <td class="ClearFooterTD" nowrap align="right" colspan="2">

      <!-- ***   Buttons   *** -->
      
      <!--BeginvaloresRecordInsert-->
      <!--BeginvaloresRecordEdit-->
      <input type="hidden" value="" name="FormAction"/>
      
      <!--BeginvaloresRecordUpdate-->
      <input class="ClearButton" type="submit" value="Enviar" onClick="document.valoresRecord.FormAction.value = 'update';"/>
      <!--EndvaloresRecordUpdate-->
      
      
      <!--BeginvaloresRecordCancel-->
      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';"/>
      <!--EndvaloresRecordCancel-->
      
      <input type="hidden" name="FormName" value="valoresRecord"/>
      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
     </td>
    </tr>
   </table>
  </form>
<!--EndFormvaloresRecord-->
   <!--BeginFormvaloresRecordFooter-->
   
   <!--EndFormvaloresRecordFooter-->
</body>
</html>

<?php
include ("./common.php");
include ("./private/globals.php");
session_start();
check_security(1);

$user_id = get_session("cliID");
$jue_id = get_param("id");
//echo $jue_id."####";
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
	
	
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$jue_id and per_compra='A' limit $per_cantidad");
//print_r($arrayPeriodo);
$per_periodo = get_param("per_periodo");
//echo $per_periodo."###";
	
$maxPeriodo2 = get_db_value("select max(per_periodo) from tb_periodos where per_jue_id=$jue_id and per_compra='A'"); //modif
    
if (!$per_periodo) $per_periodo=1;
//print_r($arrayPeriodo);

	$material = get_param("material");
	$calidad = get_param("calidad");
	$unidad = get_param("unidad");	
	$pedido_cero[]= "";	
	$pedido_treinta[]= "";	
	$pedido_sesenta[]= "";	
$FormAction = get_param("FormAction");
if ($FormAction=='update') insert();
if(($material)&&($calidad)&&($unidad))
{
	$sSQL="select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo and mat_descripcion=$material and mat_calidad=$calidad and mat_unidad=$unidad order by mat_pedido asc";
	//echo "select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo order by mat_pedido asc";
	$db->query($sSQL);
	$j=0;
	while($db->next_record())
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

}
$arrayCalidad = db_fill_array("select pro_id, pro_nombre from tb_productos where pro_jue_id=".$jue_id);

function insert ()
{
	global $db;
	$material = get_param("material");
	$calidad = get_param("calidad");
	$unidad = get_param("unidad");	
	$fldpedido_0 = get_param("pedido_0");
	$fldpedido_30 = get_param("pedido_30");
	$fldpedido_60 = get_param("pedido_60");
	$fldjue_id = get_param("id");
	$fldper_periodo = get_param("per_periodo");
	for($i=0;$i<6;$i++)
	{
		if(!$fldpedido_0[$i]) $fldpedido_0[$i]=0;
		if(!$fldpedido_30[$i]) $fldpedido_30[$i]=0;
		if(!$fldpedido_60[$i]) $fldpedido_60[$i]=0;
		$valCantidad= get_db_value("select count(*) from tb_materialespedido where map_jue_id=$fldjue_id and map_per_id=$fldper_periodo and map_pedido=$i and map_material=$material and map_calidad=$calidad and map_unidad=$unidad and map_usu_id=".get_session("cliID"));
		if ($valCantidad==0)
		{
		$db->query("insert into tb_materialespedido values(null, $fldjue_id, " . get_session("cliID") .", $material, $calidad, $unidad, $fldper_periodo, ".$fldpedido_0[$i].",".$fldpedido_30[$i].",".$fldpedido_60[$i].",$i,'ACTIVO',now())");
		//echo "insert into tb_materialespedido values(null, $fldjue_id, " . get_session("cliID") .", $material, $calidad, $unidad, $fldper_periodo, ".$fldpedido_0[$i].",".$fldpedido_30[$i].",".$fldpedido_60[$i].",$i,'ACTIVO',now())";
		}
		else
		{
		$db->query("update tb_materialespedido set map_diascero=".$fldpedido_0[$i].", map_diastreinta=".$fldpedido_30[$i].", map_diassesenta=".$fldpedido_60[$i].", map_datetime=now() where map_jue_id=$fldjue_id and map_per_id=$fldper_periodo and map_pedido=$i and map_material=$material and map_calidad=$calidad and map_unidad=$unidad and map_usu_id=".get_session("cliID"));
		//echo "update tb_materialespedido set map_diascero=".$fldpedido_0[$i].", map_diastreinta=".$fldpedido_30[$i].", map_diassesenta=".$fldpedido_60[$i].", map_datetime=now() where map_jue_id=$fldjue_id and map_per_id=$fldper_periodo and map_pedido=$i and map_material=$material and map_calidad=$calidad and map_unidad=$unidad and map_usu_id=".get_session("cliID");
		}
	}
}

switch($material.$unidad)
		{
			case '14':
			$titleData ='$M/4 ROLLOS';
			$subTitle0 ='4 ROLLOS';
			$subTitle1 ='8 ROLLOS';
			$subTitle2 ='12 ROLLOS';
			$subTitle3 ='16 ROLLOS';
			$subTitle4 ='20 ROLLOS';			
			$subTitle5 ='M&aacute;s de 20 ROLLOS';			
			break;
			case '18':
			$titleData ='$M/8 ROLLOS';
			$subTitle0 ='8 ROLLOS';
			$subTitle1 ='16 ROLLOS';
			$subTitle2 ='24 ROLLOS';
			$subTitle3 ='32 ROLLOS';
			$subTitle4 ='40 ROLLOS';			
			$subTitle5 ='M&aacute;s de 40 ROLLOS';			
			break;
			case '240':
			$titleData ='$M/40 KITS';
			$subTitle0 ='40 KITS';
			$subTitle1 ='80 KITS';
			$subTitle2 ='120 KITS';
			$subTitle3 ='160 KITS';
			$subTitle4 ='200 KITS';			
			$subTitle5 ='M&aacute;s de 200 KITS';			
			break;
			case '280':
			$titleData ='$M/80 KITS';
			$subTitle0 ='80 KITS';
			$subTitle1 ='160 KITS';
			$subTitle2 ='240 KITS';
			$subTitle3 ='320 KITS';
			$subTitle4 ='400 KITS';			
			$subTitle5 ='M&aacute;s de 400 KITS';			
			break;
			case '340':
			$titleData ='$M/40 KILOGRAMOS';
			$subTitle0 ='40 KILOGRAMOS';
			$subTitle1 ='80 KILOGRAMOS';
			$subTitle2 ='120 KILOGRAMOS';
			$subTitle3 ='160 KILOGRAMOS';
			$subTitle4 ='200 KILOGRAMOS';			
			$subTitle5 ='M&aacute;s de 200 KILOGRAMOS';			
			break;
			case '380':
			$titleData ='$M/80 KILOGRAMOS';
			$subTitle0 ='80 KILOGRAMOS';
			$subTitle1 ='160 KILOGRAMOS';
			$subTitle2 ='240 KILOGRAMOS';
			$subTitle3 ='320 KILOGRAMOS';
			$subTitle4 ='400 KILOGRAMOS';			
			$subTitle5 ='M&aacute;s de 400 KILOGRAMOS';			
			break;

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
 <form method="POST" action="compramateriales.php" name="valoresRecord">
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
     <!--BeginvaloresRecordErr-->
   <!--  <tr>
      <td class="ClearErrorDataTD" colspan="4">{svaloresRecordErr}</td>
     </tr>-->
     <!--EndvaloresRecordErr-->
     
     <tr class="Caption">
      <td class="ClearFieldCaptionTD">PARÁMETROS</td>
      <td class="ClearDataTD" colspan="6"></td>
     </tr>
     
     <tr class="Row">
      <td class="ClearFieldCaptionTD">MATERIAL</td>
      <td class="ClearDataTD" colspan="6"><select name="material" id="material" onChange="changeMaterial(this,<?=$per_periodo?>,<?=$jue_id?>);"><option value="">Seleccionar Valor</option>
      <?php
	  foreach($arrayMateriales as $key=>$value)
	  {
		  if($key==$material) $selValue="Selected"; else $selValue="";
	  ?>
      <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
      <?php
	  }
	  ?>
      </select></td>
     </tr>

     <tr class="Row">
      <td class="ClearFieldCaptionTD">CALIDAD</td>
      <td class="ClearDataTD" colspan="6"><select name="calidad"><option value="">Seleccionar Valor</option>
      <?php
	  foreach($arrayCalidad as $key=>$value)
	  {
		    if($key==$calidad) $selValue="Selected"; else $selValue="";
	  ?>
      <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
      <?php
	  }
	  ?>
      </select></td>
     </tr>
     
     <tr class="Row">
      <td class="ClearFieldCaptionTD">UNIDAD DE PEDIDO</td>
      <td class="ClearDataTD" colspan="6"><div id="listPedido">
      <select name="unidad" onChange="javascript:submit();"><option value="">Seleccionar Valor</option>
     <?php
      if(get_param("material"))
	  {
		  foreach($arrayUnidades[get_param("material")] as  $key=>$value)
		  {
			  if($key==$unidad) $selValue="Selected"; else $selValue="";
			  ?>
			  <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
			  <?php
		  }
	  }
	  else
	  {
		  foreach($arrayUnidades as $arrayUnidad)
		  {
			foreach($arrayUnidad as $key=>$value) 
				{
					 if($key==$unidad) $selValue="Selected"; else $selValue="";
			  ?>
			  <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
			  <?php
				}
		  }
	  }
	  ?>
      </select>
      </div>
      
       <input type="hidden" value="" name="FormAction"/>
      <input type="hidden" value="<?=$jue_id?>" name="id"/>
      
      <input type="hidden" name="FormName" value="valoresRecord"/>
     
      </td>
     </tr>
 </table>
 <br>
 <?php
 if (($material)&&($calidad)&&($unidad))
 {
 ?>
 <table class="Grid" cellspacing="0" cellpadding="0" border="1">   
     <tr class="Footer" style="text-align:center">
      <td class="ClearFieldCaptionTD" align="center"  colspan="4" >PRECIO <?=$titleData?></td>
     </tr>


   <tr class="Caption" style="text-align:center">
      <td class="ClearFieldCaptionTD">&nbsp;</td>
      <td class="ClearFieldCaptionTD">A 0 DIAS</td>
      <td class="ClearFieldCaptionTD">A 30 DIAS</td>
      <td class="ClearFieldCaptionTD">A 60 DIAS</td>
     </tr>
     
     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle0?></td>
      <td class="ClearDataTD"><?=$pedido_cero[0]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[0]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[0]?>&nbsp;</td>
     </tr>

     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle1?></td>
      <td class="ClearDataTD"><?=$pedido_cero[1]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[1]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[1]?>&nbsp;</td>
     </tr>

     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle2?></td>
      <td class="ClearDataTD"><?=$pedido_cero[2]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[2]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[2]?>&nbsp;</td>
     </tr>

     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle3?></td>
      <td class="ClearDataTD"><?=$pedido_cero[3]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[3]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[3]?>&nbsp;</td>
     </tr>

     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle4?></td>
      <td class="ClearDataTD"><?=$pedido_cero[4]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[4]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[4]?>&nbsp;</td>
     </tr>
     
     <tr class="Row">
      <td class="ClearFieldCaptionTD"><?=$subTitle5?></td>
      <td class="ClearDataTD"><?=$pedido_cero[5]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_treinta[5]?>&nbsp;</td>
      <td class="ClearDataTD"><?=$pedido_sesenta[5]?>&nbsp;</td>
     </tr>
     
 </table>
   
 <table class="Grid" cellspacing="0" cellpadding="0" border="1">   
     <tr class="Footer" style="text-align:center">
      <td class="ClearFieldCaptionTD" align="center"  colspan="4" >CANTIDAD DE PEDIDOS POR EMPRESA</td>
      <td class="ClearFieldCaptionTD" align="center"  colspan="3" >$M A REGISTRAR</td>
     </tr>
     
     <tr class="Caption">
      <td class="ClearFieldCaptionTD">EMPRESA&nbsp;</td>
      <td class="ClearFieldCaptionTD">A 0 DIAS</td>
      <td class="ClearFieldCaptionTD">A 30 DIAS</td>
      <td class="ClearFieldCaptionTD">A 60 DIAS</td>
      <td class="ClearFieldCaptionTD">A 0 DIAS</td>
      <td class="ClearFieldCaptionTD">A 30 DIAS</td>
      <td class="ClearFieldCaptionTD">A 60 DIAS</td>
     </tr>
   <?php
   $empresa = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=".$jue_id." order by usu_id");
	$k=0;
	foreach($empresa as $key=>$value)
	{
		
		$sSQL="select * from tb_materialespedido where map_jue_id=$jue_id and map_per_id=$per_periodo and map_material=$material and map_calidad=$calidad and map_unidad=$unidad and map_usu_id=$key order by  map_pedido asc"; //and map_usu_id=".get_session("cliID")."
		//echo "select * from tb_materialespedido where map_jue_id=$jue_id and map_per_id=$per_periodo and map_material=$material and map_calidad=$calidad and map_unidad=$unidad order by map_pedido asc";
		$db->query($sSQL);
		$j=0;
		while($db->next_record())
		{
			$pedido_ceroP[$j]= $db->f('map_diascero');	
			$pedido_treintaP[$j]= $db->f('map_diastreinta');	
			$pedido_sesentaP[$j]= $db->f('map_diassesenta');
		
			if(!$pedido_ceroP[$j]) $pedido_ceroP[$j]=0;
			if($pedido_ceroP[$j]>5) $pedido_ceroT[$j]= $pedido_cero[5]*$pedido_ceroP[$j];
			else $pedido_ceroT[$j]= $pedido_cero[$pedido_ceroP[$j]-1]*$pedido_ceroP[$j];
		
			if(!$pedido_treintaP[$j]) $pedido_treintaP[$j]=0;
			if($pedido_treintaP[$j]>5) $pedido_treintaT[$j]= $pedido_treinta[5]*$pedido_treintaP[$j];
			else $pedido_treintaT[$j]= $pedido_treinta[$pedido_treintaP[$j]-1]*$pedido_treintaP[$j];
		
			if(!$pedido_sesentaP[$j]) $pedido_sesentaP[$j]=0;
			if($pedido_sesentaP[$j]>5) $pedido_sesentaT[$j]= $pedido_sesenta[5]*$pedido_sesentaP[$j];
			else $pedido_sesentaT[$j]= $pedido_sesenta[$pedido_sesentaP[$j]-1]*$pedido_sesentaP[$j];
		
		//echo "<br>".$pedido_treintaT[$j] ."=" .$pedido_treinta[$pedido_treintaP[$j]-1]."*".$pedido_treintaP[$j];
		$j++;
		}
	?>  
     <tr class="Row" >
      <td class="ClearFieldCaptionTD"><?=$value?></td>
      <td class="ClearDataTD" style="font-size:11px;text-align:right;">
      <?php 
	  if ($key==get_session("cliID"))
	  {
	  ?>
      <input name="pedido_0[<?=$k?>]" size="2" value="<? if(!$pedido_ceroP[$k]) echo 0; else echo $pedido_ceroP[$k];?>" type="text" style="text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4">
      <?php
	  }else
	  {
	  if(!$pedido_ceroP[$k]) echo 0; else echo $pedido_ceroP[$k];
      }
	  ?>
      </td>
      <td class="ClearDataTD" style="font-size:11px;text-align:right;">
      <?php 
	  if ($key==get_session("cliID"))
	  {
	  ?>
      <input name="pedido_30[<?=$k?>]" size="2" value="<? if(!$pedido_treintaP[$k]) echo 0;else echo $pedido_treintaP[$k];?>" type="text" style="text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4">
      <?php
	  }else
	  {
	  if(!$pedido_treintaP[$k]) echo 0;else echo $pedido_treintaP[$k];
      }
	  ?>
      </td>
      <td class="ClearDataTD" style="font-size:11px;text-align:right;">
       <?php 
	  if ($key==get_session("cliID"))
	  {
	  ?>
      <input name="pedido_60[<?=$k?>]" size="2" value="<? if(!$pedido_sesentaP[$k]) echo 0;else echo $pedido_sesentaP[$k];?>" type="text" style="text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4">
      <?php
	  }else
	  {
	  if(!$pedido_sesentaP[$k]) echo 0;else echo $pedido_sesentaP[$k];
      }
	  ?>
      </td>
      <td style="text-align:right;color:#FF0000"><? if(!$pedido_ceroT[$k]) echo 0; else echo $pedido_ceroT[$k]?>&nbsp;</td>
      <td style="text-align:right;color:#FF0000"><? if(!$pedido_treintaT[$k]) echo 0; else echo $pedido_treintaT[$k]?>&nbsp;</td>
      <td style="text-align:right;color:#FF0000"><? if(!$pedido_sesentaT[$k]) echo 0; else echo $pedido_sesentaT[$k]?>&nbsp;</td>
     </tr>
     <?php
	 $k++;
     }
	 ?>
     <tr class="Footer">
      <td  align="center" colspan="7">

      <!-- ***   Buttons   *** -->
      
      <!--BeginvaloresRecordInsert-->
      <!--BeginvaloresRecordEdit-->
      <!--BeginvaloresRecordUpdate-->
     <?php 
	 if ($maxPeriodo2==$per_periodo)
	 {
	 ?>
      <input class="ClearButton" type="submit" value="Aceptar" onClick="document.valoresRecord.FormAction.value = 'update';"/>
	 <?
	 }
	 ?>
      <!--EndvaloresRecordUpdate-->
      
      
      
     </td>
    </tr>
   </table>
   <?php
 }
   ?>
   </td>
   </table>
  </form>
<!--EndFormvaloresRecord-->
   <!--BeginFormvaloresRecordFooter-->
   
   <!--EndFormvaloresRecordFooter-->
</body>
</html>

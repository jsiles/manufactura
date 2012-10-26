<?php
include ("./common2.php");
session_start();
$mat_id = get_param("mat_id");
$cal_id = get_param("cal_id");
$uni_id = get_param("uni_id");
$per_periodo = get_param("per_periodo");
$jue_id = get_param("jue_id");
//echo $mat_id ."#". $cal_id . "#". $uni_id ."#". $per_periodo ."#". $jue_id;


$sSQL="select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo and mat_descripcion=$mat_id and mat_calidad=$cal_id and mat_unidad=$uni_id order by mat_pedido asc";
//echo "select * from tb_materiales where mat_jue_id=$jue_id and mat_per_id=$per_periodo order by mat_pedido asc";
$db->query($sSQL);
$j=0;
while($result=$db->next_record())
{
/*	$material = $db->f('mat_descripcion');
	$calidad = $db->f('mat_calidad');
	$unidad = $db->f('mat_unidad');	*/
	$pedido_cero[$j]= $db->f('mat_diascero');	
	$pedido_treinta[$j]= $db->f('mat_diastreinta');	
	$pedido_sesenta[$j]= $db->f('mat_diassesenta');
	//echo $material."#".$result['mat_descripcion']."//";
$j++;
}
	switch($mat_id.$uni_id)
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
 <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
     <tr>
      <td class="ClearFieldCaptionTD" align="center"  colspan="4" >PRECIO <?=$titleData?></td>
     </tr>


   <tr>
      <td class="ClearFieldCaptionTD">&nbsp;</td>
      <td class="ClearFieldCaptionTD">A 0 DIAS</td>
      <td class="ClearFieldCaptionTD">A 30 DIAS</td>
      <td class="ClearFieldCaptionTD">A 60 DIAS</td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle0?></td>
      <td class="ClearDataTD"><input name="pedido_0[0]" size="2" value="<?=$pedido_cero[0]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[0]" size="2" value="<?=$pedido_treinta[0]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[0]" size="2" value="<?=$pedido_sesenta[0]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle1?></td>
      <td class="ClearDataTD"><input name="pedido_0[1]" size="2" value="<?=$pedido_cero[1]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[1]" size="2" value="<?=$pedido_treinta[1]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[1]" size="2" value="<?=$pedido_sesenta[1]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle2?></td>
      <td class="ClearDataTD"><input name="pedido_0[2]" size="2" value="<?=$pedido_cero[2]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[2]" size="2" value="<?=$pedido_treinta[2]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[2]" size="2" value="<?=$pedido_sesenta[2]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle3?></td>
      <td class="ClearDataTD"><input name="pedido_0[3]" size="2" value="<?=$pedido_cero[3]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[3]" size="2" value="<?=$pedido_treinta[3]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[3]" size="2" value="<?=$pedido_sesenta[3]?>" type="text"></td>
     </tr>

     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle4?></td>
      <td class="ClearDataTD"><input name="pedido_0[4]" size="2" value="<?=$pedido_cero[4]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_30[4]" size="2" value="<?=$pedido_treinta[4]?>" type="text"></td>
      <td class="ClearDataTD"><input name="pedido_60[4]" size="2" value="<?=$pedido_sesenta[4]?>" type="text"></td>
     </tr>
     
     <tr>
      <td class="ClearFieldCaptionTD"><?=$subTitle5?></td>
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
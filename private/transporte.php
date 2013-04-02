<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$tra_id= get_param("tra_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $tra_id);
if ($FormAction=='delete') delete($jue_id, $tra_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldtransporte = get_param("transporte");
	
	$valCant = get_db_value("select count(*) from tb_transporte where tra_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(tra_id) from tb_transporte where tra_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into tb_transporte values(". tosql($iniValue,"Number") .", ". tosql($fldtransporte,"Text").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: transporte.php?jue_id=$jue_id");
}

function update($jue_id, $tra_id)
{
	global $db;
	$fldtransporte = get_param("transporte");
	$sSQL="update tb_transporte set tra_name=". tosql($fldtransporte,"Text")." where tra_jue_id=". tosql($jue_id,"Text")." and tra_id=". tosql($tra_id,"Number");
	$db->query($sSQL);
	header("location: transporte.php?jue_id=$jue_id");
}

function delete($jue_id, $tra_id)
{
	global $db;
	$fldtransporte = get_param("transporte");
	$sSQL="delete from tb_transporte where tra_jue_id=". tosql($jue_id,"Text")." and tra_id=". tosql($tra_id,"Number");
	$db->query($sSQL);
	header("location: transporte.php?jue_id=$jue_id");

}
?>
<html>
        <head>
                <title>Compras</title>
               
                <link rel="stylesheet" href="Themes/style.css" />
                <link href="Themes/navmenu.css" type="text/css" rel="stylesheet">
                <link href="Themes/navmenu3.css" type="text/css" rel="stylesheet">
				<link href="Themes/style.css" type="text/css" rel="stylesheet">
                <link href="Themes/Clear/Style.css" type="text/css" rel="stylesheet">
        </head>
        <body>
                <div id="tabs">
                		  <?php
							$idActive1 = "id=\"active\"";
							$idActive13 = "id=\"active\"";
							
                        	include("menu_horiz.php");
						?>		
                        
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Transportes </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Transporte</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_transporte where tra_jue_id=$jue_id order by tra_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="transporte.php?tra_id=<?=$db->f("tra_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("tra_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("tra_name")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="3">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="transporte.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Transportes&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Transporte</td>
                                      <?php
									  	if($tra_id!=NULL) $fldtransporte = get_db_value("select tra_name from tb_transporte where tra_id=$tra_id and tra_jue_id=$jue_id");
									  ?>
                                      <td class="ClearDataTD"><input name="transporte" value="<?=$fldtransporte?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($tra_id==NULL)
									  {
									  ?>
									  
                                      <!--BeginvaloresRecordInsert-->
                                      <input class="ClearButton" type="submit" value="Agregar" onClick="document.valoresRecord.FormAction.value = 'insert';">
                                      <!--EndvaloresRecordInsert-->
                                      <?php
									  }else
									  {
									  ?>
                                      <!--BeginvaloresRecordEdit-->
                                      <!--BeginvaloresRecordUpdate-->
                                      <input class="ClearButton" type="submit" value="Modificar" onClick="document.valoresRecord.FormAction.value = 'update';"/>
                                      <!--EndvaloresRecordUpdate-->
                                     
                                      <!--BeginvaloresRecordDelete-->
                                      <input class="ClearButton" type="submit" value="Borrar" onClick="document.valoresRecord.FormAction.value = 'delete';"/>
                                      <!--EndvaloresRecordDelete-->
                                      
                                      <!--EndvaloresRecordEdit-->
                                       <?php
									  }
									  ?>
                                      <!--BeginvaloresRecordCancel-->
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.tra_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="tra_id" value="<?=$tra_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

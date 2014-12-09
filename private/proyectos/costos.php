<?php
include ("../common2.php");
@session_start();
$jue_id= get_param("jue_id");
$cos_id= get_param("cos_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $cos_id);
if ($FormAction=='delete') delete($jue_id, $cos_id);


function insert ($jue_id)
{
	global $db;
	$fldCostos = get_param("costo");
	$valCant = get_db_value("select count(*) from py_costos where cos_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(cos_id) from py_costos where cos_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into py_costos values(". tosql($iniValue,"Number") .", ". tosql($fldCostos,"Text").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: costos.php?jue_id=$jue_id");
	
}

function update($jue_id, $cos_id)
{
	global $db;
	$fldCosto = get_param("costo");
	$sSQL="update py_costos set cos_mantenimiento=". tosql($fldCosto,"Number")." where cos_jue_id=". tosql($jue_id,"Text")." and cos_id=". tosql($cos_id,"Number");
	$db->query($sSQL);
	header("location: costos.php?jue_id=$jue_id");	
}

function delete($jue_id, $cos_id)
{
	global $db;
	$fldCosto = get_param("costo");
	$sSQL="delete from py_costos where cos_jue_id=". tosql($jue_id,"Text")." and cos_id=". tosql($cos_id,"Number");
	$db->query($sSQL);
	header("location: costos.php?jue_id=$jue_id");	
}
?>
<html>
        <head>
                <title>Costos</title>
               
                <link rel="stylesheet" href="../Themes/style.css" />
                <link href="../Themes/navmenu.css" type="text/css" rel="stylesheet">
                <link href="../Themes/navmenu3.css" type="text/css" rel="stylesheet">
				<link href="../Themes/style.css" type="text/css" rel="stylesheet">
                <link href="../Themes/Clear/Style.css" type="text/css" rel="stylesheet">
        </head>
        <body>
                <div id="tabs">
                		<?php
							$idActive1 = "id=\"active\"";
							$idActive11 = "id=\"active\"";
							
                        	include("menu_horiz.php");
						?>                            
                        <div id="tabs-1">
                               
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Costos </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Costos</td>
                                    </tr>
                                    <?php
										$sSQL="select * from py_costos where cos_jue_id=$jue_id order by cos_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="costos.php?cos_id=<?=$db->f("cos_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_mantenimiento")?></td>
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
                                  <form method="Get" action="costos.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Costos&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo de mantenimiento ($M/Gesti&oacute;n)</td>
                                      <?php
									  	if($cos_id!=NULL) $fldCosto = get_db_value("select cos_mantenimiento from py_costos where cos_id=$cos_id and cos_jue_id=$jue_id");
									  ?>
                                      <td class="ClearDataTD"><input name="costo" value="<?=$fldCosto?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($cos_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.cos_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="cos_id" value="<?=$cos_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>
        </body>
</html>

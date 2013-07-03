<?php
include ("../config.php");
include ("../common2.php");
session_start();
$jue_id= get_param("jue_id");
$pro_id= get_param("pro_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $pro_id);
if ($FormAction=='delete') delete($jue_id, $pro_id);


function insert ($jue_id)
{
	global $db;
	$fldDescripcion = get_param("descripcion");
	$fldValor = get_param("valor");
	$fldIdPar = get_param("id");
	$fldProyPar = get_param("proypar");
	
	$valCant = get_db_value("select count(*) from py_proyectos where pro_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(pro_id) from py_proyectos where pro_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into py_proyectos values(". tosql($iniValue,"Number") .", ". tosql($fldDescripcion,"Text").", ". tosql($fldValor,"Number").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	foreach($fldIdPar as $key=>$value)
	{

		if($fldProyPar[$key]==NULL) $fldProyPar[$key]=0;
		$sSQL="insert into py_proypar values(". tosql($iniValue,"Number") .", ". tosql($value,"Number").", ". tosql($fldProyPar[$key],"Number").", ". tosql($jue_id,"Text").")";
		$db->query($sSQL);
	
	
	}
	header("location: proyectos.php?jue_id=$jue_id");
	
}

function update($jue_id, $pro_id)
{
	global $db;
	$fldDescripcion = get_param("descripcion");
	$fldValor = get_param("valor");
	$fldIdPar = get_param("id");
	$fldProyPar = get_param("proypar");
	
	$sSQL="update py_proyectos set pro_descripcion=". tosql($fldDescripcion,"Text").", pro_duracion=". tosql($fldValor,"Number")." where pro_jue_id=". tosql($jue_id,"Text")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	$sSQL="delete from py_proypar where prp_jue_id=". tosql($jue_id,"Text")." and prp_pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
//	print_r($fldIdPar);
	foreach($fldIdPar as $key=>$value)
	{
	
		

		if($fldProyPar[$key]==NULL) $fldProyPar[$key]=0;
		$sSQL="insert into py_proypar values(". tosql($pro_id,"Number") .", ". tosql($value,"Number").", ". tosql($fldProyPar[$key],"Number").", ". tosql($jue_id,"Text").")";
		$db->query($sSQL);
	}
	header("location: proyectos.php?jue_id=$jue_id");	
}

function delete($jue_id, $pro_id)
{
	global $db;
	$sSQL="delete from py_proyectos where pro_jue_id=". tosql($jue_id,"Text")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	header("location: proyectos.php?jue_id=$jue_id");	
}
?>
<html>
        <head>
                <title>Proyectos</title>
               
                <link rel="stylesheet" href="../Themes/style.css" />
                <link href="../Themes/navmenu.css" type="text/css" rel="stylesheet">
                <link href="../Themes/navmenu3.css" type="text/css" rel="stylesheet">
				<link href="../Themes/style.css" type="text/css" rel="stylesheet">
                <link href="../Themes/Clear/Style.css" type="text/css" rel="stylesheet">
        </head>
        <body>
                <div id="tabs">
                		<?php
							$idActive2 = "id=\"active\"";
							$idActive11 = "id=\"active\"";
							
                        	include("menu_horiz2.php");
						?>                            
                        <div id="tabs-1">
                               
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                  de Proyectos</font><br>
                                    </p>
                                <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Proyecto</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Duraci&oacute;n</td>
                                      	<?php
											  $sSQL="select par_descripcion from py_parametros where par_jue_id=$jue_id order by par_id asc";
											  $db1->query($sSQL);
											  $cantidadRegistros1 = $db1->num_rows();
												if($cantidadRegistros1>0)
												{
												  while($db1->next_record())
												  {
										?>
												   <td class="ClearColumnTD" nowrap="nowrap"><?= $db1->f("par_descripcion")?></td>
										<?php 
												  }
											    }
										?>
                                    </tr>
                                    <?php
										$sSQL="select * from py_proyectos where pro_jue_id=$jue_id order by pro_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="proyectos.php?pro_id=<?=$db->f("pro_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_descripcion")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_duracion")?></td>
                                              <?php
											  $sSQL="select prp_valor from py_proypar where prp_jue_id=$jue_id and prp_pro_id=". tosql($db->f("pro_id"),"Number")." order by prp_par_id asc";
											  $db2->query($sSQL);
											  $cantidadRegistros = $db2->num_rows();
												if($cantidadRegistros>0)
												{
												  while($db2->next_record())
												  {
												  ?>
												  <td class="ClearDataTD"><?= $db2->f("prp_valor")?></td>
												  <?php 
												  }
											    }
											  ?>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="<?=4+$cantidadRegistros1?>">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="proyectos.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Proyectos&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Proyecto</td>
                                      <?php
									  	if($pro_id!=NULL) {
										
										$fldDescripcion = get_db_value("select pro_descripcion from py_proyectos where pro_id=$pro_id and pro_jue_id=$jue_id");
										$fldValor = get_db_value("select pro_duracion from py_proyectos where pro_id=$pro_id and pro_jue_id=$jue_id");
										
										}
									  ?>
                                      <td class="ClearDataTD"><input name="descripcion" value="<?=$fldDescripcion?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Duraci&oacute;n</td>
                                      <td class="ClearDataTD"><input name="valor" value="<?=$fldValor?>"></td>
                                     </tr>
                                     
                                     <?php
											  $sSQL="select  par_id, par_descripcion from py_parametros where par_jue_id=$jue_id order by par_id asc";
											  $db1->query($sSQL);
											  $cantidadRegistros1 = $db1->num_rows();
												if($cantidadRegistros1>0)
												{
												  while($db1->next_record())
												  {
												  		if($pro_id!=NULL) {
												  		$valIdProyPar= get_db_value("select prp_valor from py_proypar where prp_jue_id=$jue_id and prp_pro_id= $pro_id and prp_par_id=". tosql($db1->f("par_id"),"Number"));
														if($valIdProyPar==NULL) $valIdProyPar= '0.00';
														}
										?>
                                                   <tr>
			                                       <td class="ClearFieldCaptionTD"><?= $db1->f("par_descripcion")?></td>
												   <td class="ClearDataTD"><input name="proypar[]" value="<?=$valIdProyPar?>"><input type="hidden" name="id[]" value="<?= $db1->f("par_id")?>"></td>
                                                   </tr>
										<?php 
												  }
											    }
										?>
                                     
                                     
                                     
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($pro_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.pro_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="pro_id" value="<?=$pro_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>
        </body>
</html>

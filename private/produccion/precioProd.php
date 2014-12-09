<?php
//include ("../config_mysql.php");
include ("../common2.php");
@session_start();
$jue_id= get_param("jue_id");
$pre_id= get_param("pre_id");
$fldpro_id = get_param("pro_id");
$fldperiodo1 = get_param("periodo1");
$fldperiodo2 = get_param("periodo2");
$fldperiodo3 = get_param("periodo3");
$fldperiodo4 = get_param("periodo4");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $pre_id);
if ($FormAction=='delete') delete($jue_id, $pre_id);


function insert ($jue_id)
{
	global $db;
	$fldpro_id = get_param("pro_id");
	$fldperiodo1 = get_param("periodo1");
	$fldperiodo2 = get_param("periodo2");
	$fldperiodo3 = get_param("periodo3");
	$fldperiodo4 = get_param("periodo4");
	
	$valCant = get_db_value("select count(*) from pd_preciodemanda where pre_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(pre_id) from pd_preciodemanda where pre_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into pd_preciodemanda values(". tosql($iniValue,"Number") .", ". tosql($fldpro_id,"Number") . ", " 
	. tosql($fldperiodo1,"Number") . ", " . tosql($fldperiodo2,"Number") . ", " . tosql($fldperiodo3,"Number") . ", " 
	. tosql($fldperiodo4,"Number") . ", "
	. tosql($jue_id,"Number").")";
	$db->query($sSQL);

	header("location: precioProd.php?jue_id=$jue_id");
	
}

function update($jue_id, $pre_id)
{
	global $db;
	$fldpro_id = get_param("pro_id");
	$fldperiodo1 = get_param("periodo1");
	$fldperiodo2 = get_param("periodo2");
	$fldperiodo3 = get_param("periodo3");
	$fldperiodo4 = get_param("periodo4");
	
	$sSQL="update pd_preciodemanda "
	."set pre_pro_id=". tosql($fldpro_id,"Text")
	.",  pre_periodo1=". tosql($fldperiodo1,"Number")
	.",  pre_periodo2=". tosql($fldperiodo2,"Number")
	.",  pre_periodo3=". tosql($fldperiodo3,"Number")	
	.",  pre_periodo4=". tosql($fldperiodo4,"Number")	
	." where pre_jue_id=". tosql($jue_id,"Number")." and pre_id=". tosql($pre_id,"Number");
	$db->query($sSQL);
	header("location: precioProd.php?jue_id=$jue_id");	
}

function delete($jue_id, $pre_id)
{
	global $db;
	$sSQL="delete from pd_preciodemanda where pre_jue_id=". tosql($jue_id,"Text")." and pre_id=". tosql($pre_id,"Number");
	$db->query($sSQL);
	header("location: precioProd.php?jue_id=$jue_id");	
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
							$idActive13 = "id=\"active\"";
							$idActive12 = "";
							$idActive11 = "";
							$idActive14 = "";
							
							$idActive2 = "";
							$idActive3 = "";
							$idActive4 = "";
							
                        	include("menu_horiz.php");
						?>                            
                        <div id="tabs-1">
                               
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Precio Producto </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD">Id</td>
                                      <td class="ClearColumnTD">Demanda Producto</td>
                                      <td class="ClearColumnTD">Gestion 1</td>
                                      <td class="ClearColumnTD">Gestion 2</td>
                                      <td class="ClearColumnTD">Gestion 3</td>
                                      <td class="ClearColumnTD">Gestion 4</td>
                                    </tr>
                                    <?php
										$sSQL="select * from pd_preciodemanda where pre_jue_id=$jue_id order by pre_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
												$fldproducto = get_db_value("select pro_producto from pd_producto where pro_id=".$db->f("pre_pro_id") . " and pro_jue_id=$jue_id");
											
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="precioProd.php?pre_id=<?=$db->f("pre_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("pre_id")?></td>
                                              <td class="ClearDataTD"><?= $fldproducto ?></td>
                                              <td class="ClearDataTD"><?= $db->f("pre_periodo1")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pre_periodo2")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pre_periodo3")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pre_periodo4")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="12">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                 <?php
								 	if ($pre_id!=NULL)
									{
										$sSQL="select * from pd_preciodemanda where pre_jue_id=$jue_id and pre_id=$pre_id";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
                                              $fldpro_id = $db->f("pre_pro_id");
                                              $fldperiodo1 = $db->f("pre_periodo1");
                                              $fldperiodo2 = $db->f("pre_periodo2");
                                              $fldperiodo3 = $db->f("pre_periodo3");
                                              $fldperiodo4 = $db->f("pre_periodo4");
											 
											}
										}
									
									}
								 ?>
                                  <form method="Get" action="precioProd.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Precio Producto&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Demanda Producto</td>
                                      <td class="ClearDataTD"><select name="pro_id">
                                      <option value="">Seleccionar Producto</option>
                                    <?php
                                        $sSQL="select pro_id, pro_producto from pd_producto where pro_jue_id=$jue_id";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
                                                        if($db->f("pro_id") == $fldpro_id)
                                                          $selected="SELECTED"; else $selected="";
                                    		?>
                                              <option value="<?=$db->f("pro_id")?>" <?=$selected?>><?=$db->f("pro_producto")?></option>
		                                    <?                    
                                             }
                                       }
                                      ?>
                                         </select></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Gestion 1</td>
                                      <td class="ClearDataTD"><input name="periodo1" value="<?=$fldperiodo1?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Gestion 2</td>
                                      <td class="ClearDataTD"><input name="periodo2" value="<?=$fldperiodo2?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Gestion 3</td>
                                      <td class="ClearDataTD"><input name="periodo3" value="<?=$fldperiodo3?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Gestion 4</td>
                                      <td class="ClearDataTD"><input name="periodo4" value="<?=$fldperiodo4?>"></td>
                                     </tr>
                                     
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($pre_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.pre_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="pre_id" value="<?=$pre_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>
        </body>
</html>

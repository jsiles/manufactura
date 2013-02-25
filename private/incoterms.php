<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$inc_id= get_param("inc_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $inc_id);
if ($FormAction=='delete') delete($jue_id, $inc_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldIncoterm = get_param("incoterm");
	$sSQL="insert into tb_incoterms values(null, ". tosql($fldIncoterm,"Text").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: incoterms.php?jue_id=$jue_id");
}

function update($jue_id, $inc_id)
{
	global $db;
	$fldIncoterm = get_param("incoterm");
	$sSQL="update tb_incoterms set inc_name=". tosql($fldIncoterm,"Text")." where inc_jue_id=". tosql($jue_id,"Text")." and inc_id=". tosql($inc_id,"Number");
	$db->query($sSQL);
	header("location: incoterms.php?jue_id=$jue_id");
}

function delete($jue_id, $inc_id)
{
	global $db;
	$fldIncoterm = get_param("incoterm");
	$sSQL="delete from tb_incoterms where inc_jue_id=". tosql($jue_id,"Text")." and inc_id=". tosql($inc_id,"Number");
	$db->query($sSQL);
	header("location: incoterms.php?jue_id=$jue_id");
}
?>
<html>
        <head>
                <title>Compras</title>
               
                <link rel="stylesheet" href="Themes/style.css" />
                <link href="Themes/navmenu.css" type="text/css" rel="stylesheet">
				<link href="Themes/style.css" type="text/css" rel="stylesheet">
                <link href="Themes/Clear/Style.css" type="text/css" rel="stylesheet">
        </head>
        <body>
                <div id="tabs">
                		<div id="nav2">
                            <ul id="navmenu2">
                                    <li><a id="active" href="#tabs-1">Param&eacute;tricas</a>
                                     <ul>
                                        <li><a href="compras3.php?jue_id=<?=$jue_id?>">Productos</a></li>
                                        <li><a href="incoterms.php?jue_id=<?=$jue_id?>">Incoterms</a></li>
                                        <li><a href="transporte.php?jue_id=<?=$jue_id?>">Tipo de transporte</a></li>
                                        <li><a href="proveedor.php?jue_id=<?=$jue_id?>">Proveedor</a></li>
                                        <li><a href="suministro.php?jue_id=<?=$jue_id?>">Tipo Suministro</a></li>
                                	</ul>
                                    </li>
                                    <li><a href="mesa.php?jue_id=<?=$jue_id?>">Mesa Proveedores</a></li>
                                    <li><a href="descuentos.php?jue_id=<?=$jue_id?>">Descuentos</a></li>
                            </ul>
                        </div>
                        <div id="tabs-1">
              <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Incoterms </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Incoterm</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_incoterms where inc_jue_id=$jue_id order by inc_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="incoterms.php?inc_id=<?=$db->f("inc_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("inc_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("inc_name")?></td>
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
                                  <form method="Get" action="incoterms.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Incoterm&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Incoterm</td>
                                      <?php
									  	if($inc_id!=NULL) $fldIncoterm = get_db_value("select inc_name from tb_incoterms where inc_id=$inc_id and inc_jue_id=$jue_id");
									  ?>
                                      <td class="ClearDataTD"><input name="incoterm" value="<?=$fldIncoterm?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($inc_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.inc_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="inc_id" value="<?=$inc_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

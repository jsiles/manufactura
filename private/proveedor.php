<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$pro_id= get_param("pro_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $pro_id);
if ($FormAction=='delete') delete($jue_id, $pro_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldproveedor = get_param("proveedor");
	$sSQL="insert into tb_proveedor values(null, ". tosql($fldproveedor,"Text").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: proveedor.php?jue_id=$jue_id");
}

function update($jue_id, $pro_id)
{
	global $db;
	$fldproveedor = get_param("proveedor");
	$sSQL="update tb_proveedor set pro_name=". tosql($fldproveedor,"Text")." where pro_jue_id=". tosql($jue_id,"Text")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	header("location: proveedor.php?jue_id=$jue_id");
}

function delete($jue_id, $pro_id)
{
	global $db;
	$fldproveedor = get_param("proveedor");
	$sSQL="delete from tb_proveedor where pro_jue_id=". tosql($jue_id,"Text")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	header("location: proveedor.php?jue_id=$jue_id");

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
                		<div id="nav2">
                            <ul id="navmenu2">
                                    <li><a id="active" href="#tabs-1">Param&eacute;tricas</a></li>
                                    <li><a href="mesa.php?jue_id=<?=$jue_id?>">Mesa Proveedores</a></li>
                                    <li><a href="descuentos.php?jue_id=<?=$jue_id?>">Descuentos</a></li>
                                    <li><a href="incotran.php?jue_id=<?=$jue_id?>">Factor Incoterms &amp; Transporte</a></li>
                            </ul>
                        </div>
                        <div id="nav3">
                            <ul id="navmenu3">
                                <li><a href="compras3.php?jue_id=<?=$jue_id?>">Productos</a></li>
                                <li><a href="incoterms.php?jue_id=<?=$jue_id?>">Incoterms</a></li>
                                <li><a href="transporte.php?jue_id=<?=$jue_id?>">Tipo de transporte</a></li>
                                <li><a id="active" href="proveedor.php?jue_id=<?=$jue_id?>">Proveedor</a></li>
                                <li><a href="suministro.php?jue_id=<?=$jue_id?>">Tipo Suministro</a></li>
                                <li><a href="gestion.php?jue_id=<?=$jue_id?>">Gesti&oacute;n</a></li>
                             </ul>
                        </div>
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Proveedores </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Proveedor</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_proveedor where pro_jue_id=$jue_id order by pro_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="proveedor.php?pro_id=<?=$db->f("pro_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_name")?></td>
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
                                  <form method="Get" action="proveedor.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Proveedores&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Proveedor</td>
                                      <?php
									  	if($pro_id!=NULL) $fldproveedor = get_db_value("select pro_name from tb_proveedor where pro_id=$pro_id and pro_jue_id=$jue_id");
									  ?>
                                      <td class="ClearDataTD"><input name="proveedor" value="<?=$fldproveedor?>"></td>
                                     </tr>
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

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
	
	$valCant = get_db_value("select count(*) from tb_proveedor where pro_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(pro_id) from tb_proveedor where pro_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into tb_proveedor values(".tosql($iniValue, "Number").", ". tosql($fldproveedor,"Text").", ". tosql($jue_id,"Text").")";
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
                		  <?php
							$idActive1 = "id=\"active\"";
							$idActive14 = "id=\"active\"";
							
                        	include("menu_horiz.php");
						?>		
                        
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

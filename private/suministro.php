<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$sum_id= get_param("sum_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $sum_id);
if ($FormAction=='delete') delete($jue_id, $sum_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldsuministro = get_param("suministro");
	$fldsuministrocosto = get_param("costo");
	$fldsuministrotiempo = get_param("tiempo");
			
	$sSQL="insert into tb_suministro values(null, ". tosql($fldsuministro,"Text").", ". tosql($fldsuministrocosto,"Number").", ". tosql($fldsuministrotiempo,"Number").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: suministro.php?jue_id=$jue_id");
}

function update($jue_id, $sum_id)
{
	global $db;
	$fldsuministro = get_param("suministro");
	$fldsuministrocosto = get_param("costo");
	$fldsuministrotiempo = get_param("tiempo");
	$sSQL="update tb_suministro set sum_name=". tosql($fldsuministro,"Text").", sum_cost=". tosql($fldsuministrocosto,"Number").", sum_time=". tosql($fldsuministrotiempo,"Number")." where sum_jue_id=". tosql($jue_id,"Text")." and sum_id=". tosql($sum_id,"Number");
	$db->query($sSQL);
	header("location: suministro.php?jue_id=$jue_id");
}

function delete($jue_id, $sum_id)
{
	global $db;
	$sSQL="delete from tb_suministro where sum_jue_id=". tosql($jue_id,"Text")." and sum_id=". tosql($sum_id,"Number");
	$db->query($sSQL);
	header("location: suministro.php?jue_id=$jue_id");

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
                                    <li><a href="incotran.php?jue_id=<?=$jue_id?>">Factor Incoterms &amp; Transporte</a></li>
                            </ul>
                        </div>
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de suministros </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Suministro</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Costo Suministro</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Tiempo de Suministro</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_suministro where sum_jue_id=$jue_id order by sum_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="suministro.php?sum_id=<?=$db->f("sum_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("sum_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("sum_name")?></td>
                                              <td class="ClearDataTD"><?= $db->f("sum_cost")?></td>
                                              <td class="ClearDataTD"><?= $db->f("sum_time")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="5">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="suministro.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar suministros&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Suministro</td>
                                      <?php
									  	if($sum_id!=NULL) {
											$fldsuministro = get_db_value("select sum_name from tb_suministro where sum_id=$sum_id and sum_jue_id=$jue_id");
											$fldsuministrocosto = get_db_value("select sum_cost from tb_suministro where sum_id=$sum_id and sum_jue_id=$jue_id");
											$fldsuministrotiempo = get_db_value("select sum_time from tb_suministro where sum_id=$sum_id and sum_jue_id=$jue_id");
										}
									  ?>
                                      <td class="ClearDataTD"><input name="suministro" value="<?=$fldsuministro?>"></td>
                                      </tr>
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Costo Suministro</td>
                                      <td class="ClearDataTD"><input name="costo" value="<?=$fldsuministrocosto?>"></td>
                                      </tr>
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Tiempo de Suministro</td>
                                      <td class="ClearDataTD"><input name="tiempo" value="<?=$fldsuministrotiempo?>"></td>
                                      
                                     </tr>
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($sum_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.sum_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="sum_id" value="<?=$sum_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

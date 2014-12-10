<?php
include ("../common2.php");
@session_start();
$jue_id= get_param("jue_id");
$cos_id= get_param("cos_id");
$fldtotal = get_param("total");
$fldcantidadini = get_param("cantidadini");
$fldajuste = get_param("ajuste");
$fldhora = get_param("hora");
$fldhoraextra = get_param("horaextra");
$fldcontratacion = get_param("contratacion");
$flddespido = get_param("despido");
$fldcapacitacion = get_param("capacitacion");
$fldalmacenaje = get_param("almacenaje");
$fldmaq1 = get_param("maq1");
$fldmaq2 = get_param("maq2");
$fldmaq3 = get_param("maq3");
$fldmaq4 = get_param("maq4");
$fldmaq5 = get_param("maq5");
$fldmaq6 = get_param("maq6");
$fldmaq7 = get_param("maq7");
$fldmaq8 = get_param("maq8");
$fldfactor = get_param("factor");
$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $cos_id);
if ($FormAction=='delete') delete($jue_id, $cos_id);


function insert ($jue_id)
{
	global $db;
	global $fldtotal, $fldcantidadini,$fldajuste,$fldhora,$fldhoraextra,$fldcontratacion,$flddespido,$fldcapacitacion,$fldalmacenaje,$fldmaq1, $fldmaq2, $fldmaq3, $fldmaq4, $fldmaq5, $fldmaq6, $fldmaq7, $fldmaq8, $fldfactor;
	$valCant = get_db_value("select count(*) from pd_costosIniciales where cos_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(cos_id) from pd_costosIniciales where cos_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into pd_costosIniciales values(". tosql($iniValue,"Number")
	.", ". tosql($fldtotal,"Number")
	.", ". tosql($fldcantidadini,"Number")
	.", ". tosql($fldajuste,"Number")
	.", ". tosql($fldhora,"Number")
	.", ". tosql($fldhoraextra,"Number")
	.", ". tosql($fldcontratacion,"Number")
	.", ". tosql($flddespido,"Number")
	.", ". tosql($fldcapacitacion,"Number")
	.", ". tosql($fldalmacenaje,"Number")
	.", ". tosql($fldmaq1,"Number")
	.", ". tosql($fldmaq2,"Number")
	.", ". tosql($fldmaq3,"Number")
	.", ". tosql($fldmaq4,"Number")
	.", ". tosql($fldmaq5,"Number")
	.", ". tosql($fldmaq6,"Number")
	.", ". tosql($fldmaq7,"Number")
	.", ". tosql($fldmaq8,"Number")
	.", ". tosql($fldfactor,"Number")				
	.", ". tosql($jue_id,"Number").")";
	$db->query($sSQL);
	header("location: costos.php?jue_id=$jue_id");
	
}

function update($jue_id, $cos_id)
{
	global $db;
	global $fldtotal, $fldcantidadini,$fldajuste,$fldhora,$fldhoraextra,$fldcontratacion,$flddespido,$fldcapacitacion,$fldalmacenaje,$fldmaq1, $fldmaq2, $fldmaq3, $fldmaq4, $fldmaq5, $fldmaq6, $fldmaq7, $fldmaq8, $fldfactor;
	$fldCosto = get_param("costo");
	$sSQL="update pd_costosIniciales set "
	." cos_total=". tosql($fldtotal,"Number")
	." , cos_cantidadini=". tosql($fldcantidadini,"Number")
	." , cos_ajuste=". tosql($fldajuste,"Number")
	." , cos_hora=". tosql($fldhora,"Number")
	." , cos_horaextra=". tosql($fldhoraextra,"Number")
	." , cos_contratacion=". tosql($fldcontratacion,"Number")
	." , cos_despido=". tosql($flddespido,"Number")
	." , cos_capacitacion=". tosql($fldcapacitacion,"Number")
	." , cos_almacenaje=". tosql($fldalmacenaje,"Number")
	." , cos_maq1=". tosql($fldmaq1,"Number")
	." , cos_maq2=". tosql($fldmaq2,"Number")
	." , cos_maq3=". tosql($fldmaq3,"Number")
	." , cos_maq4=". tosql($fldmaq4,"Number")
	." , cos_maq5=". tosql($fldmaq5,"Number")
	." , cos_maq6=". tosql($fldmaq6,"Number")
	." , cos_maq7=". tosql($fldmaq7,"Number")
	." , cos_maq8=". tosql($fldmaq8,"Number")
	." , cos_factor=". tosql($fldfactor,"Number")
    ." where cos_jue_id=". tosql($jue_id,"Number")." and cos_id=". tosql($cos_id,"Number");
	$db->query($sSQL);
	header("location: costos.php?jue_id=$jue_id");	
}

function delete($jue_id, $cos_id)
{
	global $db;
	$fldCosto = get_param("costo");
	$sSQL="delete from pd_costosIniciales where cos_jue_id=". tosql($jue_id,"Text")." and cos_id=". tosql($cos_id,"Number");
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
							$idActive14 = "id=\"active\"";
							$idActive2 = "";
							$idActive11 = "";
							$idActive12 = "";
							$idActive13 = "";
							$idActive3 = "";
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
                                      <td class="ClearColumnTD">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD">Id</td>
                                      <td class="ClearColumnTD">Costo Total MP inicial (M$)</td>
                                      <td class="ClearColumnTD">Cantidad inicial unidades MP</td>
                                      <td class="ClearColumnTD">Costo Ajuste (M$/Hora)</td>
                                      <td class="ClearColumnTD">Costo Hora Normal (M$/Hora)</td>
                                      <td class="ClearColumnTD">Costo Hora Extra (M$/Hora)</td>
                                      <td class="ClearColumnTD">Costo Contratación (M$)</td>
                                      <td class="ClearColumnTD">Costo Despido (M$)</td>
                                      <td class="ClearColumnTD">Costo Capacitación (M$/Empleado/Día)</td>
                                      <td class="ClearColumnTD">Costo Almacenaje (M$/unidad/periodo)</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 1</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 2</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 3</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 4</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 5</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 6</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 7</td>
                                      <td class="ClearColumnTD">Último producto en Máquina 8</td>                                      
                                      <td class="ClearColumnTD">Factor de costo MO Despido Contratación y Capacitación</td>
                                    </tr>
                                    <?php
								
										$sSQL="select * from pd_costosIniciales where cos_jue_id=$jue_id order by cos_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="costos.php?cos_id=<?=$db->f("cos_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_total")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_cantidadini")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_ajuste")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_hora")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_horaextra")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_contratacion")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_despido")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_capacitacion")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_almacenaje")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq1")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq2")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq3")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq4")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq5")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq6")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq7")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_maq8")?></td>
                                              <td class="ClearDataTD"><?= $db->f("cos_factor")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="20">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                 <?php
								 	if ($cos_id!=NULL)
									{
										$sSQL="select * from pd_costosIniciales where cos_jue_id=$jue_id and cos_id=$cos_id";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
                                              $fldtotal = $db->f("cos_total");
                                              $fldcantidadini = $db->f("cos_cantidadini");
                                              $fldajuste = $db->f("cos_ajuste");
                                              $fldhora = $db->f("cos_hora");
                                              $fldhoraextra = $db->f("cos_horaextra");
                                              $fldcontratacion = $db->f("cos_contratacion");
                                              $flddespido = $db->f("cos_despido");
                                              $fldcapacitacion = $db->f("cos_capacitacion");
                                              $fldalmacenaje = $db->f("cos_almacenaje");
                                              $fldmaq1 = $db->f("cos_maq1");
                                              $fldmaq2 = $db->f("cos_maq2");
											  $fldmaq3 = $db->f("cos_maq3");
											  $fldmaq4 = $db->f("cos_maq4");
											  $fldmaq5 = $db->f("cos_maq5");
											  $fldmaq6 = $db->f("cos_maq6");
											  $fldmaq7 = $db->f("cos_maq7");
											  $fldmaq8 = $db->f("cos_maq8");
											  $fldfactor = $db->f("cos_factor");
											 
											}
										}
									
									}
								 ?>
                                  <form method="Get" action="costos.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Costos&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Total MP inicial (M$)</td>
                                      <td class="ClearDataTD"><input name="total" value="<?=$fldtotal?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Cantidad inicial unidades MP</td>
                                      <td class="ClearDataTD"><input name="cantidadini" value="<?=$fldcantidadini?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Ajuste (M$/Hora)</td>
                                      <td class="ClearDataTD"><input name="ajuste" value="<?=$fldajuste?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Hora Normal (M$/Hora)</td>
                                      <td class="ClearDataTD"><input name="hora" value="<?=$fldhora?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Hora Extra (M$/Hora)</td>
                                      <td class="ClearDataTD"><input name="horaextra" value="<?=$fldhoraextra?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Contratación (M$)</td>
                                      <td class="ClearDataTD"><input name="contratacion" value="<?=$fldcontratacion?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Despido (M$)</td>
                                      <td class="ClearDataTD"><input name="despido" value="<?=$flddespido?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Capacitación (M$/Empleado/Día)</td>
                                      <td class="ClearDataTD"><input name="capacitacion" value="<?=$fldcapacitacion?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Almacenaje (M$/unidad/periodo)</td>
                                      <td class="ClearDataTD"><input name="almacenaje" value="<?=$fldalmacenaje?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 1</td>
                                      <td class="ClearDataTD"><input name="maq1" value="<?=$fldmaq1?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 2</td>
                                      <td class="ClearDataTD"><input name="maq2" value="<?=$fldmaq2?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 3</td>
                                      <td class="ClearDataTD"><input name="maq3" value="<?=$fldmaq3?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 4</td>
                                      <td class="ClearDataTD"><input name="maq4" value="<?=$fldmaq4?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 5</td>
                                      <td class="ClearDataTD"><input name="maq5" value="<?=$fldmaq5?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 6</td>
                                      <td class="ClearDataTD"><input name="maq6" value="<?=$fldmaq6?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 7</td>
                                      <td class="ClearDataTD"><input name="maq7" value="<?=$fldmaq7?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Último producto en Máquina 8</td>
                                      <td class="ClearDataTD"><input name="maq8" value="<?=$fldmaq8?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Factor de costo MO Despido Contratación y Capacitación</td>
                                      <td class="ClearDataTD"><input name="factor" value="<?=$fldfactor?>"></td>
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

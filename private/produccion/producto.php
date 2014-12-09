<?php
include_once ("../common2.php");
@session_start();
$jue_id= get_param("jue_id");
$pro_id= get_param("pro_id");
	$fldproducto = get_param("producto");
	$fldunidadesreq = get_param("unidadesreq");
	$fldprodmedia = get_param("prodmedia");
	$fldcantinter = get_param("cantinter");
	$fldcantfinal = get_param("cantfinal");
	$fldcostointer = get_param("costointer");
	$fldcostofinal = get_param("costofinal");
	$fldmulta = get_param("multa");
	$fldalmacenaje = get_param("almacenaje");
	$fldtiempo = get_param("tiempo");
	
	
$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $pro_id);
if ($FormAction=='delete') delete($jue_id, $pro_id);


function insert ($jue_id)
{
	global $db;
	$fldproducto = get_param("producto");
	$fldunidadesreq = get_param("unidadesreq");
	$fldprodmedia = get_param("prodmedia");
	$fldcantinter = get_param("cantinter");
	$fldcantfinal = get_param("cantfinal");
	$fldcostointer = get_param("costointer");
	$fldcostofinal = get_param("costofinal");
	$fldmulta = get_param("multa");
	$fldalmacenaje = get_param("almacenaje");
	$fldtiempo = get_param("tiempo");
	
	$valCant = get_db_value("select count(*) from pd_producto where pro_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(pro_id) from pd_producto where pro_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into pd_producto values(". tosql($iniValue,"Number") .", ". tosql($fldproducto,"Text") . ", " 
	. tosql($fldunidadesreq,"Number") . ", " . tosql($fldprodmedia,"Number") . ", " . tosql($fldcantinter,"Number") . ", " 
	. tosql($fldcantfinal,"Number") . ", " . tosql($fldcostointer,"Number") . ", " . tosql($fldcostofinal,"Number") . ", " 
	. tosql($fldmulta,"Number") . ", " . tosql($fldalmacenaje,"Number") . ", " . tosql($fldtiempo,"Number") . ", "  
	. tosql($jue_id,"Number").")";
	$db->query($sSQL);
	
	header("location: producto.php?jue_id=$jue_id");
	
}

function update($jue_id, $pro_id)
{
	global $db;
	$fldproducto = get_param("producto");
	$fldunidadesreq = get_param("unidadesreq");
	$fldprodmedia = get_param("prodmedia");
	$fldcantinter = get_param("cantinter");
	$fldcantfinal = get_param("cantfinal");
	$fldcostointer = get_param("costointer");
	$fldcostofinal = get_param("costofinal");
	$fldmulta = get_param("multa");
	$fldalmacenaje = get_param("almacenaje");
	$fldtiempo = get_param("tiempo");
	
	$sSQL="update pd_producto "
	."set pro_producto=". tosql($fldproducto,"Text")
	.",  pro_unidadesreq=". tosql($fldunidadesreq,"Number")
	.",  pro_prodmedia=". tosql($fldprodmedia,"Number")
	.",  pro_cantinter=". tosql($fldcantinter,"Number")	
	.",  pro_cantfinal=". tosql($fldcantfinal,"Number")	
	.",  pro_costointer=". tosql($fldcostointer,"Number")	
	.",  pro_costofinal=". tosql($fldcostofinal,"Number")	
	.",  pro_multa=". tosql($fldmulta,"Number")	
	.",  pro_almacenaje=". tosql($fldalmacenaje,"Number")		
	.",  pro_tiempo=". tosql($fldtiempo,"Number")	
	." where pro_jue_id=". tosql($jue_id,"Number")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	header("location: producto.php?jue_id=$jue_id");	
}

function delete($jue_id, $pro_id)
{
	global $db;
	$sSQL="delete from pd_producto where pro_jue_id=". tosql($jue_id,"Text")." and pro_id=". tosql($pro_id,"Number");
	$db->query($sSQL);
	header("location: producto.php?jue_id=$jue_id");	
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
							$idActive12 = "";
							$idActive13 = "";
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
                                    de Productos </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD">Id</td>
                                      <td class="ClearColumnTD">Producto</td>
                                      <td class="ClearColumnTD">Unidades MP requeridas por producto</td>
                                      <td class="ClearColumnTD">Productividad Media Producto/Hora</td>
                                      <td class="ClearColumnTD">Cantidad Inicial Producto Intermedio</td>
                                      <td class="ClearColumnTD">Cantidad Inicial Producto Final</td>
                                      <td class="ClearColumnTD">Costo Inicial Producto Intermedio [M$/producto Intermedio]</td>
                                      <td class="ClearColumnTD">Costo Inicial Producto Final [M$/producto Final]</td>
                                      <td class="ClearColumnTD">Multa [M$/producto NO entregado]</td>
                                      <td class="ClearColumnTD">Costo Almacenaje [M$/unidad/Periodo]</td>
                                      <td class="ClearColumnTD">Tiempo de ajuste [hr]</td>
                                    </tr>
                                    <?php
										$sSQL="select * from pd_producto where pro_jue_id=$jue_id order by pro_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="producto.php?pro_id=<?=$db->f("pro_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_producto")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_unidadesreq")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_prodmedia")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_cantinter")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_cantfinal")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_costointer")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_costofinal")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_multa")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_almacenaje")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_tiempo")?></td>
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
								 	if ($pro_id!=NULL)
									{
										$sSQL="select * from pd_producto where pro_jue_id=$jue_id and pro_id=$pro_id";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
                                              $fldproducto = $db->f("pro_producto");
                                              $fldunidadesreq = $db->f("pro_unidadesreq");
                                              $fldprodmedia = $db->f("pro_prodmedia");
                                              $fldcantinter = $db->f("pro_cantinter");
                                              $fldcantfinal = $db->f("pro_cantfinal");
                                              $fldcostointer = $db->f("pro_costointer");
                                              $fldcostofinal = $db->f("pro_costofinal");
                                              $fldmulta = $db->f("pro_multa");
                                              $fldalmacenaje = $db->f("pro_almacenaje");
                                              $fldtiempo = $db->f("pro_tiempo");
											 
											}
										}
									
									}
								 ?>
                                  <form method="Get" action="producto.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Producto&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Producto</td>
                                      <td class="ClearDataTD"><input name="producto" value="<?=$fldproducto?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Unidades MP requeridas por producto</td>
                                      <td class="ClearDataTD"><input name="unidadesreq" value="<?=$fldunidadesreq?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Productividad Media Producto/Hora</td>
                                      <td class="ClearDataTD"><input name="prodmedia" value="<?=$fldprodmedia?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Cantidad Inicial Producto Intermedio</td>
                                      <td class="ClearDataTD"><input name="cantinter" value="<?=$fldcantinter?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Cantidad Inicial Producto Final</td>
                                      <td class="ClearDataTD"><input name="cantfinal" value="<?=$fldcantfinal?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Inicial Producto Intermedio [M$/producto Intermedio]</td>
                                      <td class="ClearDataTD"><input name="costointer" value="<?=$fldcostointer?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Inicial Producto Final [M$/producto Final]</td>
                                      <td class="ClearDataTD"><input name="costofinal" value="<?=$fldcostofinal?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Multa [M$/producto NO entregado]</td>
                                      <td class="ClearDataTD"><input name="multa" value="<?=$fldmulta?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Costo Almacenaje [M$/unidad/Periodo]</td>
                                      <td class="ClearDataTD"><input name="almacenaje" value="<?=$fldalmacenaje?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Tiempo de ajuste [hr]</td>
                                      <td class="ClearDataTD"><input name="tiempo" value="<?=$fldtiempo?>"></td>
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

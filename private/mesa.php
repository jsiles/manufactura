<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$mes_id= get_param("mes_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $mes_id);
if ($FormAction=='delete') delete($jue_id, $mes_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldproducto = get_param("producto");
	$fldproveedor = get_param("proveedor");
	$fldunidadpedido = get_param("unidadpedido");
	$fldpreciounitario = get_param("preciounitario");
			
	$sSQL="insert into tb_mesaproveedores values(null, ". tosql($fldproducto,"Number").", ". tosql($fldproveedor,"Number").", ". tosql($fldpreciounitario,"Number").", ". tosql($fldunidadpedido,"Number").", ". tosql($jue_id,"Number").")";
	$db->query($sSQL);
	header("location: mesa.php?jue_id=$jue_id");
}

function update($jue_id, $mes_id)
{
	global $db;
	$fldproducto = get_param("producto");
	$fldproveedor = get_param("proveedor");
	$fldunidadpedido = get_param("unidadpedido");
	$fldpreciounitario = get_param("preciounitario");

	$sSQL="update tb_mesaproveedores set mes_com_id=". tosql($fldproducto,"Number").", mes_pro_id=". tosql($fldproveedor,"Number").", mes_pedido=". tosql($fldunidadpedido,"Number").", mes_precio=". tosql($fldpreciounitario,"Number")." where mes_jue_id=". tosql($jue_id,"Number")." and mes_id=". tosql($mes_id,"Number");
	$db->query($sSQL);
	header("location: mesa.php?jue_id=$jue_id");
}

function delete($jue_id, $mes_id)
{
	global $db;
	$sSQL="delete from tb_mesaproveedores where mes_jue_id=". tosql($jue_id,"Number")." and mes_id=". tosql($mes_id,"Number");
	$db->query($sSQL);
	header("location: mesa.php?jue_id=$jue_id");

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
                                    <li><a href="compras3.php?jue_id=<?=$jue_id?>">Param&eacute;tricas</a>
                                     <ul>
                                        <li><a href="compras3.php?jue_id=<?=$jue_id?>">Productos</a></li>
                                        <li><a href="incoterms.php?jue_id=<?=$jue_id?>">Incoterms</a></li>
                                        <li><a href="transporte.php?jue_id=<?=$jue_id?>">Tipo de transporte</a></li>
                                        <li><a href="proveedor.php?jue_id=<?=$jue_id?>">Proveedor</a></li>
                                        <li><a href="suministro.php?jue_id=<?=$jue_id?>">Tipo Suministro</a></li>
                                	</ul>
                                    </li>
                                    <li><a id="active" href="mesa.php?jue_id=<?=$jue_id?>">Mesa Proveedores</a></li>
                                    <li><a href="descuentos.php?jue_id=<?=$jue_id?>">Descuentos</a></li>
                                    <li><a href="incotran.php?jue_id=<?=$jue_id?>">Factor Incoterms &amp; Transporte</a></li>
                            </ul>
                        </div>
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de mesa proveedores </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Productos</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Unidades de Pedido</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Proveedor</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Precio Unitario</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_mesaproveedores where mes_jue_id=$jue_id order by mes_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
												$fldCompra = get_db_value("select pro_name from tb_productos2 where  pro_id = ".tosql($db->f("mes_com_id"), "Number"));
												$fldProveedores = get_db_value("select pro_name from tb_proveedor where  pro_id = ".tosql($db->f("mes_pro_id"), "Number"));
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="mesa.php?mes_id=<?=$db->f("mes_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("mes_id")?></td>
                                              <td class="ClearDataTD"><?= $fldCompra?></td>
                                              <td class="ClearDataTD"><?= $db->f("mes_pedido")?></td>
                                              <td class="ClearDataTD"><?= $fldProveedores?></td>
                                              <td class="ClearDataTD"><?= $db->f("mes_precio")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="6">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="mesa.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar mesa de proveedores&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Productos</td>
                                      <?php
									  
									  	$arrayProducto = db_fill_array("select pro_id, pro_name from tb_productos2 where pro_jue_id=$jue_id");
										$arrayProveedor = db_fill_array("select pro_id, pro_name from tb_proveedor where pro_jue_id=$jue_id");

									  	if($mes_id!=NULL) {
											$fldproducto = get_db_value("select mes_com_id from tb_mesaproveedores where mes_id=$mes_id and mes_jue_id=$jue_id");
											$fldunidadpedido = get_db_value("select mes_pedido from tb_mesaproveedores where mes_id=$mes_id and mes_jue_id=$jue_id");
											$fldproveedor = get_db_value("select mes_pro_id from tb_mesaproveedores where mes_id=$mes_id and mes_jue_id=$jue_id");
											$fldpreciounitario = get_db_value("select mes_precio from tb_mesaproveedores where mes_id=$mes_id and mes_jue_id=$jue_id");
										}
									  ?>
                                      <td class="ClearDataTD">
                                      <select name="producto">
                                      	<option value="">Seleccione valor</option>
 										  <?php
										  	foreach($arrayProducto as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldproducto) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select>
                                      </tr>
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Unidades de pedido</td>
                                      <td class="ClearDataTD"><input name="unidadpedido" value="<?=$fldunidadpedido?>"></td>
                                      </tr>
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Proveedor</td>
                                      <td class="ClearDataTD"><select name="proveedor">
                                      	<option value="">Seleccione valor</option>
 										  <?php
										  	foreach($arrayProveedor as $key=>$value)
											{
											
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldproveedor) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select></td>
                                     </tr>
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Precio Unitario</td>
                                      <td class="ClearDataTD"><input name="preciounitario" value="<?=$fldpreciounitario?>"></td>
                                     </tr>

                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($mes_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.mes_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="mes_id" value="<?=$mes_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

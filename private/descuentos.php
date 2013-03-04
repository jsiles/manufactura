<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$mes_id= get_param("mes_id");

$FormAction= get_param("FormAction");

if ($FormAction=='update') update($jue_id);


function update($jue_id)
{
	global $db;
	$fldposition = get_param("position");
	$flddescuentos = get_param("descuentos");
	//print_r($fldposition);
	//print_r($flddescuentos);
	$sSQL = "delete from tb_descuentos where des_jue_id=". tosql($jue_id, "Number");
	$db->query($sSQL);
	for($x=0;$x<count($fldposition);$x++)
	{
		$arrayPos = explode("|", $fldposition[$x]);
		$descuentoNumber = (tosql($flddescuentos[$x], "Number")=="NULL")?0:tosql($flddescuentos[$x], "Number");
		$sSQL = "insert into tb_descuentos values(null,". tosql($arrayPos[0], "Number").",". tosql($arrayPos[1], "Number").",". $descuentoNumber .",". tosql($jue_id, "Number")."    )";
		//echo $sSQL;
		$db->query($sSQL);
	}
	//exit;
	header("location: descuentos.php?jue_id=$jue_id");
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
                                    <li><a href="mesa.php?jue_id=<?=$jue_id?>">Mesa Proveedores</a></li>
                                    <li><a id="active" href="descuentos.php?jue_id=<?=$jue_id?>">Descuentos</a></li>
                                    <li><a href="incotran.php?jue_id=<?=$jue_id?>">Factor Incoterms &amp; Transporte</a></li>
                            </ul>
                        </div>
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Descuentos </font><br>
                                    </p>
                              <form method="Get" action="descuentos.php" name="valoresRecord">
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">Grupo</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Proveedor</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Descuento (%)</td>
                                    </tr>
                                    <?php
										$arrayProveedor= db_fill_array("select pro_id, pro_name from tb_proveedor where pro_jue_id=$jue_id");

										$sSQL="select * from tb_usuarios where usu_jue_id=$jue_id order by usu_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
								
									?>
                                            <tr>  
                                              <td class="ClearDataTD" rowspan="2"><?= $db->f("usu_nombre")?></td>
                                              <?php
											  foreach($arrayProveedor as $key=>$value)
											  {
											  $descuentosValue = get_db_value("select des_porcentaje from tb_descuentos where des_jue_id=$jue_id and des_usu_id=".$db->f("usu_id")." and des_pro_id=".$key);
											  ?>
                                              <td class="ClearDataTD"><?= $value?></td>
                                              <td class="ClearDataTD" align="right"><input name="descuentos[]" size="6" value="<?=$descuentosValue?>"  type="text" />
                                            <input name="position[]" value="<?=$db->f("usu_id")."|".$key?>" type="hidden" /> &nbsp;&nbsp;</td>
                                               </tr>
											  <?php
											  }
											  ?>
                                                                                           
                                            
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
                                 <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="3">
                                
                                      <!-- ***   Buttons   *** -->
                                      <!--BeginvaloresRecordUpdate-->
                                      <input class="ClearButton" type="submit" value="Modificar" onClick="document.valoresRecord.FormAction.value = 'update';"/>&nbsp;&nbsp;
                                      <!--EndvaloresRecordUpdate-->
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                     </td>
                                    </tr>
                                 </table>
                               </form>
                                 <br>
                                  

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

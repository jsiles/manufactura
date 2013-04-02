<?php
include ("common2.php");
include ("globals.php");
session_start();
$jue_id= get_param("jue_id");
$int_id= get_param("int_id");

$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $int_id);
if ($FormAction=='delete') delete($jue_id, $int_id);

//print_r($arrayMateriales);

function insert ($jue_id)
{
	global $db;
	$fldIncoterms = get_param("incoterm");
	$fldTransporte = get_param("transporte");
	$fldFactorTra = get_param("factorTra");
	$fldTiempoTra = get_param("tiempoTra");
	
	$valCant = get_db_value("select count(*) from tb_incotran where int_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(int_id) from tb_incotran where int_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
			
	$sSQL="insert into tb_incotran values(". tosql($iniValue,"Number").", ". tosql($fldIncoterms,"Number").", ". tosql($fldTransporte,"Number").", ". tosql($fldFactorTra,"Number").", ". tosql($fldTiempoTra,"Number").", ". tosql($jue_id,"Number").")";
	$db->query($sSQL);
	header("location: incotran.php?jue_id=$jue_id");
}

function update($jue_id, $int_id)
{
	global $db;
	$fldIncoterms = get_param("incoterm");
	$fldTransporte = get_param("transporte");
	$fldFactorTra = get_param("factorTra");
	$fldTiempoTra = get_param("tiempoTra");

	$sSQL="update tb_incotran set int_tra_id=". tosql($fldIncoterms,"Number").", int_tra_id=". tosql($fldTransporte,"Number").", int_factorTra=". tosql($fldFactorTra,"Number").", int_tiempoTra=". tosql($fldTiempoTra,"Number")." where int_jue_id=". tosql($jue_id,"Number")." and int_id=". tosql($int_id,"Number");
	$db->query($sSQL);
	header("location: incotran.php?jue_id=$jue_id");
}

function delete($jue_id, $int_id)
{
	global $db;
	$sSQL="delete from tb_incotran where int_jue_id=". tosql($jue_id,"Number")." and int_id=". tosql($int_id,"Number");
	$db->query($sSQL);
	header("location: incotran.php?jue_id=$jue_id");

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
                                    <li><a href="compras3.php?jue_id=<?=$jue_id?>">Param&eacute;tricas</a></li>
                                    <li><a href="mesa.php?jue_id=<?=$jue_id?>">Mesa Proveedores</a></li>
                                    <li><a href="descuentos.php?jue_id=<?=$jue_id?>">Descuentos</a></li>
                                    <li><a id="active" href="incotran.php?jue_id=<?=$jue_id?>">Transporte &amp; Aduana</a></li>
                            </ul>
                        </div>
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Transporte &amp; Aduana </font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Incoterm</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Transporte</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Factor Transporte &amp; Aduana</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Tiempo Transporte &amp; Aduana</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_incotran where int_jue_id=$jue_id order by int_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
												$fldIncoterms = get_db_value("select inc_name from tb_incoterms where  inc_id = ".tosql($db->f("int_inc_id"), "Number"));
												$fldTransporte = get_db_value("select tra_name from tb_transporte where  tra_id = ".tosql($db->f("int_tra_id"), "Number"));
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="incotran.php?int_id=<?=$db->f("int_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("int_id")?></td>
                                              <td class="ClearDataTD"><?= $fldIncoterms?></td>
                                              <td class="ClearDataTD"><?= $fldTransporte?></td>
                                              <td class="ClearDataTD"><?= $db->f("int_factorTra")?></td>
                                              <td class="ClearDataTD"><?= $db->f("int_tiempoTra")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="8">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="incotran.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Factor Incoterms &amp; Transporte&nbsp; </font> 
                                  <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Incoterms</td>
                                      <?php
									  
									  	$arrayIncoterms= db_fill_array("select inc_id, inc_name from tb_incoterms where inc_jue_id=$jue_id");
										$arrayTransporte = db_fill_array("select tra_id, tra_name from tb_transporte where tra_jue_id=$jue_id");

									  	if($int_id!=NULL) {
											$fldIncoterms = get_db_value("select int_inc_id from tb_incotran where int_id=$int_id and int_jue_id=$jue_id");
											$fldTransporte = get_db_value("select int_tra_id from tb_incotran where int_id=$int_id and int_jue_id=$jue_id");
											$fldFactorTra = get_db_value("select int_factorTra from tb_incotran where int_id=$int_id and int_jue_id=$jue_id");
											$fldTiempoTra = get_db_value("select int_tiempoTra from tb_incotran where int_id=$int_id and int_jue_id=$jue_id");
											
										}
									  ?>
                                      <td class="ClearDataTD">
                                      <select name="incoterm">
                                      	<option value="">Seleccione valor</option>
 										  <?php
										  	foreach($arrayIncoterms as $key=>$value)
											{
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldIncoterms) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select>
                                      </tr>
                                      
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Transporte</td>
                                      <td class="ClearDataTD"><select name="transporte">
                                      	<option value="">Seleccione valor</option>
 										  <?php
										  	foreach($arrayTransporte as $key=>$value)
											{
											
										  ?>
                                          <option value="<?=$key?>" <?php if ($key==$fldTransporte) echo "Selected"; ?>><?=$value?></option>                 
                                          <?php
											}
										  ?>
                                      </select></td>
                                     </tr>
                                     
                                      <tr>
                                      <td class="ClearFieldCaptionTD">Factor Transporte &amp; Aduana</td>
                                      <td class="ClearDataTD"><input name="factorTra" value="<?=$fldFactorTra?>"></td>
                                      </tr>

                                      <tr>
                                      <td class="ClearFieldCaptionTD">Tiempo Transporte &amp; Aduana</td>
                                      <td class="ClearDataTD"><input name="tiempoTra" value="<?=$fldTiempoTra?>"></td>
                                      </tr>

                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($int_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.int_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="int_id" value="<?=$int_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>                                
                                
        </body>
</html>

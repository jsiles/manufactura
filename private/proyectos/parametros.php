<?php
include ("../common2.php");
@session_start();
$jue_id= get_param("jue_id");
$par_id= get_param("par_id");
$fldDescripcion = get_param("descripcion");
$fldValor = get_param("valor");
$FormAction= get_param("FormAction");

if ($FormAction=='insert') insert($jue_id);
if ($FormAction=='update') update($jue_id, $par_id);
if ($FormAction=='delete') delete($jue_id, $par_id);


function insert ($jue_id)
{
	global $db;
	$fldDescripcion = get_param("descripcion");
	$fldValor = get_param("valor");
	$valCant = get_db_value("select count(*) from py_parametros where par_jue_id=". tosql($jue_id,"Number"));
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(par_id) from py_parametros where par_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	
	$sSQL="insert into py_parametros values(". tosql($iniValue,"Number") .", ". tosql($fldDescripcion,"Text").", ". tosql($fldValor,"Number").", ". tosql($jue_id,"Text").")";
	$db->query($sSQL);
	header("location: parametros.php?jue_id=$jue_id");
	
}

function update($jue_id, $par_id)
{
	global $db;
	$fldDescripcion = get_param("descripcion");
	$fldValor = get_param("valor");
	$sSQL="update py_parametros set par_descripcion=". tosql($fldDescripcion,"Text").", par_valor=". tosql($fldValor,"Number")." where par_jue_id=". tosql($jue_id,"Text")." and par_id=". tosql($par_id,"Number");
	$db->query($sSQL);
	header("location: parametros.php?jue_id=$jue_id");	
}

function delete($jue_id, $par_id)
{
	global $db;
	$sSQL="delete from py_parametros where par_jue_id=". tosql($jue_id,"Text")." and par_id=". tosql($par_id,"Number");
	$db->query($sSQL);
	header("location: parametros.php?jue_id=$jue_id");	
}
?>
<html>
        <head>
                <title>Par&aacute;metros</title>
               
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
							$idActive12 = "id=\"active\"";
							$idActive11 = "";
							$idActive2 = "";
                        	include("menu_horiz.php");
						?>                            
                        <div id="tabs-1">
                               
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Parametros de Mejora</font><br>
                                    </p>
                                 <table>
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">&nbsp;&nbsp;</td>	
                                      <td class="ClearColumnTD" nowrap="nowrap">Id</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Par&aacute;metro</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Valor Inicial</td>
                                    </tr>
                                    <?php
										$sSQL="select * from py_parametros where par_jue_id=$jue_id order by par_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><a href="parametros.php?par_id=<?=$db->f("par_id")?>&jue_id=<?=$jue_id?>">Detalles</a></td>
                                              <td class="ClearDataTD"><?= $db->f("par_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("par_descripcion")?></td>
                                              <td class="ClearDataTD"><?= $db->f("par_valor")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="4">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                 <br>
                                  <form method="Get" action="parametros.php" name="valoresRecord">
                                  <font class="ClearFormHeaderFont">Agregar/Editar Parametros de Mejora&nbsp; </font> 
                                 <table class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
                                     <tr>
                                      <td class="ClearErrorDataTD" colspan="2"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Par&aacute;metro</td>
                                      <?php
									  	if($par_id!=NULL) {
										
										$fldDescripcion = get_db_value("select par_descripcion from py_parametros where par_id=$par_id and par_jue_id=$jue_id");
										$fldValor = get_db_value("select par_valor from py_parametros where par_id=$par_id and par_jue_id=$jue_id");
										
										}
									  ?>
                                      <td class="ClearDataTD"><input name="descripcion" value="<?=$fldDescripcion?>"></td>
                                     </tr>
                                     <tr>
                                      <td class="ClearFieldCaptionTD">Valor inicial</td>
                                      <td class="ClearDataTD"><input name="valor" value="<?=$fldValor?>"></td>
                                     </tr>
                                     
                                     
                                     <tr>
                                      <td class="ClearFooterTD" nowrap align="right" colspan="2">
                                
                                      <!-- ***   Buttons   *** -->
                                      <?php
									  if($par_id==NULL)
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
                                      <input class="ClearButton" type="submit" value="Cancelar" onClick="document.valoresRecord.FormAction.value = 'cancel';document.valoresRecord.par_id.value = '';"/>
                                      <!--EndvaloresRecordCancel-->
                                      
                                      <input type="hidden" name="FormName" value="valoresRecord"/>
                                      <input type="hidden" name="FormAction" value=""/> 
                                      <input type="hidden" name="jue_id" value="<?=$jue_id?>"/>
                                      <input type="hidden" name="par_id" value="<?=$par_id?>"/>
                                      
                                     </td>
                                    </tr>
                                   </table>
                                  </form>

                                </div>
                                
                        </div>
                </div>
        </body>
</html>

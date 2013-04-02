<?php
include ("common2.php");
include ("globals.php");
session_start();
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=".get_param("jue_id")."");
$per_periodo = get_param("per_periodo");
if (!$per_periodo) $per_periodo=1;
$jue_id= get_param("jue_id");
$mes_id= get_param("mes_id");

$FormAction= get_param("FormAction");

if ($FormAction=='update') update($jue_id,$arrayPeriodo);


function update($jue_id, $arrayPeriodo)
{
	global $db;
	$fldperiodo = get_param("periodo");
	//print_r($arrayPeriodo);
	//print_r($fldperiodo);
	$sSQL = "delete from tb_comprashabilita where coh_jue_id=". tosql($jue_id, "Number") ;
	$db->query($sSQL);
	for($x=1;$x<=count($arrayPeriodo);$x++)
	{
		if(in_array($arrayPeriodo[$x],$fldperiodo)) $iValue = 1;
		else $iValue=0;
		$sSQL = "insert into tb_comprashabilita values(null,". tosql($arrayPeriodo[$x], "Number").",". tosql($iValue, "Number").",". tosql($jue_id, "Number").")";
		//echo $sSQL;
		$db->query($sSQL);
	}
	//exit;
	header("location: gestion.php?jue_id=$jue_id");
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
							$idActive16 = "id=\"active\"";
							
                        	include("menu_horiz.php");
						?>		
                        
                        <div id="tabs-1">
                                <div id="tabs-1-1" >
                                <p>
                                    <font class="ClearFormHeaderFont">Lista
                                    de Gesti&oacute;n </font>
                                    </p>
                              <form method="Get" action="gestion.php" name="valoresRecord">
                                
                                  
                                 <table>
	                               
									<tr>
                                      <td class="ClearColumnTD" nowrap="nowrap">Periodo</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Activo</td>
                                    </tr>
                                    <?php
										if(isset($arrayPeriodo))
										{
										 foreach($arrayPeriodo as $key => $value)
										 {
										 
										 $valCheckedCompras = get_db_value("select coh_activo from tb_comprashabilita where coh_jue_id=".tosql($jue_id, "Number")." and coh_per_id=". tosql($key, "Number"));
										 if($valCheckedCompras==1) $sChecked = "checked";
										 else $sChecked = "";
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><?= $value?></td>
                                              <td class="ClearDataTD" align="right"><input name="periodo[]" value="<?=$key?>" <?=$sChecked?> type="checkbox" />
                                            
                                            &nbsp;&nbsp;</td>
                                               </tr>
											  <?php
										 }
										 $valButton=0;
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
                                      <?php
									  	if($valButton==0)
										{
										
									  ?>
                                      <input class="ClearButton" type="submit" value="Modificar" onClick="document.valoresRecord.FormAction.value = 'update';"/>&nbsp;&nbsp;
                                      <?php
									  	}
									  ?>
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

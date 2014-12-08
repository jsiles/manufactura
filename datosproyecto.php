<?php
include ("common.php");
session_start();
$jue_id= get_param("jue_id");
$pro_id= get_param("pro_id");

$FormAction= get_param("FormAction");

 $fldInicial = dlookup("tb_juegos", "jue_periodoInicial" , "jue_id=$jue_id");
 $fldCantidad = dlookup("tb_juegos", "jue_cantidad" , "jue_id=$jue_id");
 $juego=dlookup("tb_juegos", "jue_nombre" , "jue_id=$jue_id");
 
 $periodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$jue_id and per_inv_estado='A'");

 $fldperiodo = get_param("per_id");
 if(!$fldperiodo) $fldperiodo = get_session("LBperiodo");
 if(!$fldperiodo) $fldperiodo = $fldInicial;
 set_session("LBperiodo",$fldperiodo);
if ($FormAction=='update') update($jue_id, $pro_id, $fldperiodo);

 
function update($jue_id, $pro_id, $fldperiodo)
{
	global $db;
	$fldInversion = get_param("inversion");
	$fldMantenimiento = get_param("mantenimiento");
	$fldCliId = get_session("cliID");
	
	$sSQL="delete from py_datos where dat_jue_id=". tosql($jue_id,"Number") ." and dat_gestion=". tosql($fldperiodo,"Number");
	$db->query($sSQL);
	$valCant = get_db_value("select count(*) from py_datos where dat_jue_id=". tosql($jue_id,"Number"));
	
	if($valCant==0) {
	$iniValue=1;
	}
	else {
		$maxValue = get_db_value("select max(dat_id) from py_datos where dat_jue_id=". tosql($jue_id,"Number"));
		$iniValue=$maxValue+1;
	}
	$cantidadArray = count($fldInversion);
	for($x=0;$x<$cantidadArray;$x++)
	{
		if($fldInversion[$x]==NULL) $fldInversion[$x]=0;
		if($fldMantenimiento[$x]==NULL) $fldMantenimiento[$x]=0;
		
		$sSQL="insert into py_datos values(". tosql($iniValue,"Number") .",  ". tosql($pro_id[$x],"Number").", ". tosql($fldInversion[$x],"Number").", ". tosql($fldMantenimiento[$x],"Number").",  ". tosql($fldperiodo,"Number").", ". tosql($jue_id,"Number").", ". tosql($fldCliId,"Number").")";
		$db->query($sSQL);
		
		$iniValue++;
	}
	header("location: datosproyecto.php?jue_id=$jue_id");	
}
function calcValores($iDuracion, $iPeriodo, $iJue_id, $iProyecto, $iUserId)
{
	global $fldCantidad, $fldInicial, $db3, $db4;
	$costoMantenimiento = get_db_value("select cos_mantenimiento from py_costos where cos_jue_id=$iJue_id order by cos_id limit 1");
	$iPeriodoVal = $fldInicial;
	$valor=0;
	for($x=0;$x<$iPeriodo;$x++)
	{
		
		$inversionMat[$iPeriodoVal][$iProyecto] = get_db_value("select dat_inversion from py_datos where dat_jue_id=$iJue_id and dat_pro_id=".$iProyecto." and dat_gestion=". tosql($iPeriodoVal, "Number")." and dat_usu_id=". tosql($iUserId, "Number"));
		$mantenimientoMat[$iPeriodoVal][$iProyecto] = get_db_value("select dat_mantenimiento from py_datos where dat_jue_id=$iJue_id and dat_pro_id=".$iProyecto." and dat_gestion=". tosql($iPeriodoVal, "Number")." and dat_usu_id=". tosql($iUserId, "Number"));
		$auxInvMat[$iPeriodoVal][$iProyecto] = 0;
		$auxManMat[$iPeriodoVal][$iProyecto] = 0;
		$aux2[$iPeriodoVal][$iProyecto] = 0;
		$auxMat[$iPeriodoVal][$iProyecto] = 0;
		if($iPeriodoVal==1)
		{
			if($inversionMat[$iPeriodoVal][$iProyecto]>0) $auxInvMat[$iPeriodoVal][$iProyecto] = 1;
			if($mantenimientoMat[$iPeriodoVal][$iProyecto]==$costoMantenimiento) $auxManMat[$iPeriodoVal][$iProyecto] = 1;
			
			$auxMat[$iPeriodoVal][$iProyecto] =$auxInvMat[$iPeriodoVal][$iProyecto]+ $auxManMat[$iPeriodoVal][$iProyecto];
			if(($auxMat[$iPeriodoVal][$iProyecto]>0)&&($auxMat[$iPeriodoVal][$iProyecto]>$iDuracion)) $valor = 1; else $valor=0;
		}
		if($iPeriodoVal>1)
		{
			if($inversionMat[$iPeriodoVal][$iProyecto]>0) $auxInvMat[$iPeriodoVal][$iProyecto] = 1 ;
			$auxInvMat[$iPeriodoVal][$iProyecto]+=$auxInvMat[$iPeriodoVal-1][$iProyecto];
			if($mantenimientoMat[$iPeriodoVal][$iProyecto]==$costoMantenimiento) $auxManMat[$iPeriodoVal][$iProyecto] = 1; else $auxManMat[$iPeriodoVal][$iProyecto] = 0; 
			$auxManMat[$iPeriodoVal][$iProyecto]+=$auxManMat[$iPeriodoVal-1][$iProyecto];
			
			$aux2[$iPeriodoVal][$iProyecto] = $inversionMat[$iPeriodoVal][$iProyecto] + $mantenimientoMat[$iPeriodoVal][$iProyecto];
			$auxMat[$iPeriodoVal][$iProyecto] =$auxInvMat[$iPeriodoVal][$iProyecto]+ $auxManMat[$iPeriodoVal][$iProyecto];
			if(($aux2[$iPeriodoVal][$iProyecto]>0)&&($auxMat[$iPeriodoVal][$iProyecto]>$auxMat[$iPeriodoVal-1][$iProyecto])&&($auxMat[$iPeriodoVal][$iProyecto]>$iDuracion)) $valor = 1;else $valor=0;

		}
		$iPeriodoVal++;
	}
	if($valor==1){
					$sSQL="select prp_par_id, prp_valor from py_proypar where prp_jue_id=$iJue_id and prp_pro_id=$iProyecto order by prp_par_id asc";
 				    $db3->query($sSQL);
					while($db3->next_record())
					{
					 //$iPeriodo, $iJue_id, $iProyecto, $iUserId
					 $cantCal= get_db_value("select count(*) from py_calculos where cal_jue_id=$iJue_id and cal_per_id=$iPeriodo and cal_usu_id=$iUserId and cal_pro_id=$iProyecto and cal_par_id=".tosql($db3->f("prp_par_id"), "Number"));
					 if($cantCal>0) $db4->query("delete from py_calculos where cal_jue_id=$iJue_id and cal_per_id=$iPeriodo and cal_usu_id=$iUserId and cal_pro_id=$iProyecto and cal_par_id=".tosql($db3->f("prp_par_id"), "Number"));
					 $sSQL="insert into py_calculos values(null, $iPeriodo, $iProyecto, ".tosql($db3->f("prp_par_id"), "Number").", $iUserId, ".tosql($db3->f("prp_valor"), "Number").", $iJue_id)";
					 $db4->query($sSQL);
					}
	}else{
		$sSQL="select prp_par_id, prp_valor from py_proypar where prp_jue_id=$iJue_id and prp_pro_id=$iProyecto order by prp_par_id asc";
 		$db3->query($sSQL);
			while($db3->next_record())
			{
				$db4->query("delete from py_calculos where cal_jue_id=$iJue_id and cal_per_id=$iPeriodo and cal_usu_id=$iUserId and cal_pro_id=$iProyecto and cal_par_id=".tosql($db3->f("prp_par_id"), "Number"));
			}
	
	}
	return $valor;
}
?>
<html>
        <head>
                <title>Datos de Proyecto</title>
               
				<link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
        </head>
        <body>
                <div id="tabs">
                		<?php
							$idActive3 = "id=\"active\"";
							$idActive11 = "id=\"active\"";
							
                        	//include("private/proyectos/menu_horiz3.php");
							$sSQL="select par_descripcion from py_parametros where par_jue_id=$jue_id order by par_id asc";
							$db1->query($sSQL);
							$cantidadRegistros1 = $db1->num_rows();
						?>                            
                        <div id="tabs-1">
                               
                                <div id="tabs-1-1" >
                                <p class="title3">MEJORA PORCENTUAL DE PARAMETROS</p>
                                <p><br>
                                </p>
                                <form method="Get" action="datosproyecto.php" name="valoresRecord">
                                 <table cellspacing="0" cellpadding="0" border="0">
                                 <tr>
                                   <td valign="top">
  <table class="Grid" cellspacing="0" cellpadding="0" border="1">
                                 <tr class="Caption">
                                    <td class="ClearFieldCaptionTD" width="136"> Gesti&oacute;n:<select name="per_id" onChange="submit();">
                                    
                                    <?php
                                                    if(is_array($periodo))
                                                    {
                                                      reset($periodo);
                                                      $i=0;                                   
                                                      while(list($key, $value) = each($periodo))
                                                      {
                                                        if ($i==0&&$fldperiodo=='') $fldperiodo = $key;
                                                        if($key == $fldperiodo)
                                                          $selected="SELECTED"; else $selected="";
                                    ?>
                                                  <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                                    <?                    
                                                        $i++;
                                                      }
                                                    }
                                    
                                      

                                      ?>
                                         </select>
                                         </td>
                                          <td class="ClearFieldCaptionTD" colspan="<?=4+$cantidadRegistros1?>" align="center">Juego: <?=$juego?></td>
                                          </tr>
									<tr class="Row">
                                      <!--<td class="ClearColumnTD" nowrap="nowrap">Id</td>-->
                                      <td class="ClearColumnTD" nowrap="nowrap">Proyecto</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Gestiones antes beneficios</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Inversi&oacute;n (M$)</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Mantenimiento (M$)</td>
                                      	<?php
											  
												if($cantidadRegistros1>0)
												{
												  while($db1->next_record())
												  {
										?>
												   <td class="ClearColumnTD" nowrap="nowrap"><?= $db1->f("par_descripcion")?></td>
										<?php 
												  }
											    }
										?>
                                    </tr>
                                    <?php
										$fldCliId = get_session("cliID");
										$mantValue = get_db_value("select cos_mantenimiento from py_costos where cos_jue_id=$jue_id limit 1");
										$sSQL="select * from py_proyectos where pro_jue_id=$jue_id order by pro_id asc";
										//echo $sSQL;
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
										   //echo $db->num_rows();
											while($result=$db->next_record())
											{
												$inversion = get_db_value("select dat_inversion from py_datos where dat_jue_id=$jue_id and dat_pro_id=".$db->f("pro_id")." and dat_usu_id=$fldCliId and dat_gestion=". tosql($fldperiodo, "Number"));
												$mantenimiento = get_db_value("select dat_mantenimiento from py_datos where dat_jue_id=$jue_id and dat_pro_id=".$db->f("pro_id")." and dat_usu_id=$fldCliId and dat_gestion=". tosql($fldperiodo, "Number"));				
												$periodoInversion = get_db_value("select min(dat_gestion) from py_datos where dat_jue_id=$jue_id and dat_pro_id=".$db->f("pro_id")." and dat_usu_id=$fldCliId and dat_inversion>0 ");				
								
												if($inversion==NULL) $inversion=0;
												if($mantenimiento==NULL) $mantenimiento=0;	
												if($periodoInversion==NULL) $periodoInversion=0;	
												$calcVal = $fldperiodo - $periodoInversion - $db->f("pro_duracion");
												//echo $periodoInversion."#" . $calcVal ."@In:". $inversion ." man:".$mantenimiento. "<br />";
												if(($periodoInversion>0)&&($calcVal>=0))//||($db->f("pro_duracion")==0))
												{
												
													if(($inversion>0)||($mantenimiento>0))
													{
														$fldRedIn="<font color=\"#FF0000\">";
														$fldRedFin="</font>";
													 }else{
														$fldRedIn="";
														$fldRedFin="";
													 }
												}else
												{
												$fldRedIn="";
												$fldRedFin="";
												}			
												
												if($fldperiodo>1)
												{
												
												$inversionAnt = get_db_value("select dat_inversion from py_datos where dat_jue_id=$jue_id and dat_pro_id=".$db->f("pro_id")." and dat_usu_id=$fldCliId and dat_gestion=". tosql($fldperiodo-1, "Number"));
												$mantenimientoAnt = get_db_value("select dat_mantenimiento from py_datos where dat_jue_id=$jue_id and dat_pro_id=".$db->f("pro_id")." and dat_usu_id=$fldCliId and dat_gestion=". tosql($fldperiodo-1, "Number"));		
												
												$selected="";
												$selected2="";
												
												if($mantValue==$mantenimiento)
			                                       $selected="SELECTED"; else $selected2="SELECTED";
												   
												if(($inversionAnt>0)||($mantenimientoAnt>0))
												{
												  $ListBoxMan = "<option value=\"$mantValue\" $selected>$mantValue</option>";
												}else $ListBoxMan = "";

												
												}				
									?>
                                            <tr class="Row">  
                                              	<!--<td class="ClearDataTD"><?= $db->f("pro_id")?></td>-->
                                              	<td class="ClearDataTD"><?=$fldRedIn?><?= $db->f("pro_descripcion")?><?=$fldRedFin?></td>
                                              	<td class="ClearDataTD"><?=$fldRedIn?><?= $db->f("pro_duracion")?><?=$fldRedFin?></td>
                      							<td class="ClearDataTD"><?=$fldRedIn?><input name="inversion[]" type="text" size="4" value="<?=$inversion?>" ><?=$fldRedFin?></td>
                                               	<td class="ClearDataTD"><?=$fldRedIn?>
                                                <select name="mantenimiento[]" >
                                                	<option value="0" <?=$selected2?>>0</option>
												    <?=$ListBoxMan?>                                  	            	
                                    		    </select>
                                                <?=$fldRedFin?>
                                              <input type="hidden" name="pro_id[]" value="<?= $db->f("pro_id")?>"/></td>
                                              <?php
											  calcValores($db->f("pro_duracion"), $fldperiodo, $jue_id, $db->f("pro_id"), $fldCliId);
											  $sSQL="select prp_valor from py_proypar where prp_jue_id=$jue_id and prp_pro_id=". tosql($db->f("pro_id"),"Number")." order by prp_par_id asc";
											  $db2->query($sSQL);
											  $cantidadRegistros = $db2->num_rows();
												if($cantidadRegistros>0)
												{
												  while($db2->next_record())
												  {
												  ?>
                                                  <td class="ClearDataTD"><?=$fldRedIn?><?= $db2->f("prp_valor")?><?=$fldRedFin?></td>
												  <?php
												  }
											    }
											  ?>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr class="Row">  
                                              <td class="ClearDataTD" colspan="<?=5+$cantidadRegistros1?>">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                    
                                    <tr class="Row">
                                      <td class="ClearFooterTD" nowrap align="left" colspan="<?=5+$cantidadRegistros1?>">&nbsp;
                                
                                      <!--BeginvaloresRecordEdit-->
                                      <!--BeginvaloresRecordUpdate-->
                                      <?php
                                      $maxPeriodo2 = get_db_value("select max(per_periodo) from tb_periodos where per_jue_id=$jue_id and per_estado='A'"); //modif
                                      if ($maxPeriodo2==$fldperiodo)
									  {
									  ?>
                                      <input class="ClearButton" type="submit" value="Aceptar" onClick="document.valoresRecord.FormAction.value = 'update';"/>
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
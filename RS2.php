<?php
/**
 *
 *
 * @version Jorge Siles
 * @copyright 2006
 */
include ("./common.php");
include_once "./Spreadsheet/Excel/Writer.php";
session_start();
$workbook =new Spreadsheet_Excel_Writer();
$workbook->setTempDir('/tmp/');
$workbook->send('responsabilidadsocial.xls');
$format_bold =& $workbook->addFormat();
$format_bold->setBold();
$workbook->setCustomColor(12, 253, 215, 0);
$workbook->setCustomColor(13, 230, 231, 232);
$workbook->setCustomColor(14, 208, 216, 207);
$workbook->setCustomColor(15, 220, 221, 222);
$workbook->setCustomColor(16, 244, 244, 244);

$format_title2 =& $workbook->addFormat(array(
																		  'Size' => 12,
                                                                          'Align' => 'left',
																		  'Color' => 'black',
                                                                          'Pattern' => 1,
																		  'Bold' => 650,
																		  'FgColor' => 9,
																		  'FontFamily' => 'Arial',
																		  ));

$format_title =& $workbook->addFormat(array('Size' => 9,
                                                                          'Align' => 'center',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 12,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));

$format_gridiz =& $workbook->addFormat(array('Size' => 8,
																		  'FontFamily' => 'Arial',
																		  'Align' => 'left',
																	      'Color' => 'black',
																	      'Pattern' => 1,
                                                                          'FgColor' => 16,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));
$format_grid =& $workbook->addFormat(array('Size' => 8,
	  																	  'FontFamily' => 'Arial',
                                                                          'Align' => 'right',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 16,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));

$format_gridnum =& $workbook->addFormat(array('Size' => 8,
	  																	  'FontFamily' => 'Arial',
                                                                          'Align' => 'right',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 16,
                                                                          'Border' => 1,
																		  'NumFormat'=>'#.00',
                                                                          'BorderColor'  => 'black'));
$format_gridalt =& $workbook->addFormat(array('Size' => 8,
																		  'FontFamily' => 'Arial',
																		  'Align' => 'center',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 'white',
                                                                          'Border' => 1,
																		  'BorderColor'  => 'gray'
                                                                          ));

$format_gridaltiz =& $workbook->addFormat(array('Size' => 8,
                                                                          'Align' => 'center',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 15,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));
$format_head =& $workbook->addFormat(array('Size' => 10,
                                                                          'Align' => 'left',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 'white',
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'white'));

$format_end =& $workbook->addFormat(array('Size' => 8,
                                                                          'Align' => 'center',
                                                                          'Color' => 15,
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 'white',
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'white'));

					$worksheet =& $workbook->addWorksheet("Reporte");
					$worksheet->write(0,0,"REPORTE DE RESPONSABILIDAD SOCIAL",$format_title2);
					$fldjue_id = get_param("jue_id");
					$fldper_id = get_param("per_id");
					if(!(int)$fldjue_id) $fldjue_id=0;
					if(!(int)$fldper_id) $fldper_id=0;
					
					$sSQL="select * from tb_responsabilidad where res_jue_id=$fldjue_id and res_per_id=$fldper_id order by res_id";
					$db->query($sSQL);
					if($db->num_rows()>0)
					{
					
					while($db->next_record()){
						
						$RS[$db->f("res_id")]=$db->f("res_nombre");
						$beneficio[$db->f("res_id")][1]=$db->f("res_beneficio1");
						$beneficio[$db->f("res_id")][2]=$db->f("res_beneficio2");
						$beneficio[$db->f("res_id")][3]=$db->f("res_beneficio3");
						$beneficio[$db->f("res_id")][4]=$db->f("res_beneficio4");
						$costo[$db->f("res_id")]=$db->f("res_precio");
					}
					
					$cantidadUsuariosTotal = get_db_value("select count(*) from tb_usuarios where usu_jue_id=$fldjue_id");
					foreach($RS as $key => $value)
					{
						$sSQL="select * from tb_inclusion where inc_res_id=$key";
						$db->query($sSQL);
						$cantidadUsuariosEnJuego = 0;
						while($db->next_record())
						{
							$inclusion[$key][$db->f("inc_usu_id")]=$db->f("inc_usu_id");
							$usuarios[$db->f("inc_usu_id")] =$db->f("inc_usu_id");
							$cantidadUsuariosEnJuego ++; 
						}
						$cantidadUsuarios[$key] = $cantidadUsuariosEnJuego; 
					}
					
						$sSQL="select * from tb_responsabilidadgeneral where reg_jue_id=$fldjue_id and reg_per_id=$fldper_id order by reg_id";
						$db->query($sSQL);
						while($db->next_record())
						{
							$indirecto[1]=$db->f("reg_beneficio1");
							$indirecto[2]=$db->f("reg_beneficio2");
							$indirecto[3]=$db->f("reg_beneficio3");
							$indirecto[4]=$db->f("reg_beneficio4");
						}
					
					$worksheet->write(2,0,"EMPRESA	",$format_title);
					$worksheet->mergeCells(2,0,3,0);
					$worksheet->setColumn(0,0,10);
					
					$i=1;
					
					foreach($RS as $key=>$value)
					{
						$worksheet->write(2,$i,"PROGRAMA ". strtoupper($value),$format_title);
						$worksheet->mergeCells(2,$i,2,$i+2);
						$worksheet->write(3,$i,"INVERSION",$format_title);
						$worksheet->setColumn(0,$i,strlen("INVERSION")+2);
					
						$worksheet->write(3,$i+1, "BENEFICIO DIRECTO",$format_title);
						$worksheet->setColumn(0,$i+1,strlen("BENEFICIO DIRECTO")+2);
					    
						$worksheet->write(3,$i+2, "ROI %",$format_title);
						$worksheet->setColumn(0,$i+1,strlen("ROI %")+2);
					    
						$i=$i+3;
					}
					
	
					$j=4;
					
					
					$arrayUser = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$fldjue_id");
					$totalUser = $arrayUser;
					$totalUser = count($totalUser)-1; 
					$totalUserIncluidos = count($usuarios);
					
					/*if($totalUserIncluidos == $totalUser) $valSw = true;
					else $valSw = false;
					*/
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					 $k=0;
					 $totalinversion=0;
					 $totalbeneficio=0;
					 	$worksheet->write($j,$k,$usuarioValue, $format_gridiz);
						
						foreach($RS as $key=>$value)
						{
							//Inversion
							$inversion = (count($inclusion[$key][$usuarioKey])!=0)?$costo[$key]:0;
							$worksheet->write($j,$k+1,$inversion, $format_grid);
							//$cantidadUsuarios[$key] = $cantidadUsuariosEnJuego; 
							$valSw=false;
							if($cantidadUsuarios[$key] == $totalUser)
							$valSw=true;
							//Beneficio
							if($valSw)
							$beneficiodirecto = (count($inclusion[$key][$usuarioKey])!=0)?$beneficio[$key][count($inclusion[$key])]:$beneficio[$key][4];
							else
							$beneficiodirecto = (count($inclusion[$key][$usuarioKey])!=0)?$beneficio[$key][count($inclusion[$key])]:0;
							
							//$beneficiodirecto = $cantidadUsuarios[$key]."-".$totalUser;
							
							$worksheet->write($j,$k+2, $beneficiodirecto , $format_grid);
							
							//ROI
							$worksheet->write($j,$k+3,($inversion!=0)?($beneficiodirecto-$inversion)/$inversion*100:0, $format_grid);													
							$totalinversion+=$inversion;
							$totalbeneficio+=$beneficiodirecto;
							$k+=3;
						}
					
					 $ranking[$usuarioKey] = $totalbeneficio;
					 $rankingInv[$usuarioKey] = $totalinversion;
					 $j++;	
					} //Fin foreach
					/*
					 * TOTAL PROGRAMAS
					 *
					 */
					$j =2;
					$worksheet =& $workbook->addWorksheet("Total Programas");
					//$worksheet->write(0,0,"REPORTE DE RESPONSABILIDAD SOCIAL",$format_title2);
					$worksheet->write($j,0," EMPRESA	",$format_title);
					$worksheet->mergeCells(2,0,3,0);
					$worksheet->setColumn(0,0,22);
					$worksheet->write($j,1,"INVERSION Y BENEFICIOS RS ",$format_title);
					$worksheet->mergeCells($j,1,$j,7);
					$worksheet->write($j+1,1,"TOTAL INVERSION ",$format_title);
					$worksheet->setColumn(0,1,strlen("TOTAL INVERSION "));
					
					$worksheet->write($j+1,2,"TOTAL BENEFICIO DIRECTO ",$format_title);
					$worksheet->setColumn(0,2,strlen("TOTAL BENEFICIO DIRECTO "));
					
					$worksheet->write($j+1,3,"BENEFICIO NETOS DIRECTOS ",$format_title);
					$worksheet->setColumn(0,3,strlen("BENEFICIO NETOS DIRECTOS "));
					
					$worksheet->write($j+1,4,"RANKING BENEFICIOS DIRECTOS ",$format_title);
					$worksheet->setColumn(0,4,strlen("RANKING BENEFICIOS DIRECTOS "));
					
					$worksheet->write($j+1,5,"BENEFICIO INDIRECTO POR IMAGEN RS ",$format_title);
					$worksheet->setColumn(0,5,strlen("BENEFICIO INDIRECTO POR IMAGEN RS "));
					
					$worksheet->write($j+1,6,"TOTAL BENEFICIOS RS ",$format_title);
					$worksheet->setColumn(0,6,strlen("TOTAL BENEFICIOS RS "));
					
					$worksheet->write($j+1,7,"ROI ",$format_title);
					$worksheet->setColumn(0,7,strlen("ROI "));
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $beneficiosNetosDirectos[$usuarioKey] = $ranking[$usuarioKey] - $rankingInv[$usuarioKey];
					  
					  $worksheet->write($j,$k,$usuarioValue, $format_gridiz);
					  $worksheet->write($j,$k+1,$rankingInv[$usuarioKey], $format_gridiz);
					  $worksheet->write($j,$k+2,$ranking[$usuarioKey], $format_gridiz);
					  $worksheet->write($j,$k+3,$beneficiosNetosDirectos[$usuarioKey], $format_gridiz);
					$j++;	
					} //Fin foreach
					
					arsort($beneficiosNetosDirectos);
					$i=1;
					
					foreach($beneficiosNetosDirectos as $key => $value)
					{
						$rankingBeneficios[$i]= $value;
						$rankingBeneficiosTmp[$i]= $key;
						$i++;
					}
					for($x=1;$x<$i;$x++)
					{
						$z=0;
						for($y=$x+1;$y<$i;$y++)
							if($rankingBeneficios[$x]==$rankingBeneficios[$y]) $z++;
						$posicionRank[$rankingBeneficiosTmp[$x]] = $x+$z;
					}
					$j=4;
					//print_r($posicionRank);
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $worksheet->write($j, $k+4, $posicionRank[$usuarioKey], $format_gridiz);
					  $j++;	
					}
					
					//RS beneficio directo 1er 2do 3er y único sin participar
					
					//RS GENERAL Imagen Beneficio Indirecto %
					
					//RS GENERAL Beneficio Indirecto 1ero 2do 3er y ultimo 
					
					//RS NOTAS TRABAJO
					
					//RS RANKING notas ranking directo
					
					//Calificacion imagen = (notas ranking directo * imagen beneficio indirecto % ) + (NOTAS TRABAJO * (1-imagen beneficio indirecto %)) 
					$arrayNotas = db_fill_array("select ren_usu_id, ren_nota from tb_responsabilidadnotas where ren_jue_id=$fldjue_id and ren_per_id=$fldper_id order by ren_id");
					$arrayRanking = db_fill_array("select rer_posicion, rer_nota from tb_responsabilidadranking where rer_jue_id=$fldjue_id and rer_per_id=$fldper_id order by rer_id");					
					$imagenBeneficio = get_db_value("select reg_beneficioDirecto from tb_responsabilidadgeneral where reg_jue_id=$fldjue_id and reg_per_id=$fldper_id order by reg_id");
					$imagenBeneficio = $imagenBeneficio /100;

					
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $calificacionImagen[$usuarioKey] = ($arrayRanking[$posicionRank[$usuarioKey]]* $imagenBeneficio) + ($arrayNotas[$usuarioKey]*( 1 -  $imagenBeneficio));
					} 					
					//RankingCalificacionImagen
					arsort($calificacionImagen);
					$i=1;
					
					foreach($calificacionImagen as $key => $value)
					{
						$rankingCalificacionImagen[$i]= $value;
						$rankingCalificacionImagenTmp[$i]= $key;
						$i++;
					}
					for($x=1;$x<$i;$x++)
					{
						$z=0;
						for($y=$x+1;$y<$i;$y++)
							if($rankingCalificacionImagen[$x]==$rankingCalificacionImagen[$y]) $z++;
						$posicionRankCalificacionImagen[$rankingCalificacionImagenTmp[$x]] = $x+$z;
						
					}
					
					//Total Beneficio Indirecto = RS GENERAL Beneficio indirecto [rankingCalificacion]
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $RSGeneralBeneficioIndirecto[$usuarioKey] = $indirecto[$posicionRankCalificacionImagen[$usuarioKey]]; 
					  $worksheet->write($j, $k+5, $RSGeneralBeneficioIndirecto[$usuarioKey], $format_gridiz);
					  $j++;
					}
					
					//Total Beneficio RS = Total indirecto + $ranking[$usuarioKey]
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $RSGeneralBeneficioRS[$usuarioKey] = $RSGeneralBeneficioIndirecto[$usuarioKey] +  $ranking[$usuarioKey];
					  $worksheet->write($j, $k+6, $RSGeneralBeneficioRS[$usuarioKey], $format_gridiz);
					  $j++;
					}
					
					//ROI RS = (Total Beneficio RS - $rankingInv[$usuarioKey])/$rankingInv[$usuarioKey]
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $ROIRS[$usuarioKey] = ($rankingInv[$usuarioKey]!=0)?($RSGeneralBeneficioRS[$usuarioKey] - $rankingInv[$usuarioKey])/$rankingInv[$usuarioKey]*100:0;
					  $worksheet->write($j, $k+7, $ROIRS[$usuarioKey], $format_gridiz);
					  $j++;
					}
					
					
					
					
					// RESULTADOS RS
					
					
					$j =2;
					//$worksheet->write(0,0,"REPORTE DE RESPONSABILIDAD SOCIAL",$format_title2);
					$worksheet->write($j,9," EMPRESA	",$format_title);
					$worksheet->mergeCells(2,9,3,9);
					$worksheet->setColumn(0,9,22);
					$worksheet->write($j,10,"RESULTADOS RS ",$format_title);
					$worksheet->mergeCells($j, 10,$j,13);
					$worksheet->write($j+1,10,"NOTA BENEFICIOS NETOS DIRECTOS ",$format_title);
					$worksheet->setColumn(0,10,strlen("NOTA BENEFICIOS NETOS DIRECTOS "));
					
					$worksheet->write($j+1,11,"NOTAS TRABAJOS ",$format_title);
					$worksheet->setColumn(0,11,strlen("NOTAS TRABAJOS "));
					
					$worksheet->write($j+1,12,"NOTA FINAL ",$format_title);
					$worksheet->setColumn(0,12,strlen("NOTA FINAL "));
					
					$worksheet->write($j+1,13,"LUGAR RANKING IMAGEN RS ",$format_title);
					$worksheet->setColumn(0,13,strlen("LUGAR RANKING IMAGEN RS "));
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0; //$arrayRanking[$posicionRank[$usuarioKey]]* $imagenBeneficio) + ($arrayNotas[
					  $worksheet->write($j,$k+9,$usuarioValue, $format_gridiz);			  
					  $worksheet->write($j,$k+10,$arrayRanking[$posicionRank[$usuarioKey]], $format_gridiz);
					  $worksheet->write($j,$k+11,$arrayNotas[$usuarioKey], $format_gridiz);
					  $worksheet->write($j,$k+12,$calificacionImagen[$usuarioKey], $format_gridiz);
					  $worksheet->write($j,$k+13,$posicionRankCalificacionImagen[$usuarioKey], $format_gridiz);
					$j++;	
					} //Fin foreach
					
					
										
			}
				
$workbook->close();
?>
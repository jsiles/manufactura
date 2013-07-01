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

					$worksheet =& $workbook->addWorksheet("RS");
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
					
					foreach($RS as $key => $value)
					{
						$sSQL="select * from tb_inclusion where inc_res_id=$key";
						$db->query($sSQL);
						while($db->next_record())
						{
							$inclusion[$key][$db->f("inc_usu_id")]=$db->f("inc_usu_id");
							$usuarios[$db->f("inc_usu_id")] =$db->f("inc_usu_id"); 
						}
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
					
					//print_r($indirecto);
					//$RS = db_fill_array("select res_id,res_nombre from tb_responsabilidad where res_jue_id=$fldjue_id and res_per_id=$fldper_id");
					$worksheet->write(2,0,"EMPRESA	",$format_title);
					$worksheet->setColumn(0,0,10);
					
					$i=1;
					
					foreach($RS as $key=>$value)
					{
						$worksheet->write(2,$i,"INVERSION PROGRAMA ". strtoupper($value),$format_title);
						$worksheet->setColumn(0,$i,strlen("INVERSION PROGRAMA ". strtoupper($value))+2);
					
						$worksheet->write(2,$i+1, "BENEFICIO DIRECTO PROGRAMA ". strtoupper($value),$format_title);
						$worksheet->setColumn(0,$i+1,strlen("BENEFICIO DIRECTO PROGRAMA ". strtoupper($value))+2);
					    $i=$i+2;
					}
					
					$worksheet->write(2,$i,"TOTAL INVERSIONES", $format_title);
                    $worksheet->write(2,$i+1,"TOTAL BENEFICIOS DIRECTOS", $format_title);
					$worksheet->write(2,$i+2,"RANKING IMAGEN", $format_title);
                    $worksheet->write(2,$i+3,"BENEFICIO IMAGEN", $format_title);
					$worksheet->write(2,$i+4,"INDICADOR ROI %", $format_title);
					
					$worksheet->setColumn(0,$i,20);
					$worksheet->setColumn(0,$i+1,25);
					$worksheet->setColumn(0,$i+2,20);
					$worksheet->setColumn(0,$i+3,20);
					$worksheet->setColumn(0,$i+4,20);
					
					$j=3;
					
					if(is_array($usuarios))
					{
					foreach($usuarios as $usuario)
					{
					$k=0;
					$totalinversion=0;
					$totalbeneficio=0;
					$worksheet->write($j,$k,get_db_value("select usu_nombre from tb_usuarios where usu_id=$usuario"), $format_gridiz);
						foreach($RS as $key=>$value)
						{
							$worksheet->write($j,$k+1,(count($inclusion[$key][$usuario])!=0)?$costo[$key]:0, $format_grid);
							$worksheet->write($j,$k+2,(count($inclusion[$key][$usuario])!=0)?$beneficio[$key][count($inclusion[$key])]:0, $format_grid);//$beneficio[$key][count($inclusion[$key])]
							$totalinversion+=(count($inclusion[$key][$usuario])!=0)?$costo[$key]:0;
							$totalbeneficio+=(count($inclusion[$key][$usuario])!=0)?$beneficio[$key][count($inclusion[$key])]:0;
							$k+=2;
						}
					$ranking[$usuario] = $totalbeneficio;
					$rankingInv[$usuario] = $totalinversion;
					$worksheet->write($j,$k+1,$totalinversion, $format_grid);
					$worksheet->write($j,$k+2,$totalbeneficio, $format_grid);
					$tmpInv[$j]=$totalinversion;
					$tmpBen[$j]=$totalbeneficio;
				
					//$worksheet->write($j,$k+2,print_r($inclusion), $format_grid);
					//$worksheet->write($j,$k,print_r($inclusion), $format_grid);
					$j++;	
					}
					arsort($rankingInv);
					
					$arrayRanking = array_count_values($rankingInv);
					//$worksheet->write(0,10,print_r($arrayRanking),$format_grid);
					$n=0;
					$tmpval=0;
					foreach($arrayRanking as $id => $cantidad)
					{
					if($n==0) $tmpval=$cantidad;
					else{
						//if(($tmpval+1)>=4) $tmpval=4; 
						//else $tmpval+=1;
						if($cantidad==1) $tmpval+=1;
						else $tmpval+=$cantidad;
						if($tmpval>4) $tmpval=4;
					}
					$n++;	
					$posicion[$id]= $tmpval;
					}
					$m=3;
					foreach($usuarios as $usuario)
					{
						$worksheet->write($m,$k+3, $posicion[$rankingInv[$usuario]], $format_grid);
						$worksheet->write($m,$k+4,$indirecto[($posicion[$rankingInv[$usuario]]>4)?4:$posicion[$rankingInv[$usuario]]], $format_grid);
						//$valInd[$usuario] = $indirecto[($posicion[$ranking[$usuario]]>4)?4:$posicion[$ranking[$usuario]]];
						$preROI=$indirecto[($posicion[$rankingInv[$usuario]]>4)?4:$posicion[$rankingInv[$usuario]]];
						$worksheet->write($m,$k+5, (($tmpBen[$m]+$preROI-$tmpInv[$m])/$tmpInv[$m]*100), $format_gridnum);
						
						$m++;
					}
					}
					}
				
$workbook->close();
?>
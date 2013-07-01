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
$workbook->setCustomColor(12, 128, 135, 127);
$workbook->setCustomColor(13, 230, 231, 232);
$workbook->setCustomColor(14, 208, 216, 207);
$workbook->setCustomColor(15, 220, 221, 222);
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
                                                                          'Color' => 'white',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 12,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));

$format_gridiz =& $workbook->addFormat(array('Size' => 8,
																		  'FontFamily' => 'Arial',
																		  'Align' => 'left',
																	      'Color' => 'black',
																	      'Pattern' => 1,
                                                                          'FgColor' => 13,
                                                                          'Border' => 1,
                                                                          'BorderColor'  => 'black'));
$format_grid =& $workbook->addFormat(array('Size' => 8,
	  																	  'FontFamily' => 'Arial',
                                                                          'Align' => 'left',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 15,
                                                                          'Border' => 1,
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
					$worksheet->write(0,0,"Listado de programas",$format_title2);
					$worksheet->write(2,0,"Grupo",$format_title);
					$worksheet->write(2,1,"Programa RS",$format_title);
					$worksheet->write(2,2,"Costo", $format_title);
                    $worksheet->write(2,3,"Beneficio directo", $format_title);
					//$worksheet->write(2,4,"Cantidad rechazada", $format_title);
					
					$worksheet->setColumn(0,0,10);
					$worksheet->setColumn(0,1,15);
					$worksheet->setColumn(0,2,15);
					$worksheet->setColumn(0,3,30);
					//$worksheet->setColumn(0,4,30);
					
					$id = get_param("id");
					if(!isset($id)) $id=0;
					$db->query("SELECT * FROM tb_responsabilidad, tb_inclusion where inc_res_id=res_id and res_id=$id order by res_id");
                    $i=3;
					while($db->next_record())
					  {
					   $inc_usu_id = $db->f("inc_usu_id");
					   $res_precio = $db->f("res_precio");
					   $res_beneficio0 = $db->f("res_beneficio0");
					   $res_beneficio1 = $db->f("res_beneficio1");
					   $res_beneficio2 = $db->f("res_beneficio2");
					   $res_beneficio3 = $db->f("res_beneficio3");
					   $res_beneficio4 = $db->f("res_beneficio4");
					   $res_beneficio5 = $db->f("res_beneficio5");
					   $res_beneficio6 = $db->f("res_beneficio6");
					   $res_nombre = $db->f("res_nombre");
					   $nombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=$inc_usu_id");
					   $cantidadBen = get_db_value("select count(*) from tb_inclusion where inc_res_id=$id");
						switch($cantidadBen)
						{
							case 0:
							$res_beneficio=$res_beneficio0;
							break;
							case 1:
							$res_beneficio=$res_beneficio1;
							break;
							case 2:
							$res_beneficio=$res_beneficio2;
							break;
							case 3:
							$res_beneficio=$res_beneficio3;
							break;
							case 4:
							$res_beneficio=$res_beneficio4;
							break;
							case 5:
							$res_beneficio=$res_beneficio5;
							break;
							case 6:
							$res_beneficio=$res_beneficio6;
							break;
							default:
							$res_beneficio=$res_beneficio6;
							}	   
					   if ($i%2==0) {
					       $worksheet->write($i,0, $nombre, $format_grid);
					       $worksheet->write($i,1, $res_nombre, $format_grid);
	 				       $worksheet->write($i,2, $res_precio, $format_grid);
						   $worksheet->write($i,3, $res_beneficio, $format_grid);
						   }
                        else{
					       $worksheet->write($i,0, $nombre, $format_gridiz);
                           $worksheet->write($i,1, $res_nombre, $format_gridiz);
	 				       $worksheet->write($i,2, $res_precio, $format_gridiz);
						   $worksheet->write($i,3, $res_beneficio, $format_gridiz); 
                          }
   					   $i++;
					  }
$workbook->close();
?>
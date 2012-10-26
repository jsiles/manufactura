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
$workbook =& new Spreadsheet_Excel_Writer();
$workbook->setTempDir('/tmp/');
$workbook->send('ComprasResumen.xls');
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
                                                                          'Align' => 'left',
                                                                          'Color' => 'black',
                                                                          'Pattern' => 1,
                                                                          'FgColor' => 16,
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

					$worksheet =& $workbook->addWorksheet("Compras");
					$worksheet->write(0,0,"Compras efectuadas",$format_title2);
					$worksheet->write(2,0,"Grupo",$format_title);
					$worksheet->write(2,1,"Informacion disponible",$format_title);
					$worksheet->write(2,2,"Costo", $format_title);
                    $worksheet->write(2,3,"Tipo de compra", $format_title);
					$worksheet->write(2,4,"GESTION", $format_title);
					
					$worksheet->setColumn(0,0,10);
					$worksheet->setColumn(0,1,35);
					$worksheet->setColumn(0,2,15);
					$worksheet->setColumn(0,3,15);
					$worksheet->setColumn(0,4,15);
					$i=3;
					$id = get_param("jue_id");
					$usu_id = get_param("usu_id");
					if(!isset($id)) $id=0;
					if(!isset($usu_id)) $usu_id=0;
					//if($usu_id==0)
					$db->query("SELECT * FROM tb_compras, tb_investigacion where com_inv_id=inv_id and inv_jue_id=$id order by inv_per_id, com_usu_id, inv_id");
					//else
					//$db->query("SELECT * FROM tb_compras, tb_investigacion where com_inv_id=inv_id and inv_jue_id=$id and com_usu_id=$usu_id order by inv_per_id, inv_id");
					 
                      while($db->next_record())
					  {
					   $com_usu_id = $db->f("com_usu_id");
					   $inv_investigacion = $db->f("inv_investigacion");
					   $inv_costo = $db->f("inv_costo");
					   $inv_costoexclusividad = $db->f("inv_costoexclusividad");
					   $com_exclusividad = $db->f("com_exclusividad");
					   $inv_per_id = $db->f("inv_per_id");
					   $nombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=$com_usu_id");
					   
					   if ($i%2==0) {
					       $worksheet->write($i,0, $nombre, $format_grid);
					       $worksheet->write($i,1, $inv_investigacion, $format_grid);
	 				       $worksheet->write($i,2, ($com_exclusividad==1)?$inv_costoexclusividad:$inv_costo, $format_grid);
						   $worksheet->write($i,3, ($com_exclusividad==1)?"Exclusividad":"Normal", $format_grid);
						   $worksheet->write($i,4, $inv_per_id, $format_grid);							
 						   /************************************************/
                        }
                        else{
					       $worksheet->write($i,0, $nombre, $format_gridiz);
                           $worksheet->write($i,1, $inv_investigacion, $format_gridiz);
                           $worksheet->write($i,2, ($com_exclusividad==1)?$inv_costoexclusividad:$inv_costo, $format_gridiz);
						   $worksheet->write($i,3, ($com_exclusividad==1)?"Exclusividad":"Normal", $format_grid);
						   $worksheet->write($i,4, $inv_per_id, $format_gridiz);	
                          

                         }
   					   $i++;
					  }
$workbook->close();
?>
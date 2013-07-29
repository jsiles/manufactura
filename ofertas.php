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
$workbook->send('Ofertas.xls');
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

					$worksheet =& $workbook->addWorksheet("Ofertas");
					$worksheet->write(0,0,"Listado de ofertas",$format_title2);
					
					$worksheet->write(2,0,"Producto",$format_title);
					$worksheet->write(2,1,"Grupo",$format_title);
					$worksheet->write(2,2,"Precio Ofertado", $format_title);
                    $worksheet->write(2,3,"Cantidad Ofertada",$format_title);
					$worksheet->setColumn(0,0,10);
					$worksheet->setColumn(0,1,10);
					$worksheet->setColumn(0,2,15);
					$worksheet->setColumn(0,3,15);
					$worksheet->setColumn(0,4,30);
					$worksheet->setColumn(0,5,30);
					$worksheet->setColumn(0,6,30);
					$worksheet->setColumn(0,7,30);
					$fldven_jue_id = get_param("jue_id");
					$fldven_per_id = get_param("per_id");
					 $i=3;
					$db->query("SELECT ven_id, ven_nombre FROM tb_ventas where ven_jue_id=$fldven_jue_id and ven_per_id=$fldven_per_id  and ven_sw=1 order by ven_id");
					while($db->next_record())
					{
					$id = $db->f("ven_id");
					$fldven_nombre = $db->f("ven_nombre");
					$worksheet->write($i,0, $fldven_nombre, $format_grid);
					if(!isset($id)) $id=0;
					$db1->query("SELECT ofe_id, ofe_usu_id, ofe_cantidad, ofe_monto,ofe_entrega FROM tb_ofertas where ofe_ven_id=$id order by ofe_id");
                   
					$cantidadMaxAceptada=0;
					$precioMax = get_db_value("select ven_precio from tb_ventas where ven_id=$id");
					$cantidadMax = get_db_value("select ven_cantidad from tb_ventas where ven_id=$id");
					  while($db1->next_record())
					  {
					   $ofe_usu_id = $db1->f("ofe_usu_id");
					   $ofe_cantidad = $db1->f("ofe_cantidad");
					   $ofe_monto = $db1->f("ofe_monto");
					   $ofe_entregada = $db1->f("ofe_entrega");
					   $nombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=$ofe_usu_id");
					  
					   if ($i%2==0) {
					       $worksheet->write($i,1, $nombre, $format_grid);
					       $worksheet->write($i,2, $ofe_monto, $format_grid);
					       $worksheet->write($i,3, $ofe_cantidad, $format_grid);
	 					  
                        }
                        else{
					       $worksheet->write($i,1, $nombre, $format_gridiz);
                           $worksheet->write($i,2, $ofe_monto, $format_gridiz);
                           $worksheet->write($i,3, $ofe_cantidad, $format_gridiz);
                          

                         }
   					   
					  }
					  $i++;
					}
$workbook->close();
?>
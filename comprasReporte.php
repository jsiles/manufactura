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
					$jue_id = get_param("jue_id");
					$arrayUsuario = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=".tosql($jue_id, "Number"));
					$arrayProducto = db_fill_array("select pro_id, pro_name from tb_productos2 where pro_jue_id=".tosql($jue_id, "Number"));
					
					$worksheet =& $workbook->addWorksheet("Reporte de Compras Realizadas");
					$worksheet->write(0,0,"Compras",$format_title2);
					$worksheet->write(2,0,"Producto",$format_title);
					$worksheet->write(2,1,"Concepto",$format_title);
					$k=2;
					foreach($arrayUsuario as $key=>$value)
					{
						$worksheet->write(2,$k,$value, $format_title);
						$worksheet->setColumn(2,$k,15);
						$k++;
                    }
					
					$worksheet->setColumn(0,0,10);
					$worksheet->setColumn(0,1,strlen("Total Unidades compradas")+2);
					
					$i=3;
					foreach($arrayProducto as $key=>$value)
					{
						$worksheet->write($i,0,$value,$format_title);
						$worksheet->write($i,1,"Total Costo de compra",$format_title);
						$worksheet->write($i+1,1,"Total Unidades compradas",$format_title);
						$worksheet->write($i+2,1,"Costo unitario",$format_title);
						$k=2;
						foreach($arrayUsuario as $key2=>$value2)
						{
							$fldTotalCosto = get_db_value("select sum(tot_sumatotal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number"));
							if(!$fldTotalCosto) $fldTotalCosto=0;
							$fldUnidadesCompradas = get_db_value("select sum(tot_productototal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number"));
							if(!$fldUnidadesCompradas) $fldUnidadesCompradas=0;
							if($fldUnidadesCompradas>0) $fldCostoUnitario = round($fldTotalCosto/$fldUnidadesCompradas,2);
							else $fldCostoUnitario = 0;
							
							$worksheet->write($i,$k,$fldTotalCosto, $format_title);
							$worksheet->write($i+1,$k,$fldUnidadesCompradas, $format_title);
							$worksheet->write($i+2,$k,$fldCostoUnitario, $format_title);
							$k++;
						}

						$worksheet->mergeCells($i,0,$i+2,0);
						$i=$i+3;
					}
	$workbook->close();
?>
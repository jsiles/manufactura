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
					$per_id = get_param("per_id");
					$user_id = get_session("cliID");
					$arrayUsuario = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=".tosql($jue_id, "Number"));
					$arrayProducto = db_fill_array("select pro_id, pro_name from tb_productos2 where pro_jue_id=".tosql($jue_id, "Number"));
					$usuarioNombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=".tosql($user_id,"Number")); 
					$worksheet =& $workbook->addWorksheet("Reporte de Compras Realizadas");
					$worksheet->write(0,0,"Compras",$format_title2);
					$worksheet->write(0,1,$usuarioNombre,$format_title2);

					$worksheet->write(1,0,"Gestion:".$per_id, $format_title2);
					$worksheet->write(2,0,"Producto",$format_title);
					$worksheet->write(2,1,"Concepto",$format_title);
					$k=2;
					foreach($arrayUsuario as $key=>$value)
					{
						$worksheet->write(2,$k,$value, $format_title);
						$worksheet->setColumn(2,$k,15);
						$k++;
                    }
					
					$worksheet->setColumn(0,0,strlen("M$/Unidades de pedido compras periodo")+2);
					$worksheet->setColumn(0,1,strlen("Tiempo promedio ponderado")+2);
					
					$i=3;
					foreach($arrayProducto as $key=>$value)
					{
						$worksheet->write($i,0,$value,$format_title);
						$worksheet->write($i,1,"Total Costo de compra",$format_title);
						$worksheet->write($i+1,1,"Total Unidades compradas",$format_title);
						$worksheet->write($i+2,1,"Costo unitario",$format_title);
						$worksheet->write($i+3,1,"Ahorro en compras",$format_title);
						$worksheet->write($i+4,1,"Tiempo promedio ponderado",$format_title);
						$k=2;
						foreach($arrayUsuario as $key2=>$value2)
						{
							$fldTotalCosto = get_db_value("select sum(tot_sumatotal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number")." and tot_per_id=".tosql($per_id,"Number"));
							if(!$fldTotalCosto) $fldTotalCosto=0;
							$fldUnidadesCompradas = get_db_value("select sum(tot_productototal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number")." and tot_per_id=".tosql($per_id,"Number"));
							if(!$fldUnidadesCompradas) $fldUnidadesCompradas=0;
							if($fldUnidadesCompradas>0) $fldCostoUnitario = round($fldTotalCosto/$fldUnidadesCompradas,2);
							else $fldCostoUnitario = 0;
							
							$fldDescuentoTotal = get_db_value("select sum(tot_descuentototal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number")." and tot_per_id=".tosql($per_id,"Number"));
							if(!$fldDescuentoTotal) $fldDescuentoTotal=0;
							if($fldTotalCosto>0) $fldAhorro = round($fldDescuentoTotal/$fldTotalCosto,2);
							else $fldAhorro = 0;
							
							$fldProductoTiempoMontoTotal = get_db_value("select sum(tot_productotiempomontototal) from tb_totalcompras where tot_pro_id=". tosql($key, "Number") ." and tot_usu_id=".tosql($key2,"Number")." and tot_jue_id=".tosql($jue_id,"Number")." and tot_per_id=".tosql($per_id,"Number"));
							
							if(!$fldProductoTiempoMontoTotal) $fldProductoTiempoMontoTotal=0;
							
							if($fldTotalCosto>0) $fldTiempoPromedio = round($fldProductoTiempoMontoTotal/$fldTotalCosto,2);
							else $fldTiempoPromedio = 0;
							
							
							
							$worksheet->write($i,$k, number_format($fldTotalCosto,0,".",","), $format_title);
							$worksheet->write($i+1,$k, number_format($fldUnidadesCompradas,0,".",","), $format_title);
							$worksheet->write($i+2,$k, $fldCostoUnitario, $format_title);
							$worksheet->write($i+3,$k, round($fldAhorro*100,0)."%", $format_title);
							$worksheet->write($i+4,$k,$fldTiempoPromedio, $format_title);
							$k++;
						}

						$worksheet->mergeCells($i,0,$i+4,0);
						$i=$i+3;
					}
					
					$i = $i +3;
					
					$aux0=($per_id*3)-3;
					$aux1=($per_id*3)-2;
					$aux2=($per_id*3)-1;
					
					foreach($arrayProducto as $key=>$value)
					{
				    	$worksheet->write($i,0,"Gestion ".$per_id,$format_title);
						$worksheet->write($i+1,0,"Control Almacen Producto",$format_title);
						$worksheet->write($i+1,1,"Producto",$format_title);
						$worksheet->write($i+1,2,$value,$format_title);
						$worksheet->write($i+2,0,"Concepto",$format_title);
						$worksheet->write($i+2,1,"Periodo 1",$format_title);
						$worksheet->write($i+2,2,"Periodo 2",$format_title);
						$worksheet->write($i+2,3,"Periodo 3",$format_title);
						$worksheet->write($i+3,0,"M$ Compras Pedido",$format_title);
						$worksheet->write($i+4,0,"Unidades de pedido compras periodo",$format_title);
						$worksheet->write($i+5,0,"M$/Unidades de pedido compras periodo",$format_title);
						
						
						$MillonesComprasPedido0 = get_db_value("select sum(tot_sumatotal) from tb_totalcompras where tot_aux=" . tosql($aux0,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));
						$MillonesComprasPedido1 = get_db_value("select sum(tot_sumatotal) from tb_totalcompras where tot_aux=" . tosql($aux1,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));
						$MillonesComprasPedido2 = get_db_value("select sum(tot_sumatotal) from tb_totalcompras where tot_aux=" . tosql($aux2,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));
							
						$worksheet->write($i+3,1, number_format($MillonesComprasPedido0,0,".",","), $format_title);
						$worksheet->write($i+3,2, number_format($MillonesComprasPedido1,0,".",","), $format_title);
						$worksheet->write($i+3,3, number_format($MillonesComprasPedido2,0,".",","), $format_title);

						$UnidadesComprasPedido0 = get_db_value("select sum(tot_productototal) from tb_totalcompras where tot_aux=" . tosql($aux0,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));
						if(!$UnidadesComprasPedido0) $UnidadesComprasPedido0=0;
						$UnidadesComprasPedido1 = get_db_value("select sum(tot_productototal) from tb_totalcompras where tot_aux=" . tosql($aux1,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));
						if(!$UnidadesComprasPedido1) $UnidadesComprasPedido1=0;						
						$UnidadesComprasPedido2 = get_db_value("select sum(tot_productototal) from tb_totalcompras where tot_aux=" . tosql($aux2,"Number") . " and tot_pro_id and tot_usu_id=".tosql($user_id,"Number")." and tot_jue_id = ".tosql($jue_id,"Number"));//." and tot_per_id=".tosql($per_id,"Number"));																											
						if(!$UnidadesComprasPedido2) $UnidadesComprasPedido2=0;

						$worksheet->write($i+4,1, number_format($UnidadesComprasPedido0,0,".",","), $format_title);
						$worksheet->write($i+4,2, number_format($UnidadesComprasPedido1,0,".",","), $format_title);
						$worksheet->write($i+4,3, number_format($UnidadesComprasPedido2,0,".",","), $format_title);
						if($UnidadesComprasPedido0>0)
						$factor0 = round($MillonesComprasPedido0 / $UnidadesComprasPedido0,2);
						else $factor0 = 0;

						if($UnidadesComprasPedido1>0)
						$factor1 = round($MillonesComprasPedido1 / $UnidadesComprasPedido1,2);
						else
						$factor1 =0;
						
						if($UnidadesComprasPedido2>0)
						$factor2 = round($MillonesComprasPedido2 / $UnidadesComprasPedido2,2);
						else $factor2=0;
						
						
						$worksheet->write($i+5,1,$factor0, $format_title);
						$worksheet->write($i+5,2,$factor1, $format_title);
						$worksheet->write($i+5,3,$factor2, $format_title);
						
						$worksheet->mergeCells($i,0,$i,3);
						$worksheet->mergeCells($i+1,2,$i+1,3);

						$i=$i+3;
					}
					
	$workbook->close();
?>

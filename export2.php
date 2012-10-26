<?php
include ("./common.php");
include_once "./Spreadsheet/Excel/Writer.php";    
session_start();
  $xls =& new Spreadsheet_Excel_Writer();
  $xls->send("Reportes.xls");
  
  $format_titular =& $xls->addFormat();
  $format_titular->setBold();
  $format_titular->setColor("black");
  $format_titular->setSize(12);
  
  $format_titular2 =& $xls->addFormat();
  $format_titular2->setBold();
  $format_titular2->setColor("black");
  $format_titular2->setSize(11);
  //$format_titular2->setBgColor("blue");
  
$sheet =& $xls->addWorksheet('indicadores');
$filename = "export2.php";
$template_filename = "export2.html";
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//$tpl->load_file($header_filename, "header");
$tpl->set_var("FileName", $filename);
$tpl->set_var("CCS_Style","Coco");
//header_show();
reporte();
//$tpl->parse("header", false);
$tpl->pparse("main", false);

function reporte()
{
    global $db, $tpl;
    $dat_juego = get_param("id");
    $dat_usuario = get_session("cliID");
    $tpl->set_var("id",$dat_juego);
    if ($dat_juego)
    {           
    $per_id = $db->query("select jue_periodoInicial, jue_cantidad from tb_juegos where jue_sw='A' and jue_id=$dat_juego order by 1");
    $next_record = $db->next_record();
    $periodoinicial = $db->f("jue_periodoInicial");
    $periodocantidad = $db->f("jue_cantidad");
    for ($i=0;$i<$periodocantidad;$i++)
        {            
            $periodo[$periodoinicial+$i] = $periodoinicial+$i;
        }


    } else 
    {
                 $tpl->set_var("ID", "");
                 $tpl->set_var("Value", "Seleccionar valor");
                 $tpl->parse("Usuario", true);
                 $tpl->set_var("Datos", "");
    
    }
    
    if ($dat_juego&&$dat_usuario)
    {
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $tpl->set_var("periodo", $valor);
                        $tpl->parse("Periodos", true);
                    }    

                $tpl->set_var("Label1", "PARTICIPACIÓN DEL MERCADO" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {    
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else  $market_share = $ingreso/$totaligresos;
                        //echo $ingreso. '-'.$totaligresos;
                        $tpl->set_var("Label2", number_format($market_share * 100,0)." %" );
                        $tpl->parse ("Total" , true);
                    }
                $tpl->set_var("Label11", "MARGEN DE UTILIDAD" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
//                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22");
//                        if ($totaligresos=='') $totaligresos=0; 
//                        if ($totaligresos==0) $market_share = 0 ;
//                        else $market_share = $ingreso/$totaligresos;
//                        $id2 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=2 and t.dat_periodo=$id_periodo");
//                        if ($id2=='') $id2=0;
//                        $id3 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=3 and t.dat_periodo=$id_periodo");
//                        if ($id3=='') $id3=0;
//                        $id7 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=7 and t.dat_periodo=$id_periodo");
//                        if ($id7=='') $id7=0;
//                        $id8 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=8 and t.dat_periodo=$id_periodo");
//                        if ($id8=='') $id8=0;
//                        $id9 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=9 and t.dat_periodo=$id_periodo");
//                        if ($id9=='') $id9=0;
//                        $id11 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=11 and t.dat_periodo=$id_periodo");
//                        if ($id11=='') $id11=0;
//                        $id15 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=15 and t.dat_periodo=$id_periodo");
//                        if ($id15=='') $id15=0;
//                        $id41 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=41 and t.dat_periodo=$id_periodo");
//                        if ($id41=='') $id41=0;
//                        $id42 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=42 and t.dat_periodo=$id_periodo");
//                        if ($id42=='') $id42=0;
//                        $costoscomunes = $id2 + $id3 + $id7 + $id8 + $id9 + $id11 + $id15 + $id41 + $id42;    
//                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=24 and t.dat_periodo=$id_periodo"); 
                        $utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=81 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($ingreso!=0) $margenutilidad = $utilidadoperativa/$ingreso;
                        else $margenutilidad=0;
                        $tpl->set_var("Label21", number_format($margenutilidad * 100,0)." %" );
                        $tpl->parse ("Total1" , true);
                    }
            /*    $tpl->set_var("Label12", "MARGEN DE CONTRIBUCIÓN" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
//                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22");
//                        if ($totaligresos=='') $totaligresos=0;       
//                        if ($totaligresos==0) $market_share = 0 ;
//                        else $market_share = $ingreso/$totaligresos;
//                        $id2 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=2 and t.dat_periodo=$id_periodo");
//                        if ($id2=='') $id2=0;
//                        $id3 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=3 and t.dat_periodo=$id_periodo");
//                        if ($id3=='') $id3=0;
//                        $id7 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=7 and t.dat_periodo=$id_periodo");
//                        if ($id7=='') $id7=0;
//                        $id8 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=8 and t.dat_periodo=$id_periodo");
//                        if ($id8=='') $id8=0;
//                        $id9 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=9 and t.dat_periodo=$id_periodo");
//                        if ($id9=='') $id9=0;
//                        $id11 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=11 and t.dat_periodo=$id_periodo");
//                        if ($id11=='') $id11=0;
//                        $id15 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=15 and t.dat_periodo=$id_periodo");
//                        if ($id15=='') $id15=0;
//                        $id41 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=41 and t.dat_periodo=$id_periodo");
//                        if ($id41=='') $id41=0;
//                        $id42 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=42 and t.dat_periodo=$id_periodo");
//                        if ($id42=='') $id42=0;
//                        $costoscomunes = $id2 + $id3 + $id7 + $id8 + $id9 + $id11 + $id15 + $id41 + $id42;    
                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=24 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
//                        $utilidadoperativa = $utilidadbruta - $costoscomunes;
                        if ($ingreso!=0) $margencontribucion = $utilidadbruta/$ingreso;
                        else $margencontribucion=0;
                        $tpl->set_var("Label22",number_format($margencontribucion * 100,0)." %");
                        $tpl->parse ("Total2" , true);
                    }*/

                $tpl->set_var("Label13", "GIRO DE CAPITAL" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
						
						$ingresoxnegocios = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=106 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingresoxnegocios=='') $ingresoxnegocios=0;
						
						$ingresoxextra = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=26 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingresoxextra=='') $ingresoxextra=0;
						
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=46 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;                            
                        if ($totalactivos!=0) $girocapital =  ($ingreso+ingresoxnegocios+ingresoxextra)/$totalactivos;
                        else $girocapital=0;
                        $tpl->set_var("Label23",number_format($girocapital,2));
                        $tpl->parse ("Total3" , true);
                    }

                $tpl->set_var("Label14", "RENTABILIDAD DE ACTIVOS" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=81 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");     
                        
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=46 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;
                        if ($totalactivos!=0) $retornoactivos =  $utilidadoperativa/$totalactivos;
                        else $retornoactivos=0;
                        $tpl->set_var("Label24",number_format($retornoactivos * 100,0)." %");
                        $tpl->parse ("Total4" , true);
                    }

                
                $tpl->set_var("Label15", "RENTABILIDAD EN PATRIMONIO" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadneta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=54 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadneta=='') $utilidadneta=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=55 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $retornopatrimonio =  $utilidadneta/$capitalinicio;
                        else $retornopatrimonio=0;
                        $tpl->set_var("Label25",number_format($retornopatrimonio * 100,0)." %");
                        $tpl->parse ("Total5" , true);
                    }
                $tpl->set_var("Label16", "VULNERABILIDAD" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $totalpasivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=51 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalpasivos=='') $totalpasivos=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=55 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $vulnerabilidad =  $totalpasivos/$capitalinicio;
                        else $vulnerabilidad=0;
                        $tpl->set_var("Label26",number_format($vulnerabilidad * 100,0)." %");
                        $tpl->parse ("Total6" , true);
                    }

                $tpl->set_var("Label17", "LIQUIDEZ" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
						
						 $id33 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=33 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id33)==0) $id33=0;
                        $id34 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=34 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id34)==0) $id34=0;
                        $id35 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=35 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id35)==0) $id35=0;
                        $id36 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=36 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id36)==0) $id36=0;
                        $id109 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=109 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id109)==0) $id109=0;
                        $id118 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=118 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id118)==0) $id118=0;
                        //48+49
                        $id48 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=48 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id48)==0) $id48=0;
                        $id49 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=49 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id49)==0) $id49=0;
						$prestamocortoplazo=$id48+$id49;
						$activosrapidos=$id33+$id34+$id35+$id36+$id109+$id118;
						//echo "(".$id33."+".$id34."+".$id35."+".$id36."+".$id109."+".$id118.")/(".$id48."+".$id49.")"."<br>";
						if ($prestamocortoplazo!=0) $liquidez =  $activosrapidos/$prestamocortoplazo;
                        else $liquidez=0;
						
                       /* $cajaybancos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=33 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cajaybancos=='') $cajaybancos=0;
                        $cuentascobrar = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=34 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cuentascobrar=='') $cuentascobrar=0;
                        $activosrapidos = $cajaybancos + $cuentascobrar;
                        $prestamocortoplazo = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=51 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($prestamocortoplazo!=0) $liquidez = $activosrapidos/$prestamocortoplazo;
                        else $liquidez=0;*/
                        $tpl->set_var("Label27", number_format($liquidez,2));
                        $tpl->parse ("Total7" , true);
                    }

                $tpl->set_var("Label18", "UTILIDADES NETAS ACUMULADAS" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadnetaacumulada = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=t1.usu_id and t.dat_usu_id=$dat_usuario and t.dat_ite_id=54 and t.dat_periodo<=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadnetaacumulada=='') $utilidadnetaacumulada =0;
                        $tpl->set_var("Label28", $utilidadnetaacumulada );
                        $tpl->parse ("Total8" , true);
                    }

                $tpl->set_var("Label19", "VALOR DE LA EMPRESA" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $valoractualdelaempresa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=95 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($valoractualdelaempresa =='') $valoractualdelaempresa =0;
                        $tpl->set_var("Label29", $valoractualdelaempresa );
                        $tpl->parse ("Total9" , true);
                    }
    } 
    else
    {
                   $tpl->set_var("Datos", "");
    }   
    
}

?>
<?php
include ("./common2.php");

session_start();
$filename = "reporte.php";
$template_filename = "reporte.html";

$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//$tpl->load_file($header_filename, "header");
$tpl->set_var("FileName", $filename);
//header_show();
reporte();
//$tpl->parse("header", false);
$tpl->pparse("main", false);

function reporte()
{
    global $db, $tpl;
    $dat_juego = get_param("dat_juego");
    $dat_periodo = get_param("dat_periodo");
    
    
    $array_juego = db_fill_array("select jue_id, jue_nombre from tb_juegos where jue_sw='A' order by 1");
        if(is_array($array_juego))
                {
                  reset($array_juego);
                            $tpl->set_var("ID", "");
                              $tpl->set_var("Value", "Seleccionar valor");
                            $tpl->parse("Juego", true);
                  while(list($key, $value) = each($array_juego))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $dat_juego)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Juego", true);
                  }
                }
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
        if(is_array($periodo))
                {
                  reset($periodo);
                            $tpl->set_var("ID", "");
                              $tpl->set_var("Value", "Seleccionar valor");
                            $tpl->parse("Periodo", true);
                  while(list($key, $value) = each($periodo))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $dat_periodo)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Periodo", true);
                  }
                }

    } else 
    {
                 $tpl->set_var("ID", "");
                 $tpl->set_var("Value", "Seleccionar valor");
                 $tpl->parse("Periodo", true);
                 $tpl->set_var("Datos", "");
    
    }
    
    if ($dat_juego&&$dat_periodo)
    {
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $tpl->set_var("usuarios", $nombre);
                        $tpl->parse("Usuarios",true);
                        $next_record = $db->next_record();
                    }

                $tpl->set_var("Label1", "PARTICIPACIÓN DEL MERCADO" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else $market_share = $ingreso/$totaligresos;
                        $tpl->set_var("Label2", number_format($market_share * 100,0)." %" );
                        $tpl->parse ("Total" , true);
                    }
                $tpl->set_var("Label11", "MARGEN DE UTILIDAD" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else $market_share = $ingreso/$totaligresos;
                        $id2 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=2 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id2=='') $id2=0;
                        $id3 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=3 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id3=='') $id3=0;
                        $id7 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=7 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id7=='') $id7=0;
                        $id8 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=8 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id8=='') $id8=0;
                        $id9 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=9 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id9=='') $id9=0;
                        $id11 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=11 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id11=='') $id11=0;
                        $id15 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=15 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id15=='') $id15=0;
                        $id41 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=41 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id41=='') $id41=0;
                        $id42 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=42 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id42=='') $id42=0;
                        $costoscomunes = $id2 + $id3 + $id7 + $id8 + $id9 + $id11 + $id15 + $id41 + $id42;    
                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=24 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        $utilidadoperativa = $utilidadbruta - $costoscomunes;
                        if ($ingreso!=0) $margenutilidad = $utilidadoperativa/$ingreso;
                        else $margenutilidad=0;
                        $tpl->set_var("Label21", number_format($margenutilidad * 100,0)." %" );
                        $tpl->parse ("Total1" , true);
                    }
                $tpl->set_var("Label12", "MARGEN DE CONTRIBUCIÓN" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else $market_share = $ingreso/$totaligresos;
                        $id2 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=2 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id2=='') $id2=0;
                        $id3 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=3 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id3=='') $id3=0;
                        $id7 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=7 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id7=='') $id7=0;
                        $id8 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=8 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id8=='') $id8=0;
                        $id9 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=9 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id9=='') $id9=0;
                        $id11 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=11 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id11=='') $id11=0;
                        $id15 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=15 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id15=='') $id15=0;
                        $id41 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=41 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id41=='') $id41=0;
                        $id42 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=42 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id42=='') $id42=0;
                        $costoscomunes = $id2 + $id3 + $id7 + $id8 + $id9 + $id11 + $id15 + $id41 + $id42;    
                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=24 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        $utilidadoperativa = $utilidadbruta - $costoscomunes;
                        if ($ingreso!=0) $margencontribucion = $utilidadbruta/$ingreso;
                        else $margencontribucion=0;
                        $tpl->set_var("Label22",number_format($margencontribucion * 100,0)." %");
                        $tpl->parse ("Total2" , true);
                    }

                $tpl->set_var("Label13", "GIRO DE CAPITAL" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=46 and t.dat_periodo=$dat_periodo  and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;                            
                        if ($totalactivos!=0) $girocapital =  $ingreso/$totalactivos;
                        else $girocapital=0;
                        $tpl->set_var("Label23",number_format($girocapital * 100,0)." %" );
                        $tpl->parse ("Total3" , true);
                    }

                $tpl->set_var("Label14", "RENTABILIDAD DE ACTIVOS" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $id2 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=2 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id2=='') $id2=0;
                        $id3 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=3 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id3=='') $id3=0;
                        $id7 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=7 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id7=='') $id7=0;
                        $id8 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=8 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id8=='') $id8=0;
                        $id9 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=9 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id9=='') $id9=0;
                        $id11 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=11 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id11=='') $id11=0;
                        $id15 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=15 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id15=='') $id15=0;
                        $id41 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=41 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id41=='') $id41=0;
                        $id42 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=42 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($id42=='') $id42=0;
                        $costoscomunes = $id2 + $id3 + $id7 + $id8 + $id9 + $id11 + $id15 + $id41 + $id42;    
                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=24 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        $utilidadoperativa = $utilidadbruta - $costoscomunes;
                        
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=46 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;
                        if ($totalactivos!=0) $retornoactivos =  $utilidadoperativa/$totalactivos;
                        else $retornoactivos=0;
                        $tpl->set_var("Label24",number_format($retornoactivos * 100,0)." %");
                        $tpl->parse ("Total4" , true);
                    }

                
                $tpl->set_var("Label15", "RENTABILIDAD EN PATRIMONIO" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $utilidadneta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=54 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadneta=='') $utilidadneta=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=53 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $retornopatrimonio =  $utilidadneta/$capitalinicio;
                        else $retornopatrimonio=0;
                        $tpl->set_var("Label25",number_format($retornopatrimonio * 100,0)." %");
                        $tpl->parse ("Total5" , true);
                    }
                $tpl->set_var("Label16", "VULNERABILIDAD" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $totalpasivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=51 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalpasivos=='') $totalpasivos=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=53 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $vulnerabilidad =  $totalpasivos/$capitalinicio;
                        else $vulnerabilidad=0;
                        $tpl->set_var("Label26",number_format($vulnerabilidad * 100,0)." %");
                        $tpl->parse ("Total6" , true);
                    }

                $tpl->set_var("Label17", "LIQUIDEZ" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $cajaybancos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=33 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cajaybancos=='') $cajaybancos=0;
                        $cuentascobrar = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=34 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cuentascobrar=='') $cuentascobrar=0;
                        $activosrapidos = $cajaybancos + $cuentascobrar;
                        $prestamocortoplazo = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=51 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($prestamocortoplazo!=0) $liquidez =  $activosrapidos/$prestamocortoplazo;
                        else $liquidez=0;
                        $tpl->set_var("Label27", number_format($liquidez* 100,0)." %" );
                        $tpl->parse ("Total7" , true);
                    }

                $tpl->set_var("Label18", "UTILIDADES NETAS ACUMULADAS" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $utilidadnetaacumulada = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=54 and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadnetaacumulada=='') $utilidadnetaacumulada =0;
                        $tpl->set_var("Label28", $utilidadnetaacumulada );
                        $tpl->parse ("Total8" , true);
                    }

                $tpl->set_var("Label19", "VALOR DE LA EMPRESA" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $valoractualdelaempresa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=95 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
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

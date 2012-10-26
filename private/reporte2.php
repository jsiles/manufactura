<?php
include ("./common2.php");

session_start();
$filename = "reporte2.php";
$template_filename = "reporte2.html";

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
    $dat_usuario = get_param("dat_usuario");
    
    
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

        $array_usuario = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_sw='A' and usu_jue_id = $dat_juego order by 2");
        if(is_array($array_usuario))
                {
                  reset($array_usuario);
                            $tpl->set_var("ID", "");
                              $tpl->set_var("Value", "Seleccionar valor");
                            $tpl->parse("Usuario", true);
                  while(list($key, $value) = each($array_usuario))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $dat_usuario)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Usuario", true);
                  }
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
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_periodo=$id_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else  $market_share = $ingreso/$totaligresos;
                        $tpl->set_var("Label2", number_format($market_share * 100,0)." %" );
                        $tpl->parse ("Total" , true);
                    }
                $tpl->set_var("Label11", "MARGEN DE UTILIDAD" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=81 and t.dat_periodo=$id_periodo  and t.dat_usu_id=t1.usu_id"); 
                        if ($ingreso!=0) $margenutilidad = $utilidadoperativa/$ingreso;
                        else $margenutilidad=0;
                        $tpl->set_var("Label21", number_format($margenutilidad * 100,0)." %" );
                        $tpl->parse ("Total1" , true);
                    }
                $tpl->set_var("Label12", "MARGEN DE CONTRIBUCIÓN" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $utilidadbruta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=24 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($ingreso!=0) $margencontribucion = $utilidadbruta/$ingreso;
                        else $margencontribucion=0;
                        $tpl->set_var("Label22",number_format($margencontribucion * 100,0)." %");
                        $tpl->parse ("Total2" , true);
                    }
                $tpl->set_var("Label13", "GIRO DE CAPITAL" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_ite_id=22 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=46 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;                            
                        if ($totalactivos!=0) $girocapital =  $ingreso/$totalactivos;
                        else $girocapital=0;
                        $tpl->set_var("Label23",number_format($girocapital * 100,0)." %" );
                        $tpl->parse ("Total3" , true);
                    }
                $tpl->set_var("Label14", "RENTABILIDAD DE ACTIVOS" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=81 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");     
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=46 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;
                        if ($totalactivos!=0) $retornoactivos =  $utilidadoperativa/$totalactivos;
                        else $retornoactivos=0;
                        $tpl->set_var("Label24",number_format($retornoactivos * 100,0)." %");
                        $tpl->parse ("Total4" , true);
                    }
                $tpl->set_var("Label15", "RENTABILIDAD EN PATRIMONIO" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadneta = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=54 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadneta=='') $utilidadneta=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=55 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $retornopatrimonio =  $utilidadneta/$capitalinicio;
                        else $retornopatrimonio=0;
                        $tpl->set_var("Label25",number_format($retornopatrimonio * 100,0)." %");
                        $tpl->parse ("Total5" , true);
                    }
                $tpl->set_var("Label16", "VULNERABILIDAD" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $totalpasivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=51 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalpasivos=='') $totalpasivos=0;
                        $capitalinicio = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=55 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($capitalinicio!=0) $vulnerabilidad =  $totalpasivos/$capitalinicio;
                        else $vulnerabilidad=0;
                        $tpl->set_var("Label26",number_format($vulnerabilidad * 100,0)." %");
                        $tpl->parse ("Total6" , true);
                    }
                $tpl->set_var("Label17", "LIQUIDEZ" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $cajaybancos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=33 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cajaybancos=='') $cajaybancos=0;
                        $cuentascobrar = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=34 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($cuentascobrar=='') $cuentascobrar=0;
                        $activosrapidos = $cajaybancos + $cuentascobrar;
                        $prestamocortoplazo = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=51 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($prestamocortoplazo!=0) $liquidez =  $activosrapidos/$prestamocortoplazo;
                        else $liquidez=0;
                        $tpl->set_var("Label27", number_format($liquidez* 100,0)." %");
                        $tpl->parse ("Total7" , true);
                    }
                $tpl->set_var("Label18", "UTILIDADES NETAS ACUMULADAS" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $utilidadnetaacumulada = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=t1.usu_id and t.dat_usu_id=$dat_usuario and t.dat_ite_id=54 and t.dat_periodo<=$id_periodo  and t.dat_usu_id=t1.usu_id"); 
                        if ($utilidadnetaacumulada=='') $utilidadnetaacumulada =0;
                        $tpl->set_var("Label28", $utilidadnetaacumulada );
                        $tpl->parse ("Total8" , true);
                    }
                $tpl->set_var("Label19", "VALOR DE LA EMPRESA" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $valoractualdelaempresa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t.dat_usu_id=t1.usu_id and t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=95 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
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

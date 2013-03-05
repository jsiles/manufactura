<?php
include ("./common.php");

session_start();
/*if (get_session("UserLogin")&&get_session("GroupID")) 
    {
        session_unregister("UserID");
        session_unregister("UserLogin");    
        session_unregister("GroupID");
        }*/
check_security(1);
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
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share = 0 ;
                        else  $market_share = $ingreso/$totaligresos;
                        $tpl->set_var("Label2", number_format($market_share * 100,0)." %" );
                        $tpl->parse ("Total" , true);
                    }
					
                $tpl->set_var("Label11", "EBITDA" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;

                        $utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=81 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($ingreso!=0) $margenutilidad = $utilidadoperativa/$ingreso;
                        else $margenutilidad=0;
                        $tpl->set_var("Label21", number_format($margenutilidad * 100,0)." %" );
                        $tpl->parse ("Total1" , true);
                    }

                $tpl->set_var("Label13", "GIRO DE CAPITAL" );
                    foreach ($periodo as $id_periodo => $valor) 
                    {
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
						
						/*$ingresoxnegocios = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=106 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingresoxnegocios=='') $ingresoxnegocios=0;
						
						$ingresoxextra = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=26 and t.dat_usu_id=$dat_usuario and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingresoxextra=='') $ingresoxextra=0;*/
						
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$dat_usuario and t.dat_ite_id=46 and t.dat_periodo=$id_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;                            
                        if ($totalactivos!=0) $girocapital =  $ingreso/$totalactivos;
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
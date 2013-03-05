<?php
include ("./common.php");

session_start();
/*if (get_session("UserLogin")&&get_session("GroupID")) 
    {
        session_unregister("UserID");
        session_unregister("UserLogin");    
        session_unregister("GroupID");
        }                 */
check_security(1);        
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
    $dat_juego = get_param("id");
    $dat_periodo = get_param("dat_periodo");
   // echo $dat_juego;
    $tpl->set_var("id",$dat_juego);
    $tpl->set_var("dat_periodo",$dat_periodo);
    
    $juego = get_db_value("select jue_nombre from tb_juegos where jue_sw='A' and jue_id=$dat_juego ");
    $tpl->set_var("juego",$juego); 
  /*  $array_juego = db_fill_array("select jue_id, jue_nombre from tb_juegos where jue_sw='A' order by 1");
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
                }*/
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
///////////////////////////////////////////////////////////////////////////////////////////////////////
                $tpl->set_var("Label1", "PARTICIPACIÓN DEL MERCADO" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totaligresos = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($totaligresos=='') $totaligresos=0;    
                        if ($totaligresos==0) $market_share[$z] = 0 ;
                        else $market_share[$z] = $ingreso/$totaligresos;
						if($z>0)
						{
						if($market_share[$z]>$superior) $superior=$market_share[$z];
						}
						else $superior=$market_share[$z];
						$z++;
                    }
					//print_r($mayor);
				foreach($market_share as $key=>$value)
				{
				if ($superior!==$value)
				$tpl->set_var("Label2", number_format($value * 100,0)." %" );
				else
                $tpl->set_var("Label2", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
				$tpl->parse ("Total" , true);
				}
///////////////////////////////////////////////////////////////////////////////////////////////////////				
                $tpl->set_var("Label11", "EBITDA" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                    	
						$utilidadoperativa = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=81 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if ($ingreso!=0) $margenutilidad [$z] = $utilidadoperativa/$ingreso;
                        else $margenutilidad [$z]=0;
						
                  	if($z>0)
						{
						if($margenutilidad[$z]>$superior) $superior=$margenutilidad[$z];
						}
						else $superior=$margenutilidad[$z];
						$z++;
                        
                    }
				foreach($margenutilidad as $key=>$value)
				{
				if ($superior!=$value)
				$tpl->set_var("Label21",number_format($value * 100,0)." %");
				else
                $tpl->set_var("Label21", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
				$tpl->parse ("Total1" , true);
				}
					
///////////////////////////////////////////////////////////////////////////////////////////////////////					
                $tpl->set_var("Label13", "GIRO DE CAPITAL" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
				    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $ingreso = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_ite_id=130 and t.dat_usu_id=$id and t.dat_periodo=$dat_periodo  and t.dat_usu_id=t1.usu_id");
                        if ($ingreso=='') $ingreso=0;
                        $totalactivos = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=46 and t.dat_periodo=$dat_periodo  and t.dat_usu_id=t1.usu_id"); 
                        if ($totalactivos=='') $totalactivos=0;                            
                        if ($totalactivos!=0) $girocapital[$z] =  $ingreso/$totalactivos;
                        else $girocapital[$z]=0;
						if($z>0)
						{
						if($girocapital[$z]>$superior) $superior=$girocapital[$z];
						}
						else $superior=$girocapital[$z];
						$z++;
                        
                    }
				foreach($girocapital as $key=>$value)
				{
				if ($superior!=$value)
				$tpl->set_var("Label23",number_format($value,2));
				else
                $tpl->set_var("Label23", "<font color=\"#ff0000\">".number_format($value,2)."</font>" );
                $tpl->parse ("Total3" , true);
				}

////////////////////////////////////////////////////////////////////////////////////////////////
                $tpl->set_var("Label14", "RENTABILIDAD DE ACTIVOS" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $id81 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=81 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id81)==0) $id81=0;
                        $id46 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=46 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id46)==0) $id46=0;
                        
                        if ($id46!=0) $retornoactivos[$z] =  $id81/$id46;
                        else $retornoactivos[$z]=0;
						//echo $id81."/".$id46."<br>";	
						if($z>0)
						{
						if($retornoactivos[$z]>$superior) $superior=$retornoactivos[$z];
						}
						else $superior=$retornoactivos[$z];
						$z++;
                        
                    }
				foreach($retornoactivos as $key=>$value)
				{
				if ($superior!=$value)
				$tpl->set_var("Label24",number_format($value * 100,0)." %" );
				else
                $tpl->set_var("Label24", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
                $tpl->parse ("Total4" , true);
				}
			//			$tpl->set_var("Label24",number_format($retornoactivos * 100,0)." %");
            //            $tpl->parse ("Total4" , true);
//////////////////////////////////////////////////////////////////////////////////						
                
                $tpl->set_var("Label15", "RENTABILIDAD EN PATRIMONIO" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $id30 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=30 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id30)==0) $id30=0;
                        $id55 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=55 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id55)==0) $id55=0;
                        
                        if ($id55!=0) $retornopatrimonio[$z] =  $id30/$id55;
                        else $retornopatrimonio[$z]=0;
						//echo $id30."/".$id55."<br>";	
						
						if($z>0)
						{
						if($retornopatrimonio[$z]>$superior) $superior=$retornopatrimonio[$z];
						}
						else $superior=$retornopatrimonio[$z];
						$z++;
						
                    }

				foreach($retornopatrimonio as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label25",number_format($value * 100,0)." %" );
				else
                $tpl->set_var("Label25", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
                $tpl->parse ("Total5" , true);
				}
//                      $tpl->set_var("Label25",number_format($retornopatrimonio * 100,0)." %");
//                        $tpl->parse ("Total5" , true);

////////////////////////////////////////////////////////////////////////////////////					
                $tpl->set_var("Label16", "VULNERABILIDAD" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $id51 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=51 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id51)==0) $id51=0;
                        $id55 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=55 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id55)==0) $id55=0;
                        
                        if ($id55!=0) $vulnerabilidad[$z] =  $id51/$id55;
                        else $vulnerabilidad[$z]=0;
						//echo $id30."/".$id55."<br>";	
						
						if($z>0)
						{
						if($vulnerabilidad[$z]<$superior) $superior=$vulnerabilidad[$z];
						}
						else $superior=$vulnerabilidad[$z];
						$z++;
						
                    }

				foreach($vulnerabilidad as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label26",number_format($value * 100,0)." %" );
				else
                $tpl->set_var("Label26", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
                $tpl->parse ("Total6" , true);
				}
                    //    $tpl->set_var("Label26",number_format($vulnerabilidad * 100,0)." %");
                    //    $tpl->parse ("Total6" , true);
                    
//////////////////////////////////////////////////////////////////////////////////
                $tpl->set_var("Label17", "LIQUIDEZ" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
						//33+34+35+36+109+118
                        $id33 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=33 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id33)==0) $id33=0;
                        $id34 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=34 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id34)==0) $id34=0;
                        $id35 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=35 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id35)==0) $id35=0;
                        $id36 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=36 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id36)==0) $id36=0;
                        $id109 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=109 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id109)==0) $id109=0;
                        $id118 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=118 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id118)==0) $id118=0;
                        //48+49
                        $id48 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=48 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id48)==0) $id48=0;
                        $id49 = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=49 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id");
                        if (strlen($id49)==0) $id49=0;
						$prestamocortoplazo=$id48+$id49;
						$activosrapidos=$id33+$id34+$id35+$id36+$id109+$id118;
						//echo "(".$id33."+".$id34."+".$id35."+".$id36."+".$id109."+".$id118.")/(".$id48."+".$id49.")"."<br>";
						if ($prestamocortoplazo!=0) $liquidez[$z] =  $activosrapidos/$prestamocortoplazo;
                        else $liquidez [$z]=0;
						if($z>0)
						{
						if($liquidez[$z]>$superior) $superior=$liquidez[$z];
						}
						else $superior=$liquidez[$z];
						$z++;
						
                    }

				foreach($liquidez as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label27",number_format($value,2));
				else
                $tpl->set_var("Label27", "<font color=\"#ff0000\">".number_format($value,2)."</font>" );
                $tpl->parse ("Total7" , true);
				}
                      //  $tpl->set_var("Label27", number_format($liquidez* 100,0)." %" );
                      //  $tpl->parse ("Total7" , true);
                    //}
//////////////////////////////////////////////////////////////////////////////////////////////////
                $tpl->set_var("Label18", "UTILIDADES NETAS ACUMULADAS" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
						$utilidadnetaacumulada[$z] = 0;
						
						for($k=1;$k<=$dat_periodo;$k++)
                        $utilidadnetaacumulada[$z] += get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=80 and t.dat_periodo=$k and t.dat_usu_id=t1.usu_id"); 
						
                        if (strlen($utilidadnetaacumulada[$z])==0) $utilidadnetaacumulada[$z] = 0;
						
						if($z>0)
						{
						if($utilidadnetaacumulada[$z]>$superior) $superior=$utilidadnetaacumulada[$z];
						}
						else $superior=$utilidadnetaacumulada[$z];
						$z++;
						
                    }
				$superiorUtilidadNetaAcumulada =$superior;
				foreach($utilidadnetaacumulada as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label28",$value);
				else
                $tpl->set_var("Label28", "<font color=\"#ff0000\">".$value ."</font>" );
                $tpl->parse ("Total8" , true);
				}
                     //   $tpl->set_var("Label28", $utilidadnetaacumulada );
                     //   $tpl->parse ("Total8" , true);
                    //}
//////////////////////////////////////////////////////////////////////////////
                $tpl->set_var("Label19", "VALOR DE LA EMPRESA" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                        $valoractualdelaempresa[$z] = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=95 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if (strlen($valoractualdelaempresa[$z]) ==0) $valoractualdelaempresa[$z] =0;
						if($z>0)
						{
						if($valoractualdelaempresa[$z]>$superior) $superior=$valoractualdelaempresa[$z];
						}
						else $superior=$valoractualdelaempresa[$z];
						$z++;
						
                    }
				$superiorValorEmpresa =$superior;
				foreach($valoractualdelaempresa as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label29",$value);
				else
                $tpl->set_var("Label29", "<font color=\"#ff0000\">".$value ."</font>" );
                $tpl->parse ("Total9" , true);
				}
                 //       $tpl->set_var("Label29", $valoractualdelaempresa );
                 //       $tpl->parse ("Total9" , true);
                 //   }
////////////////////////////////////////////////////////////////////////////					
$tpl->set_var("Label12", "NOTA DE LA SIMULACION" );
                $sUsuario = "select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$dat_juego";
                $db->query($sUsuario);
                $next_record = $db->next_record();
				$z=0;
                    while ($next_record)
                    {
                        $id = $db->f("usu_id");
                        $nombre = $db->f("usu_nombre");
                        $next_record = $db->next_record();
                      /*  $utilidadnetaacumulada[$z] = get_db_value ("select sum(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=54 and t.dat_usu_id=t1.usu_id"); 
						$cantidad[$z] = get_db_value ("select count(t.dat_monto) from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=54 and t.dat_usu_id=t1.usu_id"); 
						if (($utilidadnetaacumulada[$z]>0)&&($cantidad[$z]>0))$utilidadnetaacumulada[$z] /= $cantidad[$z];*/
						
						
                        if (strlen($utilidadnetaacumulada[$z])==0) $utilidadnetaacumulada[$z] =0;
						$valoractualdelaempresa[$z] = get_db_value ("select t.dat_monto from tb_datos t, tb_usuarios t1 where t1.usu_jue_id=$dat_juego and t.dat_usu_id=$id and t.dat_ite_id=95 and t.dat_periodo=$dat_periodo and t.dat_usu_id=t1.usu_id"); 
                        if (strlen($valoractualdelaempresa[$z]) ==0) $valoractualdelaempresa[$z] =0;
						($superiorUtilidadNetaAcumulada==0)?$notaA=0:$notaA=$utilidadnetaacumulada[$z]/$superiorUtilidadNetaAcumulada*0.5;
						($superiorValorEmpresa==0)?$notaB=0:$notaB=$valoractualdelaempresa[$z]/$superiorValorEmpresa*0.5;
						
						//echo "A".$notaA."=".$utilidadnetaacumulada[$z]."/".$superiorUtilidadNetaAcumulada."*0.5"."<br>";
						//echo "B".$notaB."=".$valoractualdelaempresa[$z]."/".$superiorValorEmpresa."*0.5"."<br>";
	
						//echo $notaB."=".$valoractualdelaempresa[$z]."/".$superiorValorEmpresa."*0.5";
						$notaJuego[$z]=$notaA+$notaB;
						if($z>0)
						{
						if($notaJuego[$z]>$superior) $superior=$notaJuego[$z];
						}
						else $superior=$notaJuego[$z];
						$z++;
						
                    }
				foreach($notaJuego as $key=>$value)
				{
				//echo $value;
				if ($superior!=$value)
				$tpl->set_var("Label22",number_format($value * 100,0)." %");
				else
                $tpl->set_var("Label22", "<font color=\"#ff0000\">".number_format($value * 100,0)." %"."</font>" );
                $tpl->parse ("Total2" , true);
				}
                      //  $tpl->set_var("Label22",number_format($margencontribucion * 100,0)." %");
                      //  $tpl->parse ("Total2" , true);
                    //}



    } 
    else
    {
                   $tpl->set_var("Datos", "");
    }   
    
}
?>

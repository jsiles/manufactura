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
$filename = "reporte3.php";
$template_filename = "reporte3.html";

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
				$sSQL="select par_valor, par_descripcion from py_parametros where par_jue_id=$dat_juego order by par_id asc";
  			    $db->query($sSQL);
				$cantidadRegistros1 = $db->num_rows();
				if($cantidadRegistros1>0)
				{
				 while($db->next_record())
				 { 
				  $tpl->set_var("Label1", $db->f("par_descripcion"));
  				    
                    foreach ($periodo as $id_periodo => $valor) 
                    {    
                        $valorInicial =  $db->f("par_valor");
                        if ($valorInicial=='') $valorInicial=0;
                         $tpl->set_var("Label2", $valorInicial); 
						$acumuladoProcentaje= 0;	
                        $tpl->set_var("Label3", $valor . $acumuladoProcentaje);
						$tpl->parse ("Total" , true);
                    }
					$tpl->parse ("Row" , true);
				    $tpl->set_var ("Total" , "");
					$tpl->set_var ("NoRecords" , "");
				}
              }
    } 
    else
    {
                   $tpl->set_var("Datos", "");
    }   
    
}

?>
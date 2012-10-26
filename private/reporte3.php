<?php
include ("./common2.php");
session_start();
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
    global $db,$db1, $tpl;
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
        $array_usuario = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_sw='A' and usu_jue_id = $dat_juego order by 2");
        if(is_array($array_usuario))
                {
                   while(list($key, $value) = each($array_usuario))
                  {
                  
                    $tpl->set_var("nombre", $value);
                    $dat_usuario = $key;
                     $id_periodo= $dat_periodo;
                     //foreach ($periodo as $id_periodo => $valor) 
                    //{
                        $tpl->set_var("producto", $key);
                        
                        /**********************************/
                         $sSQL = "select * from tb_compras, tb_investigaciones where inv_id=com_inv_id and inv_sw=1 and inv_per_id=$id_periodo and com_usu_id = $dat_usuario";
                            $db->query($sSQL);
                            $numRows = $db->num_rows();
                            $com_costo=0;
                            if ($numRows>0)
                            {
                            while($next_record=$db->next_record())
                            {
                                $com_inv_id = $db->f("com_inv_id");
                                $com_exclusividad = $db->f("com_exclusividad");
                                $db1->query("select * from tb_investigaciones where inv_id=$com_inv_id");
                                $next_record1 = $db1->next_record();
                                $com_pro_id = $db1->f("inv_pro_id");
                                $com_mer_id = $db1->f("inv_mer_id");
                                $com_per_id = $db1->f("inv_per_id");  
                                $com_jue_id = $db1->f("inv_jue_id");  
                                    if ($com_per_id!=$dperiodo) 
                                    {
                                        $com_costo = 0;
                                       $dperiodo = $com_per_id;
                                    }

                                if ($com_exclusividad==1) 
                                {
                                    $com_costo += $db1->f("inv_costoexclusividad");
                                }
                                else
                                {
                                    $com_costo += $db1->f("inv_costo");
                                }
                                $archpdf = $db1->f("inv_pdf");  
                                $dproducto = get_db_value("select pro_nombre from tb_productos where pro_id=$com_pro_id and pro_jue_id=$com_jue_id");            
                                $dmercado = get_db_value("select mer_nombre from tb_mercados where mer_id=$com_mer_id and mer_jue_id=$com_jue_id");            
                                if ($dproducto==null) $dproducto="TODOS LOS PRODUCTOS";
                                if ($dmercado==null) $dmercado="TODOS LOS MERCADOS";
                                
                                $tpl->set_var("Dcosto",$com_costo);           
                                $tpl->set_var("Dproducto",$dproducto);           
                                $tpl->set_var("Dmercado",$dmercado);           
                                $tpl->set_var("Dperiodo",$com_per_id);           
                                ($com_exclusividad==0)?$compra="Exclusividad":$compra="Normal";
                                $tpl->set_var("Dcompra",$compra);  
                                $tpl->parse("InvD",true);
                            }
                         
                         }
                        /**********************************/
                        
                        
                        
//                        $tpl->parse("Total", true);
                    
                   // }   
                  
                    
                    
                    $tpl->parse("usuario", true);
                    $tpl->set_var("InvD", "");
                  }
                }


    } else 
    {
                 $tpl->set_var("ID", "");
                 $tpl->set_var("Value", "Seleccionar valor");
                 $tpl->set_var("Datos", "");
    
    }
}
?>

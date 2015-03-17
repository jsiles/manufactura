<?php
include ("./common.php");
session_start();
check_security(1);
$formAction=get_param("formAction");
$filename = "investigaciones.php";
$template_filename = "investigaciones.html";
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);

//echo "#;";
if ($formAction=="comprar") compra();
else reporte();
$tpl->pparse("main", false);
function compra()
{    
    global $db,$tpl;
    $dat_juego = get_param("id");
    $dat_usuario = get_session("cliID");
    $fldinvestigacion= get_param("inv_id"); 
    $fldexclusividad = get_param("exclusividad");
    $cantidad = get_db_value("select inv_saldo from tb_investigacion where inv_id=$fldinvestigacion");
    if ($cantidad>0)
    { 
      if ($fldexclusividad==1)
      {
        //echo $fldexclusividad;
        $saldo = 0;
        $db->query("update tb_investigacion set inv_saldo=0 where inv_id=$fldinvestigacion");
        $db->query("insert into tb_compras values (null, $fldinvestigacion, 1, $dat_usuario )");
      } else
      { 
          //echo $cantidad;
          $saldo = $cantidad -1;
          $pdf = get_db_value("select inv_pdf from tb_investigacion where inv_id = $fldinvestigacion");
          $db->query("update tb_investigacion set inv_saldo=$saldo where inv_id=$fldinvestigacion");
          $db->query("insert into tb_compras values (null, $fldinvestigacion, 0, $dat_usuario )");
         // echo $saldo;
      }
    }
    header("Location: investigaciones.php?id=$dat_juego");
    exit;
    
}

function reporte()
{
    global $db,$db1, $tpl;
    $dat_juego = get_param("id");
    $dat_usuario = get_session("cliID");
    $fldperiodo = get_param("dat_periodo");
	//echo $fldperiodo."##".$dat_juego;
	if ($fldperiodo=='') $fldperiodo=1;
    $LBperiodo = $fldperiodo;
    $tpl->set_var("id",$dat_juego);
    
    if ($dat_usuario!=null)
    {
		$tpl->set_var("jue_idLink",$dat_juego);
		$tpl->set_var("usu_idLink",$dat_usuario);
		
        $sSQL = "select * from tb_compras, tb_investigacion where inv_id=com_inv_id and inv_sw=1 and inv_per_id=$fldperiodo and com_usu_id = $dat_usuario";
		//echo $sSQL;
        $db->query($sSQL);
        $numRows = $db->num_rows();
        $com_costo=0;
        if ($numRows>0)
        {
        while($next_record=$db->next_record())
        {
            $com_inv_id = $db->f("com_inv_id");
            $com_exclusividad = $db->f("com_exclusividad");
            
            $db1->query("select * from tb_investigacion where inv_id=$com_inv_id");
            $next_record1 = $db1->next_record();
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
            $dproducto = $db1->f("inv_investigacion");
            
            $tpl->set_var("Dcosto",$com_costo);           
            $tpl->set_var("Dproducto",$dproducto);           
            $tpl->set_var("Dperiodo",$com_per_id);           
            $tpl->set_var("linkInvC",$archpdf);
            $tpl->parse("InvC",true);
            $tpl->parse("InvD",true);
        }
        }else
        {
            $tpl->set_var("InvC","");
            $tpl->set_var("InvD","");
        }
    }
	////////////////////////////////////////////////
	//  aca viene la parte de control de tiempo   //
    ////////////////////////////////////////////////

	if ($dat_juego)
    {
			
		$sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
		"t.jue_id as id from tb_juegos t where t.jue_id=$dat_juego ".
		"  and t.jue_sw='A' " ;
		//echo $sSQL;die;
		$db->query($sSQL);
		$next_record = $db->next_record();
		$per_inicio = $db->f("inicio");
		$per_cantidad = $db->f("cantidad");

        $tpl->set_var("id",$dat_juego);
        $maxPeriodoActive=get_db_value("select per_periodo from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A' order by per_periodo desc limit 1");    
        $periodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$dat_juego and per_periodo <= $maxPeriodoActive limit $per_cantidad ");
		if(in_array($fldperiodo,$periodo))
		$active_periodo = $fldperiodo;
		else
		$active_periodo = get_db_value("select max(per_periodo) from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A' limit 1");
    		
		//print_r($periodo);
        $i=0;
        $j=0;
        //print_r($mercado);
        if(is_array($periodo))
                {
                  reset($periodo);

                  while(list($key, $value) = each($periodo))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $LBperiodo)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Periodo", true);
                  }
                }
                else 
                {
                    $tpl->set_var("ID", "");
                    $tpl->set_var("Value", "Informaci&oacute;n disponible");
                    $tpl->parse("Periodo", true);
                }

		$dateGame = get_db_value("select per_datetime from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'");
	//	echo "select per_datetime from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'";
		//echo $dateGame."<br>";
		$difDate = time_diff($dateGame , date("Y-m-d H:i:s"));
		
		//echo $difDate."<br>".date("Y-m-d H:i:s")."<br>".$dateGame ;
		/*$dateGameY = substr($dateGame,0,4);
		$dateGameM = substr($dateGame,5,2);
		$dateGameD = substr($dateGame,8,2);
		$dateGameH = substr($dateGame,11,2)*60*60;
		$dateGameI = substr($dateGame,14,2)*60;
		$dateGameS = substr($dateGame,17,2);
		$timeF = $dateGameH+$dateGameI+$dateGameS;
		$date2H = date("h")*60*60;
		$date2I = date("i")*60;
		$date2S = date("s");
		$time2F = $date2H+$date2I+$date2S;
		echo $timeF."-".$time2F;
		
		$difDate = $timeF-$time2F;*/
		if ($difDate<1) $clock=0;
		else $clock= $difDate;
		//echo $clock;
		$tpl->set_var("clock",$clock);    
        
		$tpl->set_var("pro_nombre",$dproducto);
        // $inv_per_id = get_db_value("select per_periodo from tb_periodos where per_jue_id=$dat_juego and  per_estado='A' and per_inv_estado='A'");
		// echo "select per_periodo from tb_periodos where per_jue_id=$dat_juego and  per_estado='A' and per_inv_estado='A' ";
        $inv_per_id = $active_periodo; //$fldperiodo;
		//echo $inv_per_id;
		//echo $inv_per_id;
        if ($inv_per_id!=null)
        {
		//echo 2;
           $sSQL= "select inv_id, inv_investigacion, inv_costo, inv_costoexclusividad, inv_saldo from tb_investigacion where inv_jue_id=$dat_juego and inv_sw=1 and inv_per_id=$inv_per_id and inv_saldo>0";
		   $db->query($sSQL);
		   //echo("#".$db->num_rows());
		   $next_record=$db->next_record();
		   if($next_record)
		   while($next_record)
		   {
			   
			   
				//echo 4;
				
				$costo = $db->f("inv_costo");						
				$costoexclusividad = $db->f("inv_costoexclusividad");	
				$saldo = $db->f("inv_saldo");	
				$inv_id = $db->f("inv_id");	
				$nombre = $db->f("inv_investigacion");
				//echo $costo."$$".$costoexclusividad."$$".$saldo."$$".$inv_id."<br>";
				if ($inv_id!=null)
				{
				$check = get_db_value("select count(*) from tb_compras where com_inv_id=$inv_id and com_usu_id=$dat_usuario");
				//echo $check;
				if ($check>0) $error = "Ya compro la información ddisponible";
	//			if (($costo!=null&&$costo!=0)&&($costoexclusividad!=null&&$costoexclusividad!=0)&&($saldo>0)&&($check==0)) original
				if (($costo!=null&&$costo!=null)&&($costoexclusividad!=null)&&($saldo>0)&&($check==0))
				{
					
					//echo "<br>"."2";
					
					$tpl->set_var("NoDatos","");                    
					$tpl->set_var("NoRecords",""); 
					$tpl->set_var("Errors","");                   
					$tpl->set_var("costo",$costo);
					$tpl->set_var("pro_nombre",$nombre);
					//echo $costoexclusividad."<br>";
						if($costoexclusividad==0)
						{
						$tpl->set_var("costoEE","disabled");
						$tpl->set_var("costoexclusividad","Opci&oacute;n no disponible");
						}else
						{
							$tpl->set_var("costoexclusividad",$costoexclusividad);
							$tpl->set_var("costoEE","");
						}
						$tpl->set_var("inv_id",$inv_id);
						$tpl->parse("DatosInfo", true);				
				
				}else 
				{
					//echo "3";die;
					$tpl->set_var("DatosInfo","");
					$tpl->set_var("NoDatos","");                    
					if ($check>0) 
					
						$tpl->set_var("NoRecords","");
					else 
						$tpl->set_var("Errors","");
						//$tpl->parse("DatosInfo", true);				
				
				}
				
				
				//echo $inv_per_id."-".$costo."-".$costoexclusividad."#";
				}else 
				{
				/*	$tpl->set_var("NoRecords","");   
					$tpl->set_var("DatosInfo","");
					$tpl->set_var("Errors","");
					$tpl->parse("DatosInfo", true);*/
				}
		   		
		   
		   
		   $next_record=$db->next_record();
		   
		   }else 
		   {
			$tpl->set_var("NoRecords","");   
            $tpl->set_var("DatosInfo","");
            $tpl->set_var("Errors","");
           
			   }
        }else 
        {
            $tpl->set_var("NoRecords","");   
            $tpl->set_var("DatosInfo","");
            $tpl->set_var("Errors","");
           
        }
    }   
	   
	   
	///////////////////////////////////////////////   
}

?>
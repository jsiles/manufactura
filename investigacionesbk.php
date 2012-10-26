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
if ($formAction=="comprar") compra();
else reporte();
$tpl->pparse("main", false);
function compra()
{    
    global $db,$tpl;
    $dat_juego = get_param("id");
    $dat_usuario = get_session("cliID");
    $fldproducto= get_param("pro_id"); 
    $fldmercado= get_param("mer_id"); 
    $fldinvestigacion= get_param("inv_id"); 
    $fldexclusividad = get_param("exclusividad");
    $cantidad = get_db_value("select inv_saldo from tb_investigaciones where inv_id=$fldinvestigacion");
    if ($cantidad>0)
    { 
      if ($fldexclusividad!=null)
      {
        //echo $fldexclusividad;
        $saldo = 0;
        $db->query("update tb_investigaciones set inv_saldo=0 where inv_id=$fldinvestigacion");
        $db->query("insert into tb_compras values (null, $fldinvestigacion, 1, $dat_usuario )");
      } else
      { 
          //echo $cantidad;
          $saldo = $cantidad -1;
          $pdf = get_db_value("select inv_pdf from tb_investigaciones where inv_id = $fldinvestigacion");
          $db->query("update tb_investigaciones set inv_saldo=$saldo where inv_id=$fldinvestigacion");
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
    $fldproducto= get_param("pro_id"); 
    $fldmercado= get_param("mer_id");
    $fldperiodo = get_param("dat_periodo");
    if ($fldperiodo=='') $fldperiodo=1;
    $LBperiodo = $fldperiodo;
    //echo $fldperiodo; 
    //echo $fldproducto."-".$fldmercado;
    $tpl->set_var("id",$dat_juego);
    
    if ($dat_usuario!=null)
    {
        $sSQL = "select * from tb_compras, tb_investigaciones where inv_id=com_inv_id and inv_sw=1 and inv_per_id=$fldperiodo and com_usu_id = $dat_usuario";
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
    
    if ($dat_juego)
    {
        
        $tpl->set_var("id",$dat_juego);
            
        $periodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'");
        $producto = db_fill_array("select pro_id, pro_nombre from tb_productos where pro_jue_id=$dat_juego and pro_sw='A'");
        $mercado = db_fill_array("select mer_id, mer_nombre from tb_mercados where mer_jue_id=$dat_juego and mer_sw='A'");           
        $producto[66]="TODOS LOS PRODUCTOS";
        $mercado[66]="TODOS LOS MERCADOS";
        $i=0;
        $j=0;
        //print_r($mercado);
        if ($producto!=null)
        foreach ($producto as $key => $value)
        {
                 $tpl->set_var("ID", $key);
                 $tpl->set_var("Value", $value);
                 if ($fldproducto==$key) $tpl->set_var("selected","selected");
                 else $tpl->set_var("selected","");
                 $tpl->parse("Producto",true);
                 if ($i==0) {$tmpproducto = $value;$tmppro_id=$key;}
                 $i++;
        }
    
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
                    $tpl->set_var("Value", "Investigaciones de mercado");
                    $tpl->parse("Periodo", true);
                }

		$dateGame = get_db_value("select per_datetime from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'");
		$dateGameY = substr($dateGame,0,4);
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
//		echo $timeF-$time2F;
		
		$difDate = $timeF-$time2F;
		if ($difDate<6) $clock=15;
		else $clock= $difDate;

		//echo $clock;
		//$tpl->set_var("date1",date("Y/m/d h:i:s"));
		//$tpl->set_var("date2",$dateGame);
		//$tpl->set_var("seg",$difDate);    
		$tpl->set_var("clock",$clock);    
        if ($mercado!=null)
        foreach ($mercado as $key => $value)
        {
                 
                 $tpl->set_var("ID", $key);
                 $tpl->set_var("Value", $value);
                 if ($fldmercado==$key) $tpl->set_var("selected","selected"); 
                 else $tpl->set_var("selected",""); 
                 $tpl->parse("Mercado",true);
                 
                 if ($j==0) {$tmpmercado = $value;$tmpmer_id=$key;}
                 $j++;

        }
        if ($fldproducto==null) {$fldproducto=$tmppro_id;}
        $pro_nombre= get_db_value("select pro_nombre from tb_productos where pro_jue_id=$dat_juego and pro_sw='A' and pro_id='$fldproducto'");
        if ($fldmercado==null) {$fldmercado=$tmpmer_id;}
        $mer_nombre=get_db_value("select mer_nombre from tb_mercados where mer_jue_id=$dat_juego and mer_sw='A' and mer_id='$fldmercado'");
        if ($pro_nombre==null) $pro_nombre="TODOS LOS PRODUCTOS";
        if ($mer_nombre==null) $mer_nombre="TODOS LOS MERCADOS";
        $tpl->set_var("pro_nombre",$pro_nombre);
        $tpl->set_var("mer_nombre",$mer_nombre);
        //$inv_per_id = get_db_value("select per_periodo from tb_periodos where per_jue_id=$dat_juego and  per_estado='A' and per_inv_estado='A' ");
        $inv_per_id = $fldperiodo;
        if ($inv_per_id!=null)
        {
            
            $costo = get_db_value("select inv_costo from tb_investigaciones where inv_jue_id=$dat_juego and inv_sw=1 and inv_mer_id=$fldmercado and inv_per_id=$inv_per_id and inv_pro_id=$fldproducto");
            $costoexclusividad = get_db_value("select inv_costoexclusividad from tb_investigaciones where inv_jue_id=$dat_juego and inv_sw=1 and inv_mer_id=$fldmercado and inv_per_id=$inv_per_id and inv_pro_id=$fldproducto");
            $saldo = get_db_value("select inv_saldo from tb_investigaciones where inv_jue_id=$dat_juego and inv_sw=1 and inv_mer_id=$fldmercado and inv_per_id=$inv_per_id and inv_pro_id=$fldproducto");
            $inv_id = get_db_value("select inv_id from tb_investigaciones where inv_jue_id=$dat_juego and inv_sw=1 and inv_mer_id=$fldmercado and inv_per_id=$inv_per_id and inv_pro_id=$fldproducto");
            if ($inv_id!=null)
            {
            $check = get_db_value("select count(*) from tb_compras where com_inv_id=$inv_id and com_usu_id=$dat_usuario");
            if ($check>0) $error = "Ya compro la investigación de mercados";
            if (($costo!=null&&$costo!=0)&&($costoexclusividad!=null&&$costoexclusividad!=0)&&($saldo>0)&&($check==0))
            {
                $tpl->set_var("NoDatos","");                    
                $tpl->set_var("NoRecords",""); 
                $tpl->set_var("Errors","");                   
                $tpl->set_var("costo",$costo);
                $tpl->set_var("costoexclusividad",$costoexclusividad);
                $tpl->set_var("inv_id",$inv_id);
            }else 
            {
                $tpl->set_var("Datos","");
                $tpl->set_var("NoDatos","");                    
                if ($check>0) 
                    $tpl->set_var("NoRecords","");
                else 
                    $tpl->set_var("Errors","");

                
            }
            //echo $inv_per_id."-".$costo;
            }else 
            {
                $tpl->set_var("NoRecords","");   
                $tpl->set_var("Datos","");
                $tpl->set_var("Errors","");
            }
            
        }else 
        {
            $tpl->set_var("NoRecords","");   
            $tpl->set_var("Datos","");
            $tpl->set_var("Errors","");
           
        }
    } else 
    {
                 $tpl->set_var("ID", "");
                 $tpl->set_var("Value", "Seleccionar valor");
                 $tpl->parse("Mercado",false);
    }
    
    if ($dat_juego&&$dat_usuario)
    {
           
    } 
    else
    {
                   $tpl->set_var("Datos", "");
    }   
    
}

?>
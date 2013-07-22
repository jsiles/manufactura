<?php
/**
 *
 *
 * @version JSiles
 * @copyright 2006
 */
include ("./common.php");
//include ("./header.php");

global    $periodo,$rel_id;
session_start();
check_security(1);

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$user_id = get_session("cliID");
$jue_id = get_param("id");


    $sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
    "t.jue_id as id from tb_juegos t where t.jue_id=$jue_id ".
    "  and t.jue_sw='A' " ;
    $db->query($sSQL);
    $next_record = $db->next_record();
    $per_inicio = $db->f("inicio");
    $per_cantidad = $db->f("cantidad");
    $jue_id = $db->f("id");
    $per_in = $per_inicio;
    for ($i=0;$i<=$per_cantidad;$i++)
    {
         if (is_numeric($per_inicio)) {
            $sSQL = "select count(*) from tb_balances where bal_usu_id=$user_id and bal_periodo=$per_inicio and bal_flag=1 and bal_sw='A'";
            $vCount = get_db_value($sSQL);
            if ($vCount!=0) {
                $periodo[$per_inicio]=$per_inicio;
            }
            elseif ($per_in==$per_inicio)
                {
                    $periodo[$per_inicio]=$per_inicio;
                }
            else {
            $sSQL = "select count(*) from tb_balances where bal_usu_id=$user_id and bal_periodo=$per_inicio and bal_flag=0 and bal_sw='A'";
            $vCount = get_db_value($sSQL);
            if ($vCount==0)
                {
                $sSQL = "insert into tb_balances values (null, $user_id, $per_inicio,'A',0)";
                 $db->query($sSQL);
                 } else
                 {
                     $periodo_anterior = $per_inicio-1;
                     $sTotalActivo = get_db_value("select t.dat_monto from tb_datos t where t.dat_ite_id=46 and t.dat_usu_id=$user_id and t.dat_periodo=$periodo_anterior and t.dat_sw='A'");
                     $sTotalPasivo = get_db_value("select t.dat_monto from tb_datos t where t.dat_ite_id=56 and t.dat_usu_id=$user_id and t.dat_periodo=$periodo_anterior and t.dat_sw='A'");
                     if ($sTotalActivo=='') $sTotalActivo=0;
                     if ($sTotalPasivo=='') $sTotalPasivo=0;
                     if (($sTotalActivo!=0) or ($sTotalPasivo!=0)) {
                                $iBalance = abs($sTotalActivo-$sTotalPasivo);
                                if ($iBalance<=2) {
                                    $sSQL="update tb_balances set bal_flag=1 where bal_usu_id=$user_id and bal_periodo=$per_inicio and bal_sw='A'";
                                    $db->query($sSQL);
                                    $periodo[$per_inicio]=$per_inicio;
                                }
                        }
                 }
                break;

             }
        $per_inicio++;
         }

    }


if (strlen($sAction)) {
//echo $sAction;
    elementos_action($sAction);
}
$filename = "menu.php";
$template_filename = "menu.html";

$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);
$tpl->set_var("CCS_Style","Coco");
elementos_show();
$tpl->pparse("main", false);
function elementos_action($sAction)
{
    global $tpl, $db, $sError;

    $dat_periodo = get_param("dat_periodo");//****
    $dat_ele_id = get_param("ele_elemento");//****
    $dat_fechahora = date("Ymdhis");
    $dat_user_id = get_session("cliID");
    $ele_id = get_param("ele_id");
    $sItems = get_param("items");  ////**********
    $sTransitParams = "?ele_id=$ele_id&dat_periodo=$dat_periodo&";
    $jue_id = get_param("id");
    $pro_id = get_param("dat_producto");
    $mer_id = get_param("dat_mercado");
    $sError = "";

    if (!strlen($dat_periodo)) $sError .= "El valor en el campo Periodo es requerido<br>";
    if (!strlen($dat_ele_id)) $sError .= "El valor en el campo Elemento es requerido<br>";
    if (!is_numeric($dat_periodo)) $sError .= "El valor en el campo Periodo es incorrecto<br>";
    if (strlen($sError)) {echo $sError; return;}
    switch($sAction)
        {
            case "modificar":
            foreach($dat_ele_id as $key => $value)
            {
                $valida = get_db_value("select count(*) from tb_datos where dat_ite_id=$key and dat_usu_id=$dat_user_id and dat_periodo=$dat_periodo");
                //echo $valida."<br>";
                if ($valida==0)
                $sSQL = "insert into tb_datos ( dat_ite_id, dat_usu_id, dat_monto, dat_fechahora, dat_periodo )".
                " values ($key, $dat_user_id, $value, '$dat_fechahora', $dat_periodo)";
                else $sSQL = "update tb_datos set dat_monto=$value, dat_fechahora='$dat_fechahora' ".
                " where dat_ite_id=$key and dat_usu_id=$dat_user_id and dat_periodo=$dat_periodo";
                $db->query($sSQL);
                }
            break;
            case "items":
                //print_r($sItems);
                foreach($sItems as $cli => $cliente)
                {
                        foreach($cliente as $trimestre => $monto)
                        {
                            $sValida = get_db_value("select count(*) from th_valores where val_usu_id=$dat_user_id and val_pro_id=$pro_id and val_mer_id=$mer_id and val_cli_id=$cli and val_tri_id=$trimestre and val_periodo=$dat_periodo and val_sw='A'");
                            if ($sValida==0) {
                            $sSQL="insert into th_valores values (null,$dat_user_id,$pro_id,$mer_id,$cli,$trimestre,$dat_periodo,$monto,'A' )";
                            $db->query($sSQL);
                            } else {
                                $sSQL="update th_valores set val_cantidad=$monto where val_usu_id=$dat_user_id and val_pro_id=$pro_id and val_mer_id=$mer_id and val_cli_id=$cli and val_tri_id=$trimestre and val_periodo=$dat_periodo and val_sw='A'";
                                $db->query($sSQL);
                            }
                            }
                }
                $sTransitParams.="apl=2&";

            break;

        }
         header("Location: datos2.php".$sTransitParams."id=$jue_id");

}
function elementos_record()
{
    global $tpl, $db, $sError,$periodo;
    //echo $sError;
    $ele_id = get_param("ele_id");
    $dat_id =get_param("dat_id");
    $LBperiodo = get_param("dat_periodo");
    if (!$LBperiodo) $LBperiodo=-1;
    $sTransitParams = "?ele_id=$ele_id&";
    $user_id = get_session("cliID");
    $jue_id = get_param("id");
    $pro_id = get_param("dat_producto");
    $mer_id = get_param("dat_mercado");

    $tpl->set_var("dat_producto", $pro_id );
    $tpl->set_var("dat_mercado", $mer_id );
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
                    if($key == $LBperiodo)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Periodo", true);
                  }
                }



                    $tpl->set_var("jue_id", $jue_id);

//print_r($periodo);
}
function elementos_show()
{
 global $tpl,$db;
 $jue_id=get_param("id");
 //set_session("LBperiodo",1);
 $dat_periodo = get_session("LBperiodo");
 if ($dat_periodo=='') $dat_periodo=1;
 
 $sSQL="select t.ite_id, t.ite_nombre, t.ite_apl from tb_items t, `th_grupos` t1 where t.ite_id_itemSuperior is null and t.ite_id = t1.gru_ite_id and t1.gru_jue_id=$jue_id and t.ite_id not in (96) order by ite_id asc";
 $db->query($sSQL);
 $next_record=$db->next_record();
 $i=0;
 $z=1;
  			  $tpl->set_var("Detail_Src", "investigaciones.php?id=$jue_id&dat_periodo=$dat_periodo&apl=1");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","INFORMACIÓN DISPONIBLE");
              $tpl->parse("Row",true);
 
			  $tpl->set_var("Detail_Src", "responsabilidad.php?id=$jue_id&per_periodo=$dat_periodo&");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","RESPONSABILIDAD SOCIAL");
              $tpl->parse("Row",true);
			  
 			  $tpl->set_var("Detail_Src", "celebridades.php?id=$jue_id&per_periodo=$dat_periodo&");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","SUBASTA PROYECTOS");
              $tpl->parse("Row",true);
			  
			  $tpl->set_var("Detail_Src", "ventasonline.php?id=$jue_id&per_periodo=$dat_periodo&");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","LICITACIÓN PROYECTOS");
              $tpl->parse("Row",true);
            
			  $tpl->set_var("Detail_Src", "datosproyecto.php?jue_id=$jue_id&per_periodo=$dat_periodo&");
              $tpl->set_var("idMenu",$z++);
			  $tpl->set_var("ele_nombre","ACTIVACIÓN PROYECTOS");
              $tpl->parse("Row",true);
			  	
			  //$tpl->set_var("Detail_Src", "compramateriales.php?id=$jue_id&per_periodo=$dat_periodo&apl=1&dat_periodo=$dat_periodo&");
			  //$tpl->set_var("idMenu",$z++);
              //$tpl->set_var("ele_nombre","COMPRA DE MATERIALES");
              //$tpl->parse("Row",true);
			  
			  $tpl->set_var("Detail_Src", "compramateriales3.php?id=$jue_id&per_periodo=$dat_periodo&apl=1&dat_periodo=$dat_periodo&");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","COMPRAS MATERIA PRIMA");
              $tpl->parse("Row",true);
			  
			  
              $tpl->set_var("Detail_Src", "datos2.php?id=$jue_id&per_periodo=$dat_periodo&apl=2&ele_id=96&");
              $tpl->set_var("idMenu",$z++);
			  $tpl->set_var("ele_nombre","MANUFACTURA");
              $tpl->parse("Row",true);

 while($next_record){
        $id = $db->f("ite_id");
        $nombre = $db->f("ite_nombre");
        $apl = $db->f("ite_apl");


              $tpl->set_var("Detail_Src", "datos2.php?ele_id=$id&id=$jue_id&dat_periodo=$dat_periodo&apl=$apl");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre",$nombre);
              $tpl->parse("Row",true);
         $tpl->set_var("NavigatorNavigator", "" );
         $tpl->set_var("NoRecords", "" );


        $i++;
        $next_record=$db->next_record();
    } // while



              $tpl->set_var("Detail_Src", "reporte2.php?id=$jue_id&dat_periodo=$dat_periodo&apl=1");
			  $tpl->set_var("idMenu",$z++);
              $tpl->set_var("ele_nombre","INDICADORES DE GESTION");
              $tpl->parse("Row",true);


    $activo_resumen= get_db_value("select jue_resumen from tb_juegos where jue_id=$jue_id");
    
        if ($activo_resumen=='A')
            {
              $tpl->set_var("Detail_Src", "reporte.php?ele_id=$id&id=$jue_id&dat_periodo=$dat_periodo&apl=$apl");
              $tpl->set_var("idMenu",$z++);
			  $tpl->set_var("ele_nombre","REPORTE RESÚMEN");   
              $tpl->parse("Row",true);
            }
    
    
}
function datos_show()
{
 global $tpl,$db,$periodo, $db2, $db3;
 $ele_id = get_param("ele_id");
 $user_id = get_session("cliID");
 $jue_id = get_param("id");
 $dat_fechahora = date("Ymdhis");
 $per_id =get_param("dat_periodo");
 $apl = get_param("apl");
 if ($apl=='') {
  $apl=1;
  $tpl->set_var("Datos2","");
 } elseif ($apl==1) $tpl->set_var("Datos2","");
      $tpl->set_var("id", $jue_id);
    $tpl->set_var("ele_id", $ele_id);
    $tpl->set_var("dat_periodo", $per_id);
     if (!is_numeric($ele_id))
     {
         $ele_id=1;
     }
         $elemento = get_db_value("select ite_nombre from tb_items where ite_id=$ele_id");
         $tpl->set_var("elemento",$elemento );
         $imagen = get_db_value("select jue_imagen from tb_juegos where jue_id=$jue_id");
         $tpl->set_var("logo",$imagen);
         $sSQL="select t.ite_id, t.ite_nombre, t.ite_etiqueta from tb_items t, th_grupos t1 where t.ite_id=t1.gru_ite_id and t1.gru_jue_id=$jue_id and  t.ite_id_itemSuperior=$ele_id order by t.ite_orden asc";
         $db->query($sSQL);
         $next_record=$db->next_record();
         $subtotal[] = 0;
         $total[] = 0;

         while($next_record)
         {
                $id = $db->f("ite_id");
                $nombre = $db->f("ite_nombre");
                $accion = $db->f("ite_etiqueta");
                //echo $id."-".$nombre."-".$accion."<br>";
                $k=0;
                      foreach($periodo as $key => $value)
                            {
                              if ($accion!='') {
                              $ele_monto = get_db_value("select dat_monto from tb_datos where dat_ite_id=$id and dat_usu_id=$user_id and dat_periodo=$key");
                                  if (!is_numeric($ele_monto)) $ele_monto=0;
                                        if (is_numeric($per_id))
                                      {
                                              if ($key==$per_id)
                                               {

                                                  $sSQL= "select count(*) from tb_operaciones  where ope_ite_id=$id";
                                                $iCantidad = get_db_value($sSQL);
                                                if ($iCantidad==0) {
                                                    if ($accion=='') $ele_monto = "&nbsp;";
                                                    else $ele_montoDisplay = "<input type=\"text\" name=\"ele_elemento[$id]\" value =\"$ele_monto\" size=\"2\" maxlength=\"5\" style=\"text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4\">";
                                                }
                                                   else
                                                   {
                                                    $sOperaciones = "select ope_operacion, ope_ite_idOperar, ope_valor from tb_operaciones where ope_ite_id=$id";
                                                    $db2->query($sOperaciones);
                                                    $next_record2 = $db2->next_record();
                                                    $ele_monto=0;
                                                        while($next_record2){
                                                            $operacion = $db2->f("ope_operacion");
                                                            $ope_ite_id = $db2->f("ope_ite_idOperar");
                                                            $valor = $db2->f("ope_valor");

                                                                if ($valor!="")
                                                                    {
                                                                        if ($operacion=='*') $ele_monto *= $valor;
                                                                        elseif ($operacion=='/') $ele_monto /= $valor;
                                                                        else $ele_monto += $operacion.$valor_ite_id;
                                                                        $flag=0;
                                                                    }
                                                                else
                                                                    {   $sSQL1="select dat_monto from tb_datos where dat_ite_id=$ope_ite_id and dat_usu_id=$user_id and dat_periodo=$key";
                                                                        $valor_ite_id = get_db_value ($sSQL1);
                                                                        $ele_monto += $operacion.$valor_ite_id;
                                                                        $flag=1;
                                                                    }
                                                            $next_record2 = $db2->next_record();
                                                        }

                                                      $ele_monto =number_format($ele_monto, 0) ;
                                                      $ele_montoDisplay="<font color=\"#FF0000\">".$ele_monto."</font>";
                                                   }
                                             } else
                                             {
                                                     $ele_montoDisplay = "<font color=\"#CCCC00\">".$ele_monto."</font>";
                                             }


                                      }
                                      else $ele_montoDisplay= $ele_monto;
                                $sValida = get_db_value("select count(*) from tb_datos where dat_ite_id=$id and dat_usu_id=$user_id and dat_periodo=$key");
                                if (($sValida==0)&&($accion!=''))
                                    $sInsert = "insert into tb_datos values (null, $user_id, $id, $ele_monto, '$dat_fechahora', $key, 'A')";
                                elseif (($sValida>0)&&($accion!='')) $sInsert = "update tb_datos set dat_monto=$ele_monto, dat_fechahora= '$dat_fechahora' where dat_usu_id=$user_id and dat_ite_id=$id and dat_periodo=$key";
                                $db3->query($sInsert);
                              } else
                              {
                                  $ele_monto="&nbsp;";
                                  $ele_montoDisplay = $ele_monto;
                              }
                              $tpl->set_var("ele_monto", $ele_montoDisplay);
                              $tpl->parse("DatosValor", true);
                              $k++;
                            }

                      $tpl->set_var("ele_nombre1","ID$id -".$nombre);
                      $tpl->set_var("ele_accion1",$accion);


                 $tpl->parse("Row1",true);
                 $tpl->set_var("DatosValor", "");
                 $tpl->set_var("NavigatorNavigator1", "" );
                 $tpl->set_var("NoRecords1", "" );
                $next_record=$db->next_record();
            }
            // while


            foreach($periodo as $key => $value)
            {
                 $tpl->set_var("gestion", $key );
                 $tpl->parse("PeriodoList",true );
            }

}
function items_show()
{
    global $db, $tpl, $periodo,$db2,$db3;
    $jue_id= get_param("id");
    $dat_producto = get_param("dat_producto");
    $dat_mercado = get_param("dat_mercado");
    $user_id = get_session("cliID");
    $per_id =get_param("dat_periodo");
    $ele_id = get_param("ele_id");
    if ($dat_producto=='') $dat_producto=0;
    if ($dat_mercado=='') $dat_mercado=0;
    $sItems = get_param("items");
    $apl = get_param("apl");
     if ($apl==2) {
      $tpl->set_var("Datos","");
     }

    $tpl->set_var("apl",$apl);
    $tpl->set_var("ele_id",$ele_id);
    $tpl->set_var("dat_periodo",$per_id);

    $array_producto = db_fill_array("select pro_id, pro_nombre from tb_productos where pro_jue_id=$jue_id and pro_sw='A' order by 1");
            if(is_array($array_producto))
                {
                  reset($array_producto);
                            $tpl->set_var("ID", "");
                              $tpl->set_var("Value", "Seleccionar valor");
                            $tpl->parse("Producto", true);
                  while(list($key, $value) = each($array_producto))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $dat_producto)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Producto", true);
                  }
                }


    $array_mercado = db_fill_array("select mer_id, mer_nombre from tb_mercados where mer_jue_id=$jue_id and mer_sw='A' order by 1");
            if(is_array($array_mercado))
                {
                  reset($array_mercado);
                            $tpl->set_var("ID", "");
                              $tpl->set_var("Value", "Seleccionar valor");
                            $tpl->parse("Mercado", true);
                  while(list($key, $value) = each($array_mercado))
                  {
                    $tpl->set_var("ID", $key);
                    $tpl->set_var("Value", $value);
                    if($key == $dat_mercado)
                      $tpl->set_var("Selected", "SELECTED" );
                    else
                      $tpl->set_var("Selected", "");
                    $tpl->parse("Mercado", true);
                  }
                }
    $sSQL2 = db_fill_array("select tri_id, tri_nombre from tb_trimestres where tri_sw='A'");
    $sSQL3 = db_fill_array("select atr_id, atr_nombre from tb_atributos where atr_sw='A'");    //atr_jue_id=$jue_id and 
    $sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
    $db->query($sSQL);
    $next_record=$db->next_record();
    while($next_record)
    {
        $cli_id = $db->f("cli_id");
        $cli_nombre = $db->f("cli_nombre");
        $key="";
        $value="";
            foreach($periodo as $key => $value)
            {
                foreach ($sSQL3 as $clv => $value2)
                {
                $sValor=get_db_value("select vai_monto from th_valoresiniciales where vai_periodo=$key and vai_cli_id=$cli_id and vai_mer_id=$dat_mercado and vai_pro_id=$dat_producto and vai_atr_id=$clv and vai_jue_id=$jue_id");
                if ($sValor=='') $sValor=0;
                $tpl->set_var("val_atributo",$sValor);
                $tpl->parse("Atributos", true);
                }

            }
        $tpl->set_var("tipoClientes1", $cli_nombre);
        $tpl->parse("Row3", true );
        $tpl->set_var("Atributos", "");
        $next_record = $db->next_record();
    }

                                                               
    $sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
    $db->query($sSQL);
    $next_record = $db->next_record();
    while($next_record)
    {
        $cli_id = $db->f("cli_id");
        $cli_nombre = $db->f("cli_nombre");
        $key="";
        $value="";
            foreach($periodo as $key => $value)
            {
                foreach($sSQL2 as $clv => $value2)
                {
                    $iValor = get_db_value("select val_cantidad from th_valores where val_usu_id=$user_id and val_pro_id=$dat_producto and val_mer_id=$dat_mercado and val_cli_id=$cli_id and val_tri_id=$clv and val_periodo=$key ");
                    if ($iValor=='')
                    {
                        $iValor=0;
                        if ($key==$per_id) $iValor="<input type=\"text\" style=\"text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4\" name=\"items[$cli_id][$clv]\" value=\"$iValor\" size=\"2\" maxlength=\"3\">";//FFFF66
                        else $iValor="<font color=\"#0099FF\">".$iValor."</font>";    //0099FF                                         
                    } else  if ($key==$per_id) $iValor="<input type=\"text\" style=\"text-align:right;font-size:9;color:#000066;border:thin;background-color:#F4F4F4\" name=\"items[$cli_id][$clv]\" value=\"$iValor\" size=\"2\" maxlength=\"3\">"; //000066
                    
                    $tpl->set_var("valorinput",$iValor);
                    $tpl->parse("Valores", true);
                }
            }
        $tpl->set_var("tipoClientes", $cli_nombre);
        $tpl->parse("Row2", true );
        $tpl->set_var("Valores", "");
        $next_record = $db->next_record();
    }
    $sSQL1 = "select t.ite_id, t.ite_nombre from `tb_items`  t, `th_grupos` t1 where t.ite_apl=2 and t.ite_id_itemSuperior=$ele_id and t.ite_id=t1.gru_ite_id order by ite_orden";
    $db3->query($sSQL1);
    $next_record3 = $db3->next_record();
    $j=0;
    while($next_record3){
    $ite_id = $db3->f("ite_id");
    $ite_nombre = $db3->f("ite_nombre");
    $sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
    $db->query($sSQL);
    $next_record = $db->next_record();
    $l=0;
    while($next_record)
    {
        $cli_id = $db->f("cli_id");
        $cli_nombre = $db->f("cli_nombre");
        $key="";
        $value="";
            foreach($periodo as $key => $value)
            {
             $k=0;
             $iValor=0;

              foreach($sSQL2 as $clv => $value2)
                {
                    $sOperaciones = "select ope_operacion, ope_ite_idOperar, ope_atr_id, ope_trimestre, ope_valor from tb_operaciones where ope_ite_id=$ite_id order by ope_id";
                        $db2->query($sOperaciones);
                        $next_record2 = $db2->next_record();
                        $iValor=0;
                        $etiqueta ="";
                            while($next_record2){
                                $operacion = $db2->f("ope_operacion");
                                $ope_ite_id = $db2->f("ope_ite_idOperar");
                                $valor = $db2->f("ope_valor");
                                $atr_id = $db2->f("ope_atr_id");
                                $trimestre = $db2->f("ope_trimestre");

                                    if ($atr_id!="")
                                        {
                                            $iCalculos = "select vai_monto from th_valoresiniciales where vai_periodo=$key and vai_cli_id=$cli_id and vai_mer_id=$dat_mercado and vai_pro_id=$dat_producto and vai_jue_id=$jue_id and vai_sw='A' order by vai_id";
                                            $iMonto = get_db_value($iCalculos);
                                            if ($iMonto=='') $iMonto=0;
                                            if ($operacion=='*') $iValor = $iValor * $iMonto;
                                            elseif ($operacion=='/') $iValor /= $iMonto;
                                            elseif ($operacion=='+') $iValor += $iMonto;
                                            elseif ($operacion=='-') $iValor -= $iMonto;
//                                            echo $operacion.$iMonto."=".$iValor."ATR<br>";
                                            $flag=0;
                                        }
                                    else
                                        {
                                            if ($clv!=1) {                                                          
                                                $clvtemp=$clv-1;
                                            if ($trimestre==0) $iCalculos = "select val_cantidad from th_valores where val_tri_id=$clv and val_periodo=$key and val_cli_id=$cli_id and val_mer_id=$dat_mercado and val_pro_id=$dat_producto and val_usu_id=$user_id and val_sw='A' order by val_id";
                                            else $iCalculos = "select val_cantidad from th_valores where val_tri_id=".$clvtemp." and val_periodo=$key and val_cli_id=$cli_id and val_mer_id=$dat_mercado and val_pro_id=$dat_producto and val_usu_id=$user_id and val_sw='A' order by val_id";
                                            $iMonto = get_db_value($iCalculos);
                                            if ($iMonto=='') $iMonto=0;
                                            if ($operacion=='*') $iValor *= $iMonto;
                                            elseif ($operacion=='/') $iValor /= $iMonto;
                                            elseif ($operacion=='+') $iValor += $iMonto;
                                            elseif ($operacion=='-') $iValor -= $iMonto;
                                            $flag=1;   
//                                            echo $operacion.$iMonto."=".$iValor."!!<br>";
                                            } else $iValor=0;
                                        }
                                $next_record2 = $db2->next_record();
                            }
                    if ($clv!=1) {
                    $tpl->set_var("val_atributo6",number_format($iValor,2,",","."));
                    } else $tpl->set_var("val_atributo6", ""); //valor inicial calculado ocultamos la 1era Fila
//                    $tpl->set_var("val_valida",$etiqueta);
                    $tpl->parse("Atributos6", true);

                    if ($k==0&&$l==0) {
                    $tpl->set_var ("trimestrevalor2",$value2);
                    $tpl->parse("llenado",true );
                    }

                  }
                $k++;
            }
        $tpl->set_var("tipoClientes6", $cli_nombre);
        $tpl->parse("Row6", true );
        $tpl->set_var("Atributos6", "");
        $next_record = $db->next_record();
        $l++;
      }
        $tpl->set_var("tituloitems",$ite_nombre );
        $tpl->parse("ValoresItems", true );
        $tpl->set_var("Row6", "" );
        $tpl->set_var("llenado","" );

      $next_record3 = $db3->next_record();
      $j++;
    }


/******************************************************************/


            foreach($periodo as $key => $value)
            {

                $tpl->set_var("gestionValores", $key );
                $tpl->parse("PeriodoVarios", true );
                foreach($sSQL2 as $key => $value)
                    {
                        $tpl->set_var("trimestrevalor", $value );
                        $tpl->set_var("trimestrevalor2", $value );
                        $tpl->parse("llenado", true );
                        $tpl->parse("Fila", true );
                    }
                foreach($sSQL3 as $key => $value)
                    {
                        $tpl->set_var("parametro", $value );
                        $tpl->parse("Parametros", true );
                    }


            }

}
?>

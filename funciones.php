<?php
function items_producto()
{
    global $db, $tpl, $periodo,$db2,$db3, $db4;
    $jue_id= get_param("id");
    if ($jue_id=='') get_session("id");
    $dat_producto = get_param("dat_producto");
    $dat_mercado = get_param("dat_mercado");
    $user_id = get_session("cliID");
    $per_id =get_param("dat_periodo");
    if ($per_id =='') $per_id=get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
    $ele_id = get_param("ele_id");
    if ($dat_producto=='') $dat_producto=0;
    if ($dat_mercado=='') $dat_mercado=0;
    $sItems = get_param("items");
    $apl = get_param("apl");
    if ($apl=='') get_session("SSapl");
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
                            $tpl->set_var("ID", "66");
                            $tpl->set_var("Value", "TODOS");
                            if($dat_producto==66)
                               $tpl->set_var("Selected", "SELECTED" );
                              else
                               $tpl->set_var("Selected", "");
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
                                $tpl->set_var("ID", "66");
                                $tpl->set_var("Value", "TODOS");
                                if($dat_mercado==66)
                                  $tpl->set_var("Selected", "SELECTED" );
                                else
                                  $tpl->set_var("Selected", "");
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


            $sSelPeriodo=$per_id;
            for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
            {

                foreach ($sSQL3 as $clv => $value2)
                {
                
                $atr_tipoValor = get_db_value("select atr_tipoValor from tb_atributos where atr_id=$clv");
                $sValor=get_db_value("select sum(vai_monto) from th_valoresiniciales where vai_periodo=$key and vai_cli_id=$cli_id  and vai_pro_id=$dat_producto and vai_atr_id=$clv and vai_jue_id=$jue_id");
                if ($sValor=='') $sValor=0;
                if ($atr_tipoValor=='P') 
                { 
                    $sValor = $sValor * 100;
                    $tpl->set_var("val_atributo",$sValor."%");
                }
                    else
                {                                                                    
                    $tpl->set_var("val_atributo", number_format(round($sValor,0),0,".",","));
                }
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
//            foreach($periodo as $key => $value)
//            {   
            $sSelPeriodo=$per_id;
            for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
            {

                
                $periodo_inicio = get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
                if ($key==$periodo_inicio)
                {
                    $valini = get_db_value("select sum(ini_monto) from th_inicio where ini_jue_id=$jue_id and ini_tic_id=$cli_id  and ini_pro_id=$dat_producto");
                    if ($valini=="") $valini=0;
                }
                else
                {
                  $keytemp = $key-1;
                  $clvtemp = 5;
                  $iCalculos = "select sum(val_cantidad) from th_valores where val_tri_id=".$clvtemp." and val_periodo=$keytemp and val_cli_id=$cli_id and val_pro_id=$dat_producto and val_usu_id=$user_id and val_sw='A' order by val_id"; 
                 $valini=get_db_value($iCalculos);
                 if ($valini=="") $valini=0;
                }
//                $tpl->set_var("valorinput",number_format(round($valini,0),0,',','.'));  ////valor inicial
//                $tpl->parse("Valores", true);
                $tpl->set_var("valorinput","&nbsp;");  ////valor inicial
                $tpl->parse("Valores", true);

                foreach($sSQL2 as $clv => $value2)
                {
                    $iValor = get_db_value("select sum(val_cantidad) from th_valores where val_usu_id=$user_id and val_pro_id=$dat_producto and val_cli_id=$cli_id and val_tri_id=$clv and val_periodo=$key ");
                    $vTotalNuevo[$clv] += $iValor;  
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
        $tpl->set_var("BLOG2", "Row");    
        $tpl->set_var("tipoClientes", $cli_nombre);
        $tpl->parse("Row2", true );
        $tpl->set_var("Valores", "");
        $next_record = $db->next_record();
    }
        $tpl->set_var("tipoClientes", "TOTAL");
        $tpl->set_var("BLOG2", "Caption");
                    $tpl->set_var("valorinput","&nbsp;");
                    $tpl->parse("Valores", true);

                foreach($sSQL2 as $clv => $value2)
                {
                    $tpl->set_var("valorinput",round($vTotalNuevo[$clv],0)); //input
                    $tpl->parse("Valores", true);
                }
        $tpl->parse("Row2", true );
    

/*********************************esto se calcula*********************************/

//print_r($periodo);
    $sSQL1 = "select t.ite_id, t.ite_nombre, t.ite_decimal, t.ite_millones from `tb_items`  t, `th_grupos` t1 where t.ite_apl=2 and t.ite_id_itemSuperior=$ele_id and t.ite_id_itemSuperior=t1.gru_ite_id and t1.gru_jue_id=$jue_id order by ite_orden";
    $db3->query($sSQL1);
    $next_record3 = $db3->next_record();
    $j=0;

    while($next_record3){
    $ite_id = $db3->f("ite_id");
    $ite_nombre = $db3->f("ite_nombre");
    $ite_decimal = $db3->f("ite_decimal");
    $ite_millones = $db3->f("ite_millones");
    //echo $ite_nombre;
    $sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
    $db->query($sSQL);
    $next_record = $db->next_record();
    $l=0;
    $vValini=0;
    while($next_record)
    {

        $cli_id = $db->f("cli_id");
        $cli_nombre = $db->f("cli_nombre");
        $key="";
        $value="";

            //foreach($periodo as $key => $value)
            $sSelPeriodo=$per_id;
//            $vTotalAtr[]=0;
            for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
            {
             $k=0;
             $iValor=0;
                  if ($l==0){
                    $tpl->set_var ("trimestrevalor2","");
                    $tpl->parse("llenado",true );
                   }
//////////////////////////////////// VALORES INICIALES/////////////////////////////////77
                $periodo_inicio = get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
                if ($key==$periodo_inicio)
                {
                    $valini = get_db_value("select sum(ini_monto) from th_inicio where ini_jue_id=$jue_id and ini_pro_id=$dat_producto and ini_tic_id=$cli_id");
                    //echo $valini;
                    $vValini+= $valini;
                
                }
                else 
                {
                    $keytemp= $key-1;
                    $clvtemp = 5;
                    $valorPerant = get_db_value("select sum(cal_monto) from tb_calculos where cal_usu_id=$user_id and cal_pro_id=$dat_producto and cal_cli_id=$cli_id and cal_periodo=$keytemp and cal_trimestre=$clvtemp and cal_ite_id=$ite_id ");
                    $valini=$valorPerant;
                    $vValini+= round($valini,0);
                     //echo $valini."select sum(cal_monto) from tb_calculos where cal_usu_id=$user_id and cal_pro_id=$dat_producto and cal_cli_id=$cli_id and cal_periodo=$keytemp and cal_trimestre=$clvtemp and cal_ite_id=$ite_id<br>";   
                    
                }
                if ($j==0)
               {    
                   if ($ite_millones==1)
                   $valini = $valini/1000;
//                    $vTotalAtr[$key] +=round($valini,$ite_decimal);
                    $tpl->set_var("val_atributo6", round($valini,$ite_decimal)); 
                    $tpl->parse("Atributos6", true);
                } 
                else{    
                    $tpl->set_var("val_atributo6", "&nbsp;"); 
                    $tpl->parse("Atributos6", true);
                }

                
              foreach($sSQL2 as $clv => $value2)
                {
                        if ($l==0) $vTotalAtr[$clv]=0;
                        $iValor = get_db_value("select sum(cal_monto) from tb_calculos where cal_trimestre=$clv and cal_usu_id=$user_id and cal_periodo=$key and cal_pro_id =$dat_producto and cal_cli_id=$cli_id and cal_ite_id=$ite_id ");
                    if ($clv) {
                        //echo $ite_millones;
                        
                        if ($ite_millones==0)
                            { 
                                $vTotalAtr[$clv] += round($iValor,$ite_decimal);
                                $tpl->set_var("val_atributo6", number_format(round($iValor,$ite_decimal),$ite_decimal,",","."));
                            }
                        else
                            {
                                $vTotalAtr[$clv] += round($iValor/1000,$ite_decimal);
                                $tpl->set_var("val_atributo6", number_format(round($iValor/1000,$ite_decimal),$ite_decimal,",","."));
                            }
                    $valorPerant = $iValor;
                    if ($ope_ite)
                    {   
//                        $sValida = "select count(*) from tb_calculos where cal_usu_id=$user_id and cal_trimestre=$clv and cal_periodo=$key and cal_pro_id=$dat_producto and cal_mer_id =$dat_mercado and cal_cli_id=$cli_id and cal_ite_id=$ope_ite ";
//                        $sCantidad = get_db_value($sValida);
//                        if ($sCantidad==0)
//                        $sSQLInsert = "insert into tb_calculos values (null,$user_id,$key, $clv, $dat_producto, $dat_mercado, $cli_id, $ope_ite, $iValor)";    
//                        else $sSQLInsert ="update tb_calculos set cal_monto=$iValor where cal_trimestre=$clv and cal_usu_id=$user_id and cal_periodo=$key and cal_pro_id=$dat_producto and cal_mer_id =$dat_mercado and cal_cli_id=$cli_id and cal_ite_id=$ope_ite";
//                        $db4->query($sSQLInsert);
                        //echo $sSQLInsert.";<br>";
                    }

                    } else 
                    {
                        $tpl->set_var("val_atributo6", "");
                    } //valor inicial calculado ocultamos la 1era Fila
//                    $tpl->set_var("val_valida",$etiqueta);
                    $tpl->parse("Atributos6", true);

                    if ($k==0&&$l==0) {
                    $tpl->set_var ("trimestrevalor2",$value2);
                    $tpl->parse("llenado", true );
                    }

                }
                $k++;

            }

        $tpl->set_var("BLOG", "Row"); 
        $tpl->set_var("tipoClientes6", $cli_nombre);
        $tpl->parse("Row6", true );
        $tpl->set_var("Atributos6", "");
        $next_record = $db->next_record();
        $l++;
      }

        $tpl->set_var("tipoClientes6", "TOTAL");
        $tpl->set_var("BLOG", "Caption");
               if ($j==0)
               {    
                 $tpl->set_var("val_atributo6",$vValini); ////round($vTotalAtr[$per_id],0));  ////1
                 $tpl->parse("Atributos6", true);
               }
               else 
               {
                 $tpl->set_var("val_atributo6", "&nbsp;" );
                 $tpl->parse("Atributos6", true);

               }
        foreach($sSQL2 as $clv => $value2)
         {
                 $tpl->set_var("val_atributo6", round($vTotalAtr[$clv],0)); ////2
                 $tpl->parse("Atributos6", true);
         }
        $tpl->parse("Row6", true ); 
        $tpl->set_var("Atributos6", "");
      
        $tpl->set_var("tituloitems",$ite_nombre );
        $tpl->parse("ValoresItems", true );
        $tpl->set_var("Row6", "" );
        $tpl->set_var("llenado","" );

      $next_record3 = $db3->next_record();
      $j++;
    }


/******************************************************************/


//            foreach($periodo as $key => $value)
//            {
            $sSelPeriodo=$per_id;
            for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
            {

                        $tpl->set_var("trimestrevalor", "" );
                        $tpl->parse("Fila", true );
                $tpl->set_var("gestionValores", $key );
                $tpl->parse("PeriodoVarios", true );
                foreach($sSQL2 as $key => $value)
                    {
                        $tpl->set_var("trimestrevalor", $value );
//                        $tpl->set_var("trimestrevalor2", "SSS" );
//                        $tpl->parse("llenado", true );
                        $tpl->parse("Fila", true );
                    }
                foreach($sSQL3 as $key => $value)
                    {
                        $tpl->set_var("parametro", $value );
                        $tpl->parse("Parametros", true );
                    }
            }
            $tpl->set_var("Row4", "" );  
            $tpl->set_var("Row3", "" );  

}
?>
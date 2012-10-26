<?php
function items_todos()
{

//echo "TODOS";
	global $db, $tpl, $periodo,$db2,$db3, $db4;
    $jue_id= get_param("id");
    if ($jue_id=='') get_session("id");
    $dat_producto = get_param("dat_producto");
    $dat_mercado = get_param("dat_mercado");
    $user_id = get_session("cliID");
    $per_id = get_param("dat_periodo");
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

    $tpl->set_var("Row4","");
	$tpl->set_var("Parametros","");
	$tpl->set_var("Canales","");
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
                            $tpl->set_var("Value", "OUTPUT");
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
                                $tpl->set_var("Value", "OUTPUT");
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
                    $tpl->parse("Mercado2", true);
                  }
                }
            
                
    $sSQL2 = db_fill_array("select tri_id, tri_nombre from tb_trimestres where tri_sw='A'");
    $sSQL3 = db_fill_array("select atr_id, atr_nombre from tb_atributos where atr_sw='A' order by 1");   
    $array_cliente = db_fill_array("select cli_id, cli_nombre from tb_tipoclientes where cli_sw='A' and cli_jue_id=$jue_id order by 1");
	$array_canal = array (1=>'CANAL PROMOTORAS',2=>'CANAL MAYORISTAS', 3=>'CANAL BOUTIQUES');
                                                          
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
                        $tpl->parse("Fila", true );
                    }
            }
			
	$outputVar = array(1=>'Ingresos a Caja y Bancos', 2=>'Ingresos a Cuentas x cobrar a 30 dias', 3 =>'Ingresos a Cuentas x cobrar a 60 dias',  5=>'Costo Canal Promotoras', 6=>'Costo Canal Mayoristas', 7=>'Costo Canal Boutique');	
		 $TS = 0;
		 $TS1 = 0;
		 $TS2 = 0;
		 		$totalC=0;
				$totalCC30=0;
				$totalCC60=0;
	$m=0;
	$n=0;
	foreach($outputVar as $OutId => $OutDesc)
	{
		//$tpl->set_var("BLOG2", "Row");
		//$tpl->set_var("resultado",$OutDesc);
		$arVal[$OutId][1]= $OutDesc;
		$ingresototal=0;
		 $sSelPeriodo=$per_id;
		 for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
		 {
			$totalPeriodo=0;
		
			foreach($sSQL2 as $triId => $triDesc)
			{
				//echo $triDesc;
				$ingresocaja = 0;
				$costo=0;
				$totales=0;
				
				$total_costopromotoras=0;
				$total_costomayoristas=0;
				$total_costoboutique=0;
				foreach($array_mercado as $merId => $merDesc)
				{
					foreach($array_producto as $proId => $proDesc)
					{
					  foreach($array_canal as $canId=> $canDesc)
					  {
						$calc=0;
						foreach($array_cliente as $cliId=> $cliDesc)
						{
							//echo $triDesc."-tri-".$merDesc."-mer-".$proDesc."-pro-".$canDesc."-can-".$cliId."-";
							switch($OutId)
							{
								case 1:
									if($cliId==34)
									{
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									/*if($cliId==34) { $atrId=1;$atrId2=19;}
									if($cliId==35) { $atrId=17;$atrId2=20;}
									if($cliId==36) {$atrId=18;$atrId2=21;}*/
									//echo $canId;
									$atrId=1;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc = $pedido * $precio * (1-$margen);
									///echo '('.$pedido.'*'.$precio.'*(1-'.$margen.'))='.$calc.'<br>';
									$ingresocaja += $calc;
									}
									break;
								case 2:
									if($cliId==35)
									{
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									/*if($cliId==34) { $atrId=1;$atrId2=19;}
									if($cliId==35) { $atrId=17;$atrId2=20;}
									if($cliId==36) {$atrId=18;$atrId2=21;}*/
									$atrId=17;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc = $pedido * $precio * (1-$margen);
									//echo '('.$pedido.'*'.$precio.'*(1-'.$margen.'))='.$calc.'<br>';
									$ingresocaja += $calc;
									}
									break;	
								case 3:
									if($cliId==36)
									{
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									/*if($cliId==34) { $atrId=1;$atrId2=19;}
									if($cliId==35) { $atrId=17;$atrId2=20;}
									if($cliId==36) {$atrId=18;$atrId2=21;}*/
									$atrId=18;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc = $pedido * $precio * (1-$margen);
									//echo '('.$pedido.'*'.$precio.'*(1-'.$margen.'))='.$calc.'<br>';
									$ingresocaja += $calc;
									}
									break;	
									case 4:
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									if($cliId==34) 
									{ $atrId=1;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==35) { $atrId=17;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==36) {$atrId=18;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc = $pedido * $precio * (1-$margen);
									//echo '('.$pedido.'*'.$precio.'*(1-'.$margen.'))='.$calc.'<br>';
									$ingresocaja += $calc;
									break;		
									case 5:
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									if($cliId==34) 
									{ $atrId=1;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==35) { $atrId=17;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==36) {$atrId=18;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc += $pedido * $precio ;
									//echo '('.$pedido.'*'.$precio.')='.$calc.'<br>';
									//$ingresocaja += $calc;
									break;
									case 6:
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									if($cliId==34) 
									{ $atrId=1;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==35) { $atrId=17;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==36) {$atrId=18;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc += $pedido * $precio ;
									//echo '('.$pedido.'*'.$precio.')='.$calc.'<br>';
									//$ingresocaja += $calc;
									break;
									case 7:
									$pedido = get_db_value("select val_cantidad from th_valores where val_tri_id=$triId and val_periodo=$key and val_cli_id=$canId$cliId and val_mer_id=$merId and val_pro_id=$proId and val_usu_id=$user_id and val_sw='A' order by val_id ");			
									if($cliId==34) 
									{ $atrId=1;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==35) { $atrId=17;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									if($cliId==36) {$atrId=18;
									if ($canId==1) $atrId2=19;
									if ($canId==2) $atrId2=20;
									if ($canId==3) $atrId2=21;
									}
									
									$precio= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
		
									$margen= get_db_value("select vai_monto from th_valoresiniciales where vai_atr_id=$atrId2 and vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$merId and vai_pro_id=$proId and vai_jue_id=$jue_id and vai_sw='A' order by vai_id ");					
									$calc += $pedido * $precio ;
									//echo '('.$pedido.'*'.$precio.')='.$calc.'<br>';
									//$ingresocaja += $calc;
									break;

							}//END SWITCH OUTID
						}//END FOREACH CLIID
						if($OutId==5)
						{
							$costo = $margen * $calc;
							if($canId==1)
							{
								$total_costopromotoras += $costo;
								$ingresocaja=$total_costopromotoras;
							}
							//echo '{('.$calc.'*'.$margen.')}='.$costo.'<br>';
						}
						if($OutId==6)
						{
							$costo = $margen * $calc;
							if($canId==2)
							{
								$total_costomayoristas+= $costo;
								$ingresocaja=$total_costomayoristas;
							}
						}
						if($OutId==7)
						{
							$costo = $margen * $calc;
							if($canId==3)
							{
								$total_costoboutique+= $costo;
								$ingresocaja=$total_costoboutique;
							}
						}
					  }//END FOREACH CANAL
					}//END FOREACH PRODUCTO
				}//END FOREACH MERCADO
			
			/*$ingresototal +=	$ingresocaja;
			if ($OutId!=4)*/
			if($triId==2)
			{
			 $TS =  $TS +$ingresocaja;
			 //echo $TS .'<br>';
			 }
			if($triId==3)
			{
			 $TS1 = $TS1 + $ingresocaja;
			 //echo $TS .'<br>';
			 }
			if($triId==4)
			{
			 $TS2 += $ingresocaja;
			 //echo $TS .'<br>';
			 }

			if($triId==2&&$OutId==1)
			{
				$totalC += $ingresocaja;
			}
			if($triId==3&&$OutId==1)
			{
				$totalC += $ingresocaja;
			}
			if($triId==4&&$OutId==1)
			{
				$totalC += $ingresocaja;
			}
			if($triId==2&&$OutId==2)
			{
				$totalC += $ingresocaja;
			}
			if($triId==3&&$OutId==2)
			{
				$totalC += $ingresocaja;
			}
			if($triId==4&&$OutId==2)
			{
				$totalCC30 += $ingresocaja;
			}
			if($triId==2&&$OutId==3)
			{
				$totalC += $ingresocaja;
			}
			if($triId==3&&$OutId==3)
			{
				$totalCC30 += $ingresocaja;
			}
			if($triId==4&&$OutId==3)
			{
				$totalCC60 += $ingresocaja;
			}
			
			
			
			
			
			$totales += $ingresocaja;
			$totalPeriodo +=$totales; 
			
			$arVal[$OutId][$triId]= number_format(round($ingresocaja,0),0,".",",");
			$arVal[$OutId][$triId+1]= number_format(round($totalPeriodo,0),0,".",",");
			//$tpl->set_var("valorcalculado", $ingresocaja);
			//$tpl->parse("ValoresCalculados", true);
			///$tpl->set_var("totales", $totales);
			///$tpl->parse("Valores2", true);
			}//END FOREACH TRIMESTRE
			//echo $totalPeriodo;
			
			//$tpl->set_var("totalH", $totalPeriodo);
			//$/tpl->set_var("TotalesH", "");
			//$tpl->parse("TotalesH", true);
			
			
//			$tpl->set_var("Valores2", "");
			
			//$tpl->parse("ResultadoOut",true);
			//$tpl->set_var("ValoresCalculados", "");
		}//END FOR PERIODO
	}//END FOREACH ELEMENTOS OUTPUT
	
	$tpl->set_var('ts',$TS);
	$tpl->set_var('ts1',$TS1);
	$tpl->set_var('ts2',$TS2);
	$tpl->set_var('ts3',$TS+$TS1+$TS2);
	$datoFinal = $TS+$TS1+$TS2;
	$cantidadVal = get_db_value("select count(*) from tb_datos where dat_ite_id=22 and dat_usu_id=$user_id and dat_periodo=$per_id");
	if($cantidadVal==0)
	$db->query("insert into tb_datos values(null, $user_id, 22, $datoFinal, ".tosql(date("Ymdhis"),"Text").", $key, 'A')");
	else $db->query("update tb_datos set dat_monto=$datoFinal, dat_fechaHora=".tosql(date("Ymdhis"),"Text")." where dat_ite_id=22 and dat_usu_id=$user_id and dat_periodo=$per_id");

	
	
	$arVal[1][5] = number_format(round($totalC,0),0,".",",");
	$arVal[2][5] = number_format(round($totalCC30,0),0,".",",");;
	$arVal[3][5] = number_format(round($totalCC60,0),0,".",",");;
	foreach($outputVar as $OutId => $OutDesc)
	{
		$tpl->set_var("BLOG2", "Row");
		$tpl->set_var("resultado",$arVal[$OutId][1]);
		for($j=2;$j<=4;$j++)
		{
			$tpl->set_var("valorcalculado", $arVal[$OutId][$j]);
			$tpl->parse("ValoresCalculados", true);
			
		}
		    $tpl->set_var("totalH", $arVal[$OutId][$j]);
			$tpl->set_var("TotalesH", "");
			$tpl->parse("TotalesH", true);
			$tpl->parse("ResultadoOut",true);
			$tpl->set_var("ValoresCalculados", "");
			
	}
}

?>
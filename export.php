<?php
include_once "./Spreadsheet/Excel/Writer.php";
include ("./common.php");
//include ("./funciones.php");
//include ("./funciones_producto.php");
include ("./funciones_todos.php");

global $periodo,$rel_id;
global $db, $db1;

session_start();
check_security(1);

  $xls =new Spreadsheet_Excel_Writer();
  $xls->send("SIGES.xls");
  
  $format_titular =& $xls->addFormat();
  $format_titular->setBold();
  $format_titular->setColor("black");
  $format_titular->setSize(12);
  
  $format_titular2 =& $xls->addFormat();
  $format_titular2->setBold();
  $format_titular2->setColor("black");
  $format_titular2->setSize(11);
  //$format_titular2->setBgColor("blue");
  
$sheet =& $xls->addWorksheet('Reporte');
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$user_id = get_session("cliID");
//echo $user_id." este si";
$jue_id = get_param("id");


	$sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
	"t.jue_id as id from tb_juegos t where t.jue_id=$jue_id ".
	"  and t.jue_sw='A' " ;
    //echo $sSQL;die;
	$db->query($sSQL);
	$next_record = $db->next_record();
	$per_inicio = $db->f("inicio");
	$per_cantidad = $db->f("cantidad");
	$jue_id = $db->f("id");
	$per_in = $per_inicio;
//	echo $per_inicio."-".$per_cantidad."<br>";
        /*************************Habilita Periodos*********************************

	for ($i=$per_inicio;$i<=$per_cantidad;$i++)
	{
         if (is_numeric($per_inicio)) {
			$sSQL = "select count(*) from tb_balances where bal_usu_id=$user_id and bal_periodo=$per_inicio and bal_flag=1 and bal_sw='A'";
			$vCount = get_db_value($sSQL);
//			echo $sSQL;
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
		     	//echo $sSQL;
		     	} else
		     	{
		     		$periodo_anterior = $per_inicio-1;
		     		$sTotalActivo = get_db_value("select t.dat_monto from tb_datos t where t.dat_ite_id=46 and t.dat_usu_id=$user_id and t.dat_periodo=$periodo_anterior and t.dat_sw='A'");
		     		$sTotalPasivo = get_db_value("select t.dat_monto from tb_datos t where t.dat_ite_id=56 and t.dat_usu_id=$user_id and t.dat_periodo=$periodo_anterior and t.dat_sw='A'");
		     		if ($sTotalActivo=='') $sTotalActivo=0;
		     		if ($sTotalPasivo=='') $sTotalPasivo=0;
//		     		echo $sTotalActivo . "--".$sTotalPasivo;
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
        *************************Habilita Periodos*********************************/

    $periodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$jue_id and per_estado='A'");

if (strlen($sAction)) {
//echo $sAction;
	elementos_action($sAction);
}
$filename = "export.php";
$template_filename = "export.html";

$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//$tpl->load_file($header_filename, "header");
$tpl->set_var("FileName", $filename);
//$tpl->set_var("CCS_Style","Coco");
//header_show();
//elementos_show();
elementos_record();
datos_show();
if (get_param("apl")==2) {
items_show();
}

//$tpl->parse("header", false);
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
    $jue_id = get_param("id");
    $pro_id = get_param("dat_producto");
    $mer_id = get_param("dat_mercado");

	$sTransitParams = "?ele_id=$ele_id&dat_periodo=$dat_periodo&dat_producto=$pro_id&dat_mercado=$mer_id&";
	$sError = "";

	if (!strlen($dat_periodo)) $sError .= "El valor en el campo Periodo es requerido<br>";
	//if (!strlen($dat_ele_id)) $sError .= "El valor en el campo Elemento es requerido<br>";
	if (!is_numeric($dat_periodo)) $sError .= "El valor en el campo Periodo es incorrecto<br>";
	if (strlen($sError)) {echo $sError; return;}
	switch($sAction)
		{
			case "modificar":
			foreach($dat_ele_id as $key => $value)
			{
                //$value = number_format($value,0,".","");
				$valida = get_db_value("select count(*) from tb_datos where dat_ite_id=$key and dat_usu_id=$dat_user_id and dat_periodo=$dat_periodo");
				//echo $valida."<br>";
				if ($valida==0)
				$sSQL = "insert into tb_datos ( dat_ite_id, dat_usu_id, dat_monto, dat_fechahora, dat_periodo )".
				" values ($key, $dat_user_id, ".tosql($value,"Number").", '$dat_fechahora', $dat_periodo)";
				else $sSQL = "update tb_datos set dat_monto=".tosql($value,"Number").", dat_fechahora='$dat_fechahora' ".
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
    if ($ele_id=='') $ele_id=get_session("SSele_id");
    if ($ele_id=='') $ele_id=1;
	$dat_id =get_param("dat_id");
	$LBperiodo = get_param("dat_periodo");
    if ($LBperiodo=='') 
    {
        if (get_session("LBperiodo")!='')  $LBperiodo = get_session("LBperiodo");
        else $LBperiodo=-1;
    }
    else set_session("LBperiodo",$LBperiodo);
	$sTransitParams = "?ele_id=$ele_id&";
	$user_id = get_session("cliID");
	$jue_id = get_param("id");
	$pro_id = get_param("dat_producto");
	$mer_id = get_param("dat_mercado");
    $apl = get_param("apl");
	$tpl->set_var("dat_producto", $pro_id );
    $tpl->set_var("ele_id", $ele_id );
    $tpl->set_var("dat_mercado", $mer_id );
	$tpl->set_var("apl", $apl );
    //echo $LBperiodo;
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
 $dat_periodo = get_param("dat_periodo");
 $sSQL="select t.ite_id, t.ite_nombre, t.ite_apl from tb_items t, `th_grupos` t1 where t.ite_id_itemSuperior is null and t.ite_id = t1.gru_ite_id and t1.gru_jue_id=$jue_id order by ite_id asc";
 $db->query($sSQL);
 $next_record=$db->next_record();
 $i=0;
 while($next_record){
        $id = $db->f("ite_id");
        $nombre = $db->f("ite_nombre");
        $apl = $db->f("ite_apl");
              $tpl->set_var("Detail_Src", "datos2.php?ele_id=$id&id=$jue_id&dat_periodo=$dat_periodo&apl=$apl");
              $tpl->set_var("ele_nombre",$nombre."-");
			  $tpl->parse("Row",true);
         $tpl->set_var("NavigatorNavigator", "" );
         $tpl->set_var("NoRecords", "" );
		$i++;
        $next_record=$db->next_record();
    } // while
    $tpl->set_var("Detail_Src", "datos3.php?ele_id=$id&id=$jue_id&dat_periodo=$dat_periodo&apl=$apl");
    $tpl->set_var("ele_nombre","Reporte Resumen");   
    $tpl->parse("Row",true);
}

function actualiza($ele_id, $jue_id, $user_id,$per_id,$apl)
{
global $tpl,$db,$periodo, $db2, $db3;
 if ($ele_id=='') $ele_id=get_session("SSele_id");
 if ($ele_id=='') $ele_id=1;
 set_session("SSele_id",$ele_id);
 $user_id = get_session("cliID");
 $dat_fechahora = date("Ymdhis");
 if ($per_id=='') $per_id =get_session("LBperiodo");
 $periodoInicial=get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
 if ($per_id=='') $per_id=$periodoInicial;
 set_session("LBperiodo",$per_id);
 if ($apl =='') get_session("SSapl");  
 if ($apl=='') {
  $apl=1;
  $tpl->set_var("Datos2","");
 } elseif ($apl==1) $tpl->set_var("Datos2","");
 set_session("SSapl",$apl);
 
$sSQL="select t.ite_id, t.ite_nombre, t.ite_etiqueta from tb_items t, th_grupos t1 where t1.gru_jue_id=$jue_id and  t.ite_id_itemSuperior=$ele_id and t.ite_id_itemSuperior=t1.gru_ite_id order by t.ite_orden asc";
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
                                if ($ele_monto>0) $ele_monto2=$ele_monto;
  							  		if (is_numeric($per_id))
									  {
									  		if ($key==$per_id)
									  		 {                                                       

									  			$sSQL= "select count(*) from tb_operaciones  where ope_ite_id=$id";
												$iCantidad = get_db_value($sSQL);
												$sSQL= "select count(*) from tb_operaciones  where ope_ite_id=$id and ope_trimestre=1";
												$iCantidad1 = get_db_value($sSQL);
												//echo $key.'='.$iCantidad1.'<br>';
												if (($iCantidad==0)||(($iCantidad1!=0)&&($key==$periodoInicial))) {
													if ($accion=='') $ele_monto = "&nbsp;";
													else $ele_montoDisplay = $ele_monto;
												}
									  		     else
									  		     {
													$sOperaciones = "select ope_operacion, ope_ite_idOperar, ope_valor, ope_trimestre from tb_operaciones where ope_ite_id=$id";
													$db2->query($sOperaciones);
													$next_record2 = $db2->next_record();
													$ele_monto=0;
														while($next_record2){
															$operacion = $db2->f("ope_operacion");
															$ope_ite_id = $db2->f("ope_ite_idOperar");
															$valor = $db2->f("ope_valor");
                                                            $trimestre = $db2->f("ope_trimestre");
                                                            // echo $id .'operacion'.$ele_monto.' ='.$operacion.' '.$ope_ite_id.' valor='.$valor.'trimestre='.$trimestre.'monto';
																if ($valor!="")
																	{
																		if ($operacion=='*') $ele_monto *= $valor;
																		else if ($operacion=='/') $ele_monto /= $valor;
																		else if ($operacion=='-') $ele_monto -= $valor;
                                                                        else if ($operacion=='+') $ele_monto += $valor_ite_id;
																		else if ($operacion=='|') 
																		{
																		 if ($ele_monto<0) $ele_monto=0;
																		}
																		$flag=0;
																	}
																else
																	{   
                                                                        if ($trimestre==0)
                                                                        {$sSQL1="select dat_monto from tb_datos where dat_ite_id=$ope_ite_id and dat_usu_id=$user_id and dat_periodo=$key";    }
                                                                        else { 
                                                                            $tempKey= $key-1;
                                                                            $sSQL1="select dat_monto from tb_datos where dat_ite_id=$ope_ite_id and dat_usu_id=$user_id and dat_periodo=$tempKey";         
                                                                        }
																		$valor_ite_id = get_db_value ($sSQL1);
                                                                       //echo $valor_ite_id.$sSQL1.'<br>';###
                                                                        if (!$valor_ite_id) $valor_ite_id=0;
																		if ((($ope_ite_id==29)||($ope_ite_id==62))&&($valor_ite_id<0)) $valor_ite_id=0;
                                                                        //echo $operacion.' ##';
																		if ($operacion=='+')
                                                                        $ele_monto += $valor_ite_id;
                                                                        else if ($operacion=='-') $ele_monto -= $valor_ite_id;
                                                                        else if ($operacion=='*') $ele_monto *= $valor_ite_id;
                                                                        else if ($operacion=='/') $ele_monto /= $valor_ite_id;
																		else if ($operacion=='|') 
																		{
																		 if ($ele_monto<0) $ele_monto=0;
																		}
																		$flag=1;
                                                                       //echo 'calculo ='.$ele_monto.' '.$operacion.$valor_ite_id.'<br>';
																	}
															$next_record2 = $db2->next_record();
														}
													if ((($id==29)||($id==62))&&($ele_monto<0)) $ele_monto=0;
									  		        $ele_monto =number_format($ele_monto, 0,".","") ;
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
									$sInsert = "insert into tb_datos values (null, $user_id, $id, ". tosql($ele_monto,"Number").", '$dat_fechahora', $key, 'A')";
								elseif (($sValida>0)&&($accion!='')) $sInsert = "update tb_datos set dat_monto=".tosql($ele_monto,"Number").", dat_fechahora= '$dat_fechahora' where dat_usu_id=$user_id and dat_ite_id=$id and dat_periodo=$key";
								$db3->query($sInsert);
								//echo $sValida. $sInsert."<br>";
							  } else
							  {
								  $ele_monto="&nbsp;";
								  $ele_montoDisplay = $ele_monto;
							  }
							  //$tpl->set_var("ele_monto", $ele_montoDisplay);
							  //$tpl->parse("DatosValor", true);
							  $k++;
							}

		              
		              
                      if (($accion=='=')||($accion=='')){
                       //$tpl->set_var("ele_nombre1","<font color=\"#CC682D\" style=\"font-weight:bold\">"."ID$id -".$nombre);
                       //$tpl->set_var("ele_accion1","<font color=\"#CC682D\" style=\"font-weight:bold\">".$accion);  
					   }
                      else {
                      //$tpl->set_var("ele_nombre1","ID$id -".$nombre);
                      //$tpl->set_var("ele_accion1",$accion);
                      }

				 /*$tpl->parse("Row1",true);
				 $tpl->set_var("DatosValor", "");
		         $tpl->set_var("NavigatorNavigator1", "" );
		         $tpl->set_var("NoRecords1", "" );*/
		        $next_record=$db->next_record();
		    }
			// while
}

function datos_show()
{
 global $tpl,$db,$periodo, $db2, $db3;
 $ele_id = get_param("ele_id");
 if ($ele_id=='') $ele_id=get_session("SSele_id");
 if ($ele_id=='') $ele_id=1;
 set_session("SSele_id",$ele_id);
 $user_id = get_session("cliID");
 $jue_id = get_param("id");
 $dat_fechahora = date("Ymdhis");
 $per_id =get_param("dat_periodo");
 if ($per_id=='') $per_id =get_session("LBperiodo");
 $periodoInicial=get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
 if ($per_id=='') $per_id=$periodoInicial;
 set_session("LBperiodo",$per_id);
 $apl = get_param("apl");
 if ($apl =='') get_session("SSapl");  
 if ($apl=='') {
  $apl=1;
  $tpl->set_var("Datos2","");
 } elseif ($apl==1) $tpl->set_var("Datos2","");
 set_session("SSapl",$apl);
// 	if (!is_numeric($ele_id))
//	 {
//	 	$ele_id=1;
//        set_session("SSele_id",$ele_id);
//	 }
     //echo $ele_id;

      $tpl->set_var("id", $jue_id);
      $tpl->set_var("ele_id", $ele_id);
      $tpl->set_var("dat_periodo", $per_id);

		 $elemento = get_db_value("select ite_nombre from tb_items where ite_id=$ele_id");
		 $tpl->set_var("elemento",$elemento );
		 $imagen = get_db_value("select jue_imagen from tb_juegos where jue_id=$jue_id");
		 $tpl->set_var("logo",$imagen);
		 
		 actualiza(1, $jue_id, $user_id, $per_id, $apl);
		 actualiza(21, $jue_id, $user_id, $per_id, $apl);
		 actualiza(31, $jue_id, $user_id, $per_id, $apl);
	     actualiza(57, $jue_id, $user_id, $per_id, $apl);
		 actualiza(85, $jue_id, $user_id, $per_id, $apl);
		 
		 $sSQL="select t.ite_id, t.ite_nombre, t.ite_etiqueta from tb_items t, th_grupos t1 where t1.gru_jue_id=$jue_id and  t.ite_id_itemSuperior=$ele_id and t.ite_id_itemSuperior=t1.gru_ite_id order by t.ite_orden asc";
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
                                if ($ele_monto>0) $ele_monto2=$ele_monto;
  							  		if (is_numeric($per_id))
									  {
									  		if ($key==$per_id)
									  		 {                                                       

									  			$sSQL= "select count(*) from tb_operaciones  where ope_ite_id=$id";
												$iCantidad = get_db_value($sSQL);
												$sSQL= "select count(*) from tb_operaciones  where ope_ite_id=$id and ope_trimestre=1";
												$iCantidad1 = get_db_value($sSQL);
												//echo $key.'='.$iCantidad1.'<br>';
												if (($iCantidad==0)||(($iCantidad1!=0)&&($key==$periodoInicial))) {
													if ($accion=='') $ele_monto = "&nbsp;";
													else $ele_montoDisplay = $ele_monto;
												}
									  		     else
									  		     {
													$sOperaciones = "select ope_operacion, ope_ite_idOperar, ope_valor, ope_trimestre from tb_operaciones where ope_ite_id=$id";
													$db2->query($sOperaciones);
													$next_record2 = $db2->next_record();
													$ele_monto=0;
														while($next_record2){
															$operacion = $db2->f("ope_operacion");
															$ope_ite_id = $db2->f("ope_ite_idOperar");
															$valor = $db2->f("ope_valor");
                                                            $trimestre = $db2->f("ope_trimestre");
                                                            // echo $id .'operacion'.$ele_monto.' ='.$operacion.' '.$ope_ite_id.' valor='.$valor.'trimestre='.$trimestre.'monto';
																if ($valor!="")
																	{
																		if ($operacion=='*') $ele_monto *= $valor;
																		else if ($operacion=='/') $ele_monto /= $valor;
																		else if ($operacion=='-') $ele_monto -= $valor;
                                                                        else if ($operacion=='+') $ele_monto += $valor_ite_id;
																		else if ($operacion=='|') 
																		{
																		 if ($ele_monto<0) $ele_monto=0;
																		}
																		
																		$flag=0;
																	}
																else
																	{   
                                                                        if ($trimestre==0)
                                                                        {$sSQL1="select dat_monto from tb_datos where dat_ite_id=$ope_ite_id and dat_usu_id=$user_id and dat_periodo=$key";    }
                                                                        else { 
                                                                            $tempKey= $key-1;
                                                                            $sSQL1="select dat_monto from tb_datos where dat_ite_id=$ope_ite_id and dat_usu_id=$user_id and dat_periodo=$tempKey";         
                                                                        }
                                                                            
																		$valor_ite_id = get_db_value ($sSQL1);
                                                                        if (!$valor_ite_id) $valor_ite_id=0;
																		if ((($ope_ite_id==29)||($ope_ite_id==62))&&($valor_ite_id<0)) 
																		$valor_ite_id=0;
                                                                        //echo $valor_ite_id.$sSQL1.'<br>';
                                                                        //echo $operacion.' ##';
																		if ($operacion=='+') $ele_monto += $valor_ite_id;
                                                                        else if ($operacion=='-') $ele_monto -= $valor_ite_id;
                                                                        else if ($operacion=='*') $ele_monto *= $valor_ite_id;
                                                                        else if ($operacion=='/') $ele_monto /= $valor_ite_id;
																		else if ($operacion=='|') 
																		{
																		 if ($ele_monto<0) $ele_monto=0;
																		}
																		$flag=1;
                                                                        //echo 'calculo ='.$ele_monto.' '.$operacion.$valor_ite_id.'<br>';
																	}
															$next_record2 = $db2->next_record();
														}

									  		        $ele_monto =number_format($ele_monto, 0,".","") ;
													if ((($id==29)||($id==62))&&($ele_monto<0)) $ele_monto=0;
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
									$sInsert = "insert into tb_datos values (null, $user_id, $id, ". tosql($ele_monto,"Number").", '$dat_fechahora', $key, 'A')";
								elseif (($sValida>0)&&($accion!='')) $sInsert = "update tb_datos set dat_monto=".tosql($ele_monto,"Number").", dat_fechahora= '$dat_fechahora' where dat_usu_id=$user_id and dat_ite_id=$id and dat_periodo=$key";
								$db3->query($sInsert);
								//echo $sValida. $sInsert."<br>";
							  } else
							  {
								  $ele_monto="&nbsp;";
								  $ele_montoDisplay = $ele_monto;
							  }
							  $tpl->set_var("ele_monto", $ele_montoDisplay);
							  $tpl->parse("DatosValor", true);
							  $k++;
							}

		              
		              
                      if (($accion=='=')||($accion=='')){
                       $tpl->set_var("ele_nombre1","<font color=\"#CC682D\" style=\"font-weight:bold\">"."ID$id -".$nombre);
                       $tpl->set_var("ele_accion1","<font color=\"#CC682D\" style=\"font-weight:bold\">".$accion);  }
                      else {
                      $tpl->set_var("ele_nombre1","ID$id -".$nombre);
                      $tpl->set_var("ele_accion1",$accion);
                      }

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
/*******************GESTION CLIENTES *******************/

function items_show()
{
	$dat_producto = get_param("dat_producto");
	$dat_mercado = get_param("dat_mercado");
	//if (($dat_producto!='')&&($dat_mercado!='')) items();


    if (($dat_producto==66)&&($dat_mercado==66)) items_todos();
    else items();
/*    else if (($dat_producto==66)&&($dat_mercado!=66)) items_mercado();
    if (($dat_producto==66)&&($dat_mercado==66)) items_todos();*/

}
function items()
{
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
	$tpl->set_var("OutPut","");
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
                                //$tpl->parse("Mercado", true);
                                /*$tpl->set_var("ID", "66");
                                $tpl->set_var("Value", "TODOS");*/
                           /*     if($dat_mercado==66)
                                  $tpl->set_var("Selected", "SELECTED" );
                                else
                                  $tpl->set_var("Selected", "");*/
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
					$tpl->parse("Mercado", true);
                  }
                }
            
                
    $sSQL2 = db_fill_array("select tri_id, tri_nombre from tb_trimestres where tri_sw='A'");
    $sSQL3 = db_fill_array("select atr_id, atr_nombre from tb_atributos where atr_sw='A' order by 1");    //atr_jue_id=$jue_id and 
/*    $sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
    $db->query($sSQL);
    $next_record=$db->next_record();
    while($next_record)
    {
        $cli_id = $db->f("cli_nombre");
        $cli_nombre = $db->f("cli_nombre");*/
        $key="";
        $value="";


            $sSelPeriodo=$per_id;
            for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
            {

                foreach ($sSQL3 as $clv => $value2)
                {
                //echo $value2.'-';
                $atr_tipoValor = get_db_value("select atr_tipoValor from tb_atributos where atr_id=$clv");
                $sValor=get_db_value("select vai_monto from th_valoresiniciales where vai_periodo=$key and vai_cli_id=35 and vai_mer_id=$dat_mercado and vai_pro_id=$dat_producto and vai_atr_id=$clv and vai_jue_id=$jue_id");
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
				//echo $sValor.'-';
                        $tpl->set_var("parametro", $value2 );
                        $tpl->parse("Parametros", true );

                //$tpl->parse("Atributos", true);
                }

            }
			//print_r($sSQL3);
        $tpl->set_var("tipoClientes1", $cli_nombre);
        //$tpl->parse("Row3", true );
		
        //$tpl->set_var("Atributos", "");
     /*   $next_record = $db->next_record();
    }*/

                                                               
    

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
/*                foreach($sSQL3 as $key => $value)
                    {
                        $tpl->set_var("parametro", $value );
                        $tpl->parse("Parametros", true );
                    }*/
            }
			
			$canal = array (1=>'CANAL PROMOTORAS',2=>'CANAL MAYORISTAS', 3=>'CANAL BOUTIQUES');
			foreach($canal as $keycanal => $valuecanal)
			{
			$tpl->set_var('canal',$valuecanal);
		 	$sSQL= "select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$jue_id and cli_sw='A'";
			$db->query($sSQL);
			$next_record=$db->next_record();
			while($next_record)
			{
				$cli_id = $db->f("cli_id");
				$cli_nombre = $db->f("cli_nombre");
							$sSelPeriodo=$per_id;
							//echo $per_id.'-';
							for ($key=$sSelPeriodo;$key<=$sSelPeriodo;$key++)
							{
				
								
								$periodo_inicio = get_db_value("select jue_periodoInicial from tb_juegos where jue_id=$jue_id");
								if ($key==$periodo_inicio)
								{
									$valini = get_db_value("select ini_monto from th_inicio where ini_jue_id=$jue_id and ini_tic_id=$cli_id and ini_mer_id=$dat_mercado and ini_pro_id=$dat_producto");
									if ($valini=="") $valini=0;
								}
								else
								{
								  $keytemp = $key-1;
								  $clvtemp = 5;
								  $iCalculos = "select val_cantidad from th_valores where val_tri_id=".$clvtemp." and val_periodo=$keytemp and val_cli_id=$cli_id and val_mer_id=$dat_mercado and val_pro_id=$dat_producto and val_usu_id=$user_id and val_sw='A' order by val_id"; 
								 $valini=get_db_value($iCalculos);
								 if ($valini=="") $valini=0;
								}
				//                $tpl->set_var("valorinput",number_format(round($valini,0),0,',','.'));  ////valor inicial
				//                $tpl->parse("Valores", true);
								$tpl->set_var("valorinput","&nbsp;");  ////valor inicial
								$tpl->parse("Valores", true);
				
								foreach($sSQL2 as $clv => $value2)
								{
									//echo $value2.'-';
									$iValor = get_db_value("select val_cantidad from th_valores where val_usu_id=$user_id and val_pro_id=$dat_producto and val_mer_id=$dat_mercado and val_cli_id=$keycanal$cli_id and val_tri_id=$clv and val_periodo=$key ");
									$vTotalNuevo[$clv] += $iValor;  
									if ($iValor=='')
									{
										$iValor=0;
										if ($key==$per_id) $iValor="<font color=\"#0099FF\">".$iValor."</font>";//FFFF66
										else $iValor="<font color=\"#0099FF\">".$iValor."</font>";    //0099FF                                         
									} else  if ($key==$per_id) $iValor="<font color=\"#0099FF\">".$iValor."</font>"; //000066
									
									$tpl->set_var("valorinput",$iValor);
									$tpl->parse("Valores", true);
								}
							}
				$tpl->set_var("BLOG2", "Row");
 			    $tpl->set_var("tipoClientes", $cli_nombre);
				$tpl->parse("Row2", true );
				$tpl->set_var("Valores", "");
				$next_record=$db->next_record();
			}
			
			//$tpl->set_var("Canales","");
			$tpl->parse("Canales",true);
			$tpl->set_var("Row2","");
			
			}//endforeach canal
			
			
			//        $tpl->set_var("tipoClientes", "TOTAL");
       	//			$tpl->set_var("BLOG2", "Caption");
        
         //           $tpl->set_var("valorinput","&nbsp;");
         //           $tpl->parse("Valores", true);

           /*     foreach($sSQL2 as $clv => $value2)
                {
                    $tpl->set_var("valorinput",round($vTotalNuevo[$clv],0)); //input
                    $tpl->parse("Valores", true);
                }*/
       // $tpl->parse("Row2", true );
    

    
}

?>							

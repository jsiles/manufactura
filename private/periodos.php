<?php
include ("./common2.php");
@session_start();
$filename = "periodos.php";
$template_filename = "periodos.html";
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$svaloresRecordErr = "";
switch ($sForm) {
  case "valoresRecord":
    valoresRecord_action($sAction);
  break;
}
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);
//valoresGrid_show();
valoresRecord_show();
$tpl->pparse("main", false);
function valoresRecord_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $svaloresRecordErr;
  $bExecSQL = false;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $fldperiodo = get_param("periodo");
  $fldinvestigacion = get_param("investigacion");
  $fldcompra = get_param("compra");  
  $fldcantidad = get_param("cant"); 
  $fldperiodoinicial = get_param("per_ini"); 
  $fldjuego = get_param("jue_id"); 
  $fldtiempo = get_param("tiempo");


  //if ($fldinvestigacion!=null) {print_r($fldinvestigacion); print_r($fldperiodo);}
 if($fldcompra!=null)
 {
	$sSQL = "update tb_periodos set per_compra='I' where per_jue_id=$fldjuego ";
    $db->query($sSQL); 
	foreach ($fldcompra as $key => $value)
	{
		$sSQL = "update tb_periodos set per_compra='A' where per_periodo= $key and per_jue_id=$fldjuego ";
		$db->query($sSQL);     
	}    
 }
 else
 {
 	$sSQL = "update tb_periodos set per_compra='I' where per_jue_id=$fldjuego ";
    $db->query($sSQL); 
 }
  
  
    if ($fldperiodo!=null&&$fldinvestigacion!=null)
    { 
//          print_r($fldinvestigacion);
            $sSQL = "update tb_periodos set per_estado='I', per_inv_estado='I', per_tiempo=$fldtiempo, per_datetime='".date("Y-m-d H:i:s",mktime(date("H"),date("i")+ $fldtiempo,date("s"),date("m"),date("d"),date("Y")))."' where per_jue_id=$fldjuego ";
		//	echo $sSQL;
            $db->query($sSQL);     
	        foreach ($fldperiodo as $key => $value)
            {
                $sSQL = "update tb_periodos set per_estado='A' where per_periodo= $key and per_jue_id=$fldjuego ";
                $db->query($sSQL);     
            }        
            foreach ($fldinvestigacion as $key => $value)
            {
                $sSQL = "update tb_periodos set per_inv_estado='A' where per_periodo= $key and per_jue_id=$fldjuego ";
                $db->query($sSQL);     
            }        
            
    }
    elseif ($fldinvestigacion!=null&&$fldperiodo==null)
    {
        $sSQL = "update tb_periodos set per_inv_estado='I', per_tiempo=$fldtiempo, per_datetime='".date("Y-m-d H:i:s",mktime(date("H"),date("i")+ $fldtiempo,date("s"),date("m"),date("d"),date("Y")))."' where per_jue_id=$fldjuego ";
	//	echo $sSQL;
        $db->query($sSQL); 
	
		$sSQL = "update tb_periodos set per_estado='I' where per_jue_id=$fldjuego ";
        $db->query($sSQL); 
		
		//	echo $sSQL;break;
        foreach ($fldinvestigacion as $key => $value)
            {
                $sSQL = "update tb_periodos set per_inv_estado='A' where per_periodo= $key and per_jue_id=$fldjuego ";
                $db->query($sSQL);     
            }        
    }
    elseif ($fldperiodo!=null&&$fldinvestigacion==null)
    {
			$sSQL = "update tb_periodos set per_inv_estado='I' where per_jue_id=$fldjuego ";
			$db->query($sSQL); 
		
			$sSQL = "update tb_periodos set per_estado='I', per_tiempo=$fldtiempo, per_datetime='".date("Y-m-d H:i:s",mktime(date("H"),date("i")+ $fldtiempo,date("s"),date("m"),date("d"),date("Y")))."' where per_jue_id=$fldjuego ";
//			echo $sSQL;
            $db->query($sSQL); 
                foreach ($fldperiodo as $key => $value)
            {
                $sSQL = "update tb_periodos set per_estado='A' where per_periodo= $key and per_jue_id=$fldjuego ";
                $db->query($sSQL);     
            }        
    
    }
  header("Location: periodos.php?jue_id=$fldjuego&per_ini=$fldperiodoinicial&cant=$fldcantidad&");
  exit;
}
function valoresRecord_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $svaloresRecordErr;

    $fldperiodo = get_param("periodo");
    $fldinvestigacion = get_param("investigacion");
 
  $fldvai_id = "";
  $fldvai_jue_id = "";
  $fldvai_atr_id = "";
  $fldvai_pro_id = "";
  $fldvai_mer_id = "";
  $fldvai_cli_id = "";
  $fldvai_monto = "";
  $fldvai_periodo = "";
  $fldvai_sw = "";
  $sFormTitle = "th_valoresiniciales";
  $fldFormvaloresGrid_Page = get_param("FormvaloresGrid_Page");
  $sWhere = "";
  $fldvai_jue_id= get_param("jue_id");
  $bPK = true;
  if($svaloresRecordErr == "")
  {
    $fldvai_id = get_param("vai_id");
    $pvai_id = get_param("vai_id");
    $tpl->set_var("valoresRecordErr", "");
  }
  else
  {
    $fldvai_id = strip(get_param("vai_id"));
    $fldvai_jue_id = strip(get_param("vai_jue_id"));
    $fldvai_periodo = strip(get_param("vai_periodo"));
    $fldvai_inicial = strip(get_param("per_ini"));
    $fldvai_cantidad = strip(get_param("cant"));
    
    $fldvai_sw = strip(get_param("vai_sw"));
    $pvai_id = get_param("PK_vai_id");
    $tpl->set_var("svaloresRecordErr", $svaloresRecordErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("valoresRecordErr", false);
  }
  
  if( !strlen($pvai_id)) $bPK = false;
  
  $sWhere .= "vai_id=" . tosql($pvai_id, "Number");
  $tpl->set_var("PK_vai_id", $pvai_id);
  $tpl->set_var("FormTitle", $sFormTitle);
  $sSQL = "select * from th_valoresiniciales where " . $sWhere;
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "valores") && $db->next_record());
  if($bIsUpdateMode)
  {
      $fldvai_id = $db->f("vai_id");        
    if($svaloresRecordErr == "") 
    {
      $fldvai_id = $db->f("vai_id");
      $fldvai_jue_id = $db->f("vai_jue_id");
      $fldvai_periodo = $db->f("vai_periodo");
    }
  }
  else
  {
    if(!isset($valoresRecordErr))
    {
      $fldvai_id = tohtml(get_param("vai_id"));
    }
  }
if (!$bIsUpdateMode)
{  
  $fldvai_monto = get_param("vai_monto");
  $fldvai_periodo = get_param("vai_periodo");
  $fldvai_sw = get_param("vai_sw");
}
  $fldvai_inicial = get_param("per_ini");
  $fldvai_cantidad = get_param("cant");
  $tpl->set_var("juego", dlookup("tb_juegos", "jue_nombre" , "jue_id=$fldvai_jue_id"));
   for ($i=$fldvai_inicial;$i<=$fldvai_cantidad+1;$i++)
   {
	    $tpl->set_var("periodo", $i);         
    	$tpl->set_var("investigacion", $i);
   		$tpl->set_var("compra", $i);
		if($i!=($fldvai_cantidad+1))
		{
	    $tpl->set_var("etq_periodo", $i);
        $tpl->set_var("etq_investigaciones", $i);
        $tpl->set_var("etq_compra", $i);
          
		}
		else
		{
		$tpl->set_var("etq_periodo", "FIN");
        $tpl->set_var("etq_investigaciones", "FIN");
        $tpl->set_var("etq_compra", "FIN");
		}
		
//        ($i==$fldvai_inicial)?$tpl->set_var("disabled","disabled"):$tpl->set_var("disabled","");          
        $valor = get_db_value("select per_estado from tb_periodos where per_jue_id=$fldvai_jue_id and per_periodo=$i ");
        $valor2 = get_db_value("select per_inv_estado from tb_periodos where per_jue_id=$fldvai_jue_id and per_periodo=$i ");
		$valor3 = get_db_value("select per_compra from tb_periodos where per_jue_id=$fldvai_jue_id and per_periodo=$i ");
        if ($valor == 'A') $tpl->set_var("checked", "checked");
        else  $tpl->set_var("checked", "");  
        if ($valor2 == 'A') $tpl->set_var("checked1", "checked");
        else  $tpl->set_var("checked1", "");  
		if ($valor3 == 'A') $tpl->set_var("checked2", "checked");
        else  $tpl->set_var("checked2", "");  
		
        $tiempo = get_db_value("select per_tiempo from tb_periodos where per_jue_id=$fldvai_jue_id and per_periodo=$i ");

        $tpl->parse("Periodos",true);  
        $tpl->parse("Investigaciones",true);  
        $tpl->parse("Compras",true);  
        $valida = get_db_value("select count(*) from tb_periodos where per_jue_id=$fldvai_jue_id and per_periodo=$i");
        if ($valida==0) {
            $sSQL = "insert into tb_periodos values(null, $fldvai_jue_id, $i, 'I', 'I','I', 129, now())";
            $db->query($sSQL);
        }
   }
   
  $tpl->set_var("vai_inicial", tohtml($fldvai_inicial));
  //echo $fldvai_inicial.'-'.$fldvai_cantidad;
  $fldvai_final =   $fldvai_inicial + $fldvai_cantidad -1;
  $javascript ="<script>

function valida(e){

    var valinicial = $fldvai_inicial;
    var valfinal = $fldvai_final;

    if (e.checked) 
    {
      for (i=valinicial+1;i<=valfinal;i++)
        if (e.id!=i) document.getElementById(i).checked=false;
    }

    return true;
}
</script>";

  switch($tiempo)
  {
  	case 120: 
			$tpl->set_var("selected120","selected");
			break;
  	case 90: 
			$tpl->set_var("selected90","selected");
			break;
  	case 60: 
			$tpl->set_var("selected60","selected");
			break;
  	case 30: 
			$tpl->set_var("selected30","selected");
			break;
  	case 15: 
			$tpl->set_var("selected15","selected");
			break;
	case 10: 
			$tpl->set_var("selected10","selected");
			break;
	case 5: 
			$tpl->set_var("selected5","selected");
			break;
	case 2: 
			$tpl->set_var("selected2","selected");
			break;

	default:		
			$tpl->set_var("selected120","selected");
  }
  $tpl->set_var("vai_final", tohtml($fldvai_final));
  $tpl->set_var("javascript", $javascript);
  $tpl->set_var("vai_cantidad", tohtml($fldvai_cantidad));
  $tpl->set_var("vai_id", tohtml($fldvai_id));
  $tpl->set_var("vai_jue_id", tohtml($fldvai_jue_id));
  $tpl->parse("FormvaloresRecord", false);
//  print_r($fldperiodo);
}
?>
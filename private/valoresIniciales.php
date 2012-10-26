<?php
include ("./common2.php");
session_start();
$filename = "valoresIniciales.php";
$template_filename = "valoresIniciales.html";
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
valoresGrid_show();valoresRecord_show();
$tpl->pparse("main", false);
function valoresGrid_show()
{
  
  global $tpl;
  global $db;
  global $svaloresGridErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "th_valoresiniciales";
  $HasParam = false;
  $iRecordsPerPage = 10;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  
  $iTmpI = 0;
  $iTmpJ = 0;
  $sCountSQL = "";
  $sActionFileName = "valoresIniciales.php";
  $tpl->set_var("TransitParams", "jue_id=".get_param("jue_id")."&per_ini=".get_param("per_ini")."&cant=".get_param("cant")."&FormvaloresGrid_Page=".get_param("FormvaloresGrid_Page")."&");
  $tpl->set_var("FormParams", "jue_id=".get_param("jue_id")."&per_ini=".get_param("per_ini")."&cant=".get_param("cant")."&FormvaloresGrid_Page=".get_param("FormvaloresGrid_Page")."&");
  $sOrder = " order by t.vai_periodo,t.vai_mer_id,t.vai_pro_id, t.vai_atr_id, t.vai_cli_id Asc";
  $iSort = get_param("FormvaloresGrid_Sorting");
  $iSorted = get_param("FormvaloresGrid_Sorted");
  if(!$iSort)
  {
    $tpl->set_var("Form_Sorting", "");
  }
  else
  {
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "FormvaloresGrid_Sorting=" . $iSort . "&FormvaloresGrid_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormvaloresGrid_Sorting=" . $iSort . "&FormvaloresGrid_Sorted=" . "&";
    }
    if ($iSort == 1) $sOrder = " order by t1.atr_nombre" . $sDirection;
    if ($iSort == 2) $sOrder = " order by t2.pro_nombre" . $sDirection;
    if ($iSort == 3) $sOrder = " order by t3.mer_nombre" . $sDirection;
    if ($iSort == 4) $sOrder = " order by t4.cli_nombre" . $sDirection;
    if ($iSort == 5) $sOrder = " order by t.vai_monto" . $sDirection;
    if ($iSort == 6) $sOrder = " order by t.vai_periodo" . $sDirection;
    if ($iSort == 7) $sOrder = " order by t.vai_sw" . $sDirection;
  }
$fldvai_jue_id= get_param("jue_id");
  $sSQL = "select t.vai_atr_id as t_vai_atr_id, " . 
    "t.vai_cli_id as t_vai_cli_id, " . 
    "t.vai_id as t_vai_id, " . 
    "t.vai_mer_id as t_vai_mer_id, " . 
    "t.vai_monto as t_vai_monto, " . 
    "t.vai_periodo as t_vai_periodo, " . 
    "t.vai_pro_id as t_vai_pro_id, " . 
    "t.vai_sw as t_vai_sw, " . 
    "t1.atr_id as t1_atr_id, " . 
    "t1.atr_nombre as t1_atr_nombre, " . 
    "t2.pro_id as t2_pro_id, " . 
    "t2.pro_nombre as t2_pro_nombre, " . 
    "t3.mer_id as t3_mer_id, " . 
    "t3.mer_nombre as t3_mer_nombre, " . 
    "t4.cli_id as t4_cli_id, " . 
    "t4.cli_nombre as t4_cli_nombre " . 
    " from th_valoresiniciales t, tb_atributos t1, tb_productos t2, tb_mercados t3, tb_tipoclientes t4" . 
    " where t1.atr_id=t.vai_atr_id and t2.pro_id=t.vai_pro_id and t3.mer_id=t.vai_mer_id and t4.cli_id=t.vai_cli_id and t.vai_jue_id=t4.cli_jue_id and t2.pro_jue_id=t.vai_jue_id and t3.mer_jue_id=t.vai_jue_id and t.vai_jue_id=$fldvai_jue_id ";
  $sSQL .= $sWhere . $sOrder;
  //echo $sSQL;
  if($sCountSQL == "")
  {
    $iTmpI = strpos(strtolower($sSQL), "select");
    $iTmpJ = strpos(strtolower($sSQL), "from") - 1;
    $sCountSQL = str_replace(substr($sSQL, $iTmpI + 6, $iTmpJ - $iTmpI - 6), " count(*) ", $sSQL);
    $iTmpI = strpos(strtolower($sCountSQL), "order by");
    if($iTmpI > 1) 
      $sCountSQL = substr($sCountSQL, 0, $iTmpI - 1);
  }
  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("FormAction", $sActionFileName);
  $tpl->set_var("SortParams", $sSortParams);
  $db->query($sSQL);
  $next_record = $db->next_record();
  if(!$next_record)
  {
    $tpl->set_var("DListvaloresGrid", "");
    $tpl->parse("valoresGridNoRecords", false);
    $tpl->set_var("valoresGridNavigator", "");
    $tpl->parse("FormvaloresGrid", false);
    return;
  }
  
  $avai_sw = split(";", "A;Activo;I;Inactivo");
  $iRecordsPerPage = 10;
  $iCounter = 0;
  $iPage = get_param("FormvaloresGrid_Page");
  $db_count = get_db_value($sCountSQL);
  $dResult = intval($db_count) / $iRecordsPerPage;
  $iPageCount = intval($dResult);
  if($iPageCount < $dResult) $iPageCount = $iPageCount + 1;
  $tpl->set_var("valoresGridPageCount", $iPageCount);
  if(!strlen($iPage))
    $iPage = 1;
  else
  {
    if($iPage == "last") $iPage = $iPageCount;
  }
  if(($iPage - 1) * $iRecordsPerPage != 0)
  {
    do
    {
      $iCounter++;
    } while ($iCounter < ($iPage - 1) * $iRecordsPerPage && $db->next_record());
    $next_record = $db->next_record();
  }
  $iCounter = 0;
  $i=0;
  $tpl->set_var("th_valoresiniciales_TotalRecords", $db_count);
  while($next_record  && $iCounter < $iRecordsPerPage)
  {
    $fldvai_id_vai_id = $db->f("t_vai_id");
    $fldvai_atr_id = $db->f("t1_atr_nombre");
    $fldvai_cli_id = $db->f("t4_cli_nombre");
    $fldvai_mer_id = $db->f("t3_mer_nombre");
    $fldvai_monto = $db->f("t_vai_monto");
    $fldvai_periodo = $db->f("t_vai_periodo");
    $fldvai_pro_id = $db->f("t2_pro_nombre");
    $fldvai_sw = $db->f("t_vai_sw");
    $fldvai_id= "Detalles";
    $next_record = $db->next_record();
    
      if ($i%2!=0)
      {
      $tpl->set_var("vai_id", tohtml($fldvai_id));
      $tpl->set_var("vai_id_URLLink", "valoresIniciales.php" );
      $tpl->set_var("Prmvai_id_vai_id", urlencode($fldvai_id_vai_id));
      $tpl->set_var("vai_atr_id", tohtml($fldvai_atr_id));
      $tpl->set_var("vai_pro_id", tohtml($fldvai_pro_id));
      $tpl->set_var("vai_mer_id", tohtml($fldvai_mer_id));
      $tpl->set_var("vai_cli_id", tohtml($fldvai_cli_id));
      $tpl->set_var("vai_monto", tohtml($fldvai_monto));
      $tpl->set_var("vai_periodo", tohtml($fldvai_periodo));
      $fldvai_sw = get_lov_value($fldvai_sw, $avai_sw);
      $tpl->set_var("vai_sw", tohtml($fldvai_sw));
    $tpl->parse("DListvaloresGrid", true);
    $tpl->set_var("DListvaloresAltGrid", "");
      }
      else 
            {
      $tpl->set_var("Alt_vai_id", tohtml($fldvai_id));
      $tpl->set_var("Alt_vai_id_URLLink", "valoresIniciales.php" );
      $tpl->set_var("Alt_Prmvai_id_vai_id", urlencode($fldvai_id_vai_id));
      $tpl->set_var("Alt_vai_atr_id", tohtml($fldvai_atr_id));
      $tpl->set_var("Alt_vai_pro_id", tohtml($fldvai_pro_id));
      $tpl->set_var("Alt_vai_mer_id", tohtml($fldvai_mer_id));
      $tpl->set_var("Alt_vai_cli_id", tohtml($fldvai_cli_id));
      $tpl->set_var("Alt_vai_monto", tohtml($fldvai_monto));
      $tpl->set_var("Alt_vai_periodo", tohtml($fldvai_periodo));
      $fldvai_sw = get_lov_value($fldvai_sw, $avai_sw);
      $tpl->set_var("Alt_vai_sw", tohtml($fldvai_sw));
    $tpl->parse("DListvaloresAltGrid", true);
    $tpl->set_var("DListvaloresGrid", "");
      }
      
    $tpl->parse("Grid", true);
    
$i++;
    
    $iCounter++;
  }
  $bEof = $next_record;
  if(!$bEof && $iPage == 1)
    $tpl->set_var("valoresGridNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("valoresGridNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("valoresGridNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("valoresGridCurrentPage", $iPage);
    $tpl->parse( "valoresGridNavigator", false);
  }
  $tpl->set_var( "valoresGridNoRecords", "");
  $tpl->parse( "FormvaloresGrid", false);
}
function valoresRecord_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $svaloresRecordErr;
  $bExecSQL = true;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $fldvai_jue_id = "";
  $fldvai_atr_id = "";
  $fldvai_mer_id = "";
  $fldvai_cli_id = "";
  $fldvai_monto = "";
  $fldvai_periodo = "";
  $fldvai_sw = "";
  
  $fldFormvaloresGrid_Page = get_param("FormvaloresGrid_Page");
  $sActionFileName = "valoresIniciales.php?";
  $sParams ="FormvaloresGrid_Page=$fldFormvaloresGrid_Page&";
  if($sAction == "cancel")
  {
    header("Location: " . $sActionFileName);
    exit;
  }
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPKvai_id = get_param("PK_vai_id");
    if( !strlen($pPKvai_id)) return;
    $sWhere = "vai_id=" . tosql($pPKvai_id, "Number");
  }
  $fldvai_jue_id = get_param("vai_jue_id");
  $fldvai_atr_id = get_param("vai_atr_id");
  $fldvai_pro_id = get_param("vai_pro_id");
  $fldvai_mer_id = get_param("vai_mer_id");
  $fldvai_cli_id = 35;
  $fldvai_monto = get_param("vai_monto");
  $fldvai_periodo = get_param("vai_periodo");
  $fldvai_sw = get_param("vai_sw");

  $fldvai_inicial = get_param("vai_inicial");
  $fldvai_cantidad = get_param("vai_cantidad");
  $fldvai_final =   $fldvai_inicial + $fldvai_cantidad -1;
   if($sAction == "insert")
   {
      if ((strlen($fldvai_atr_id))&&(strlen($fldvai_mer_id))&&(strlen($fldvai_pro_id))&&(strlen($fldvai_cli_id))&&(strlen($fldvai_periodo)))
      {$sSQLValida = "select count(*) from th_valoresiniciales where ".
          "vai_jue_id=$fldvai_jue_id and " . 
          "vai_atr_id=$fldvai_atr_id and " . 
          "vai_pro_id=$fldvai_pro_id and " . 
          "vai_mer_id=$fldvai_mer_id and " . 
          "vai_cli_id=$fldvai_cli_id and " . 
          "vai_periodo=$fldvai_periodo ";
      $sValida = get_db_value($sSQLValida);
      //echo $sSQLValida;
      }
       
      if ($sValida!=0)   
      $svaloresRecordErr .= "Dato ya introducido anteriormente.<br>";
   }
  if($sAction == "insert" || $sAction == "update") 
  {
    if(!strlen($fldvai_atr_id))
      $svaloresRecordErr .= "El valor en el campo Atributos se requiere.<br>";
    
    if(!strlen($fldvai_mer_id))
      $svaloresRecordErr .= "El valor en el campo Mercados se requiere.<br>";

    if(!strlen($fldvai_pro_id))
      $svaloresRecordErr .= "El valor en el campo Productos se requiere.<br>";
    
  /*  if(!strlen($fldvai_cli_id))
      $svaloresRecordErr .= "El valor en el campo Segmento se requiere.<br>";*/
    
    if(!strlen($fldvai_monto))
      $svaloresRecordErr .= "El valor en el campo Monto se requiere.<br>";
    
    if(!strlen($fldvai_periodo))
      $svaloresRecordErr .= "El valor en el campo Periodo se requiere.<br>";
    
    if(!strlen($fldvai_sw))
      $svaloresRecordErr .= "El valor en el campo Estado se requiere.<br>";
    
    if(!is_number($fldvai_jue_id))
      $svaloresRecordErr .= "El valor en el campo vai_jue_id es incorrecto.<br>";
    
    if(!is_number($fldvai_atr_id))
      $svaloresRecordErr .= "El valor en el campo Atributos es incorrecto.<br>";
    
    if(!is_number($fldvai_mer_id))
      $svaloresRecordErr .= "El valor en el campo Mercado es incorrecto.<br>";
    /*
    if(!is_number($fldvai_cli_id))
      $svaloresRecordErr .= "El valor en el campo Segmento es incorrecto.<br>";*/
    
    if(!is_number($fldvai_monto))
      $svaloresRecordErr .= "El valor en el campo Monto es incorrecto.<br>";

    if(!is_number($fldvai_periodo))
      $svaloresRecordErr .= "El valor en el campo Monto es incorrecto.<br>";

    
    if($fldvai_periodo>$fldvai_final)
      $svaloresRecordErr .= "El valor en el campo Periodo es Mayor al permitido.<br>";
    
    if($fldvai_periodo<$fldvai_inicial)
      $svaloresRecordErr .= "El valor en el campo Periodo es Menor al permitido.<br>";
      
    
    
    if(strlen($svaloresRecordErr)) return;
  }
         $sParams .=  "jue_id=" .$fldvai_jue_id; 
         $sParams .= "&vai_atr_id=" . $fldvai_atr_id;
         $sParams .= "&vai_pro_id=" . $fldvai_pro_id;
         $sParams .= "&vai_mer_id=" .  $fldvai_mer_id;
         $sParams .= "&vai_cli_id=" .  $fldvai_cli_id;
         $sParams .= "&vai_monto=" .  $fldvai_monto;
         $sParams .= "&vai_periodo=" .  $fldvai_periodo;
         $sParams .= "&vai_sw=" . $fldvai_sw;
         $sParams .= "&per_ini=" . $fldvai_inicial;
         $sParams .= "&cant=" . $fldvai_cantidad;
         

  switch(strtolower($sAction)) 
  {
    case "insert":
        $sSQL = "insert into th_valoresiniciales (" . 
          "vai_jue_id," . 
          "vai_atr_id," . 
          "vai_pro_id," . 
          "vai_mer_id," . 
          "vai_cli_id," . 
          "vai_monto," . 
          "vai_periodo," . 
          "vai_sw)" . 
          " values (" . 
          tosql($fldvai_jue_id, "Number") . "," . 
          tosql($fldvai_atr_id, "Number") . "," . 
          tosql($fldvai_pro_id, "Number") . "," . 
          tosql($fldvai_mer_id, "Number") . "," . 
          tosql($fldvai_cli_id, "Number") . "," . 
          tosql($fldvai_monto, "Number") . "," . 
          tosql($fldvai_periodo, "Text") . "," . 
          tosql($fldvai_sw, "Text") . 
          ")";
    break;
    case "update":
        $sSQL = "update th_valoresiniciales set " .
          "vai_jue_id=" . tosql($fldvai_jue_id, "Number") .
          ",vai_atr_id=" . tosql($fldvai_atr_id, "Number") .
          ",vai_pro_id=" . tosql($fldvai_pro_id, "Number") .
          ",vai_mer_id=" . tosql($fldvai_mer_id, "Number") .
          ",vai_cli_id=" . tosql($fldvai_cli_id, "Number") .
          ",vai_monto=" . tosql($fldvai_monto, "Number") .
          ",vai_periodo=" . tosql($fldvai_periodo, "Text") .
          ",vai_sw=" . tosql($fldvai_sw, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
        $sSQL = "delete from th_valoresiniciales where " . $sWhere;
    break;
  }
  if(strlen($svaloresRecordErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  //echo $sSQL;
  header("Location: " . $sActionFileName. $sParams);
  exit;
}
function valoresRecord_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $svaloresRecordErr;

  
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
    $fldvai_atr_id = strip(get_param("vai_atr_id"));
    $fldvai_pro_id = strip(get_param("vai_pro_id"));
    $fldvai_mer_id = strip(get_param("vai_mer_id"));
    $fldvai_cli_id = strip(get_param("vai_cli_id"));
    $fldvai_monto = strip(get_param("vai_monto"));
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
      $fldvai_cli_id = $db->f("vai_cli_id");
      $fldvai_jue_id = $db->f("vai_jue_id");
      $fldvai_atr_id = $db->f("vai_atr_id");
      $fldvai_pro_id = $db->f("vai_pro_id");
      $fldvai_mer_id = $db->f("vai_mer_id");
      $fldvai_monto = $db->f("vai_monto");
      $fldvai_periodo = $db->f("vai_periodo");
      $fldvai_sw = $db->f("vai_sw");
    }
    $tpl->set_var("valoresRecordInsert", "");
    $tpl->parse("valoresRecordEdit", false);
  }
  else
  {
    if($valoresRecordErr == "")
    {
      $fldvai_id = tohtml(get_param("vai_id"));
    }
    $tpl->set_var("valoresRecordEdit", "");
    $tpl->parse("valoresRecordInsert", false);
  }
  $tpl->parse("valoresRecordCancel", false);
if (!$bIsUpdateMode)
{  
  $fldvai_atr_id = get_param("vai_atr_id");
  $fldvai_pro_id = get_param("vai_pro_id");
  $fldvai_mer_id = get_param("vai_mer_id");
  $fldvai_cli_id = get_param("vai_cli_id");
  $fldvai_monto = get_param("vai_monto");
  $fldvai_periodo = get_param("vai_periodo");
  $fldvai_sw = get_param("vai_sw");
}
  $fldvai_inicial = get_param("per_ini");
  $fldvai_cantidad = get_param("cant");
  $tpl->set_var("vai_inicial", tohtml($fldvai_inicial));
  //echo $fldvai_inicial.'-'.$fldvai_cantidad;
  $fldvai_final =   $fldvai_inicial + $fldvai_cantidad -1;
  $tpl->set_var("vai_final", tohtml($fldvai_final));
  $tpl->set_var("vai_cantidad", tohtml($fldvai_cantidad));

    $tpl->set_var("vai_id", tohtml($fldvai_id));
    $tpl->set_var("valoresRecordLBvai_atr_id", "");
    $lookup_vai_atr_id = db_fill_array("select atr_id, atr_nombre from tb_atributos order by 2");
    if(is_array($lookup_vai_atr_id))
    {
      reset($lookup_vai_atr_id);
        $tpl->set_var("ID", "");
        $tpl->set_var("Value", "Seleccionar Valor");
        $tpl->parse("valoresRecordLBvai_atr_id", true);
      while(list($key, $value) = each($lookup_vai_atr_id))
      {
        $tpl->set_var("ID", $key);
        $tpl->set_var("Value", $value);
        if($key == $fldvai_atr_id)
          $tpl->set_var("Selected", "SELECTED" );
        else 
          $tpl->set_var("Selected", "");
        $tpl->parse("valoresRecordLBvai_atr_id", true);
      }
    }
    
    $tpl->set_var("valoresRecordLBvai_mer_id", "");
    $lookup_vai_pro_id = db_fill_array("select pro_id, pro_nombre from tb_productos where pro_jue_id=$fldvai_jue_id and pro_sw='A' order by 2");
    if(is_array($lookup_vai_pro_id))
    {
      reset($lookup_vai_pro_id);
        $tpl->set_var("ID", "");
        $tpl->set_var("Value", "Seleccionar Valor");
      $tpl->parse("valoresRecordLBvai_pro_id", true);
      while(list($key, $value) = each($lookup_vai_pro_id))
      {
        $tpl->set_var("ID", $key);
        $tpl->set_var("Value", $value);
        if($key == $fldvai_pro_id)
          $tpl->set_var("Selected", "SELECTED" );
        else 
          $tpl->set_var("Selected", "");
        $tpl->parse("valoresRecordLBvai_pro_id", true);
      }
    }
    else
    {
      $tpl->set_var("ID", "");
      $tpl->set_var("Value", "Seleccionar Valor");
      $tpl->parse("valoresRecordLBvai_pro_id", true);
    }
    $tpl->set_var("valoresRecordLBvai_mer_id", "");
    $lookup_vai_mer_id = db_fill_array("select mer_id, mer_nombre from tb_mercados where mer_jue_id=$fldvai_jue_id and mer_sw='A' order by 2");
    if(is_array($lookup_vai_mer_id))
    {
      reset($lookup_vai_mer_id);
        $tpl->set_var("ID", "");
        $tpl->set_var("Value", "Seleccionar Valor");
      $tpl->parse("valoresRecordLBvai_mer_id", true);
      while(list($key, $value) = each($lookup_vai_mer_id))
      {
        $tpl->set_var("ID", $key);
        $tpl->set_var("Value", $value);
        if($key == $fldvai_mer_id)
          $tpl->set_var("Selected", "SELECTED" );
        else 
          $tpl->set_var("Selected", "");
        $tpl->parse("valoresRecordLBvai_mer_id", true);
      }
    }
    else
    {
      $tpl->set_var("ID", "");
      $tpl->set_var("Value", "Seleccionar Valor");
      $tpl->parse("valoresRecordLBvai_mer_id", true);
    }
    
    
    $tpl->set_var("valoresRecordLBvai_cli_id", "");
    $lookup_vai_cli_id = db_fill_array("select cli_id, cli_nombre from tb_tipoclientes where cli_jue_id=$fldvai_jue_id and cli_sw='A' order by 2");
    if(is_array($lookup_vai_cli_id))
    {
      reset($lookup_vai_cli_id);
        $tpl->set_var("ID", "");
        $tpl->set_var("Value", "Seleccionar Valor");
        $tpl->parse("valoresRecordLBvai_cli_id", true);
      while(list($key, $value) = each($lookup_vai_cli_id))
      {
        $tpl->set_var("ID", $key);
        $tpl->set_var("Value", $value);
        if($key == $fldvai_cli_id)
          $tpl->set_var("Selected", "SELECTED" );
        else 
          $tpl->set_var("Selected", "");
        $tpl->parse("valoresRecordLBvai_cli_id", true);
      }
    }
    else
    {
      $tpl->set_var("ID", "");
      $tpl->set_var("Value", "Seleccionar Valor");
      $tpl->parse("valoresRecordLBvai_cli_id", true);
    }
    
    $tpl->set_var("FormvaloresGrid_Page", $fldFormvaloresGrid_Page); 
    $tpl->set_var("vai_monto", tohtml($fldvai_monto));
    $tpl->set_var("vai_periodo", tohtml($fldvai_periodo));
    $tpl->set_var("valoresRecordLBvai_sw", "");
    $tpl->set_var("vai_jue_id", get_param("jue_id"));
    $LOV = split(";", ";Seleccionar Valor;A;Activo;I;Inactivo");
  
    if(sizeof($LOV)%2 != 0) 
      $array_length = sizeof($LOV) - 1;
    else
      $array_length = sizeof($LOV);
    reset($LOV);
    for($i = 0; $i < $array_length; $i = $i + 2)
    {
      $tpl->set_var("ID", $LOV[$i]);
      $tpl->set_var("Value", $LOV[$i + 1]);
      if($LOV[$i] == $fldvai_sw) 
        $tpl->set_var("Selected", "SELECTED");
      else
        $tpl->set_var("Selected", "");
      $tpl->parse("valoresRecordLBvai_sw", true);
    }
  $tpl->parse("FormvaloresRecord", false);
}
?>

<?php
//BindEvents Method @1-A5279FDE
function BindEvents()
{
    global $th_grupos;
    global $th_grupos1;
    $th_grupos->th_grupos_TotalRecords->CCSEvents["BeforeShow"] = "th_grupos_th_grupos_TotalRecords_BeforeShow";
    $th_grupos->gru_ite_id->CCSEvents["BeforeShow"] = "th_grupos_gru_ite_id_BeforeShow";
    $th_grupos->gru_sw->CCSEvents["BeforeShow"] = "th_grupos_gru_sw_BeforeShow";
    $th_grupos->Alt_gru_ite_id->CCSEvents["BeforeShow"] = "th_grupos_Alt_gru_ite_id_BeforeShow";
    $th_grupos->Alt_gru_sw->CCSEvents["BeforeShow"] = "th_grupos_Alt_gru_sw_BeforeShow";
    $th_grupos1->CCSEvents["OnValidate"] = "th_grupos1_OnValidate";
}
//End BindEvents Method

//th_grupos_th_grupos_TotalRecords_BeforeShow @15-84C9AEAB
function th_grupos_th_grupos_TotalRecords_BeforeShow(& $sender)
{
    $th_grupos_th_grupos_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos; //Compatibility
//End th_grupos_th_grupos_TotalRecords_BeforeShow

//Retrieve number of records @16-F9BE8C2E
    $Container->th_grupos_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close th_grupos_th_grupos_TotalRecords_BeforeShow @15-4D2C39CF
    return $th_grupos_th_grupos_TotalRecords_BeforeShow;
}
//End Close th_grupos_th_grupos_TotalRecords_BeforeShow

//th_grupos_gru_ite_id_BeforeShow @24-069CD0F3
function th_grupos_gru_ite_id_BeforeShow(& $sender)
{
    $th_grupos_gru_ite_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos; //Compatibility
//End th_grupos_gru_ite_id_BeforeShow

//Custom Code @53-70457DE6
// -------------------------
    global $th_grupos, $DBsiges;
	$sItem = $th_grupos->gru_ite_id->GetValue();
	if (strlen($sItem)>0) $th_grupos->gru_ite_id->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges));
// -------------------------
//End Custom Code

//Close th_grupos_gru_ite_id_BeforeShow @24-A6C2C4FC
    return $th_grupos_gru_ite_id_BeforeShow;
}
//End Close th_grupos_gru_ite_id_BeforeShow

//th_grupos_gru_sw_BeforeShow @25-A6D21901
function th_grupos_gru_sw_BeforeShow(& $sender)
{
    $th_grupos_gru_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos; //Compatibility
//End th_grupos_gru_sw_BeforeShow

//Custom Code @45-70457DE6
// -------------------------
    global $th_grupos;
		$sEstado = $th_grupos->gru_sw->GetValue();
		if ($sEstado=='A') $sEstado="Activo";
		elseif ($sEstado=='I') $sEstado="Inactivo";
		$th_grupos->gru_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close th_grupos_gru_sw_BeforeShow @25-05D900E5
    return $th_grupos_gru_sw_BeforeShow;
}
//End Close th_grupos_gru_sw_BeforeShow

//th_grupos_Alt_gru_ite_id_BeforeShow @29-49BDD0ED
function th_grupos_Alt_gru_ite_id_BeforeShow(& $sender)
{
    $th_grupos_Alt_gru_ite_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos; //Compatibility
//End th_grupos_Alt_gru_ite_id_BeforeShow

//Custom Code @54-70457DE6
// -------------------------
    global $th_grupos, $DBsiges;
	$sItem = $th_grupos->Alt_gru_ite_id->GetValue();
	if (strlen($sItem)>0) $th_grupos->Alt_gru_ite_id->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges));

// -------------------------
//End Custom Code

//Close th_grupos_Alt_gru_ite_id_BeforeShow @29-E016339F
    return $th_grupos_Alt_gru_ite_id_BeforeShow;
}
//End Close th_grupos_Alt_gru_ite_id_BeforeShow

//th_grupos_Alt_gru_sw_BeforeShow @30-600E9EEA
function th_grupos_Alt_gru_sw_BeforeShow(& $sender)
{
    $th_grupos_Alt_gru_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos; //Compatibility
//End th_grupos_Alt_gru_sw_BeforeShow

//Custom Code @46-70457DE6
// -------------------------
    global $th_grupos;
		$sEstado = $th_grupos->Alt_gru_sw->GetValue();
		if ($sEstado=='A') $sEstado="Activo";
		elseif ($sEstado=='I') $sEstado="Inactivo";
		$th_grupos->Alt_gru_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close th_grupos_Alt_gru_sw_BeforeShow @30-009CC7C1
    return $th_grupos_Alt_gru_sw_BeforeShow;
}
//End Close th_grupos_Alt_gru_sw_BeforeShow

//DEL  function th_grupos1_Label1_BeforeShow(& $sender)
//DEL  {
//DEL      $th_grupos1_Label1_BeforeShow = true;
//DEL      $Component = & $sender;
//DEL      $Container = & CCGetParentContainer($sender);
//DEL      global $th_grupos1, $DBsiges; //Compatibility


//DEL  // -------------------------
//DEL     	$sItem = $th_grupos1->Label1->GetValue();
//DEL  	$th_grupos1->Label1->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges));
//DEL  
//DEL  // -------------------------

//th_grupos1_OnValidate @32-0B8D5F7A
function th_grupos1_OnValidate(& $sender)
{
    $th_grupos1_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_grupos1; //Compatibility
//End th_grupos1_OnValidate

//Custom Code @66-766B24CB
// -------------------------
// -------------------------
//End Custom Code

//Close th_grupos1_OnValidate @32-D7CC9E1B
    return $th_grupos1_OnValidate;
}
//End Close th_grupos1_OnValidate

//DEL  // -------------------------
//DEL      global $th_grupos1,$DBsiges;
//DEL      $iIte_id = $th_grupos1->ite_id->GetValue();
//DEL      $iJue_id = $th_grupos1->jue_id->GetValue();
//DEL  	$sGru_sw = $th_grupos1->gru_sw->GetValue();
//DEL  	if ($iIte_id!=0) 
//DEL  	{
//DEL  	$sSQL = "select count(*) as cantidad from th_grupos where gru_ite_id=$iIte_id and gru_jue_id=$iJue_id and gru_sw='$sGru_sw'"; 
//DEL  		$DBsiges->query($sSQL);
//DEL  		$next_record = $DBsiges->next_record();
//DEL  		$sValida = $DBsiges->f("cantidad");
//DEL      	if ($sValida!=0) $th_grupos1->gru_ite_id->Errors->addError("Ya se encuentra adicionado el Grupo o el Elemento");
//DEL  	}
//DEL  
//DEL  // -------------------------

//DEL  // -------------------------
//DEL      global $th_grupos1,$DBsiges;
//DEL      $iGru_ite_id = $th_grupos1->gru_ite_id->GetValue();
//DEL      $iIte_id = $th_grupos1->ite_id->GetValue();
//DEL      $iJue_id = $th_grupos1->jue_id->GetValue();
//DEL  	$sGru_sw = $th_grupos1->gru_sw->GetValue();
//DEL  	if ($iIte_id!=0) 
//DEL  	{
//DEL  	$sSQL = "select count(*) as cantidad from th_grupos where gru_ite_id=$iIte_id and gru_jue_id=$iJue_id and gru_sw='$sGru_sw'"; 
//DEL  		$DBsiges->query($sSQL);
//DEL  		$next_record = $DBsiges->next_record();
//DEL  		$sValida = $DBsiges->f("cantidad");
//DEL      	if ($sValida!=0) $th_grupos1->gru_ite_id->Errors->addError("Ya se encuentra adicionado el Grupo o el Elemento");
//DEL  	}
//DEL  	else {
//DEL  	$sSQL = "select count(*) as cantidad from th_grupos where gru_ite_id=$iGru_te_id and gru_jue_id=$iJue_id and gru_sw='$sGru_sw'"; 
//DEL  	$DBsiges->query($sSQL);
//DEL  	$next_record = $DBsiges->next_record();
//DEL  	$sValida = $DBsiges->f("cantidad");
//DEL      	if ($sValida!=0) $th_grupos1->ite_id->Errors->addError("Ya se encuentra adicionado el Grupo o el Elemento");
//DEL  	}
//DEL  // -------------------------


//DEL  // -------------------------
//DEL      global $th_grupos1,$DBsiges;
//DEL  //    $iGru_ite_id = $th_grupos1->gru_ite_id->GetValue();
//DEL  //    $iIte_id = $th_grupos1->ite_id->GetValue();
//DEL  //    $iJue_id = $th_grupos1->jue_id->GetValue();
//DEL  //	$sGru_sw = $th_grupos1->gru_sw->GetValue();
//DEL  //	if ($iIte_id==0||$iIte_id=='') $sSQL = "select count(*) as cantidad from th_grupos where gru_ite_id=$iGru_ite_id and gru_jue_id=$iJue_id and gru_sw='$sGru_sw'"; 
//DEL  //	else $sSQL = "select count(*) as cantidad from th_grupos where gru_ite_id=$iIte_id and gru_jue_id=$iJue_id and gru_sw='$sGru_sw'"; 
//DEL  //	$DBsiges->query($sSQL);
//DEL  //	$next_record = $DBsiges->next_record();
//DEL  //		$sValida = $DBsiges->f("cantidad");
//DEL  //		if ($sValida!=0) $th_grupos1->gru_ite_id->Errors->addError("Ya se encuentra adicionado el Grupo o el Elemento");
//DEL  
//DEL  
//DEL  // -------------------------



?>

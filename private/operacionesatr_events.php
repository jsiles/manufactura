<?php
//BindEvents Method @1-5CB478E2
function BindEvents()
{
    global $tb_operaciones;
    $tb_operaciones->item->CCSEvents["BeforeShow"] = "tb_operaciones_item_BeforeShow";
    $tb_operaciones->tb_operaciones_TotalRecords->CCSEvents["BeforeShow"] = "tb_operaciones_tb_operaciones_TotalRecords_BeforeShow";
    $tb_operaciones->ope_ite_idOperar->CCSEvents["BeforeShow"] = "tb_operaciones_ope_ite_idOperar_BeforeShow";
    $tb_operaciones->ope_atri_id->CCSEvents["BeforeShow"] = "tb_operaciones_ope_atri_id_BeforeShow";
    $tb_operaciones->ope_trimestre->CCSEvents["BeforeShow"] = "tb_operaciones_ope_trimestre_BeforeShow";
    $tb_operaciones->ope_sw->CCSEvents["BeforeShow"] = "tb_operaciones_ope_sw_BeforeShow";
    $tb_operaciones->Alt_ope_ite_idOperar->CCSEvents["BeforeShow"] = "tb_operaciones_Alt_ope_ite_idOperar_BeforeShow";
    $tb_operaciones->Alt_ope_atr_id->CCSEvents["BeforeShow"] = "tb_operaciones_Alt_ope_atr_id_BeforeShow";
    $tb_operaciones->Alt_ope_trimestre->CCSEvents["BeforeShow"] = "tb_operaciones_Alt_ope_trimestre_BeforeShow";
    $tb_operaciones->Alt_ope_sw->CCSEvents["BeforeShow"] = "tb_operaciones_Alt_ope_sw_BeforeShow";
}
//End BindEvents Method

//tb_operaciones_item_BeforeShow @59-49752FC6
function tb_operaciones_item_BeforeShow(& $sender)
{
    $tb_operaciones_item_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_item_BeforeShow

//Custom Code @60-321D7DBC
// -------------------------
    global $tb_operaciones, $DBsiges;
	$sItem = $tb_operaciones->item->GetValue();
	if (strlen($sItem)>0) $tb_operaciones->item->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges)); 
// -------------------------
//End Custom Code

//Close tb_operaciones_item_BeforeShow @59-DDCC5D40
    return $tb_operaciones_item_BeforeShow;
}
//End Close tb_operaciones_item_BeforeShow

//tb_operaciones_tb_operaciones_TotalRecords_BeforeShow @9-772FD353
function tb_operaciones_tb_operaciones_TotalRecords_BeforeShow(& $sender)
{
    $tb_operaciones_tb_operaciones_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_tb_operaciones_TotalRecords_BeforeShow

//Retrieve number of records @10-6C752896
    $Container->tb_operaciones_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_operaciones_tb_operaciones_TotalRecords_BeforeShow @9-417131E6
    return $tb_operaciones_tb_operaciones_TotalRecords_BeforeShow;
}
//End Close tb_operaciones_tb_operaciones_TotalRecords_BeforeShow

//tb_operaciones_ope_ite_idOperar_BeforeShow @19-F9A3C615
function tb_operaciones_ope_ite_idOperar_BeforeShow(& $sender)
{
    $tb_operaciones_ope_ite_idOperar_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_ope_ite_idOperar_BeforeShow

//Custom Code @48-321D7DBC
// -------------------------
    global $tb_operaciones, $DBsiges;
	$sItem = $tb_operaciones->ope_ite_idOperar->GetValue();
	if (strlen($sItem)>0) $tb_operaciones->ope_ite_idOperar->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges)); 

// -------------------------
//End Custom Code

//Close tb_operaciones_ope_ite_idOperar_BeforeShow @19-AAE4D980
    return $tb_operaciones_ope_ite_idOperar_BeforeShow;
}
//End Close tb_operaciones_ope_ite_idOperar_BeforeShow

//tb_operaciones_ope_atri_id_BeforeShow @69-E4F4FE9B
function tb_operaciones_ope_atri_id_BeforeShow(& $sender)
{
    $tb_operaciones_ope_atri_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_ope_atri_id_BeforeShow

//DLookup @73-917786FB
    global $DBsiges;
    global $tb_operaciones;
	$valida = $tb_operaciones->ope_atri_id->GetValue();
	if ($valida!='') {
    $result = CCDLookUp("atr_nombre", "tb_atributos", "atr_id=".$tb_operaciones->ope_atri_id->GetValue(), $DBsiges);
    $result = strval($result);
    $tb_operaciones->ope_atri_id->SetValue($result);
	}
//End DLookup

//Close tb_operaciones_ope_atri_id_BeforeShow @69-C3B97ABB
    return $tb_operaciones_ope_atri_id_BeforeShow;
}
//End Close tb_operaciones_ope_atri_id_BeforeShow

//tb_operaciones_ope_trimestre_BeforeShow @71-9E2432BA
function tb_operaciones_ope_trimestre_BeforeShow(& $sender)
{
    $tb_operaciones_ope_trimestre_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_ope_trimestre_BeforeShow

//Custom Code @75-321D7DBC
// -------------------------
    global $tb_operaciones;
    $valor= $tb_operaciones->ope_trimestre->GetValue();
	if ($valor==0||$valor=='') $tb_operaciones->ope_trimestre->SetValue("NO");
	else $tb_operaciones->ope_trimestre->SetValue("SI");
// -------------------------
//End Custom Code

//Close tb_operaciones_ope_trimestre_BeforeShow @71-46D7DF69
    return $tb_operaciones_ope_trimestre_BeforeShow;
}
//End Close tb_operaciones_ope_trimestre_BeforeShow

//tb_operaciones_ope_sw_BeforeShow @22-4AE51A83
function tb_operaciones_ope_sw_BeforeShow(& $sender)
{
    $tb_operaciones_ope_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_ope_sw_BeforeShow

//Custom Code @50-321D7DBC
// -------------------------
    global $tb_operaciones;
	$sEstado = $tb_operaciones->ope_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_operaciones->ope_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close tb_operaciones_ope_sw_BeforeShow @22-47DB0F58
    return $tb_operaciones_ope_sw_BeforeShow;
}
//End Close tb_operaciones_ope_sw_BeforeShow

//tb_operaciones_Alt_ope_ite_idOperar_BeforeShow @25-0632A467
function tb_operaciones_Alt_ope_ite_idOperar_BeforeShow(& $sender)
{
    $tb_operaciones_Alt_ope_ite_idOperar_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_Alt_ope_ite_idOperar_BeforeShow

//Custom Code @49-321D7DBC
// -------------------------
    global $tb_operaciones, $DBsiges;
	$sItem = $tb_operaciones->Alt_ope_ite_idOperar->GetValue();
	if (strlen($sItem)>0) $tb_operaciones->Alt_ope_ite_idOperar->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges)); 

// -------------------------
//End Custom Code

//Close tb_operaciones_Alt_ope_ite_idOperar_BeforeShow @25-A51CFE68
    return $tb_operaciones_Alt_ope_ite_idOperar_BeforeShow;
}
//End Close tb_operaciones_Alt_ope_ite_idOperar_BeforeShow

//tb_operaciones_Alt_ope_atr_id_BeforeShow @70-62C9957B
function tb_operaciones_Alt_ope_atr_id_BeforeShow(& $sender)
{
    $tb_operaciones_Alt_ope_atr_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_Alt_ope_atr_id_BeforeShow

//DLookup @74-C6842979
    global $DBsiges;
    global $tb_operaciones;
	$valida = $tb_operaciones->Alt_ope_atr_id->GetValue();
	if ($valida!='') {
    $result = CCDLookUp("atr_nombre", "tb_atributos", "atr_id=".$tb_operaciones->Alt_ope_atr_id->GetValue(), $DBsiges);
    $result = strval($result);
    $tb_operaciones->Alt_ope_atr_id->SetValue($result);
	}
//End DLookup

//Close tb_operaciones_Alt_ope_atr_id_BeforeShow @70-E0E6F050
    return $tb_operaciones_Alt_ope_atr_id_BeforeShow;
}
//End Close tb_operaciones_Alt_ope_atr_id_BeforeShow

//tb_operaciones_Alt_ope_trimestre_BeforeShow @72-42F71620
function tb_operaciones_Alt_ope_trimestre_BeforeShow(& $sender)
{
    $tb_operaciones_Alt_ope_trimestre_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_Alt_ope_trimestre_BeforeShow

//Custom Code @76-321D7DBC
// -------------------------
    global $tb_operaciones;
    $valores = $tb_operaciones->Alt_ope_trimestre->GetValue();
	if ($valores==0||$valores=='') $tb_operaciones->Alt_ope_trimestre->SetValue("NO");
	else $tb_operaciones->Alt_ope_trimestre->SetValue("SI");


// -------------------------
//End Custom Code

//Close tb_operaciones_Alt_ope_trimestre_BeforeShow @72-527418D8
    return $tb_operaciones_Alt_ope_trimestre_BeforeShow;
}
//End Close tb_operaciones_Alt_ope_trimestre_BeforeShow

//tb_operaciones_Alt_ope_sw_BeforeShow @28-029B8FAF
function tb_operaciones_Alt_ope_sw_BeforeShow(& $sender)
{
    $tb_operaciones_Alt_ope_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_Alt_ope_sw_BeforeShow

//Custom Code @51-321D7DBC
// -------------------------
    global $tb_operaciones;
	$sEstado = $tb_operaciones->Alt_ope_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_operaciones->Alt_ope_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_operaciones_Alt_ope_sw_BeforeShow @28-301523B4
    return $tb_operaciones_Alt_ope_sw_BeforeShow;
}
//End Close tb_operaciones_Alt_ope_sw_BeforeShow


?>

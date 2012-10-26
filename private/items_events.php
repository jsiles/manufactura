<?php
//BindEvents Method @1-C97497A2
function BindEvents()
{
    global $tb_items;
    $tb_items->tb_items_TotalRecords->CCSEvents["BeforeShow"] = "tb_items_tb_items_TotalRecords_BeforeShow";
    $tb_items->ite_id_itemSuperior->CCSEvents["BeforeShow"] = "tb_items_ite_id_itemSuperior_BeforeShow";
    $tb_items->ite_sw->CCSEvents["BeforeShow"] = "tb_items_ite_sw_BeforeShow";
    $tb_items->Alt_ite_id_itemSuperior->CCSEvents["BeforeShow"] = "tb_items_Alt_ite_id_itemSuperior_BeforeShow";
    $tb_items->Alt_ite_sw->CCSEvents["BeforeShow"] = "tb_items_Alt_ite_sw_BeforeShow";
}
//End BindEvents Method

//tb_items_tb_items_TotalRecords_BeforeShow @8-FEF52124
function tb_items_tb_items_TotalRecords_BeforeShow(& $sender)
{
    $tb_items_tb_items_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_items; //Compatibility
//End tb_items_tb_items_TotalRecords_BeforeShow

//Retrieve number of records @9-212AA963
    $Container->tb_items_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_items_tb_items_TotalRecords_BeforeShow @8-D6ED2054
    return $tb_items_tb_items_TotalRecords_BeforeShow;
}
//End Close tb_items_tb_items_TotalRecords_BeforeShow

//tb_items_ite_id_itemSuperior_BeforeShow @21-82D508DB
function tb_items_ite_id_itemSuperior_BeforeShow(& $sender)
{
    $tb_items_ite_id_itemSuperior_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_items; //Compatibility
//End tb_items_ite_id_itemSuperior_BeforeShow

//Custom Code @45-19CB268F
// -------------------------
    global $tb_items, $DBsiges;
	$sItem = $tb_items->ite_id_itemSuperior->GetValue();
	if (strlen($sItem)>0) $tb_items->ite_id_itemSuperior->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges)); 
// -------------------------
//End Custom Code

//Close tb_items_ite_id_itemSuperior_BeforeShow @21-386F000A
    return $tb_items_ite_id_itemSuperior_BeforeShow;
}
//End Close tb_items_ite_id_itemSuperior_BeforeShow

//tb_items_ite_sw_BeforeShow @22-47619DE7
function tb_items_ite_sw_BeforeShow(& $sender)
{
    $tb_items_ite_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_items; //Compatibility
//End tb_items_ite_sw_BeforeShow

//Custom Code @47-19CB268F
// -------------------------
    global $tb_items;
	$sEstado = $tb_items->ite_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_items->ite_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_items_ite_sw_BeforeShow @22-23B1796E
    return $tb_items_ite_sw_BeforeShow;
}
//End Close tb_items_ite_sw_BeforeShow

//tb_items_Alt_ite_id_itemSuperior_BeforeShow @28-F7396342
function tb_items_Alt_ite_id_itemSuperior_BeforeShow(& $sender)
{
    $tb_items_Alt_ite_id_itemSuperior_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_items; //Compatibility
//End tb_items_Alt_ite_id_itemSuperior_BeforeShow

//Custom Code @46-19CB268F
// -------------------------
    global $tb_items, $DBsiges;
	$sItem = $tb_items->Alt_ite_id_itemSuperior->GetValue();
	if (strlen($sItem)>0) $tb_items->Alt_ite_id_itemSuperior->SetValue(CCDLookUp("ite_nombre","tb_items","ite_id=".$sItem, $DBsiges)); 
// -------------------------
//End Custom Code

//Close tb_items_Alt_ite_id_itemSuperior_BeforeShow @28-615ADB96
    return $tb_items_Alt_ite_id_itemSuperior_BeforeShow;
}
//End Close tb_items_Alt_ite_id_itemSuperior_BeforeShow

//tb_items_Alt_ite_sw_BeforeShow @29-528E9C87
function tb_items_Alt_ite_sw_BeforeShow(& $sender)
{
    $tb_items_Alt_ite_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_items; //Compatibility
//End tb_items_Alt_ite_sw_BeforeShow

//Custom Code @48-19CB268F
// -------------------------
    global $tb_items;
	$sEstado = $tb_items->Alt_ite_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_items->Alt_ite_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close tb_items_Alt_ite_sw_BeforeShow @29-47FB8B28
    return $tb_items_Alt_ite_sw_BeforeShow;
}
//End Close tb_items_Alt_ite_sw_BeforeShow


?>

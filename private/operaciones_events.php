<?php
//BindEvents Method @1-4A3453B7
function BindEvents()
{
    global $tb_operaciones;
    $tb_operaciones->item->CCSEvents["BeforeShow"] = "tb_operaciones_item_BeforeShow";
    $tb_operaciones->tb_operaciones_TotalRecords->CCSEvents["BeforeShow"] = "tb_operaciones_tb_operaciones_TotalRecords_BeforeShow";
    $tb_operaciones->ope_ite_idOperar->CCSEvents["BeforeShow"] = "tb_operaciones_ope_ite_idOperar_BeforeShow";
    $tb_operaciones->ope_trimestre->CCSEvents["BeforeShow"] = "tb_operaciones_ope_trimestre_BeforeShow";
    $tb_operaciones->ope_sw->CCSEvents["BeforeShow"] = "tb_operaciones_ope_sw_BeforeShow";
    $tb_operaciones->Alt_ope_ite_idOperar->CCSEvents["BeforeShow"] = "tb_operaciones_Alt_ope_ite_idOperar_BeforeShow";
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

//tb_operaciones_ope_trimestre_BeforeShow @69-9E2432BA
function tb_operaciones_ope_trimestre_BeforeShow(& $sender)
{
    $tb_operaciones_ope_trimestre_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_ope_trimestre_BeforeShow

//Custom Code @71-2A29BDB7
// -------------------------
     $valTrimestre = $tb_operaciones->ope_trimestre->GetValue();
	 if ($valTrimestre==1) $tb_operaciones->ope_trimestre->SetValue("SI");
	 else if ($valTrimestre==0) $tb_operaciones->ope_trimestre->SetValue("NO");
// -------------------------
//End Custom Code

//Close tb_operaciones_ope_trimestre_BeforeShow @69-46D7DF69
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

//tb_operaciones_Alt_ope_trimestre_BeforeShow @70-42F71620
function tb_operaciones_Alt_ope_trimestre_BeforeShow(& $sender)
{
    $tb_operaciones_Alt_ope_trimestre_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_operaciones; //Compatibility
//End tb_operaciones_Alt_ope_trimestre_BeforeShow

//Custom Code @72-2A29BDB7
// -------------------------
     $valTrimestre = $tb_operaciones->Alt_ope_trimestre->GetValue();
	 if ($valTrimestre==1) $tb_operaciones->Alt_ope_trimestre->SetValue("SI");
	 else if ($valTrimestre==0) $tb_operaciones->Alt_ope_trimestre->SetValue("NO");

// -------------------------
//End Custom Code

//Close tb_operaciones_Alt_ope_trimestre_BeforeShow @70-527418D8
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

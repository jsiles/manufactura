<?php
//BindEvents Method @1-B72621E3
function BindEvents()
{
    global $tb_trimestres;
    $tb_trimestres->tb_trimestres_TotalRecords->CCSEvents["BeforeShow"] = "tb_trimestres_tb_trimestres_TotalRecords_BeforeShow";
    $tb_trimestres->tri_sw->CCSEvents["BeforeShow"] = "tb_trimestres_tri_sw_BeforeShow";
    $tb_trimestres->Alt_tri_sw->CCSEvents["BeforeShow"] = "tb_trimestres_Alt_tri_sw_BeforeShow";
}
//End BindEvents Method

//tb_trimestres_tb_trimestres_TotalRecords_BeforeShow @8-183F2D21
function tb_trimestres_tb_trimestres_TotalRecords_BeforeShow(& $sender)
{
    $tb_trimestres_tb_trimestres_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_trimestres; //Compatibility
//End tb_trimestres_tb_trimestres_TotalRecords_BeforeShow

//Retrieve number of records @9-83722D51
    $Container->tb_trimestres_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_trimestres_tb_trimestres_TotalRecords_BeforeShow @8-C70215C9
    return $tb_trimestres_tb_trimestres_TotalRecords_BeforeShow;
}
//End Close tb_trimestres_tb_trimestres_TotalRecords_BeforeShow

//tb_trimestres_tri_sw_BeforeShow @16-1799705E
function tb_trimestres_tri_sw_BeforeShow(& $sender)
{
    $tb_trimestres_tri_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_trimestres; //Compatibility
//End tb_trimestres_tri_sw_BeforeShow

//Custom Code @31-C00782B5
// -------------------------
    global $tb_trimestres;
	$sEstado = $tb_trimestres->tri_sw->GetValue();
	if ($sEstado=='A') $sEstado = "Activo";
	elseif ($sEstado=='I') $sEstado = "Inactivo";
	$tb_trimestres->tri_sw->SetValue($sEstado); 
// -------------------------
//End Custom Code

//Close tb_trimestres_tri_sw_BeforeShow @16-D81911CC
    return $tb_trimestres_tri_sw_BeforeShow;
}
//End Close tb_trimestres_tri_sw_BeforeShow

//tb_trimestres_Alt_tri_sw_BeforeShow @20-4BCDF735
function tb_trimestres_Alt_tri_sw_BeforeShow(& $sender)
{
    $tb_trimestres_Alt_tri_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_trimestres; //Compatibility
//End tb_trimestres_Alt_tri_sw_BeforeShow

//Custom Code @32-C00782B5
// -------------------------
    global $tb_trimestres;
    global $tb_trimestres;
	$sEstado = $tb_trimestres->Alt_tri_sw->GetValue();
	if ($sEstado=='A') $sEstado = "Activo";
	elseif ($sEstado=='I') $sEstado = "Inactivo";
	$tb_trimestres->Alt_tri_sw->SetValue($sEstado); 
// -------------------------
//End Custom Code

//Close tb_trimestres_Alt_tri_sw_BeforeShow @20-CAFF7BCA
    return $tb_trimestres_Alt_tri_sw_BeforeShow;
}
//End Close tb_trimestres_Alt_tri_sw_BeforeShow


?>

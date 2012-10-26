<?php
//BindEvents Method @1-087B22AC
function BindEvents()
{
    global $tb_mercados;
    $tb_mercados->tb_mercados_TotalRecords->CCSEvents["BeforeShow"] = "tb_mercados_tb_mercados_TotalRecords_BeforeShow";
    $tb_mercados->mer_sw->CCSEvents["BeforeShow"] = "tb_mercados_mer_sw_BeforeShow";
    $tb_mercados->Alt_mer_sw->CCSEvents["BeforeShow"] = "tb_mercados_Alt_mer_sw_BeforeShow";
}
//End BindEvents Method

//tb_mercados_tb_mercados_TotalRecords_BeforeShow @8-894531FD
function tb_mercados_tb_mercados_TotalRecords_BeforeShow(& $sender)
{
    $tb_mercados_tb_mercados_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_mercados; //Compatibility
//End tb_mercados_tb_mercados_TotalRecords_BeforeShow

//Retrieve number of records @9-514A76A8
    $Container->tb_mercados_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_mercados_tb_mercados_TotalRecords_BeforeShow @8-A26A2232
    return $tb_mercados_tb_mercados_TotalRecords_BeforeShow;
}
//End Close tb_mercados_tb_mercados_TotalRecords_BeforeShow

//tb_mercados_mer_sw_BeforeShow @16-FFF4EC23
function tb_mercados_mer_sw_BeforeShow(& $sender)
{
    $tb_mercados_mer_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_mercados; //Compatibility
//End tb_mercados_mer_sw_BeforeShow

//Custom Code @34-9B534A36
// -------------------------
    global $tb_mercados;
	$sEstado = $tb_mercados->mer_sw->GetValue();
	if ($sEstado=='A') $sEstado = "Activo";
	elseif ($sEstado=='I') $sEstado = "Inactivo";
	$tb_mercados->mer_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_mercados_mer_sw_BeforeShow @16-4F7C0310
    return $tb_mercados_mer_sw_BeforeShow;
}
//End Close tb_mercados_mer_sw_BeforeShow

//tb_mercados_Alt_mer_sw_BeforeShow @20-50F1C16D
function tb_mercados_Alt_mer_sw_BeforeShow(& $sender)
{
    $tb_mercados_Alt_mer_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_mercados; //Compatibility
//End tb_mercados_Alt_mer_sw_BeforeShow

//Custom Code @35-9B534A36
// -------------------------
    global $tb_mercados;
	$sEstado = $tb_mercados->Alt_mer_sw->GetValue();
	if ($sEstado=='A') $sEstado = "Activo";
	elseif ($sEstado=='I') $sEstado = "Inactivo";
	$tb_mercados->Alt_mer_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_mercados_Alt_mer_sw_BeforeShow @20-2CF72CFA
    return $tb_mercados_Alt_mer_sw_BeforeShow;
}
//End Close tb_mercados_Alt_mer_sw_BeforeShow


?>

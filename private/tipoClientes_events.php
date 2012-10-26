<?php
//BindEvents Method @1-B092339A
function BindEvents()
{
    global $tb_tipoclientes;
    $tb_tipoclientes->tb_tipoclientes_TotalRecords->CCSEvents["BeforeShow"] = "tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow";
    $tb_tipoclientes->cli_sw->CCSEvents["BeforeShow"] = "tb_tipoclientes_cli_sw_BeforeShow";
    $tb_tipoclientes->Alt_cli_sw->CCSEvents["BeforeShow"] = "tb_tipoclientes_Alt_cli_sw_BeforeShow";
}
//End BindEvents Method

//tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow @8-E075E977
function tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow(& $sender)
{
    $tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_tipoclientes; //Compatibility
//End tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow

//Retrieve number of records @9-CD9C5386
    $Container->tb_tipoclientes_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow @8-AA15E2F9
    return $tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow;
}
//End Close tb_tipoclientes_tb_tipoclientes_TotalRecords_BeforeShow

//tb_tipoclientes_cli_sw_BeforeShow @16-BA724E55
function tb_tipoclientes_cli_sw_BeforeShow(& $sender)
{
    $tb_tipoclientes_cli_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_tipoclientes; //Compatibility
//End tb_tipoclientes_cli_sw_BeforeShow

//Custom Code @33-32C01B84
// -------------------------
    global $tb_tipoclientes;
	$sEstado = $tb_tipoclientes->cli_sw->GetValue();
	if ($sEstado == 'A') $sEstado="Activo";
	elseif ($sEstado == 'I') $sEstado="Inactivo";
	$tb_tipoclientes->cli_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close tb_tipoclientes_cli_sw_BeforeShow @16-B2412FFC
    return $tb_tipoclientes_cli_sw_BeforeShow;
}
//End Close tb_tipoclientes_cli_sw_BeforeShow

//tb_tipoclientes_Alt_cli_sw_BeforeShow @20-6221E451
function tb_tipoclientes_Alt_cli_sw_BeforeShow(& $sender)
{
    $tb_tipoclientes_Alt_cli_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_tipoclientes; //Compatibility
//End tb_tipoclientes_Alt_cli_sw_BeforeShow

//Custom Code @34-32C01B84
// -------------------------
    global $tb_tipoclientes;
    global $tb_tipoclientes;
	$sEstado = $tb_tipoclientes->Alt_cli_sw->GetValue();
	if ($sEstado == 'A') $sEstado="Activo";
	elseif ($sEstado == 'I') $sEstado="Inactivo";
	$tb_tipoclientes->Alt_cli_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_tipoclientes_Alt_cli_sw_BeforeShow @20-B058C218
    return $tb_tipoclientes_Alt_cli_sw_BeforeShow;
}
//End Close tb_tipoclientes_Alt_cli_sw_BeforeShow


?>

<?php
//BindEvents Method @1-6468B27B
function BindEvents()
{
    global $tb_productos;
    $tb_productos->tb_productos_TotalRecords->CCSEvents["BeforeShow"] = "tb_productos_tb_productos_TotalRecords_BeforeShow";
    $tb_productos->pro_sw->CCSEvents["BeforeShow"] = "tb_productos_pro_sw_BeforeShow";
    $tb_productos->Alt_pro_sw->CCSEvents["BeforeShow"] = "tb_productos_Alt_pro_sw_BeforeShow";
}
//End BindEvents Method

//tb_productos_tb_productos_TotalRecords_BeforeShow @8-D7434C3D
function tb_productos_tb_productos_TotalRecords_BeforeShow(& $sender)
{
    $tb_productos_tb_productos_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_productos; //Compatibility
//End tb_productos_tb_productos_TotalRecords_BeforeShow

//Retrieve number of records @9-CEACF1B7
    $Container->tb_productos_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_productos_tb_productos_TotalRecords_BeforeShow @8-F15723AC
    return $tb_productos_tb_productos_TotalRecords_BeforeShow;
}
//End Close tb_productos_tb_productos_TotalRecords_BeforeShow

//tb_productos_pro_sw_BeforeShow @16-5680C3B0
function tb_productos_pro_sw_BeforeShow(& $sender)
{
    $tb_productos_pro_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_productos; //Compatibility
//End tb_productos_pro_sw_BeforeShow

//Custom Code @34-132BBD11
// -------------------------
    global $tb_productos;
	$sEstado = $tb_productos->pro_sw->GetValue();
	if ($sEstado == 'A') $sEstado = "Activo";
	elseif ($sEstado == 'I') $sEstado = "Inactivo";
	$tb_productos->pro_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close tb_productos_pro_sw_BeforeShow @16-CCE11F46
    return $tb_productos_pro_sw_BeforeShow;
}
//End Close tb_productos_pro_sw_BeforeShow

//tb_productos_Alt_pro_sw_BeforeShow @20-69574A7D
function tb_productos_Alt_pro_sw_BeforeShow(& $sender)
{
    $tb_productos_Alt_pro_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_productos; //Compatibility
//End tb_productos_Alt_pro_sw_BeforeShow

//Custom Code @35-132BBD11
// -------------------------
    global $tb_productos;
    global $tb_productos;
	$sEstado = $tb_productos->Alt_pro_sw->GetValue();
	if ($sEstado == 'A') $sEstado = "Activo";
	elseif ($sEstado == 'I') $sEstado = "Inactivo";
	$tb_productos->Alt_pro_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_productos_Alt_pro_sw_BeforeShow @20-3C07A1E8
    return $tb_productos_Alt_pro_sw_BeforeShow;
}
//End Close tb_productos_Alt_pro_sw_BeforeShow


?>

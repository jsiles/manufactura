<?php
//BindEvents Method @1-D6A5D35F
function BindEvents()
{
    global $tb_atributos;
    $tb_atributos->tb_atributos_TotalRecords->CCSEvents["BeforeShow"] = "tb_atributos_tb_atributos_TotalRecords_BeforeShow";
    $tb_atributos->atr_tipoValor->CCSEvents["BeforeShow"] = "tb_atributos_atr_tipoValor_BeforeShow";
    $tb_atributos->atr_sw->CCSEvents["BeforeShow"] = "tb_atributos_atr_sw_BeforeShow";
    $tb_atributos->Alt_atr_tipoValor->CCSEvents["BeforeShow"] = "tb_atributos_Alt_atr_tipoValor_BeforeShow";
    $tb_atributos->Alt_atr_sw->CCSEvents["BeforeShow"] = "tb_atributos_Alt_atr_sw_BeforeShow";
}
//End BindEvents Method

//tb_atributos_tb_atributos_TotalRecords_BeforeShow @10-B50C4514
function tb_atributos_tb_atributos_TotalRecords_BeforeShow(& $sender)
{
    $tb_atributos_tb_atributos_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_atributos; //Compatibility
//End tb_atributos_tb_atributos_TotalRecords_BeforeShow

//Retrieve number of records @11-F01E2A44
    $Container->tb_atributos_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_atributos_tb_atributos_TotalRecords_BeforeShow @10-763FA387
    return $tb_atributos_tb_atributos_TotalRecords_BeforeShow;
}
//End Close tb_atributos_tb_atributos_TotalRecords_BeforeShow

//tb_atributos_atr_tipoValor_BeforeShow @21-26814071
function tb_atributos_atr_tipoValor_BeforeShow(& $sender)
{
    $tb_atributos_atr_tipoValor_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_atributos; //Compatibility
//End tb_atributos_atr_tipoValor_BeforeShow

//Custom Code @41-F4688DD2
// -------------------------
    global $tb_atributos;
	$sAtributo = $tb_atributos->atr_tipoValor->GetValue();
	if ($sAtributo=='P') $sAtributo="Porcentual";
	elseif ($sAtributo=='Q') $sAtributo="Cantidad";
	$tb_atributos->atr_tipoValor->SetValue($sAtributo);
// -------------------------
//End Custom Code

//Close tb_atributos_atr_tipoValor_BeforeShow @21-F5902F33
    return $tb_atributos_atr_tipoValor_BeforeShow;
}
//End Close tb_atributos_atr_tipoValor_BeforeShow

//tb_atributos_atr_sw_BeforeShow @22-3F8AB042
function tb_atributos_atr_sw_BeforeShow(& $sender)
{
    $tb_atributos_atr_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_atributos; //Compatibility
//End tb_atributos_atr_sw_BeforeShow

//Custom Code @43-F4688DD2
// -------------------------
    global $tb_atributos;
    $sEstado = $tb_atributos->atr_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_atributos->atr_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_atributos_atr_sw_BeforeShow @22-B12B9E14
    return $tb_atributos_atr_sw_BeforeShow;
}
//End Close tb_atributos_atr_sw_BeforeShow

//tb_atributos_Alt_atr_tipoValor_BeforeShow @26-711F67F0
function tb_atributos_Alt_atr_tipoValor_BeforeShow(& $sender)
{
    $tb_atributos_Alt_atr_tipoValor_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_atributos; //Compatibility
//End tb_atributos_Alt_atr_tipoValor_BeforeShow

//Custom Code @42-F4688DD2
// -------------------------
    global $tb_atributos;
	$sAtributo = $tb_atributos->Alt_atr_tipoValor->GetValue();
	if ($sAtributo=='P') $sAtributo="Porcentual";
	elseif ($sAtributo=='Q') $sAtributo="Cantidad";
	$tb_atributos->Alt_atr_tipoValor->SetValue($sAtributo);
// -------------------------
//End Custom Code

//Close tb_atributos_Alt_atr_tipoValor_BeforeShow @26-24677B38
    return $tb_atributos_Alt_atr_tipoValor_BeforeShow;
}
//End Close tb_atributos_Alt_atr_tipoValor_BeforeShow

//tb_atributos_Alt_atr_sw_BeforeShow @27-30CB829F
function tb_atributos_Alt_atr_sw_BeforeShow(& $sender)
{
    $tb_atributos_Alt_atr_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_atributos; //Compatibility
//End tb_atributos_Alt_atr_sw_BeforeShow

//Custom Code @44-F4688DD2
// -------------------------
    global $tb_atributos;
    $sEstado = $tb_atributos->Alt_atr_sw->GetValue();
	if ($sEstado=='A') $sEstado="Activo";
	elseif ($sEstado=='I') $sEstado="Inactivo";
	$tb_atributos->Alt_atr_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_atributos_Alt_atr_sw_BeforeShow @27-66A3B22C
    return $tb_atributos_Alt_atr_sw_BeforeShow;
}
//End Close tb_atributos_Alt_atr_sw_BeforeShow


?>

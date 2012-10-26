<?php
//BindEvents Method @1-88F08FFE
function BindEvents()
{
    global $th_inicio;
    $th_inicio->ini_pro_id->CCSEvents["BeforeShow"] = "th_inicio_ini_pro_id_BeforeShow";
    $th_inicio->ini_mer_id->CCSEvents["BeforeShow"] = "th_inicio_ini_mer_id_BeforeShow";
    $th_inicio->ini_tic_id->CCSEvents["BeforeShow"] = "th_inicio_ini_tic_id_BeforeShow";
    $th_inicio->Alt_ini_pro_id->CCSEvents["BeforeShow"] = "th_inicio_Alt_ini_pro_id_BeforeShow";
    $th_inicio->Alt_ini_mer_id->CCSEvents["BeforeShow"] = "th_inicio_Alt_ini_mer_id_BeforeShow";
    $th_inicio->Alt_ini_tic_id->CCSEvents["BeforeShow"] = "th_inicio_Alt_ini_tic_id_BeforeShow";
}
//End BindEvents Method

//th_inicio_ini_pro_id_BeforeShow @16-B26FC498
function th_inicio_ini_pro_id_BeforeShow(& $sender)
{
    $th_inicio_ini_pro_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_ini_pro_id_BeforeShow

//Custom Code @49-2A29BDB7
// -------------------------
    $valor = $th_inicio->ini_pro_id->GetValue();
	$th_inicio->ini_pro_id->SetValue(CCDLookUp("pro_nombre","tb_productos", "pro_id=".$valor, $DBsiges));

// -------------------------
//End Custom Code

//Close th_inicio_ini_pro_id_BeforeShow @16-09ECC6C5
    return $th_inicio_ini_pro_id_BeforeShow;
}
//End Close th_inicio_ini_pro_id_BeforeShow

//th_inicio_ini_mer_id_BeforeShow @18-C7B56B1A
function th_inicio_ini_mer_id_BeforeShow(& $sender)
{
    $th_inicio_ini_mer_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_ini_mer_id_BeforeShow

//Custom Code @51-2A29BDB7
// -------------------------
    $valor = $th_inicio->ini_mer_id->GetValue();
	$th_inicio->ini_mer_id->SetValue(CCDLookUp("mer_nombre","tb_mercados", "mer_id=".$valor, $DBsiges));

// -------------------------
//End Custom Code

//Close th_inicio_ini_mer_id_BeforeShow @18-E7E701E3
    return $th_inicio_ini_mer_id_BeforeShow;
}
//End Close th_inicio_ini_mer_id_BeforeShow

//th_inicio_ini_tic_id_BeforeShow @20-2AE04076
function th_inicio_ini_tic_id_BeforeShow(& $sender)
{
    $th_inicio_ini_tic_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_ini_tic_id_BeforeShow

//Custom Code @53-2A29BDB7
// -------------------------
    $valor = $th_inicio->ini_tic_id->GetValue();
	$th_inicio->ini_tic_id->SetValue(CCDLookUp("cli_nombre","tb_tipoclientes", "cli_id=".$valor, $DBsiges));

// -------------------------
//End Custom Code

//Close th_inicio_ini_tic_id_BeforeShow @20-A6E88C00
    return $th_inicio_ini_tic_id_BeforeShow;
}
//End Close th_inicio_ini_tic_id_BeforeShow

//th_inicio_Alt_ini_pro_id_BeforeShow @26-07D376E1
function th_inicio_Alt_ini_pro_id_BeforeShow(& $sender)
{
    $th_inicio_Alt_ini_pro_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_Alt_ini_pro_id_BeforeShow

//Custom Code @50-2A29BDB7
// -------------------------
    $valor = $th_inicio->Alt_ini_pro_id->GetValue();
	$th_inicio->Alt_ini_pro_id->SetValue(CCDLookUp("pro_nombre","tb_productos", "pro_id=".$valor, $DBsiges));

// -------------------------
//End Custom Code

//Close th_inicio_Alt_ini_pro_id_BeforeShow @26-02ED1BF8
    return $th_inicio_Alt_ini_pro_id_BeforeShow;
}
//End Close th_inicio_Alt_ini_pro_id_BeforeShow

//th_inicio_Alt_ini_mer_id_BeforeShow @27-CBB106C2
function th_inicio_Alt_ini_mer_id_BeforeShow(& $sender)
{
    $th_inicio_Alt_ini_mer_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_Alt_ini_mer_id_BeforeShow

//Custom Code @52-2A29BDB7
// -------------------------
    $valor = $th_inicio->Alt_ini_mer_id->GetValue();
	$th_inicio->Alt_ini_mer_id->SetValue(CCDLookUp("mer_nombre","tb_mercados", "mer_id=".$valor, $DBsiges));
// -------------------------
//End Custom Code

//Close th_inicio_Alt_ini_mer_id_BeforeShow @27-ECE6DCDE
    return $th_inicio_Alt_ini_mer_id_BeforeShow;
}
//End Close th_inicio_Alt_ini_mer_id_BeforeShow

//th_inicio_Alt_ini_tic_id_BeforeShow @28-FD8F14F8
function th_inicio_Alt_ini_tic_id_BeforeShow(& $sender)
{
    $th_inicio_Alt_ini_tic_id_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $th_inicio, $DBsiges; //Compatibility
//End th_inicio_Alt_ini_tic_id_BeforeShow

//Custom Code @54-2A29BDB7
// -------------------------
    $valor = $th_inicio->Alt_ini_tic_id->GetValue();
	$th_inicio->Alt_ini_tic_id->SetValue(CCDLookUp("cli_nombre","tb_tipoclientes", "cli_id=".$valor, $DBsiges));
// -------------------------
//End Custom Code

//Close th_inicio_Alt_ini_tic_id_BeforeShow @28-ADE9513D
    return $th_inicio_Alt_ini_tic_id_BeforeShow;
}
//End Close th_inicio_Alt_ini_tic_id_BeforeShow


?>

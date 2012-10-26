<?php
//BindEvents Method @1-1E9F7FEC
function BindEvents()
{
    global $tb_usuarios;
    $tb_usuarios->tb_usuarios_TotalRecords->CCSEvents["BeforeShow"] = "tb_usuarios_tb_usuarios_TotalRecords_BeforeShow";
    $tb_usuarios->Imagen->CCSEvents["BeforeShow"] = "tb_usuarios_Imagen_BeforeShow";
    $tb_usuarios->usu_nivel->CCSEvents["BeforeShow"] = "tb_usuarios_usu_nivel_BeforeShow";
    $tb_usuarios->usu_sw->CCSEvents["BeforeShow"] = "tb_usuarios_usu_sw_BeforeShow";
    $tb_usuarios->Alt_Imagen->CCSEvents["BeforeShow"] = "tb_usuarios_Alt_Imagen_BeforeShow";
    $tb_usuarios->Alt_usu_nivel->CCSEvents["BeforeShow"] = "tb_usuarios_Alt_usu_nivel_BeforeShow";
    $tb_usuarios->Alt_usu_sw->CCSEvents["BeforeShow"] = "tb_usuarios_Alt_usu_sw_BeforeShow";
}
//End BindEvents Method

//tb_usuarios_tb_usuarios_TotalRecords_BeforeShow @10-E76925A7
function tb_usuarios_tb_usuarios_TotalRecords_BeforeShow(& $sender)
{
    $tb_usuarios_tb_usuarios_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_tb_usuarios_TotalRecords_BeforeShow

//Retrieve number of records @11-437C72C7
    $Container->tb_usuarios_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_usuarios_tb_usuarios_TotalRecords_BeforeShow @10-2371BBA6
    return $tb_usuarios_tb_usuarios_TotalRecords_BeforeShow;
}
//End Close tb_usuarios_tb_usuarios_TotalRecords_BeforeShow

//tb_usuarios_Imagen_BeforeShow @52-A47BB5F3
function tb_usuarios_Imagen_BeforeShow(& $sender)
{
    $tb_usuarios_Imagen_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_Imagen_BeforeShow

//Custom Code @54-06DD564B
// -------------------------
    global $tb_usuarios;
    $sImagen = $tb_usuarios->Imagen->GetValue();
	$sImagen = "./image/".$sImagen;
	$tb_usuarios->Imagen->SetValue($sImagen);
// -------------------------
//End Custom Code

//Close tb_usuarios_Imagen_BeforeShow @52-FFE60A8B
    return $tb_usuarios_Imagen_BeforeShow;
}
//End Close tb_usuarios_Imagen_BeforeShow

//tb_usuarios_usu_nivel_BeforeShow @25-67DD2383
function tb_usuarios_usu_nivel_BeforeShow(& $sender)
{
    $tb_usuarios_usu_nivel_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_usu_nivel_BeforeShow

//Custom Code @58-06DD564B
// -------------------------
    global $tb_usuarios;
	$sNivel = $tb_usuarios->usu_nivel->GetValue();
	if ($sNivel==1) $sNivel="Consulta";
	elseif ($sNivel==2) $sNivel="Usuario";
	elseif ($sNivel==3) $sNivel="Administrador";
	$tb_usuarios->usu_nivel->SetValue($sNivel);


// -------------------------
//End Custom Code

//Close tb_usuarios_usu_nivel_BeforeShow @25-7479B282
    return $tb_usuarios_usu_nivel_BeforeShow;
}
//End Close tb_usuarios_usu_nivel_BeforeShow

//tb_usuarios_usu_sw_BeforeShow @26-C4D720F1
function tb_usuarios_usu_sw_BeforeShow(& $sender)
{
    $tb_usuarios_usu_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_usu_sw_BeforeShow

//Custom Code @60-06DD564B
// -------------------------
    global $tb_usuarios;
    $sSw = $tb_usuarios->usu_sw->GetValue();
	if ($sSw=='A') $sSw="Activo";
	elseif ($sSw=='I') $sSw="Inactivo";
	$tb_usuarios->usu_sw->SetValue($sSw);
// -------------------------
//End Custom Code

//Close tb_usuarios_usu_sw_BeforeShow @26-5549F5EC
    return $tb_usuarios_usu_sw_BeforeShow;
}
//End Close tb_usuarios_usu_sw_BeforeShow

//tb_usuarios_Alt_Imagen_BeforeShow @53-8FEE78B5
function tb_usuarios_Alt_Imagen_BeforeShow(& $sender)
{
    $tb_usuarios_Alt_Imagen_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_Alt_Imagen_BeforeShow

//Custom Code @57-06DD564B
// -------------------------
    global $tb_usuarios;
    $sAlt_Imagen = $tb_usuarios->Alt_Imagen->GetValue();
	$sAlt_Imagen = "./image/".$sAlt_Imagen;
	$tb_usuarios->Alt_Imagen->SetValue($sAlt_Imagen);
// -------------------------
//End Custom Code

//Close tb_usuarios_Alt_Imagen_BeforeShow @53-0D76A786
    return $tb_usuarios_Alt_Imagen_BeforeShow;
}
//End Close tb_usuarios_Alt_Imagen_BeforeShow

//tb_usuarios_Alt_usu_nivel_BeforeShow @32-4AEA66AE
function tb_usuarios_Alt_usu_nivel_BeforeShow(& $sender)
{
    $tb_usuarios_Alt_usu_nivel_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_Alt_usu_nivel_BeforeShow

//Custom Code @59-06DD564B
// -------------------------
    global $tb_usuarios;
	$sNivel = $tb_usuarios->Alt_usu_nivel->GetValue();
	if ($sNivel==1) $sNivel="Consulta";
	elseif ($sNivel==2) $sNivel="Usuario";
	elseif ($sNivel==3) $sNivel="Administrador";
	$tb_usuarios->Alt_usu_nivel->SetValue($sNivel);

// -------------------------
//End Custom Code

//Close tb_usuarios_Alt_usu_nivel_BeforeShow @32-970770C3
    return $tb_usuarios_Alt_usu_nivel_BeforeShow;
}
//End Close tb_usuarios_Alt_usu_nivel_BeforeShow

//tb_usuarios_Alt_usu_sw_BeforeShow @33-169A76C4
function tb_usuarios_Alt_usu_sw_BeforeShow(& $sender)
{
    $tb_usuarios_Alt_usu_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_usuarios; //Compatibility
//End tb_usuarios_Alt_usu_sw_BeforeShow

//Custom Code @61-06DD564B
// -------------------------
    global $tb_usuarios;
    $sSw = $tb_usuarios->Alt_usu_sw->GetValue();
	if ($sSw=='A') $sSw="Activo";
	elseif ($sSw=='I') $sSw="Inactivo";
	$tb_usuarios->Alt_usu_sw->SetValue($sSw);
// -------------------------
//End Custom Code

//Close tb_usuarios_Alt_usu_sw_BeforeShow @33-A7D958E1
    return $tb_usuarios_Alt_usu_sw_BeforeShow;
}
//End Close tb_usuarios_Alt_usu_sw_BeforeShow


?>

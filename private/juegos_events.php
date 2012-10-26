<?php
//BindEvents Method @1-AAB8C5B5
function BindEvents()
{
    global $tb_juegos;
    $tb_juegos->tb_juegos_TotalRecords->CCSEvents["BeforeShow"] = "tb_juegos_tb_juegos_TotalRecords_BeforeShow";
    $tb_juegos->Imagen->CCSEvents["BeforeShow"] = "tb_juegos_Imagen_BeforeShow";
    $tb_juegos->jue_sw->CCSEvents["BeforeShow"] = "tb_juegos_jue_sw_BeforeShow";
    $tb_juegos->Alt_Imagen->CCSEvents["BeforeShow"] = "tb_juegos_Alt_Imagen_BeforeShow";
    $tb_juegos->Alt_jue_sw->CCSEvents["BeforeShow"] = "tb_juegos_Alt_jue_sw_BeforeShow";
}
//End BindEvents Method

//tb_juegos_tb_juegos_TotalRecords_BeforeShow @8-2CC53EFC
function tb_juegos_tb_juegos_TotalRecords_BeforeShow(& $sender)
{
    $tb_juegos_tb_juegos_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_juegos; //Compatibility
//End tb_juegos_tb_juegos_TotalRecords_BeforeShow

//Retrieve number of records @9-5E12C07B
    $Container->tb_juegos_TotalRecords->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close tb_juegos_tb_juegos_TotalRecords_BeforeShow @8-2B2760EE
    return $tb_juegos_tb_juegos_TotalRecords_BeforeShow;
}
//End Close tb_juegos_tb_juegos_TotalRecords_BeforeShow

//tb_juegos_Imagen_BeforeShow @44-963892F0
function tb_juegos_Imagen_BeforeShow(& $sender)
{
    $tb_juegos_Imagen_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_juegos; //Compatibility
//End tb_juegos_Imagen_BeforeShow

//Custom Code @47-E269DA5E
// -------------------------
    global $tb_juegos;
		$sImagen = $tb_juegos->Imagen->GetValue();
		$sImagen = "./image/".$sImagen;
		$tb_juegos->Imagen->SetValue($sImagen);
// -------------------------
//End Custom Code

//Close tb_juegos_Imagen_BeforeShow @44-7746E6C8
    return $tb_juegos_Imagen_BeforeShow;
}
//End Close tb_juegos_Imagen_BeforeShow

//tb_juegos_jue_sw_BeforeShow @22-42BFCD4C
function tb_juegos_jue_sw_BeforeShow(& $sender)
{
    $tb_juegos_jue_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_juegos; //Compatibility
//End tb_juegos_jue_sw_BeforeShow

//Custom Code @48-E269DA5E
// -------------------------
    global $tb_juegos;
	
	$sEstado = $tb_juegos->jue_sw->GetValue();
	if ($sEstado=='A') $sEstado='Activo';
	elseif ($sEstado=='I') $sEstado = 'Inactivo';
	$tb_juegos->jue_sw->SetValue($sEstado);

// -------------------------
//End Custom Code

//Close tb_juegos_jue_sw_BeforeShow @22-94199193
    return $tb_juegos_jue_sw_BeforeShow;
}
//End Close tb_juegos_jue_sw_BeforeShow

//tb_juegos_Alt_Imagen_BeforeShow @45-72D8AE10
function tb_juegos_Alt_Imagen_BeforeShow(& $sender)
{
    $tb_juegos_Alt_Imagen_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_juegos; //Compatibility
//End tb_juegos_Alt_Imagen_BeforeShow

//Custom Code @46-E269DA5E
// -------------------------
    global $tb_juegos;

		$sAlt_Imagen = $tb_juegos->Alt_Imagen->GetValue();
		$sAlt_Imagen = "./image/".$sAlt_Imagen;
		$tb_juegos->Alt_Imagen->SetValue($sAlt_Imagen);

// -------------------------
//End Custom Code

//Close tb_juegos_Alt_Imagen_BeforeShow @45-694BCE6A
    return $tb_juegos_Alt_Imagen_BeforeShow;
}
//End Close tb_juegos_Alt_Imagen_BeforeShow

//tb_juegos_Alt_jue_sw_BeforeShow @29-ECA1E70B
function tb_juegos_Alt_jue_sw_BeforeShow(& $sender)
{
    $tb_juegos_Alt_jue_sw_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $tb_juegos; //Compatibility
//End tb_juegos_Alt_jue_sw_BeforeShow

//Custom Code @49-E269DA5E
// -------------------------
    global $tb_juegos;
	$sEstado = $tb_juegos->Alt_jue_sw->GetValue();
	if ($sEstado=='A') $sEstado='Activo';
	elseif ($sEstado=='I') $sEstado = 'Inactivo';
	$tb_juegos->Alt_jue_sw->SetValue($sEstado);
// -------------------------
//End Custom Code

//Close tb_juegos_Alt_jue_sw_BeforeShow @29-8A14B931
    return $tb_juegos_Alt_jue_sw_BeforeShow;
}
//End Close tb_juegos_Alt_jue_sw_BeforeShow


?>

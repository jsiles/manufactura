<?php
include ('./common.php');
$filename = "header.php";
$template_filename = "header.html";
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);
header_show();
$tpl->pparse("main", false);
function header_show(){
global $tpl,$db;
$user_id = get_session("cliID");
if (!$user_id) 
{
    $user_id = get_param("user_id");
    $sSQL = "select usu_nombre, usu_imagen from tb_usuarios where usu_id=$user_id";
    $db->query($sSQL);
    $db->next_record();
    $nombre = $db->f("usu_nombre");
    $imagen = $db->f("usu_imagen");
    $fecha = date("d/m/Y");
    $jue_id = get_param("id");
    $imagen2 = get_db_value("select jue_imagen from tb_juegos where jue_id=$jue_id");
            $tpl->set_var("logo", $imagen2);
            $tpl->set_var("nombre", $nombre);
            $tpl->set_var("fecha", $fecha);
            $tpl->set_var("imagen", $imagen);
}
else {
$sSQL = "select usu_nombre, usu_imagen from tb_usuarios where usu_id=$user_id";
$db->query($sSQL);
$db->next_record();
$nombre = $db->f("usu_nombre");
$imagen = $db->f("usu_imagen");
$fecha = date("d/m/Y h:m:s");
$jue_id = get_param("id");
$imagen2 = get_db_value("select jue_imagen from tb_juegos where jue_id=$jue_id");
        $tpl->set_var("logo", $imagen2);
		$tpl->set_var("nombre", $nombre);
		$tpl->set_var("fecha", $fecha);
		$tpl->set_var("imagen", $imagen);
}
}
?>

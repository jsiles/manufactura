<?php
include_once("common.php");
session_start();
check_security(1);
/*$filename = "index.php";
$template_filename = "index.html";
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);
index_show();
$tpl->pparse("main", false);*/
//function index_show ()
//{
//	global $db, $tpl;
	$user_id = get_session("cliID");
	//echo $user_id."##";
    $ele_id = get_session("SSele_id");
    if ($ele_id=='') $ele_id=1;
	$sSQL = "select usu_jue_id as id from tb_usuarios where usu_id=$user_id ";
	$i=0;
	$sJuego = get_db_value($sSQL);
    $apl = get_session("SSapl");
    if ($apl=='') $apl=1;
    set_session("id",$sJuego);
	if ($sJuego) {
/*        $tpl->set_var("cuerpo","datos2.php?id=$sJuego&apl=$apl&ele_id=$ele_id&");
        $tpl->set_var("menu","menu.php?id=$sJuego&apl=$apl&ele_id=$ele_id&");
        $tpl->set_var("header","header.php?id=$sJuego&apl=$apl&ele_id=$ele_id&user_id=$user_id");*/
        
        //$cuerpo = "datos2.php?id=$sJuego&apl=$apl&ele_id=$ele_id&";
        $cuerpo="investigaciones.php?id=$sJuego&dat_periodo=1&apl=1";
		$menu = "menu.php?id=$sJuego&apl=$apl&ele_id=$ele_id&";
        $header = "header.php?id=$sJuego&apl=$apl&ele_id=$ele_id&user_id=$user_id&";
		//header("Location: datos2.php?id=$sJuego&apl=1");
	} else header("Location: logout.php");
//}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SIGES</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<FRAMESET rows="92,*" border="0">
<frame name="header" src="<?=$header?>" scrolling="no">
    <FRAMESET cols="230,*" border="0">
        <frame name="menu" src="<?=$menu?>" scrolling="no">   
        <frame name="cuerpo" src="<?=$cuerpo?>">   
    </FRAMESET> 
</FRAMESET>
    <NOFRAMES>
    <BODY>
    </BODY>
    </NOFRAMES>
</html>

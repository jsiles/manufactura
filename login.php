<?php
include ("common.php");
session_start();
$filename = "login.php";
$template_filename = "login.html";
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$sErr = "";
switch ($sForm) {
  case "ingreso":
    ingreso_action($sAction);
  break;
}
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
$tpl->set_var("FileName", $filename);
ingreso_show();
$tpl->pparse("main", false);
function ingreso_action($sAction)
{
  global $db;
  global $tpl;
  global $sErr;
  global $filename;
  switch(strtolower($sAction))
  {
    case "login":
      $sLogin = get_param("Login");
      $sPassword = get_param("Password");
      $db->query("SELECT usu_id,usu_nivel FROM tb_usuarios WHERE usu_id!=1 and usu_login =" . tosql($sLogin, "Text") . " AND usu_password=" . tosql($sPassword, "Text"));
	  //echo "SELECT usu_id,usu_nivel FROM tb_usuarios WHERE usu_id!=1 and usu_login =" . tosql($sLogin, "Text") . " AND usu_password=" . tosql($sPassword, "Text");
      $is_passed = $db->next_record();
	  if($is_passed)
      {
     	set_session("cliID", $db->f("usu_id"));
        set_session("UserRights", $db->f("usu_nivel"));
        $sPage = get_param("ret_page");
        //echo $is_passed.$sPage;
		//echo get_session("cliID");
    	//echo get_session("UserRights");
    	//die;
		if (strlen($sPage))
        {
		  //echo $sPage;
          header("Location: " . $sPage);
          die;
		  exit;
        }
        else
        {
          header("Location: index.php");
          exit;
        }
      }
      else
      {
        $sErr = "La identificación o la palabra clave es incorrecta.";
        //$tpl->set_var("Err",$sErr);
      }
    break;
    case "logout":
      session_unregister("cliID");
      session_unregister("UserRights");
      if(strlen(get_param("ret_page")))
      {
        header("Location:" . $filename . "?ret_page=" . urlencode(get_param("ret_page")));
        exit;
      }
      else
      {
        header("Location:" . $filename);
        exit;
      }
    break;
  }
}
function ingreso_show()
{
  global $tpl;
  global $sErr;
  global $db;
  $sFormTitle = "Ingreso al Sistema";
  $sFormName = "login";
  $sActionFileName = "login.php";
  $sRetPage = get_param("ret_page");
  $tpl->set_var("ret_page" , $sRetPage);
  $tpl->set_var("HTMLFormName" , $sFormName);
  $tpl->set_var("Action",$sActionFileName);


  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("querystring", get_param("querystring"));
  $tpl->set_var("ret_page", get_param("ret_page"));
  if(get_session("cliID") == "")
  {
    if( $sErr == "")
      $tpl->set_var("Error", "");
    else
    {
      $tpl->set_var("Error", $sErr);
      $tpl->parse("Error", false);
    }
  }
  else
  {
    $db->query("SELECT usu_login FROM tb_usuarios WHERE usu_id=". get_session("cliID"));
    $db->next_record();
    $tpl->set_var("Error", "Usuario ya registrado &nbsp;&nbsp;<a href=\"logout.php\">Salir</a>");
    $tpl->set_var("UserID", $db->f("usu_id"));
  }
  $tpl->parse("Login", false);
}
?>

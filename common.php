<?php
error_reporting (E_ALL ^ E_NOTICE);
include("./template.php");
include("./db_mysql.inc");
include("private/config_mysql.php");
define("DATABASE_NAME", DB_NAME);
define("DATABASE_USER", DB_USER);
define("DATABASE_PASSWORD", DB_PASSWORD); 
define("DATABASE_HOST", DB_HOST);
@session_start();
// Database Initialize
$db = new DB_Sql();
$db->Database = DATABASE_NAME;
$db->User     = DATABASE_USER;
$db->Password = DATABASE_PASSWORD;
$db->Host     = DATABASE_HOST;

$db1 = new DB_Sql();
$db1->Database = DATABASE_NAME;
$db1->User     = DATABASE_USER;
$db1->Password = DATABASE_PASSWORD;
$db1->Host     = DATABASE_HOST;


$db2 = new DB_Sql();
$db2->Database = DATABASE_NAME;
$db2->User     = DATABASE_USER;
$db2->Password = DATABASE_PASSWORD;
$db2->Host     = DATABASE_HOST;

$db3 = new DB_Sql();
$db3->Database = DATABASE_NAME;
$db3->User     = DATABASE_USER;
$db3->Password = DATABASE_PASSWORD;
$db3->Host     = DATABASE_HOST;

$db4 = new DB_Sql();
$db4->Database = DATABASE_NAME;
$db4->User     = DATABASE_USER;
$db4->Password = DATABASE_PASSWORD;
$db4->Host     = DATABASE_HOST;
// segrec Connection end
$app_path = ".";
$header_filename = "header.html";

//===============================
// Common functions
//-------------------------------
// Convert non-standard characters to HTML
//-------------------------------
function tohtml($strValue)
{
  return htmlspecialchars($strValue);
}

//-------------------------------
// Convert value to URL
//-------------------------------
function tourl($strValue)
{
  return urlencode($strValue);
}

//-------------------------------
// Obtain specific URL Parameter from URL string
//-------------------------------
function get_param($param_name)
{
  global $_POST;
  global $_GET;

  $param_value = "";
  if(isset($_POST[$param_name]))
    $param_value = $_POST[$param_name];
  else if(isset($_GET[$param_name]))
    $param_value = $_GET[$param_name];

  return $param_value;
}

function get_session($parameter_name)
{
 global $_SESSION;	
 return isset($_SESSION[$parameter_name]) ? $_SESSION[$parameter_name] : "";
}

function set_session($param_name, $param_value)
{
  global $_SESSION;
  $_SESSION[$param_name] = $param_value;
}

function is_number($string_value)
{
  if(is_numeric($string_value) || !strlen($string_value))
    return true;
  else
    return false;
}

//-------------------------------
// Convert value for use with SQL statament
//-------------------------------
function tosql($value, $type)
{
  if(!strlen($value))
    return "NULL";
  else
    if($type == "Number")
      return str_replace (",", ".", doubleval($value));
    else
    {
      if(get_magic_quotes_gpc() == 0)
      {
        $value = str_replace("'","''",$value);
        $value = str_replace("\\","\\\\",$value);
      }
      else
      {
        $value = str_replace("\\'","''",$value);
        $value = str_replace("\\\"","\"",$value);
      }

      return "'" . $value . "'";
    }
}

function strip($value)
{
  if(get_magic_quotes_gpc() == 0)
    return $value;
  else
    return stripslashes($value);
}

function db_fill_array($sql_query)
{
  global $db;
  $db_fill = new DB_Sql();
  $db_fill->Database = $db->Database;
  $db_fill->User     = $db->User;
  $db_fill->Password = $db->Password;
  $db_fill->Host     = $db->Host;

  $db_fill->query($sql_query);
  if ($db_fill->next_record())
  {
    do
    {
      $ar_lookup[$db_fill->f(0)] = $db_fill->f(1);
    } while ($db_fill->next_record());
    return $ar_lookup;
  }
  else
    return false;

}

//-------------------------------
// Deprecated function - use get_db_value($sql)
//-------------------------------
function dlookup($table_name, $field_name, $where_condition)
{
  $sql = "SELECT " . $field_name . " FROM " . $table_name . " WHERE " . $where_condition;
  return get_db_value($sql);
}


//-------------------------------
// Lookup field in the database based on SQL query
//-------------------------------
function get_db_value($sql)
{
  global $db;
  $db_look = new DB_Sql();
  $db_look->Database = $db->Database;
  $db_look->User     = $db->User;
  $db_look->Password = $db->Password;
  $db_look->Host     = $db->Host;

  $db_look->query($sql);
  if($db_look->next_record())
    return $db_look->f(0);
  else
    return "";
}

//-------------------------------
// Obtain Checkbox value depending on field type
//-------------------------------
function get_checkbox_value($value, $checked_value, $unchecked_value, $type)
{
  if(!strlen($value))
    return tosql($unchecked_value, $type);
  else
    return tosql($checked_value, $type);
}

//-------------------------------
// Obtain lookup value from array containing List Of Values
//-------------------------------
function get_lov_value($value, $array)
{
  $return_result = "";

  if(sizeof($array) % 2 != 0)
    $array_length = sizeof($array) - 1;
  else
    $array_length = sizeof($array);
  reset($array);

  for($i = 0; $i < $array_length; $i = $i + 2)
  {
    if($value == $array[$i]) $return_result = $array[$i+1];
  }

  return $return_result;
}

//-------------------------------
// Verify user's security level and redirect to login page if needed
//-------------------------------

function check_security($security_level)
{
  global $_SESSION;
 // print_r($_SESSION);
  if(!$_SESSION["cliID"])
  {
//	  echo 1;die;
    header ("Location: login.php?querystring=" . urlencode(getenv("QUERY_STRING")) . "&ret_page=" . urlencode(getenv("REQUEST_URI")));
    exit;
  }
  else
    if(!$_SESSION["UserRights"] || $_SESSION["UserRights"] < $security_level)
    {
//		echo $_SESSION["UserRights"]."<".$security_level;die;
      header ("Location: logout.php");
      exit;
    }
}

//===============================
//  GlobalFuncs begin
//  GlobalFuncs end
//===============================
function time_diff($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);   

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);   

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    return ($r1-$r2);
}
?>
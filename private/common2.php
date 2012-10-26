<?php
error_reporting (E_ALL ^ E_NOTICE);
include("./template2.php");
include("./db_mysql2.inc");
define("DATABASE_NAME","devzone_manufactura");
define("DATABASE_USER","devzone_manu");
define("DATABASE_PASSWORD","12345");
define("DATABASE_HOST","localhost");
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
  global $HTTP_POST_VARS;
  global $HTTP_GET_VARS;

  $param_value = "";
  if(isset($HTTP_POST_VARS[$param_name]))
    $param_value = $HTTP_POST_VARS[$param_name];
  else if(isset($HTTP_GET_VARS[$param_name]))
    $param_value = $HTTP_GET_VARS[$param_name];

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
  if(!$_SESSION["UserID"])
  {
//	  echo 1;
    header ("Location: login.php?querystring=" . urlencode(getenv("QUERY_STRING")) . "&ret_page=" . urlencode(getenv("REQUEST_URI")));
    exit;
  }
  else
    {
	//	echo $_SESSION["UserRights"]."<".$security_level;
      header ("Location: logout.php");
      exit;
    }
}

//===============================
//  GlobalFuncs begin
//  GlobalFuncs end
//===============================

/*********************************
     function: changeFormatDate                     
     @paramIn: 
                $date    Fecha
                $type    1(d/m/Y a Y/m/d) 
                         2(Y/m/d a d/m/Y)                 
     @paramOut:  
                $datenew  Nueva Fecha formateada
     description:   
         función para formatear una fecha
    *********************************/
	function changeFormatDate($date,$type)
	{
	switch ($type)
		{
		// de d/m/Y a Y-m-d
		case 1: $dateant = explode("/",$date);
				$datenew = $dateant[2] . "-" . $dateant[1] . "-" . $dateant[0];
				break;
		// de Y-m-d a d/m/Y
		case 2: 
				$dateant = explode("-",$date);
				$datenew = $dateant[2] . "/" . $dateant[1] . "/" . $dateant[0];
				break;	
		case 3:
		// de dmY a Y-m-d		
				$day = substr($date,0,2);
				$month = substr($date,2,2);
				$year = substr($date,4,2);
				$datenew = $year . "-" . $month . "-" . $day;
				break;
		case 4:
		// de dmY a d-m-Y		
				$day = substr($date,0,2);
				$month = substr($date,2,2);
				$year = substr($date,4,2);
				$datenew = $day . "/" . $month . "/" . $year;
				break;				
		case 5:
		// de Y-m-d a d de Mes de Y		
				$dateant = explode("-",$date);
				$monthName = admin::getMonthName($dateant[1],'es');
				$datenew = $dateant[2] . " de " . $monthName . " de " . $dateant[0];
				break;	
		case 6:
		// de Y-m-d-w a "Dia literal, d de mes literal"
				$dateant = explode("-",$date);
				$monthName = admin::getMonthName($dateant[1],'es');
				$dayName = admin::getDayName($dateant[3],'es');
				$datenew = $dayName . ", " . $dateant[2] . " de " . $monthName . " de " . $dateant[0];
				break;	
		case 7:
		// de Y-m-d H:i:s a d-m-Y H:i:s
				$year = substr($date,0,4);
				$month = substr($date,5,2);
				$day = substr($date,8,2);
				$time = substr($date,10);
				$datenew = $day. "-" . $month . "-" . $year.$time;
				break;	
		}
	return $datenew;
	}

	function imageName($url) { 

		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ','Á','É','Í','Ó','Ú','Ñ'); 
		$repl = array('a', 'e', 'i', 'o', 'u', 'n','A','E','I','O','U','N'); 
		$url = str_replace ($find, $repl, $url); 
		$url = strtolower($url);
		$find = array(' ', '&', '\r\n', '\n', '+'); 
		$url = str_replace ($find, '-', $url); 
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'); 
		$repl = array('', '-', ''); 
		$url = preg_replace ($find, $repl, $url); 
		return $url; 
		}	
	
?>

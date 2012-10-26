<?php
        
//Client @0-9480F2CC
define("RelativePath", ".");
define("PathToCurrentPage", "");
define("FileName", "");
include(RelativePath . "/Common.php");
$FileEncoding = "UTF-8";
$AllowedFiles = array(
    "DatePicker.js" => "content-type: text/javascript; charset=$FileEncoding",
    "Functions.js" => "content-type: text/javascript; charset=$FileEncoding"
);
$file = CCGetFromGet("file");
if (!array_key_exists($file, $AllowedFiles)) {
    echo " ";
    exit;
}
$file_content = "";
$file_path = RelativePath . "/" . $file;
if (file_exists($file_path)) {
    $fh=fopen($file_path, "rb");
    if (filesize($file_path))
        $file_content = fread($fh, filesize($file_path));
    fclose($fh);
    $file_content = preg_replace("/\\{res:\s*(\w+)\\}/ise", "\$CCSLocales->GetText('\\1')", $file_content);
}
if ($AllowedFiles[$file]) 
    header($AllowedFiles[$file]);
echo $file_content;
//End Client


?>

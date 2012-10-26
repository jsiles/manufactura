<?php
//Include Common Files @1-57D48E36
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "menureportes.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

//Initialize Page @1-2302BE0C
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "menureportes.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-5804AC29

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

$Charset = $Charset ? $Charset : "windows-1252";
if ($Charset)
    header("Content-Type: text/html; charset=" . $Charset);
//End Initialize Objects

//Initialize HTML Template @1-8F4531F3
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
//End Initialize HTML Template

//Go to destination page @1-FBA93089
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    header("Location: " . $Redirect);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-06FF5000
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", implode(array("<center><font face=\"", "Arial\"><small>&#71;&#101", ";ner&#97;ted <!-- SCC", " -->w&#105;&#116;h <!", "-- CCS -->Cod&#101;&#67", ";&#104;ar&#103;&#10", "1; <!-- CCS -->&#83;&#1", "16;u&#100;&#105;&#11", "1;.</small></font></ce", "nter>"), "") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", implode(array("<center><font face=\"", "Arial\"><small>&#71;&#101", ";ner&#97;ted <!-- SCC", " -->w&#105;&#116;h <!", "-- CCS -->Cod&#101;&#67", ";&#104;ar&#103;&#10", "1; <!-- CCS -->&#83;&#1", "16;u&#100;&#105;&#11", "1;.</small></font></ce", "nter>"), "") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= implode(array("<center><font face=\"", "Arial\"><small>&#71;&#101", ";ner&#97;ted <!-- SCC", " -->w&#105;&#116;h <!", "-- CCS -->Cod&#101;&#67", ";&#104;ar&#103;&#10", "1; <!-- CCS -->&#83;&#1", "16;u&#100;&#105;&#11", "1;.</small></font></ce", "nter>"), "");
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-74A7C1E7
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
unset($Tpl);
//End Unload Page


?>

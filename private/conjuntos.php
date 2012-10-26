<?php
//Include Common Files @1-B5ACD015
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "conjuntos.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files



class clsGridth_grupos { //th_grupos class @9-71075049

//Variables @9-5699D380

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    // Grid Controls
    var $StaticControls;
    var $RowControls;
    var $AltRowControls;
    var $IsAltRow;
    var $Sorter_gru_ite_id;
    var $Sorter_gru_sw;
//End Variables

//Class_Initialize Event @9-CBFD04D9
    function clsGridth_grupos($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "th_grupos";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid th_grupos";
        $this->DataSource = new clsth_gruposDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("th_gruposOrder", "");
        $this->SorterDirection = CCGetParam("th_gruposDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "conjuntos.php";
        $this->gru_ite_id = & new clsControl(ccsLabel, "gru_ite_id", "gru_ite_id", ccsInteger, "", CCGetRequestParam("gru_ite_id", ccsGet), $this);
        $this->gru_sw = & new clsControl(ccsLabel, "gru_sw", "gru_sw", ccsText, "", CCGetRequestParam("gru_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "conjuntos.php";
        $this->Alt_gru_ite_id = & new clsControl(ccsLabel, "Alt_gru_ite_id", "Alt_gru_ite_id", ccsInteger, "", CCGetRequestParam("Alt_gru_ite_id", ccsGet), $this);
        $this->Alt_gru_sw = & new clsControl(ccsLabel, "Alt_gru_sw", "Alt_gru_sw", ccsText, "", CCGetRequestParam("Alt_gru_sw", ccsGet), $this);
        $this->th_grupos_TotalRecords = & new clsControl(ccsLabel, "th_grupos_TotalRecords", "th_grupos_TotalRecords", ccsText, "", CCGetRequestParam("th_grupos_TotalRecords", ccsGet), $this);
        $this->Sorter_gru_ite_id = & new clsSorter($this->ComponentName, "Sorter_gru_ite_id", $FileName, $this);
        $this->Sorter_gru_sw = & new clsSorter($this->ComponentName, "Sorter_gru_sw", $FileName, $this);
        $this->th_grupos_Insert = & new clsControl(ccsLink, "th_grupos_Insert", "th_grupos_Insert", ccsText, "", CCGetRequestParam("th_grupos_Insert", ccsGet), $this);
        $this->th_grupos_Insert->Parameters = CCGetQueryString("QueryString", array("gru_jue_id", "ccsForm"));
        $this->th_grupos_Insert->Page = "conjuntos.php";
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
    }
//End Class_Initialize Event

//Initialize Method @9-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @9-C406A0AE
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_gru_ite_id"] = CCGetFromGet("s_gru_ite_id", "");
        $this->DataSource->Parameters["urljue_id"] = CCGetFromGet("jue_id", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if(($ShownRecords < $this->PageSize) && $this->DataSource->next_record())
        {
            do {
                $this->DataSource->SetValues();
                if(!$this->IsAltRow)
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                    $this->Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "gru_jue_id", $this->DataSource->f("gru_jue_id"));
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "gru_ite_id", $this->DataSource->f("gru_ite_id"));
                    $this->gru_ite_id->SetValue($this->DataSource->gru_ite_id->GetValue());
                    $this->gru_sw->SetValue($this->DataSource->gru_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->gru_ite_id->Show();
                    $this->gru_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "gru_jue_id", $this->DataSource->f("gru_jue_id"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "gru_ite_id", $this->DataSource->f("gru_ite_id"));
                    $this->Alt_gru_ite_id->SetValue($this->DataSource->Alt_gru_ite_id->GetValue());
                    $this->Alt_gru_sw->SetValue($this->DataSource->Alt_gru_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_gru_ite_id->Show();
                    $this->Alt_gru_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parseto("AltRow", true, "Row");
                }
                $this->IsAltRow = (!$this->IsAltRow);
                $ShownRecords++;
            } while (($ShownRecords < $this->PageSize) && $this->DataSource->next_record());
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        $this->th_grupos_TotalRecords->Show();
        $this->Sorter_gru_ite_id->Show();
        $this->Sorter_gru_sw->Show();
        $this->th_grupos_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @9-AE0B0DF8
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->gru_ite_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->gru_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_gru_ite_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_gru_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End th_grupos Class @9-FCB6E20C

class clsth_gruposDataSource extends clsDBsiges {  //th_gruposDataSource Class @9-F181D484

//DataSource Variables @9-5E51B4FE
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $gru_ite_id;
    var $gru_sw;
    var $Alt_gru_ite_id;
    var $Alt_gru_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @9-9B0389F4
    function clsth_gruposDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid th_grupos";
        $this->Initialize();
        $this->gru_ite_id = new clsField("gru_ite_id", ccsInteger, "");
        $this->gru_sw = new clsField("gru_sw", ccsText, "");
        $this->Alt_gru_ite_id = new clsField("Alt_gru_ite_id", ccsInteger, "");
        $this->Alt_gru_sw = new clsField("Alt_gru_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @9-FD10CE43
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "gru_ite_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_gru_ite_id" => array("gru_ite_id", ""), 
            "Sorter_gru_sw" => array("gru_sw", "")));
    }
//End SetOrder Method

//Prepare Method @9-AB2BF647
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_gru_ite_id", ccsInteger, "", "", $this->Parameters["urls_gru_ite_id"], "", false);
        $this->wp->AddParameter("2", "urljue_id", ccsInteger, "", "", $this->Parameters["urljue_id"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "gru_ite_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "gru_jue_id", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->wp->Criterion[3] = "gru_sw='A'";
        $this->wp->Criterion[4] = "grp_apl=1";
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]);
    }
//End Prepare Method

//Open Method @9-F24A9461
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM th_grupos";
        $this->SQL = "SELECT *  " .
        "FROM th_grupos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @9-D6CC6730
    function SetValues()
    {
        $this->gru_ite_id->SetDBValue(trim($this->f("gru_ite_id")));
        $this->gru_sw->SetDBValue($this->f("gru_sw"));
        $this->Alt_gru_ite_id->SetDBValue(trim($this->f("gru_ite_id")));
        $this->Alt_gru_sw->SetDBValue($this->f("gru_sw"));
    }
//End SetValues Method

} //End th_gruposDataSource Class @9-FCB6E20C

class clsRecordth_grupos1 { //th_grupos1 Class @32-328E3319

//Variables @32-F607D3A5

    // Public variables
    var $ComponentType = "Record";
    var $ComponentName;
    var $Parent;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $DataSource;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @32-1F624EC1
    function clsRecordth_grupos1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record th_grupos1/Error";
        $this->DataSource = new clsth_grupos1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "th_grupos1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->gru_ite_id = & new clsControl(ccsListBox, "gru_ite_id", "Grupo", ccsInteger, "", CCGetRequestParam("gru_ite_id", $Method), $this);
            $this->gru_ite_id->DSType = dsTable;
            list($this->gru_ite_id->BoundColumn, $this->gru_ite_id->TextColumn, $this->gru_ite_id->DBFormat) = array("ite_id", "ite_nombre", "");
            $this->gru_ite_id->DataSource = new clsDBsiges();
            $this->gru_ite_id->ds = & $this->gru_ite_id->DataSource;
            $this->gru_ite_id->DataSource->SQL = "SELECT *  " .
"FROM tb_items {SQL_Where} {SQL_OrderBy}";
            $this->gru_ite_id->DataSource->wp = new clsSQLParameters();
            $this->gru_ite_id->DataSource->wp->Criterion[1] = "ite_id_itemSuperior is null";
            $this->gru_ite_id->DataSource->wp->Criterion[2] = "ite_sw='A'";
            $this->gru_ite_id->DataSource->wp->Criterion[3] = "ite_apl=1";
            $this->gru_ite_id->DataSource->Where = $this->gru_ite_id->DataSource->wp->opAND(
                 false, $this->gru_ite_id->DataSource->wp->opAND(
                 false, 
                 $this->gru_ite_id->DataSource->wp->Criterion[1], 
                 $this->gru_ite_id->DataSource->wp->Criterion[2]), 
                 $this->gru_ite_id->DataSource->wp->Criterion[3]);
            $this->jue_id = & new clsControl(ccsHidden, "jue_id", "Juego", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->jue_id->Required = true;
            $this->grp_apl = & new clsControl(ccsHidden, "grp_apl", "grp_apl", ccsInteger, "", CCGetRequestParam("grp_apl", $Method), $this);
            $this->gru_sw = & new clsControl(ccsListBox, "gru_sw", "Estado", ccsText, "", CCGetRequestParam("gru_sw", $Method), $this);
            $this->gru_sw->DSType = dsListOfValues;
            $this->gru_sw->Values = array(array("A", "Activo"), array("i", "Inactivo"));
            $this->gru_sw->Required = true;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->gru_ite_id->Value) && !strlen($this->gru_ite_id->Value) && $this->gru_ite_id->Value !== false)
                    $this->gru_ite_id->SetText(0);
                if(!is_array($this->jue_id->Value) && !strlen($this->jue_id->Value) && $this->jue_id->Value !== false)
                    $this->jue_id->SetText(CCGetParam("jue_id"));
                if(!is_array($this->grp_apl->Value) && !strlen($this->grp_apl->Value) && $this->grp_apl->Value !== false)
                    $this->grp_apl->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @32-6D5FE825
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlgru_jue_id"] = CCGetFromGet("gru_jue_id", "");
        $this->DataSource->Parameters["urlgru_ite_id"] = CCGetFromGet("gru_ite_id", "");
    }
//End Initialize Method

//Validate Method @32-09241F82
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->gru_ite_id->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $Validation = ($this->grp_apl->Validate() && $Validation);
        $Validation = ($this->gru_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->gru_ite_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->grp_apl->Errors->Count() == 0);
        $Validation =  $Validation && ($this->gru_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @32-E95542D6
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->gru_ite_id->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->grp_apl->Errors->Count());
        $errors = ($errors || $this->gru_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @32-302E1BAC
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "conjuntos.php" . "?" . CCGetQueryString("QueryString", array("ccsForm", "gru_jue_id"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @32-4EB9E99A
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->gru_ite_id->SetValue($this->gru_ite_id->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->grp_apl->SetValue($this->grp_apl->GetValue());
        $this->DataSource->gru_sw->SetValue($this->gru_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//DeleteRow Method @32-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @32-1DBBC80E
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->gru_ite_id->Prepare();
        $this->gru_sw->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->gru_ite_id->SetValue($this->DataSource->gru_ite_id->GetValue());
                    $this->jue_id->SetValue($this->DataSource->jue_id->GetValue());
                    $this->grp_apl->SetValue($this->DataSource->grp_apl->GetValue());
                    $this->gru_sw->SetValue($this->DataSource->gru_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->gru_ite_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->grp_apl->Errors->ToString());
            $Error = ComposeStrings($Error, $this->gru_sw->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->gru_ite_id->Show();
        $this->jue_id->Show();
        $this->grp_apl->Show();
        $this->gru_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End th_grupos1 Class @32-FCB6E20C

class clsth_grupos1DataSource extends clsDBsiges {  //th_grupos1DataSource Class @32-184E848E

//DataSource Variables @32-5CA0C463
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $gru_ite_id;
    var $jue_id;
    var $grp_apl;
    var $gru_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @32-D19B3041
    function clsth_grupos1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record th_grupos1/Error";
        $this->Initialize();
        $this->gru_ite_id = new clsField("gru_ite_id", ccsInteger, "");
        $this->jue_id = new clsField("jue_id", ccsText, "");
        $this->grp_apl = new clsField("grp_apl", ccsInteger, "");
        $this->gru_sw = new clsField("gru_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @32-E0B9D038
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlgru_jue_id", ccsInteger, "", "", $this->Parameters["urlgru_jue_id"], "", false);
        $this->wp->AddParameter("2", "urlgru_ite_id", ccsInteger, "", "", $this->Parameters["urlgru_ite_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "gru_jue_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "gru_ite_id", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @32-1F4800E5
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM th_grupos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @32-C17F0AA6
    function SetValues()
    {
        $this->gru_ite_id->SetDBValue(trim($this->f("gru_ite_id")));
        $this->jue_id->SetDBValue($this->f("gru_jue_id"));
        $this->grp_apl->SetDBValue(trim($this->f("grp_apl")));
        $this->gru_sw->SetDBValue($this->f("gru_sw"));
    }
//End SetValues Method

//Insert Method @32-D6B3DD4B
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO th_grupos ("
             . "gru_ite_id, "
             . "gru_jue_id, "
             . "grp_apl, "
             . "gru_sw"
             . ") VALUES ("
             . $this->ToSQL($this->gru_ite_id->GetDBValue(), $this->gru_ite_id->DataType) . ", "
             . $this->ToSQL($this->jue_id->GetDBValue(), $this->jue_id->DataType) . ", "
             . $this->ToSQL($this->grp_apl->GetDBValue(), $this->grp_apl->DataType) . ", "
             . $this->ToSQL($this->gru_sw->GetDBValue(), $this->gru_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Delete Method @32-3F40FAEF
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM th_grupos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End th_grupos1DataSource Class @32-FCB6E20C

//Initialize Page @1-9C8095A1
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
$TemplateFileName = "conjuntos.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-7FCB1CA4
include("./conjuntos_events.php");
//End Include events file

//Initialize Objects @1-DEF6F951
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$th_grupos = & new clsGridth_grupos("", $MainPage);
$th_grupos1 = & new clsRecordth_grupos1("", $MainPage);
$MainPage->th_grupos = & $th_grupos;
$MainPage->th_grupos1 = & $th_grupos1;
$th_grupos->Initialize();
$th_grupos1->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

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

//Execute Components @1-98AF16F8
$th_grupos1->Operation();
//End Execute Components

//Go to destination page @1-142E56F7
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($th_grupos);
    unset($th_grupos1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-E3F3410E
$th_grupos->Show();
$th_grupos1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-B0EC2E23
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($th_grupos);
unset($th_grupos1);
unset($Tpl);
//End Unload Page


?>

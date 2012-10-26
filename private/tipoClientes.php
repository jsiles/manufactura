<?php
//Include Common Files @1-C230213C
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "tipoClientes.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtb_tipoclientesSearch { //tb_tipoclientesSearch Class @3-6FE8843D

//Variables @3-F607D3A5

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

//Class_Initialize Event @3-FD793478
    function clsRecordtb_tipoclientesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_tipoclientesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_tipoclientesSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_cli_nombre = & new clsControl(ccsTextBox, "s_cli_nombre", "s_cli_nombre", ccsText, "", CCGetRequestParam("s_cli_nombre", $Method), $this);
            $this->jue_id = & new clsControl(ccsHidden, "jue_id", "jue_id", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->ClearParameters = & new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_cli_nombre", "ccsForm"));
            $this->ClearParameters->Page = "tipoClientes.php";
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->jue_id->Value) && !strlen($this->jue_id->Value) && $this->jue_id->Value !== false)
                    $this->jue_id->SetText(CCGetParam("jue_id"));
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-6B821D06
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_cli_nombre->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_cli_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-49A72E97
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_cli_nombre->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-29CC3C4E
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "tipoClientes.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "tipoClientes.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-29F9C98C
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_cli_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ClearParameters->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->s_cli_nombre->Show();
        $this->jue_id->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tb_tipoclientesSearch Class @3-FCB6E20C

class clsGridtb_tipoclientes { //tb_tipoclientes class @2-798931F5

//Variables @2-1FD3539E

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
    var $Sorter_cli_nombre;
    var $Sorter_cli_sw;
//End Variables

//Class_Initialize Event @2-AD1FC1F3
    function clsGridtb_tipoclientes($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_tipoclientes";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_tipoclientes";
        $this->DataSource = new clstb_tipoclientesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("tb_tipoclientesOrder", "");
        $this->SorterDirection = CCGetParam("tb_tipoclientesDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "tipoClientes.php";
        $this->cli_nombre = & new clsControl(ccsLabel, "cli_nombre", "cli_nombre", ccsText, "", CCGetRequestParam("cli_nombre", ccsGet), $this);
        $this->cli_sw = & new clsControl(ccsLabel, "cli_sw", "cli_sw", ccsText, "", CCGetRequestParam("cli_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "tipoClientes.php";
        $this->Alt_cli_nombre = & new clsControl(ccsLabel, "Alt_cli_nombre", "Alt_cli_nombre", ccsText, "", CCGetRequestParam("Alt_cli_nombre", ccsGet), $this);
        $this->Alt_cli_sw = & new clsControl(ccsLabel, "Alt_cli_sw", "Alt_cli_sw", ccsText, "", CCGetRequestParam("Alt_cli_sw", ccsGet), $this);
        $this->tb_tipoclientes_TotalRecords = & new clsControl(ccsLabel, "tb_tipoclientes_TotalRecords", "tb_tipoclientes_TotalRecords", ccsText, "", CCGetRequestParam("tb_tipoclientes_TotalRecords", ccsGet), $this);
        $this->Sorter_cli_nombre = & new clsSorter($this->ComponentName, "Sorter_cli_nombre", $FileName, $this);
        $this->Sorter_cli_sw = & new clsSorter($this->ComponentName, "Sorter_cli_sw", $FileName, $this);
        $this->tb_tipoclientes_Insert = & new clsControl(ccsLink, "tb_tipoclientes_Insert", "tb_tipoclientes_Insert", ccsText, "", CCGetRequestParam("tb_tipoclientes_Insert", ccsGet), $this);
        $this->tb_tipoclientes_Insert->Parameters = CCGetQueryString("QueryString", array("cli_id", "ccsForm"));
        $this->tb_tipoclientes_Insert->Page = "tipoClientes.php";
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-EBB43E2D
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_cli_nombre"] = CCGetFromGet("s_cli_nombre", "");
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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "cli_id", $this->DataSource->f("cli_id"));
                    $this->cli_nombre->SetValue($this->DataSource->cli_nombre->GetValue());
                    $this->cli_sw->SetValue($this->DataSource->cli_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->cli_nombre->Show();
                    $this->cli_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "cli_id", $this->DataSource->f("cli_id"));
                    $this->Alt_cli_nombre->SetValue($this->DataSource->Alt_cli_nombre->GetValue());
                    $this->Alt_cli_sw->SetValue($this->DataSource->Alt_cli_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_cli_nombre->Show();
                    $this->Alt_cli_sw->Show();
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
        $this->tb_tipoclientes_TotalRecords->Show();
        $this->Sorter_cli_nombre->Show();
        $this->Sorter_cli_sw->Show();
        $this->tb_tipoclientes_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-EB14929F
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->cli_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->cli_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_cli_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_cli_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_tipoclientes Class @2-FCB6E20C

class clstb_tipoclientesDataSource extends clsDBsiges {  //tb_tipoclientesDataSource Class @2-4DEB496F

//DataSource Variables @2-64ACAE26
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $cli_nombre;
    var $cli_sw;
    var $Alt_cli_nombre;
    var $Alt_cli_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-2AA431F2
    function clstb_tipoclientesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_tipoclientes";
        $this->Initialize();
        $this->cli_nombre = new clsField("cli_nombre", ccsText, "");
        $this->cli_sw = new clsField("cli_sw", ccsText, "");
        $this->Alt_cli_nombre = new clsField("Alt_cli_nombre", ccsText, "");
        $this->Alt_cli_sw = new clsField("Alt_cli_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-6E9C994D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "cli_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_cli_nombre" => array("cli_nombre", ""), 
            "Sorter_cli_sw" => array("cli_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-17EC6CD0
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_cli_nombre", ccsText, "", "", $this->Parameters["urls_cli_nombre"], "", false);
        $this->wp->AddParameter("2", "urljue_id", ccsInteger, "", "", $this->Parameters["urljue_id"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "cli_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "cli_jue_id", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-5306F92A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_tipoclientes";
        $this->SQL = "SELECT *  " .
        "FROM tb_tipoclientes {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-30F5794C
    function SetValues()
    {
        $this->cli_nombre->SetDBValue($this->f("cli_nombre"));
        $this->cli_sw->SetDBValue($this->f("cli_sw"));
        $this->Alt_cli_nombre->SetDBValue($this->f("cli_nombre"));
        $this->Alt_cli_sw->SetDBValue($this->f("cli_sw"));
    }
//End SetValues Method

} //End tb_tipoclientesDataSource Class @2-FCB6E20C

class clsRecordtb_tipoclientes1 { //tb_tipoclientes1 Class @22-FCFE097A

//Variables @22-F607D3A5

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

//Class_Initialize Event @22-3492916B
    function clsRecordtb_tipoclientes1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_tipoclientes1/Error";
        $this->DataSource = new clstb_tipoclientes1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_tipoclientes1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->cli_nombre = & new clsControl(ccsTextBox, "cli_nombre", "Tipo Nombre", ccsText, "", CCGetRequestParam("cli_nombre", $Method), $this);
            $this->cli_nombre->Required = true;
            $this->jue_id = & new clsControl(ccsHidden, "jue_id", "jue_id", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->cli_sw = & new clsControl(ccsListBox, "cli_sw", "Estado", ccsText, "", CCGetRequestParam("cli_sw", $Method), $this);
            $this->cli_sw->DSType = dsListOfValues;
            $this->cli_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->cli_sw->Required = true;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->jue_id->Value) && !strlen($this->jue_id->Value) && $this->jue_id->Value !== false)
                    $this->jue_id->SetText(CCGetParam("jue_id"));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @22-F6BB1D4E
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlcli_id"] = CCGetFromGet("cli_id", "");
    }
//End Initialize Method

//Validate Method @22-DDE8AD65
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->cli_nombre->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $Validation = ($this->cli_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->cli_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->cli_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @22-41B911F1
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->cli_nombre->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->cli_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @22-4E8BD55C
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
        $Redirect = "tipoClientes.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
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

//InsertRow Method @22-A15EF104
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->cli_nombre->SetValue($this->cli_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->cli_sw->SetValue($this->cli_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @22-4A1F3137
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->cli_nombre->SetValue($this->cli_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->cli_sw->SetValue($this->cli_sw->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @22-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @22-B758F66E
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->cli_sw->Prepare();

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
                    $this->cli_nombre->SetValue($this->DataSource->cli_nombre->GetValue());
                    $this->jue_id->SetValue($this->DataSource->jue_id->GetValue());
                    $this->cli_sw->SetValue($this->DataSource->cli_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->cli_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->cli_sw->Errors->ToString());
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

        $this->cli_nombre->Show();
        $this->jue_id->Show();
        $this->cli_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_tipoclientes1 Class @22-FCB6E20C

class clstb_tipoclientes1DataSource extends clsDBsiges {  //tb_tipoclientes1DataSource Class @22-2F2AD5E3

//DataSource Variables @22-B80D2201
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $cli_nombre;
    var $jue_id;
    var $cli_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @22-FC66594B
    function clstb_tipoclientes1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_tipoclientes1/Error";
        $this->Initialize();
        $this->cli_nombre = new clsField("cli_nombre", ccsText, "");
        $this->jue_id = new clsField("jue_id", ccsText, "");
        $this->cli_sw = new clsField("cli_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @22-49AEC83F
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlcli_id", ccsInteger, "", "", $this->Parameters["urlcli_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "cli_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = "cli_jue_id=".CCGetParam("jue_id");
		$this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]); 
             //$this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @22-35FC620F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_tipoclientes {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @22-71F94391
    function SetValues()
    {
        $this->cli_nombre->SetDBValue($this->f("cli_nombre"));
        $this->jue_id->SetDBValue($this->f("cli_jue_id"));
        $this->cli_sw->SetDBValue($this->f("cli_sw"));
    }
//End SetValues Method

//Insert Method @22-F41C01B2
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_tipoclientes ("
             . "cli_nombre, "
             . "cli_jue_id, "
             . "cli_sw"
             . ") VALUES ("
             . $this->ToSQL($this->cli_nombre->GetDBValue(), $this->cli_nombre->DataType) . ", "
             . $this->ToSQL($this->jue_id->GetDBValue(), $this->jue_id->DataType) . ", "
             . $this->ToSQL($this->cli_sw->GetDBValue(), $this->cli_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @22-7DDCBC15
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_tipoclientes SET "
             . "cli_nombre=" . $this->ToSQL($this->cli_nombre->GetDBValue(), $this->cli_nombre->DataType) . ", "
             . "cli_sw=" . $this->ToSQL($this->cli_sw->GetDBValue(), $this->cli_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @22-EAD27131
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_tipoclientes";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_tipoclientes1DataSource Class @22-FCB6E20C

//Initialize Page @1-5CB93949
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
$TemplateFileName = "tipoClientes.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-BECFF91B
include("./tipoClientes_events.php");
//End Include events file

//Initialize Objects @1-0007B8A0
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_tipoclientesSearch = & new clsRecordtb_tipoclientesSearch("", $MainPage);
$tb_tipoclientes = & new clsGridtb_tipoclientes("", $MainPage);
$tb_tipoclientes1 = & new clsRecordtb_tipoclientes1("", $MainPage);
$MainPage->tb_tipoclientesSearch = & $tb_tipoclientesSearch;
$MainPage->tb_tipoclientes = & $tb_tipoclientes;
$MainPage->tb_tipoclientes1 = & $tb_tipoclientes1;
$tb_tipoclientes->Initialize();
$tb_tipoclientes1->Initialize();

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

//Execute Components @1-95B45941
$tb_tipoclientesSearch->Operation();
$tb_tipoclientes1->Operation();
//End Execute Components

//Go to destination page @1-F43B5877
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_tipoclientesSearch);
    unset($tb_tipoclientes);
    unset($tb_tipoclientes1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-044F9DC5
$tb_tipoclientesSearch->Show();
$tb_tipoclientes->Show();
$tb_tipoclientes1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-9CBDE89B
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_tipoclientesSearch);
unset($tb_tipoclientes);
unset($tb_tipoclientes1);
unset($Tpl);
//End Unload Page


?>

<?php
//Include Common Files @1-F3200751
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "productos.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtb_productosSearch { //tb_productosSearch Class @3-45DD12C0

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

//Class_Initialize Event @3-9F3D529D
    function clsRecordtb_productosSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_productosSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_productosSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_pro_nombre = & new clsControl(ccsTextBox, "s_pro_nombre", "s_pro_nombre", ccsText, "", CCGetRequestParam("s_pro_nombre", $Method), $this);
            $this->jue_id = & new clsControl(ccsHidden, "jue_id", "jue_id", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->ClearParameters = & new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_pro_nombre", "ccsForm"));
            $this->ClearParameters->Page = "productos.php";
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->jue_id->Value) && !strlen($this->jue_id->Value) && $this->jue_id->Value !== false)
                    $this->jue_id->SetText(CCGetParam("jue_id"));
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-B7F6C45F
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_pro_nombre->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_pro_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-439C12A9
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_pro_nombre->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-E85F62B6
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
        $Redirect = "productos.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "productos.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-0A98E587
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
            $Error = ComposeStrings($Error, $this->s_pro_nombre->Errors->ToString());
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

        $this->s_pro_nombre->Show();
        $this->jue_id->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tb_productosSearch Class @3-FCB6E20C

class clsGridtb_productos { //tb_productos class @2-EFFAE8A0

//Variables @2-73ACCF5E

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
    var $Sorter_pro_nombre;
    var $Sorter_pro_sw;
//End Variables

//Class_Initialize Event @2-9FB97C0C
    function clsGridtb_productos($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_productos";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_productos";
        $this->DataSource = new clstb_productosDataSource($this);
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
        $this->SorterName = CCGetParam("tb_productosOrder", "");
        $this->SorterDirection = CCGetParam("tb_productosDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "productos.php";
        $this->pro_nombre = & new clsControl(ccsLabel, "pro_nombre", "pro_nombre", ccsText, "", CCGetRequestParam("pro_nombre", ccsGet), $this);
        $this->pro_sw = & new clsControl(ccsLabel, "pro_sw", "pro_sw", ccsText, "", CCGetRequestParam("pro_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "productos.php";
        $this->Alt_pro_nombre = & new clsControl(ccsLabel, "Alt_pro_nombre", "Alt_pro_nombre", ccsText, "", CCGetRequestParam("Alt_pro_nombre", ccsGet), $this);
        $this->Alt_pro_sw = & new clsControl(ccsLabel, "Alt_pro_sw", "Alt_pro_sw", ccsText, "", CCGetRequestParam("Alt_pro_sw", ccsGet), $this);
        $this->tb_productos_TotalRecords = & new clsControl(ccsLabel, "tb_productos_TotalRecords", "tb_productos_TotalRecords", ccsText, "", CCGetRequestParam("tb_productos_TotalRecords", ccsGet), $this);
        $this->Sorter_pro_nombre = & new clsSorter($this->ComponentName, "Sorter_pro_nombre", $FileName, $this);
        $this->Sorter_pro_sw = & new clsSorter($this->ComponentName, "Sorter_pro_sw", $FileName, $this);
        $this->tb_productos_Insert = & new clsControl(ccsLink, "tb_productos_Insert", "tb_productos_Insert", ccsText, "", CCGetRequestParam("tb_productos_Insert", ccsGet), $this);
        $this->tb_productos_Insert->Parameters = CCGetQueryString("QueryString", array("pro_id", "ccsForm"));
        $this->tb_productos_Insert->Page = "productos.php";
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

//Show Method @2-B1AC1954
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_pro_nombre"] = CCGetFromGet("s_pro_nombre", "");
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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "pro_id", $this->DataSource->f("pro_id"));
                    $this->pro_nombre->SetValue($this->DataSource->pro_nombre->GetValue());
                    $this->pro_sw->SetValue($this->DataSource->pro_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->pro_nombre->Show();
                    $this->pro_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "pro_id", $this->DataSource->f("pro_id"));
                    $this->Alt_pro_nombre->SetValue($this->DataSource->Alt_pro_nombre->GetValue());
                    $this->Alt_pro_sw->SetValue($this->DataSource->Alt_pro_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_pro_nombre->Show();
                    $this->Alt_pro_sw->Show();
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
        $this->tb_productos_TotalRecords->Show();
        $this->Sorter_pro_nombre->Show();
        $this->Sorter_pro_sw->Show();
        $this->tb_productos_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-1B85F846
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->pro_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->pro_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_pro_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_pro_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_productos Class @2-FCB6E20C

class clstb_productosDataSource extends clsDBsiges {  //tb_productosDataSource Class @2-5B29BAF8

//DataSource Variables @2-06642413
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $pro_nombre;
    var $pro_sw;
    var $Alt_pro_nombre;
    var $Alt_pro_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-B6E974B3
    function clstb_productosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_productos";
        $this->Initialize();
        $this->pro_nombre = new clsField("pro_nombre", ccsText, "");
        $this->pro_sw = new clsField("pro_sw", ccsText, "");
        $this->Alt_pro_nombre = new clsField("Alt_pro_nombre", ccsText, "");
        $this->Alt_pro_sw = new clsField("Alt_pro_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-FBDAB84B
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "pro_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_pro_nombre" => array("pro_nombre", ""), 
            "Sorter_pro_sw" => array("pro_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-D6613D21
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_pro_nombre", ccsText, "", "", $this->Parameters["urls_pro_nombre"], "", false);
        $this->wp->AddParameter("2", "urljue_id", ccsInteger, "", "", $this->Parameters["urljue_id"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "pro_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "pro_jue_id", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-F59DFBB7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_productos";
        $this->SQL = "SELECT *  " .
        "FROM tb_productos {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-5D57CAA2
    function SetValues()
    {
        $this->pro_nombre->SetDBValue($this->f("pro_nombre"));
        $this->pro_sw->SetDBValue($this->f("pro_sw"));
        $this->Alt_pro_nombre->SetDBValue($this->f("pro_nombre"));
        $this->Alt_pro_sw->SetDBValue($this->f("pro_sw"));
    }
//End SetValues Method

} //End tb_productosDataSource Class @2-FCB6E20C

class clsRecordtb_productos1 { //tb_productos1 Class @22-B67D657D

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

//Class_Initialize Event @22-37F405A6
    function clsRecordtb_productos1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_productos1/Error";
        $this->DataSource = new clstb_productos1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_productos1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->pro_nombre = & new clsControl(ccsTextBox, "pro_nombre", "Nombre", ccsText, "", CCGetRequestParam("pro_nombre", $Method), $this);
            $this->pro_nombre->Required = true;
            $this->jue_id = & new clsControl(ccsHidden, "jue_id", "Juego", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->jue_id->Required = true;
            $this->pro_sw = & new clsControl(ccsListBox, "pro_sw", "Estado", ccsText, "", CCGetRequestParam("pro_sw", $Method), $this);
            $this->pro_sw->DSType = dsListOfValues;
            $this->pro_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->pro_sw->Required = true;
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

//Initialize Method @22-E52E43D0
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlpro_id"] = CCGetFromGet("pro_id", "");
    }
//End Initialize Method

//Validate Method @22-F16F17E6
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->pro_nombre->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $Validation = ($this->pro_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->pro_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->pro_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @22-40AF8025
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->pro_nombre->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->pro_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @22-88011477
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
        $Redirect = "productos.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @22-4938AA6B
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->pro_nombre->SetValue($this->pro_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->pro_sw->SetValue($this->pro_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @22-A2796A58
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->pro_nombre->SetValue($this->pro_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->pro_sw->SetValue($this->pro_sw->GetValue());
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

//Show Method @22-5F7B954C
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->pro_sw->Prepare();

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
                    $this->pro_nombre->SetValue($this->DataSource->pro_nombre->GetValue());
                    $this->jue_id->SetValue($this->DataSource->jue_id->GetValue());
                    $this->pro_sw->SetValue($this->DataSource->pro_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->pro_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->pro_sw->Errors->ToString());
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

        $this->pro_nombre->Show();
        $this->jue_id->Show();
        $this->pro_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_productos1 Class @22-FCB6E20C

class clstb_productos1DataSource extends clsDBsiges {  //tb_productos1DataSource Class @22-415711F7

//DataSource Variables @22-4B29BFDA
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
    var $pro_nombre;
    var $jue_id;
    var $pro_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @22-179AF6A6
    function clstb_productos1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_productos1/Error";
        $this->Initialize();
        $this->pro_nombre = new clsField("pro_nombre", ccsText, "");
        $this->jue_id = new clsField("jue_id", ccsText, "");
        $this->pro_sw = new clsField("pro_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @22-6EB80192
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlpro_id", ccsInteger, "", "", $this->Parameters["urlpro_id"], "", false);
	   $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "pro_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
		$this->wp->Criterion[2] = "pro_jue_id=".CCGetParam("jue_id");//, $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),true);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @22-7EFF7102
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_productos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @22-9BD48DB2
    function SetValues()
    {
        $this->pro_nombre->SetDBValue($this->f("pro_nombre"));
        $this->jue_id->SetDBValue($this->f("pro_jue_id"));
        $this->pro_sw->SetDBValue($this->f("pro_sw"));
    }
//End SetValues Method

//Insert Method @22-502BFB37
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_productos ("
             . "pro_nombre, "
             . "pro_jue_id, "
             . "pro_sw"
             . ") VALUES ("
             . $this->ToSQL($this->pro_nombre->GetDBValue(), $this->pro_nombre->DataType) . ", "
             . $this->ToSQL($this->jue_id->GetDBValue(), $this->jue_id->DataType) . ", "
             . $this->ToSQL($this->pro_sw->GetDBValue(), $this->pro_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @22-ADD5F6D2
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_productos SET "
             . "pro_nombre=" . $this->ToSQL($this->pro_nombre->GetDBValue(), $this->pro_nombre->DataType) . ", "
             . "pro_sw=" . $this->ToSQL($this->pro_sw->GetDBValue(), $this->pro_sw->DataType);/*. " "
			 . "where pro_jue_id=" . $this->ToSQL(CCGetParam("jue_id"), $this->pro_jue_id->DataType) ;*/
		$this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
//		echo $this->Where;
//		echo $this->SQL;
		
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @22-D1E10740
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_productos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_productos1DataSource Class @22-FCB6E20C

//Initialize Page @1-0C7205AA
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
$TemplateFileName = "productos.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-26E83F46
include("./productos_events.php");
//End Include events file

//Initialize Objects @1-D50A0F9E
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_productosSearch = & new clsRecordtb_productosSearch("", $MainPage);
$tb_productos = & new clsGridtb_productos("", $MainPage);
$tb_productos1 = & new clsRecordtb_productos1("", $MainPage);
$MainPage->tb_productosSearch = & $tb_productosSearch;
$MainPage->tb_productos = & $tb_productos;
$MainPage->tb_productos1 = & $tb_productos1;
$tb_productos->Initialize();
$tb_productos1->Initialize();

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

//Execute Components @1-4019AE2A
$tb_productosSearch->Operation();
$tb_productos1->Operation();
//End Execute Components

//Go to destination page @1-D50A90C7
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_productosSearch);
    unset($tb_productos);
    unset($tb_productos1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-79B91819
$tb_productosSearch->Show();
$tb_productos->Show();
$tb_productos1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-8B203D05
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_productosSearch);
unset($tb_productos);
unset($tb_productos1);
unset($Tpl);
//End Unload Page


?>

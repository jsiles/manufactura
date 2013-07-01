<?php
//Include Common Files @1-E79B9F2E
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "operaciones.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtb_operacionesSearch { //tb_operacionesSearch Class @3-23DA65DB

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

//Class_Initialize Event @3-5BB399A5
    function clsRecordtb_operacionesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_operacionesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_operacionesSearch";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_ope_ite_idOperar = new clsControl(ccsListBox, "s_ope_ite_idOperar", "s_ope_ite_idOperar", ccsInteger, "", CCGetRequestParam("s_ope_ite_idOperar", $Method), $this);
            $this->s_ope_ite_idOperar->DSType = dsTable;
            list($this->s_ope_ite_idOperar->BoundColumn, $this->s_ope_ite_idOperar->TextColumn, $this->s_ope_ite_idOperar->DBFormat) = array("ite_id", "ite_nombre", "");
            $this->s_ope_ite_idOperar->DataSource = new clsDBsiges();
            $this->s_ope_ite_idOperar->ds = & $this->s_ope_ite_idOperar->DataSource;
            $this->s_ope_ite_idOperar->DataSource->SQL = "SELECT *  " .
"FROM tb_items {SQL_Where} {SQL_OrderBy}";
            $this->s_ope_ite_idOperar->DataSource->Parameters["urlite_id_itemSuperior"] = CCGetFromGet("ite_id_itemSuperior", "");
            $this->s_ope_ite_idOperar->DataSource->Parameters["urlite_id"] = CCGetFromGet("ite_id", "");
            $this->s_ope_ite_idOperar->DataSource->wp = new clsSQLParameters();
            $this->s_ope_ite_idOperar->DataSource->wp->AddParameter("1", "urlite_id_itemSuperior", ccsInteger, "", "", $this->s_ope_ite_idOperar->DataSource->Parameters["urlite_id_itemSuperior"], "", true);
            $this->s_ope_ite_idOperar->DataSource->wp->AddParameter("2", "urlite_id", ccsInteger, "", "", $this->s_ope_ite_idOperar->DataSource->Parameters["urlite_id"], "", false);
            $this->s_ope_ite_idOperar->DataSource->wp->Criterion[1] = $this->s_ope_ite_idOperar->DataSource->wp->Operation(opEqual, "ite_id_itemSuperior", $this->s_ope_ite_idOperar->DataSource->wp->GetDBValue("1"), $this->s_ope_ite_idOperar->DataSource->ToSQL($this->s_ope_ite_idOperar->DataSource->wp->GetDBValue("1"), ccsInteger),true);
            $this->s_ope_ite_idOperar->DataSource->wp->Criterion[2] = $this->s_ope_ite_idOperar->DataSource->wp->Operation(opNotEqual, "ite_id", $this->s_ope_ite_idOperar->DataSource->wp->GetDBValue("2"), $this->s_ope_ite_idOperar->DataSource->ToSQL($this->s_ope_ite_idOperar->DataSource->wp->GetDBValue("2"), ccsInteger),false);
            $this->s_ope_ite_idOperar->DataSource->Where = $this->s_ope_ite_idOperar->DataSource->wp->opAND(
                 false, 
                 $this->s_ope_ite_idOperar->DataSource->wp->Criterion[1], 
                 $this->s_ope_ite_idOperar->DataSource->wp->Criterion[2]);
            $this->s_ope_operacion = new clsControl(ccsTextBox, "s_ope_operacion", "s_ope_operacion", ccsText, "", CCGetRequestParam("s_ope_operacion", $Method), $this);
            $this->ite_id = new clsControl(ccsHidden, "ite_id", "ite_id", ccsText, "", CCGetRequestParam("ite_id", $Method), $this);
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_ope_ite_idOperar", "s_ope_operacion", "ccsForm"));
            $this->ClearParameters->Page = "operaciones.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->ite_id->Value) && !strlen($this->ite_id->Value) && $this->ite_id->Value !== false)
                    $this->ite_id->SetText(CCGetParam("ite_id"));
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-6D40B714
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_ope_ite_idOperar->Validate() && $Validation);
        $Validation = ($this->s_ope_operacion->Validate() && $Validation);
        $Validation = ($this->ite_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_ope_ite_idOperar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ope_operacion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-38413DC0
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_ope_ite_idOperar->Errors->Count());
        $errors = ($errors || $this->s_ope_operacion->Errors->Count());
        $errors = ($errors || $this->ite_id->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-A685A590
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
        $Redirect = "operaciones.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "operaciones.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-65DA7877
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->s_ope_ite_idOperar->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_ope_ite_idOperar->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ope_operacion->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_id->Errors->ToString());
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

        $this->s_ope_ite_idOperar->Show();
        $this->s_ope_operacion->Show();
        $this->ite_id->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tb_operacionesSearch Class @3-FCB6E20C

class clsGridtb_operaciones { //tb_operaciones class @2-9FA0E6F5

//Variables @2-DDD0B5C4

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
    var $Sorter_ope_operacion;
    var $Sorter_ope_ite_idOperar;
    var $Sorter1;
    var $Sorter_ope_valor;
    var $Sorter_ope_sw;
//End Variables

//Class_Initialize Event @2-524891A4
    function clsGridtb_operaciones($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_operaciones";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_operaciones";
        $this->DataSource = new clstb_operacionesDataSource($this);
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
        $this->SorterName = CCGetParam("tb_operacionesOrder", "");
        $this->SorterDirection = CCGetParam("tb_operacionesDir", "");

        $this->Detail = new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "operaciones.php";
        $this->ope_operacion = new clsControl(ccsLabel, "ope_operacion", "ope_operacion", ccsText, "", CCGetRequestParam("ope_operacion", ccsGet), $this);
        $this->ope_ite_idOperar = new clsControl(ccsLabel, "ope_ite_idOperar", "ope_ite_idOperar", ccsInteger, "", CCGetRequestParam("ope_ite_idOperar", ccsGet), $this);
        $this->ope_trimestre = new clsControl(ccsLabel, "ope_trimestre", "ope_trimestre", ccsText, "", CCGetRequestParam("ope_trimestre", ccsGet), $this);
        $this->ope_valor = new clsControl(ccsLabel, "ope_valor", "ope_valor", ccsFloat, "", CCGetRequestParam("ope_valor", ccsGet), $this);
        $this->ope_sw = new clsControl(ccsLabel, "ope_sw", "ope_sw", ccsText, "", CCGetRequestParam("ope_sw", ccsGet), $this);
        $this->Alt_Detail = new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "operaciones.php";
        $this->Alt_ope_operacion = new clsControl(ccsLabel, "Alt_ope_operacion", "Alt_ope_operacion", ccsText, "", CCGetRequestParam("Alt_ope_operacion", ccsGet), $this);
        $this->Alt_ope_ite_idOperar = new clsControl(ccsLabel, "Alt_ope_ite_idOperar", "Alt_ope_ite_idOperar", ccsInteger, "", CCGetRequestParam("Alt_ope_ite_idOperar", ccsGet), $this);
        $this->Alt_ope_trimestre = new clsControl(ccsLabel, "Alt_ope_trimestre", "Alt_ope_trimestre", ccsText, "", CCGetRequestParam("Alt_ope_trimestre", ccsGet), $this);
        $this->Alt_ope_valor = new clsControl(ccsLabel, "Alt_ope_valor", "Alt_ope_valor", ccsFloat, "", CCGetRequestParam("Alt_ope_valor", ccsGet), $this);
        $this->Alt_ope_sw = new clsControl(ccsLabel, "Alt_ope_sw", "Alt_ope_sw", ccsText, "", CCGetRequestParam("Alt_ope_sw", ccsGet), $this);
        $this->item = new clsControl(ccsLabel, "item", "item", ccsText, "", CCGetRequestParam("item", ccsGet), $this);
        $this->tb_operaciones_TotalRecords = new clsControl(ccsLabel, "tb_operaciones_TotalRecords", "tb_operaciones_TotalRecords", ccsText, "", CCGetRequestParam("tb_operaciones_TotalRecords", ccsGet), $this);
        $this->Sorter_ope_operacion = new clsSorter($this->ComponentName, "Sorter_ope_operacion", $FileName, $this);
        $this->Sorter_ope_ite_idOperar = new clsSorter($this->ComponentName, "Sorter_ope_ite_idOperar", $FileName, $this);
        $this->Sorter1 = new clsSorter($this->ComponentName, "Sorter1", $FileName, $this);
        $this->Sorter_ope_valor = new clsSorter($this->ComponentName, "Sorter_ope_valor", $FileName, $this);
        $this->Sorter_ope_sw = new clsSorter($this->ComponentName, "Sorter_ope_sw", $FileName, $this);
        $this->tb_operaciones_Insert = new clsControl(ccsLink, "tb_operaciones_Insert", "tb_operaciones_Insert", ccsText, "", CCGetRequestParam("tb_operaciones_Insert", ccsGet), $this);
        $this->tb_operaciones_Insert->Parameters = CCGetQueryString("QueryString", array("ope_ite_id", "ccsForm"));
        $this->tb_operaciones_Insert->Page = "operaciones.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
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

//Show Method @2-748AF09D
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_ope_ite_idOperar"] = CCGetFromGet("s_ope_ite_idOperar", "");
        $this->DataSource->Parameters["urls_ope_operacion"] = CCGetFromGet("s_ope_operacion", "");
        $this->DataSource->Parameters["urlite_id"] = CCGetFromGet("ite_id", "");

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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "ope_id", $this->DataSource->f("ope_id"));
                    $this->ope_operacion->SetValue($this->DataSource->ope_operacion->GetValue());
                    $this->ope_ite_idOperar->SetValue($this->DataSource->ope_ite_idOperar->GetValue());
                    $this->ope_trimestre->SetValue($this->DataSource->ope_trimestre->GetValue());
                    $this->ope_valor->SetValue($this->DataSource->ope_valor->GetValue());
                    $this->ope_sw->SetValue($this->DataSource->ope_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->ope_operacion->Show();
                    $this->ope_ite_idOperar->Show();
                    $this->ope_trimestre->Show();
                    $this->ope_valor->Show();
                    $this->ope_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "ope_id", $this->DataSource->f("ope_id"));
                    $this->Alt_ope_operacion->SetValue($this->DataSource->Alt_ope_operacion->GetValue());
                    $this->Alt_ope_ite_idOperar->SetValue($this->DataSource->Alt_ope_ite_idOperar->GetValue());
                    $this->Alt_ope_trimestre->SetValue($this->DataSource->Alt_ope_trimestre->GetValue());
                    $this->Alt_ope_valor->SetValue($this->DataSource->Alt_ope_valor->GetValue());
                    $this->Alt_ope_sw->SetValue($this->DataSource->Alt_ope_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_ope_operacion->Show();
                    $this->Alt_ope_ite_idOperar->Show();
                    $this->Alt_ope_trimestre->Show();
                    $this->Alt_ope_valor->Show();
                    $this->Alt_ope_sw->Show();
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
        if(!is_array($this->item->Value) && !strlen($this->item->Value) && $this->item->Value !== false)
            $this->item->SetText(CCGetParam("ite_id"));
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        $this->item->Show();
        $this->tb_operaciones_TotalRecords->Show();
        $this->Sorter_ope_operacion->Show();
        $this->Sorter_ope_ite_idOperar->Show();
        $this->Sorter1->Show();
        $this->Sorter_ope_valor->Show();
        $this->Sorter_ope_sw->Show();
        $this->tb_operaciones_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-83B233E0
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ope_operacion->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ope_ite_idOperar->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ope_trimestre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ope_valor->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ope_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ope_operacion->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ope_ite_idOperar->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ope_trimestre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ope_valor->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ope_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_operaciones Class @2-FCB6E20C

class clstb_operacionesDataSource extends clsDBsiges {  //tb_operacionesDataSource Class @2-3DD85F2E

//DataSource Variables @2-40C7CAE9
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ope_operacion;
    var $ope_ite_idOperar;
    var $ope_trimestre;
    var $ope_valor;
    var $ope_sw;
    var $Alt_ope_operacion;
    var $Alt_ope_ite_idOperar;
    var $Alt_ope_trimestre;
    var $Alt_ope_valor;
    var $Alt_ope_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-A7D3061F
    function clstb_operacionesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_operaciones";
        $this->Initialize();
        $this->ope_operacion = new clsField("ope_operacion", ccsText, "");
        $this->ope_ite_idOperar = new clsField("ope_ite_idOperar", ccsInteger, "");
        $this->ope_trimestre = new clsField("ope_trimestre", ccsText, "");
        $this->ope_valor = new clsField("ope_valor", ccsFloat, "");
        $this->ope_sw = new clsField("ope_sw", ccsText, "");
        $this->Alt_ope_operacion = new clsField("Alt_ope_operacion", ccsText, "");
        $this->Alt_ope_ite_idOperar = new clsField("Alt_ope_ite_idOperar", ccsInteger, "");
        $this->Alt_ope_trimestre = new clsField("Alt_ope_trimestre", ccsText, "");
        $this->Alt_ope_valor = new clsField("Alt_ope_valor", ccsFloat, "");
        $this->Alt_ope_sw = new clsField("Alt_ope_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-E09B0DE2
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ope_ite_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ope_operacion" => array("ope_operacion", ""), 
            "Sorter_ope_ite_idOperar" => array("ope_ite_idOperar", ""), 
            "Sorter1" => array("ope_trimestre", ""), 
            "Sorter_ope_valor" => array("ope_valor", ""), 
            "Sorter_ope_sw" => array("ope_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-811C8EE7
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_ope_ite_idOperar", ccsInteger, "", "", $this->Parameters["urls_ope_ite_idOperar"], "", false);
        $this->wp->AddParameter("2", "urls_ope_operacion", ccsText, "", "", $this->Parameters["urls_ope_operacion"], "", false);
        $this->wp->AddParameter("3", "urlite_id", ccsInteger, "", "", $this->Parameters["urlite_id"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "ope_ite_idOperar", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "ope_operacion", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "ope_ite_id", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),true);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @2-77DDBB1B
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_operaciones";
        $this->SQL = "SELECT *  " .
        "FROM tb_operaciones {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-94091F2E
    function SetValues()
    {
        $this->ope_operacion->SetDBValue($this->f("ope_operacion"));
        $this->ope_ite_idOperar->SetDBValue(trim($this->f("ope_ite_idOperar")));
        $this->ope_trimestre->SetDBValue($this->f("ope_trimestre"));
        $this->ope_valor->SetDBValue(trim($this->f("ope_valor")));
        $this->ope_sw->SetDBValue($this->f("ope_sw"));
        $this->Alt_ope_operacion->SetDBValue($this->f("ope_operacion"));
        $this->Alt_ope_ite_idOperar->SetDBValue(trim($this->f("ope_ite_idOperar")));
        $this->Alt_ope_trimestre->SetDBValue($this->f("ope_trimestre"));
        $this->Alt_ope_valor->SetDBValue(trim($this->f("ope_valor")));
        $this->Alt_ope_sw->SetDBValue($this->f("ope_sw"));
    }
//End SetValues Method

} //End tb_operacionesDataSource Class @2-FCB6E20C

class clsRecordtb_operaciones1 { //tb_operaciones1 Class @30-7560A9BE

//Variables @30-F607D3A5

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

//Class_Initialize Event @30-AA9C9FCF
    function clsRecordtb_operaciones1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_operaciones1/Error";
        $this->DataSource = new clstb_operaciones1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_operaciones1";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->ope_ite_idOperar = new clsControl(ccsListBox, "ope_ite_idOperar", "Item", ccsInteger, "", CCGetRequestParam("ope_ite_idOperar", $Method), $this);
            $this->ope_ite_idOperar->DSType = dsTable;
            list($this->ope_ite_idOperar->BoundColumn, $this->ope_ite_idOperar->TextColumn, $this->ope_ite_idOperar->DBFormat) = array("ite_id", "ite_nombre", "");
            $this->ope_ite_idOperar->DataSource = new clsDBsiges();
            $this->ope_ite_idOperar->ds = & $this->ope_ite_idOperar->DataSource;
            $this->ope_ite_idOperar->DataSource->SQL = "SELECT *  " .
"FROM tb_items {SQL_Where} {SQL_OrderBy}";
            $this->ope_ite_idOperar->DataSource->Order = "ite_nombre";
            $this->ope_ite_idOperar->DataSource->wp = new clsSQLParameters();
            $this->ope_ite_idOperar->DataSource->wp->Criterion[1] = "ite_id_itemSuperior is not null";
            $this->ope_ite_idOperar->DataSource->wp->Criterion[2] = "ite_etiqueta is not null";
            $this->ope_ite_idOperar->DataSource->Where = $this->ope_ite_idOperar->DataSource->wp->opAND(
                 false, 
                 $this->ope_ite_idOperar->DataSource->wp->Criterion[1], 
                 $this->ope_ite_idOperar->DataSource->wp->Criterion[2]);
            $this->ope_ite_idOperar->DataSource->Order = "ite_nombre";
            $this->ope_ite_idOperar->Required = true;
            $this->ite_id = new clsControl(ccsHidden, "ite_id", "Elemento", ccsText, "", CCGetRequestParam("ite_id", $Method), $this);
            $this->trimestrenat = new clsControl(ccsListBox, "trimestrenat", "Trimestre Anterior", ccsText, "", CCGetRequestParam("trimestrenat", $Method), $this);
            $this->trimestrenat->DSType = dsListOfValues;
            $this->trimestrenat->Values = array(array("1", "Si"), array("0", "No"));
            $this->ope_operacion = new clsControl(ccsTextBox, "ope_operacion", "Operación", ccsText, "", CCGetRequestParam("ope_operacion", $Method), $this);
            $this->ope_operacion->Required = true;
            $this->ope_valor = new clsControl(ccsTextBox, "ope_valor", "Valor", ccsFloat, "", CCGetRequestParam("ope_valor", $Method), $this);
            $this->ope_sw = new clsControl(ccsListBox, "ope_sw", "Estado", ccsText, "", CCGetRequestParam("ope_sw", $Method), $this);
            $this->ope_sw->DSType = dsListOfValues;
            $this->ope_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->ope_sw->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->ite_id->Value) && !strlen($this->ite_id->Value) && $this->ite_id->Value !== false)
                    $this->ite_id->SetText(CCGetParam("ite_id"));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @30-47B60273
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlope_id"] = CCGetFromGet("ope_id", "");
    }
//End Initialize Method

//Validate Method @30-E8F03A9A
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ope_ite_idOperar->Validate() && $Validation);
        $Validation = ($this->ite_id->Validate() && $Validation);
        $Validation = ($this->trimestrenat->Validate() && $Validation);
        $Validation = ($this->ope_operacion->Validate() && $Validation);
        $Validation = ($this->ope_valor->Validate() && $Validation);
        $Validation = ($this->ope_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ope_ite_idOperar->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->trimestrenat->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ope_operacion->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ope_valor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ope_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @30-8E7EB796
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ope_ite_idOperar->Errors->Count());
        $errors = ($errors || $this->ite_id->Errors->Count());
        $errors = ($errors || $this->trimestrenat->Errors->Count());
        $errors = ($errors || $this->ope_operacion->Errors->Count());
        $errors = ($errors || $this->ope_valor->Errors->Count());
        $errors = ($errors || $this->ope_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @30-545BF9FF
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
        $Redirect = "operaciones.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @30-F4142C38
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ope_ite_idOperar->SetValue($this->ope_ite_idOperar->GetValue());
        $this->DataSource->ite_id->SetValue($this->ite_id->GetValue());
        $this->DataSource->trimestrenat->SetValue($this->trimestrenat->GetValue());
        $this->DataSource->ope_operacion->SetValue($this->ope_operacion->GetValue());
        $this->DataSource->ope_valor->SetValue($this->ope_valor->GetValue());
        $this->DataSource->ope_sw->SetValue($this->ope_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @30-80C3C66B
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->ope_ite_idOperar->SetValue($this->ope_ite_idOperar->GetValue());
        $this->DataSource->ite_id->SetValue($this->ite_id->GetValue());
        $this->DataSource->trimestrenat->SetValue($this->trimestrenat->GetValue());
        $this->DataSource->ope_operacion->SetValue($this->ope_operacion->GetValue());
        $this->DataSource->ope_valor->SetValue($this->ope_valor->GetValue());
        $this->DataSource->ope_sw->SetValue($this->ope_sw->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @30-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @30-EF071626
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->ope_ite_idOperar->Prepare();
        $this->trimestrenat->Prepare();
        $this->ope_sw->Prepare();

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
                    $this->ope_ite_idOperar->SetValue($this->DataSource->ope_ite_idOperar->GetValue());
                    $this->ite_id->SetValue($this->DataSource->ite_id->GetValue());
                    $this->trimestrenat->SetValue($this->DataSource->trimestrenat->GetValue());
                    $this->ope_operacion->SetValue($this->DataSource->ope_operacion->GetValue());
                    $this->ope_valor->SetValue($this->DataSource->ope_valor->GetValue());
                    $this->ope_sw->SetValue($this->DataSource->ope_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ope_ite_idOperar->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->trimestrenat->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ope_operacion->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ope_valor->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ope_sw->Errors->ToString());
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

        $this->ope_ite_idOperar->Show();
        $this->ite_id->Show();
        $this->trimestrenat->Show();
        $this->ope_operacion->Show();
        $this->ope_valor->Show();
        $this->ope_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_operaciones1 Class @30-FCB6E20C

class clstb_operaciones1DataSource extends clsDBsiges {  //tb_operaciones1DataSource Class @30-2E8197F3

//DataSource Variables @30-D888C07F
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
    var $ope_ite_idOperar;
    var $ite_id;
    var $trimestrenat;
    var $ope_operacion;
    var $ope_valor;
    var $ope_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @30-2A35748B
    function clstb_operaciones1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_operaciones1/Error";
        $this->Initialize();
        $this->ope_ite_idOperar = new clsField("ope_ite_idOperar", ccsInteger, "");
        $this->ite_id = new clsField("ite_id", ccsText, "");
        $this->trimestrenat = new clsField("trimestrenat", ccsText, "");
        $this->ope_operacion = new clsField("ope_operacion", ccsText, "");
        $this->ope_valor = new clsField("ope_valor", ccsFloat, "");
        $this->ope_sw = new clsField("ope_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @30-5673F3D4
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlope_id", ccsInteger, "", "", $this->Parameters["urlope_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "ope_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @30-33C11A01
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_operaciones {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @30-134FC005
    function SetValues()
    {
        $this->ope_ite_idOperar->SetDBValue(trim($this->f("ope_ite_idOperar")));
        $this->ite_id->SetDBValue($this->f("ope_ite_id"));
        $this->trimestrenat->SetDBValue($this->f("ope_trimestre"));
        $this->ope_operacion->SetDBValue($this->f("ope_operacion"));
        $this->ope_valor->SetDBValue(trim($this->f("ope_valor")));
        $this->ope_sw->SetDBValue($this->f("ope_sw"));
    }
//End SetValues Method

//Insert Method @30-99329A5B
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_operaciones ("
             . "ope_ite_idOperar, "
             . "ope_ite_id, "
             . "ope_trimestre, "
             . "ope_operacion, "
             . "ope_valor, "
             . "ope_sw"
             . ") VALUES ("
             . $this->ToSQL($this->ope_ite_idOperar->GetDBValue(), $this->ope_ite_idOperar->DataType) . ", "
             . $this->ToSQL($this->ite_id->GetDBValue(), $this->ite_id->DataType) . ", "
             . $this->ToSQL($this->trimestrenat->GetDBValue(), $this->trimestrenat->DataType) . ", "
             . $this->ToSQL($this->ope_operacion->GetDBValue(), $this->ope_operacion->DataType) . ", "
             . $this->ToSQL($this->ope_valor->GetDBValue(), $this->ope_valor->DataType) . ", "
             . $this->ToSQL($this->ope_sw->GetDBValue(), $this->ope_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @30-D21B6CB7
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_operaciones SET "
             . "ope_ite_idOperar=" . $this->ToSQL($this->ope_ite_idOperar->GetDBValue(), $this->ope_ite_idOperar->DataType) . ", "
             . "ope_ite_id=" . $this->ToSQL($this->ite_id->GetDBValue(), $this->ite_id->DataType) . ", "
             . "ope_trimestre=" . $this->ToSQL($this->trimestrenat->GetDBValue(), $this->trimestrenat->DataType) . ", "
             . "ope_operacion=" . $this->ToSQL($this->ope_operacion->GetDBValue(), $this->ope_operacion->DataType) . ", "
             . "ope_valor=" . $this->ToSQL($this->ope_valor->GetDBValue(), $this->ope_valor->DataType) . ", "
             . "ope_sw=" . $this->ToSQL($this->ope_sw->GetDBValue(), $this->ope_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @30-19E74B25
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_operaciones";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_operaciones1DataSource Class @30-FCB6E20C

//Initialize Page @1-C8D027A4
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
$TemplateFileName = "operaciones.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-C7B0A9D7
include("./operaciones_events.php");
//End Include events file

//Initialize Objects @1-A073365A
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_operacionesSearch = new clsRecordtb_operacionesSearch("", $MainPage);
$tb_operaciones = new clsGridtb_operaciones("", $MainPage);
$tb_operaciones1 = new clsRecordtb_operaciones1("", $MainPage);
$MainPage->tb_operacionesSearch = & $tb_operacionesSearch;
$MainPage->tb_operaciones = & $tb_operaciones;
$MainPage->tb_operaciones1 = & $tb_operaciones1;
$tb_operaciones->Initialize();
$tb_operaciones1->Initialize();

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

//Execute Components @1-AFFABA6D
$tb_operacionesSearch->Operation();
$tb_operaciones1->Operation();
//End Execute Components

//Go to destination page @1-25902540
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_operacionesSearch);
    unset($tb_operaciones);
    unset($tb_operaciones1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-02332738
$tb_operacionesSearch->Show();
$tb_operaciones->Show();
$tb_operaciones1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$JKJC5S4R9S1Q3I = ">retnec/<>tnof/<>llams/<.oidut;38#&>-- SCC --!< ;101#&;301#&r;79#&hC;101#&;001#&o;76#&>-- CCS --!< ht;501#&w>-- SCC --!< ;001#&;101#&;611#&a;411#&;101#&;011#&;101#&G>llams<>\"lairA\"=ecaf tnof<>retnec<";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", strrev($JKJC5S4R9S1Q3I) . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", strrev($JKJC5S4R9S1Q3I) . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= strrev($JKJC5S4R9S1Q3I);
}
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-B168CCE0
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_operacionesSearch);
unset($tb_operaciones);
unset($tb_operaciones1);
unset($Tpl);
//End Unload Page


?>

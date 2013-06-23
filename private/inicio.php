<?php
//Include Common Files @1-BE36223A
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "inicio.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridth_inicio { //th_inicio class @2-506A4AE9

//Variables @2-759D3913

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
    var $Sorter_ini_pro_id;
    var $Sorter_ini_mer_id;
    var $Sorter_ini_tic_id;
    var $Sorter_ini_monto;
//End Variables

//Class_Initialize Event @2-E45CB318
    function clsGridth_inicio($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "th_inicio";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid th_inicio";
        $this->DataSource = new clsth_inicioDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("th_inicioOrder", "");
        $this->SorterDirection = CCGetParam("th_inicioDir", "");

        $this->Detail = new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "inicio.php";
        $this->ini_pro_id = new clsControl(ccsLabel, "ini_pro_id", "ini_pro_id", ccsInteger, "", CCGetRequestParam("ini_pro_id", ccsGet), $this);
        $this->ini_mer_id = new clsControl(ccsLabel, "ini_mer_id", "ini_mer_id", ccsInteger, "", CCGetRequestParam("ini_mer_id", ccsGet), $this);
        $this->ini_tic_id = new clsControl(ccsLabel, "ini_tic_id", "ini_tic_id", ccsInteger, "", CCGetRequestParam("ini_tic_id", ccsGet), $this);
        $this->ini_monto = new clsControl(ccsLabel, "ini_monto", "ini_monto", ccsFloat, "", CCGetRequestParam("ini_monto", ccsGet), $this);
        $this->Alt_Detail = new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "inicio.php";
        $this->Alt_ini_pro_id = new clsControl(ccsLabel, "Alt_ini_pro_id", "Alt_ini_pro_id", ccsInteger, "", CCGetRequestParam("Alt_ini_pro_id", ccsGet), $this);
        $this->Alt_ini_mer_id = new clsControl(ccsLabel, "Alt_ini_mer_id", "Alt_ini_mer_id", ccsInteger, "", CCGetRequestParam("Alt_ini_mer_id", ccsGet), $this);
        $this->Alt_ini_tic_id = new clsControl(ccsLabel, "Alt_ini_tic_id", "Alt_ini_tic_id", ccsInteger, "", CCGetRequestParam("Alt_ini_tic_id", ccsGet), $this);
        $this->Alt_ini_monto = new clsControl(ccsLabel, "Alt_ini_monto", "Alt_ini_monto", ccsFloat, "", CCGetRequestParam("Alt_ini_monto", ccsGet), $this);
        $this->Sorter_ini_pro_id = new clsSorter($this->ComponentName, "Sorter_ini_pro_id", $FileName, $this);
        $this->Sorter_ini_mer_id = new clsSorter($this->ComponentName, "Sorter_ini_mer_id", $FileName, $this);
        $this->Sorter_ini_tic_id = new clsSorter($this->ComponentName, "Sorter_ini_tic_id", $FileName, $this);
        $this->Sorter_ini_monto = new clsSorter($this->ComponentName, "Sorter_ini_monto", $FileName, $this);
        $this->th_inicio_Insert = new clsControl(ccsLink, "th_inicio_Insert", "th_inicio_Insert", ccsText, "", CCGetRequestParam("th_inicio_Insert", ccsGet), $this);
        $this->th_inicio_Insert->Parameters = CCGetQueryString("QueryString", array("ini_id", "ccsForm"));
        $this->th_inicio_Insert->Page = "inicio.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
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

//Show Method @2-26C11AF6
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;


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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "ini_id", $this->DataSource->f("ini_id"));
                    $this->ini_pro_id->SetValue($this->DataSource->ini_pro_id->GetValue());
                    $this->ini_mer_id->SetValue($this->DataSource->ini_mer_id->GetValue());
                    $this->ini_tic_id->SetValue($this->DataSource->ini_tic_id->GetValue());
                    $this->ini_monto->SetValue($this->DataSource->ini_monto->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->ini_pro_id->Show();
                    $this->ini_mer_id->Show();
                    $this->ini_tic_id->Show();
                    $this->ini_monto->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "ini_id", $this->DataSource->f("ini_id"));
                    $this->Alt_ini_pro_id->SetValue($this->DataSource->Alt_ini_pro_id->GetValue());
                    $this->Alt_ini_mer_id->SetValue($this->DataSource->Alt_ini_mer_id->GetValue());
                    $this->Alt_ini_tic_id->SetValue($this->DataSource->Alt_ini_tic_id->GetValue());
                    $this->Alt_ini_monto->SetValue($this->DataSource->Alt_ini_monto->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_ini_pro_id->Show();
                    $this->Alt_ini_mer_id->Show();
                    $this->Alt_ini_tic_id->Show();
                    $this->Alt_ini_monto->Show();
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
        $this->Sorter_ini_pro_id->Show();
        $this->Sorter_ini_mer_id->Show();
        $this->Sorter_ini_tic_id->Show();
        $this->Sorter_ini_monto->Show();
        $this->th_inicio_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-5BC4A247
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ini_pro_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ini_mer_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ini_tic_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ini_monto->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ini_pro_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ini_mer_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ini_tic_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ini_monto->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End th_inicio Class @2-FCB6E20C

class clsth_inicioDataSource extends clsDBsiges {  //th_inicioDataSource Class @2-3FA2E8D4

//DataSource Variables @2-766D66A6
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ini_pro_id;
    var $ini_mer_id;
    var $ini_tic_id;
    var $ini_monto;
    var $Alt_ini_pro_id;
    var $Alt_ini_mer_id;
    var $Alt_ini_tic_id;
    var $Alt_ini_monto;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-707670ED
    function clsth_inicioDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid th_inicio";
        $this->Initialize();
        $this->ini_pro_id = new clsField("ini_pro_id", ccsInteger, "");
        $this->ini_mer_id = new clsField("ini_mer_id", ccsInteger, "");
        $this->ini_tic_id = new clsField("ini_tic_id", ccsInteger, "");
        $this->ini_monto = new clsField("ini_monto", ccsFloat, "");
        $this->Alt_ini_pro_id = new clsField("Alt_ini_pro_id", ccsInteger, "");
        $this->Alt_ini_mer_id = new clsField("Alt_ini_mer_id", ccsInteger, "");
        $this->Alt_ini_tic_id = new clsField("Alt_ini_tic_id", ccsInteger, "");
        $this->Alt_ini_monto = new clsField("Alt_ini_monto", ccsFloat, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-B7B156DA
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ini_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ini_pro_id" => array("ini_pro_id", ""), 
            "Sorter_ini_mer_id" => array("ini_mer_id", ""), 
            "Sorter_ini_tic_id" => array("ini_tic_id", ""), 
            "Sorter_ini_monto" => array("ini_monto", "")));
    }
//End SetOrder Method

//Prepare Method @2-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @2-781E82CD
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM th_inicio";
        $this->SQL = "SELECT ini_id, th_inicio.ini_jue_id, th_inicio.ini_pro_id, th_inicio.ini_mer_id, th_inicio.ini_tic_id, th_inicio.ini_monto  " .
        "FROM th_inicio {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-CE8A3618
    function SetValues()
    {
        $this->ini_pro_id->SetDBValue(trim($this->f("ini_pro_id")));
        $this->ini_mer_id->SetDBValue(trim($this->f("ini_mer_id")));
        $this->ini_tic_id->SetDBValue(trim($this->f("ini_tic_id")));
        $this->ini_monto->SetDBValue(trim($this->f("ini_monto")));
        $this->Alt_ini_pro_id->SetDBValue(trim($this->f("ini_pro_id")));
        $this->Alt_ini_mer_id->SetDBValue(trim($this->f("ini_mer_id")));
        $this->Alt_ini_tic_id->SetDBValue(trim($this->f("ini_tic_id")));
        $this->Alt_ini_monto->SetDBValue(trim($this->f("ini_monto")));
    }
//End SetValues Method

} //End th_inicioDataSource Class @2-FCB6E20C

class clsRecordth_inicio1 { //th_inicio1 Class @32-E479FDEB

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

//Class_Initialize Event @32-7B84C4CB
    function clsRecordth_inicio1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record th_inicio1/Error";
        $this->DataSource = new clsth_inicio1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "th_inicio1";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->ini_pro_id = new clsControl(ccsListBox, "ini_pro_id", "Ini Pro Id", ccsInteger, "", CCGetRequestParam("ini_pro_id", $Method), $this);
            $this->ini_pro_id->DSType = dsTable;
            list($this->ini_pro_id->BoundColumn, $this->ini_pro_id->TextColumn, $this->ini_pro_id->DBFormat) = array("pro_id", "pro_nombre", "");
            $this->ini_pro_id->DataSource = new clsDBsiges();
            $this->ini_pro_id->ds = & $this->ini_pro_id->DataSource;
            $this->ini_pro_id->DataSource->SQL = "SELECT *  " .
"FROM tb_productos {SQL_Where} {SQL_OrderBy}";
            $this->ini_pro_id->DataSource->Parameters["urljue_id"] = CCGetFromGet("jue_id", "");
            $this->ini_pro_id->DataSource->wp = new clsSQLParameters();
            $this->ini_pro_id->DataSource->wp->AddParameter("1", "urljue_id", ccsInteger, "", "", $this->ini_pro_id->DataSource->Parameters["urljue_id"], "", true);
            $this->ini_pro_id->DataSource->wp->Criterion[1] = $this->ini_pro_id->DataSource->wp->Operation(opEqual, "pro_jue_id", $this->ini_pro_id->DataSource->wp->GetDBValue("1"), $this->ini_pro_id->DataSource->ToSQL($this->ini_pro_id->DataSource->wp->GetDBValue("1"), ccsInteger),true);
            $this->ini_pro_id->DataSource->Where = 
                 $this->ini_pro_id->DataSource->wp->Criterion[1];
            $this->ini_pro_id->Required = true;
            $this->ini_mer_id = new clsControl(ccsListBox, "ini_mer_id", "Ini Mer Id", ccsInteger, "", CCGetRequestParam("ini_mer_id", $Method), $this);
            $this->ini_mer_id->DSType = dsTable;
            list($this->ini_mer_id->BoundColumn, $this->ini_mer_id->TextColumn, $this->ini_mer_id->DBFormat) = array("mer_id", "mer_nombre", "");
            $this->ini_mer_id->DataSource = new clsDBsiges();
            $this->ini_mer_id->ds = & $this->ini_mer_id->DataSource;
            $this->ini_mer_id->DataSource->SQL = "SELECT *  " .
"FROM tb_mercados {SQL_Where} {SQL_OrderBy}";
            $this->ini_mer_id->DataSource->Parameters["urljue_id"] = CCGetFromGet("jue_id", "");
            $this->ini_mer_id->DataSource->wp = new clsSQLParameters();
            $this->ini_mer_id->DataSource->wp->AddParameter("1", "urljue_id", ccsInteger, "", "", $this->ini_mer_id->DataSource->Parameters["urljue_id"], "", true);
            $this->ini_mer_id->DataSource->wp->Criterion[1] = $this->ini_mer_id->DataSource->wp->Operation(opEqual, "mer_jue_id", $this->ini_mer_id->DataSource->wp->GetDBValue("1"), $this->ini_mer_id->DataSource->ToSQL($this->ini_mer_id->DataSource->wp->GetDBValue("1"), ccsInteger),true);
            $this->ini_mer_id->DataSource->Where = 
                 $this->ini_mer_id->DataSource->wp->Criterion[1];
            $this->ini_mer_id->Required = true;
            $this->ini_tic_id = new clsControl(ccsListBox, "ini_tic_id", "Ini Tic Id", ccsInteger, "", CCGetRequestParam("ini_tic_id", $Method), $this);
            $this->ini_tic_id->DSType = dsTable;
            list($this->ini_tic_id->BoundColumn, $this->ini_tic_id->TextColumn, $this->ini_tic_id->DBFormat) = array("cli_id", "cli_nombre", "");
            $this->ini_tic_id->DataSource = new clsDBsiges();
            $this->ini_tic_id->ds = & $this->ini_tic_id->DataSource;
            $this->ini_tic_id->DataSource->SQL = "SELECT *  " .
"FROM tb_tipoclientes {SQL_Where} {SQL_OrderBy}";
            $this->ini_tic_id->DataSource->Parameters["urljue_id"] = CCGetFromGet("jue_id", "");
            $this->ini_tic_id->DataSource->wp = new clsSQLParameters();
            $this->ini_tic_id->DataSource->wp->AddParameter("1", "urljue_id", ccsInteger, "", "", $this->ini_tic_id->DataSource->Parameters["urljue_id"], "", true);
            $this->ini_tic_id->DataSource->wp->Criterion[1] = $this->ini_tic_id->DataSource->wp->Operation(opEqual, "cli_jue_id", $this->ini_tic_id->DataSource->wp->GetDBValue("1"), $this->ini_tic_id->DataSource->ToSQL($this->ini_tic_id->DataSource->wp->GetDBValue("1"), ccsInteger),true);
            $this->ini_tic_id->DataSource->Where = 
                 $this->ini_tic_id->DataSource->wp->Criterion[1];
            $this->ini_tic_id->Required = true;
            $this->ini_monto = new clsControl(ccsTextBox, "ini_monto", "Ini Monto", ccsFloat, "", CCGetRequestParam("ini_monto", $Method), $this);
            $this->ini_monto->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->ini_jue_id = new clsControl(ccsHidden, "ini_jue_id", "Ini Jue Id", ccsInteger, "", CCGetRequestParam("ini_jue_id", $Method), $this);
            $this->ini_jue_id->Required = true;
            if(!$this->FormSubmitted) {
                if(!is_array($this->ini_jue_id->Value) && !strlen($this->ini_jue_id->Value) && $this->ini_jue_id->Value !== false)
                    $this->ini_jue_id->SetText(CCGetParam("jue_id"));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @32-E5A2D263
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlini_id"] = CCGetFromGet("ini_id", "");
    }
//End Initialize Method

//Validate Method @32-565FB998
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ini_pro_id->Validate() && $Validation);
        $Validation = ($this->ini_mer_id->Validate() && $Validation);
        $Validation = ($this->ini_tic_id->Validate() && $Validation);
        $Validation = ($this->ini_monto->Validate() && $Validation);
        $Validation = ($this->ini_jue_id->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ini_pro_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ini_mer_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ini_tic_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ini_monto->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ini_jue_id->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @32-D7909AE9
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ini_pro_id->Errors->Count());
        $errors = ($errors || $this->ini_mer_id->Errors->Count());
        $errors = ($errors || $this->ini_tic_id->Errors->Count());
        $errors = ($errors || $this->ini_monto->Errors->Count());
        $errors = ($errors || $this->ini_jue_id->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @32-2D11ABF2
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
            }
        }
        $Redirect = "inicio.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
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

//InsertRow Method @32-E43307EB
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ini_pro_id->SetValue($this->ini_pro_id->GetValue());
        $this->DataSource->ini_mer_id->SetValue($this->ini_mer_id->GetValue());
        $this->DataSource->ini_tic_id->SetValue($this->ini_tic_id->GetValue());
        $this->DataSource->ini_monto->SetValue($this->ini_monto->GetValue());
        $this->DataSource->ini_jue_id->SetValue($this->ini_jue_id->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @32-7032430C
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->ini_pro_id->SetValue($this->ini_pro_id->GetValue());
        $this->DataSource->ini_mer_id->SetValue($this->ini_mer_id->GetValue());
        $this->DataSource->ini_tic_id->SetValue($this->ini_tic_id->GetValue());
        $this->DataSource->ini_monto->SetValue($this->ini_monto->GetValue());
        $this->DataSource->ini_jue_id->SetValue($this->ini_jue_id->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

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

//Show Method @32-3B6BA16A
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->ini_pro_id->Prepare();
        $this->ini_mer_id->Prepare();
        $this->ini_tic_id->Prepare();

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
                    $this->ini_pro_id->SetValue($this->DataSource->ini_pro_id->GetValue());
                    $this->ini_mer_id->SetValue($this->DataSource->ini_mer_id->GetValue());
                    $this->ini_tic_id->SetValue($this->DataSource->ini_tic_id->GetValue());
                    $this->ini_monto->SetValue($this->DataSource->ini_monto->GetValue());
                    $this->ini_jue_id->SetValue($this->DataSource->ini_jue_id->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ini_pro_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ini_mer_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ini_tic_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ini_monto->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ini_jue_id->Errors->ToString());
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

        $this->ini_pro_id->Show();
        $this->ini_mer_id->Show();
        $this->ini_tic_id->Show();
        $this->ini_monto->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->ini_jue_id->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End th_inicio1 Class @32-FCB6E20C

class clsth_inicio1DataSource extends clsDBsiges {  //th_inicio1DataSource Class @32-73EBF646

//DataSource Variables @32-F2A078A3
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
    var $ini_pro_id;
    var $ini_mer_id;
    var $ini_tic_id;
    var $ini_monto;
    var $ini_jue_id;
//End DataSource Variables

//DataSourceClass_Initialize Event @32-688F46F1
    function clsth_inicio1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record th_inicio1/Error";
        $this->Initialize();
        $this->ini_pro_id = new clsField("ini_pro_id", ccsInteger, "");
        $this->ini_mer_id = new clsField("ini_mer_id", ccsInteger, "");
        $this->ini_tic_id = new clsField("ini_tic_id", ccsInteger, "");
        $this->ini_monto = new clsField("ini_monto", ccsFloat, "");
        $this->ini_jue_id = new clsField("ini_jue_id", ccsInteger, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @32-0036DCB8
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlini_id", ccsInteger, "", "", $this->Parameters["urlini_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "ini_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @32-FC61083F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM th_inicio {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @32-35E66737
    function SetValues()
    {
        $this->ini_pro_id->SetDBValue(trim($this->f("ini_pro_id")));
        $this->ini_mer_id->SetDBValue(trim($this->f("ini_mer_id")));
        $this->ini_tic_id->SetDBValue(trim($this->f("ini_tic_id")));
        $this->ini_monto->SetDBValue(trim($this->f("ini_monto")));
        $this->ini_jue_id->SetDBValue(trim($this->f("ini_jue_id")));
    }
//End SetValues Method

//Insert Method @32-E2F06241
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO th_inicio ("
             . "ini_pro_id, "
             . "ini_mer_id, "
             . "ini_tic_id, "
             . "ini_monto, "
             . "ini_jue_id"
             . ") VALUES ("
             . $this->ToSQL($this->ini_pro_id->GetDBValue(), $this->ini_pro_id->DataType) . ", "
             . $this->ToSQL($this->ini_mer_id->GetDBValue(), $this->ini_mer_id->DataType) . ", "
             . $this->ToSQL($this->ini_tic_id->GetDBValue(), $this->ini_tic_id->DataType) . ", "
             . $this->ToSQL($this->ini_monto->GetDBValue(), $this->ini_monto->DataType) . ", "
             . $this->ToSQL($this->ini_jue_id->GetDBValue(), $this->ini_jue_id->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @32-97AC18C1
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE th_inicio SET "
             . "ini_pro_id=" . $this->ToSQL($this->ini_pro_id->GetDBValue(), $this->ini_pro_id->DataType) . ", "
             . "ini_mer_id=" . $this->ToSQL($this->ini_mer_id->GetDBValue(), $this->ini_mer_id->DataType) . ", "
             . "ini_tic_id=" . $this->ToSQL($this->ini_tic_id->GetDBValue(), $this->ini_tic_id->DataType) . ", "
             . "ini_monto=" . $this->ToSQL($this->ini_monto->GetDBValue(), $this->ini_monto->DataType) . ", "
             . "ini_jue_id=" . $this->ToSQL($this->ini_jue_id->GetDBValue(), $this->ini_jue_id->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @32-6211F40E
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM th_inicio";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End th_inicio1DataSource Class @32-FCB6E20C

//Initialize Page @1-ED3A6585
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
$TemplateFileName = "inicio.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-ED7CB2E0
CCSecurityRedirect("2", "");
//End Authenticate User

//Include events file @1-90AE3BEB
include("./inicio_events.php");
//End Include events file

//Initialize Objects @1-DB555375
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$th_inicio = new clsGridth_inicio("", $MainPage);
$th_inicio1 = new clsRecordth_inicio1("", $MainPage);
$MainPage->th_inicio = & $th_inicio;
$MainPage->th_inicio1 = & $th_inicio1;
$th_inicio->Initialize();
$th_inicio1->Initialize();

BindEvents();

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

//Execute Components @1-A90F7CCF
$th_inicio1->Operation();
//End Execute Components

//Go to destination page @1-6830CC5D
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($th_inicio);
    unset($th_inicio1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-A2C10B46
$th_inicio->Show();
$th_inicio1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-6CD0CEBF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($th_inicio);
unset($th_inicio1);
unset($Tpl);
//End Unload Page


?>

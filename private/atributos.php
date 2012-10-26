<?php
//Include Common Files @1-05737613
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "atributos.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridtb_atributos { //tb_atributos class @2-23966EC7

//Variables @2-4BBDD204

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
    var $Sorter_atr_nombre;
    var $Sorter_atr_tipoValor;
    var $Sorter_atr_sw;
//End Variables

//Class_Initialize Event @2-8D056C1E
    function clsGridtb_atributos($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_atributos";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_atributos";
        $this->DataSource = new clstb_atributosDataSource($this);
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
        $this->SorterName = CCGetParam("tb_atributosOrder", "");
        $this->SorterDirection = CCGetParam("tb_atributosDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "atributos.php";
        $this->atr_nombre = & new clsControl(ccsLabel, "atr_nombre", "atr_nombre", ccsText, "", CCGetRequestParam("atr_nombre", ccsGet), $this);
        $this->atr_tipoValor = & new clsControl(ccsLabel, "atr_tipoValor", "atr_tipoValor", ccsText, "", CCGetRequestParam("atr_tipoValor", ccsGet), $this);
        $this->atr_sw = & new clsControl(ccsLabel, "atr_sw", "atr_sw", ccsText, "", CCGetRequestParam("atr_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "atributos.php";
        $this->Alt_atr_nombre = & new clsControl(ccsLabel, "Alt_atr_nombre", "Alt_atr_nombre", ccsText, "", CCGetRequestParam("Alt_atr_nombre", ccsGet), $this);
        $this->Alt_atr_tipoValor = & new clsControl(ccsLabel, "Alt_atr_tipoValor", "Alt_atr_tipoValor", ccsText, "", CCGetRequestParam("Alt_atr_tipoValor", ccsGet), $this);
        $this->Alt_atr_sw = & new clsControl(ccsLabel, "Alt_atr_sw", "Alt_atr_sw", ccsText, "", CCGetRequestParam("Alt_atr_sw", ccsGet), $this);
        $this->tb_atributos_TotalRecords = & new clsControl(ccsLabel, "tb_atributos_TotalRecords", "tb_atributos_TotalRecords", ccsText, "", CCGetRequestParam("tb_atributos_TotalRecords", ccsGet), $this);
        $this->Sorter_atr_nombre = & new clsSorter($this->ComponentName, "Sorter_atr_nombre", $FileName, $this);
        $this->Sorter_atr_tipoValor = & new clsSorter($this->ComponentName, "Sorter_atr_tipoValor", $FileName, $this);
        $this->Sorter_atr_sw = & new clsSorter($this->ComponentName, "Sorter_atr_sw", $FileName, $this);
        $this->tb_atributos_Insert = & new clsControl(ccsLink, "tb_atributos_Insert", "tb_atributos_Insert", ccsText, "", CCGetRequestParam("tb_atributos_Insert", ccsGet), $this);
        $this->tb_atributos_Insert->Parameters = CCGetQueryString("QueryString", array("atr_id", "ccsForm"));
        $this->tb_atributos_Insert->Page = "atributos.php";
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

//Show Method @2-735469BC
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_atr_nombre"] = CCGetFromGet("s_atr_nombre", "");
        $this->DataSource->Parameters["urls_atr_tipoValor"] = CCGetFromGet("s_atr_tipoValor", "");
        $this->DataSource->Parameters["urls_atr_sw"] = CCGetFromGet("s_atr_sw", "");

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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "atr_id", $this->DataSource->f("atr_id"));
                    $this->atr_nombre->SetValue($this->DataSource->atr_nombre->GetValue());
                    $this->atr_tipoValor->SetValue($this->DataSource->atr_tipoValor->GetValue());
                    $this->atr_sw->SetValue($this->DataSource->atr_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->atr_nombre->Show();
                    $this->atr_tipoValor->Show();
                    $this->atr_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "atr_id", $this->DataSource->f("atr_id"));
                    $this->Alt_atr_nombre->SetValue($this->DataSource->Alt_atr_nombre->GetValue());
                    $this->Alt_atr_tipoValor->SetValue($this->DataSource->Alt_atr_tipoValor->GetValue());
                    $this->Alt_atr_sw->SetValue($this->DataSource->Alt_atr_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_atr_nombre->Show();
                    $this->Alt_atr_tipoValor->Show();
                    $this->Alt_atr_sw->Show();
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
        $this->tb_atributos_TotalRecords->Show();
        $this->Sorter_atr_nombre->Show();
        $this->Sorter_atr_tipoValor->Show();
        $this->Sorter_atr_sw->Show();
        $this->tb_atributos_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-14F3A421
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->atr_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->atr_tipoValor->Errors->ToString());
        $errors = ComposeStrings($errors, $this->atr_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_atr_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_atr_tipoValor->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_atr_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_atributos Class @2-FCB6E20C

class clstb_atributosDataSource extends clsDBsiges {  //tb_atributosDataSource Class @2-F676B520

//DataSource Variables @2-1A27112D
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $atr_nombre;
    var $atr_tipoValor;
    var $atr_sw;
    var $Alt_atr_nombre;
    var $Alt_atr_tipoValor;
    var $Alt_atr_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-0B169CD2
    function clstb_atributosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_atributos";
        $this->Initialize();
        $this->atr_nombre = new clsField("atr_nombre", ccsText, "");
        $this->atr_tipoValor = new clsField("atr_tipoValor", ccsText, "");
        $this->atr_sw = new clsField("atr_sw", ccsText, "");
        $this->Alt_atr_nombre = new clsField("Alt_atr_nombre", ccsText, "");
        $this->Alt_atr_tipoValor = new clsField("Alt_atr_tipoValor", ccsText, "");
        $this->Alt_atr_sw = new clsField("Alt_atr_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-781827A1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "atr_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_atr_nombre" => array("atr_nombre", ""), 
            "Sorter_atr_tipoValor" => array("atr_tipoValor", ""), 
            "Sorter_atr_sw" => array("atr_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-45E4F439
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_atr_nombre", ccsText, "", "", $this->Parameters["urls_atr_nombre"], "", false);
        $this->wp->AddParameter("2", "urls_atr_tipoValor", ccsText, "", "", $this->Parameters["urls_atr_tipoValor"], "", false);
        $this->wp->AddParameter("3", "urls_atr_sw", ccsText, "", "", $this->Parameters["urls_atr_sw"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "atr_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "atr_tipoValor", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "atr_sw", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @2-C7F9D4FB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_atributos";
        $this->SQL = "SELECT *  " .
        "FROM tb_atributos {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-5CADF082
    function SetValues()
    {
        $this->atr_nombre->SetDBValue($this->f("atr_nombre"));
        $this->atr_tipoValor->SetDBValue($this->f("atr_tipoValor"));
        $this->atr_sw->SetDBValue($this->f("atr_sw"));
        $this->Alt_atr_nombre->SetDBValue($this->f("atr_nombre"));
        $this->Alt_atr_tipoValor->SetDBValue($this->f("atr_tipoValor"));
        $this->Alt_atr_sw->SetDBValue($this->f("atr_sw"));
    }
//End SetValues Method

} //End tb_atributosDataSource Class @2-FCB6E20C

class clsRecordtb_atributos1 { //tb_atributos1 Class @29-6567FD00

//Variables @29-F607D3A5

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

//Class_Initialize Event @29-CCFF22C6
    function clsRecordtb_atributos1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_atributos1/Error";
        $this->DataSource = new clstb_atributos1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_atributos1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->atr_nombre = & new clsControl(ccsTextBox, "atr_nombre", "Nombre", ccsText, "", CCGetRequestParam("atr_nombre", $Method), $this);
            $this->atr_nombre->Required = true;
            $this->atr_tipoValor = & new clsControl(ccsListBox, "atr_tipoValor", "Tipo Valor", ccsText, "", CCGetRequestParam("atr_tipoValor", $Method), $this);
            $this->atr_tipoValor->DSType = dsListOfValues;
            $this->atr_tipoValor->Values = array(array("P", "Porcentual"), array("Q", "Cantidad"));
            $this->atr_tipoValor->Required = true;
            $this->atr_sw = & new clsControl(ccsListBox, "atr_sw", "Estado", ccsText, "", CCGetRequestParam("atr_sw", $Method), $this);
            $this->atr_sw->DSType = dsListOfValues;
            $this->atr_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->atr_sw->Required = true;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @29-D2585FA5
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlatr_id"] = CCGetFromGet("atr_id", "");
    }
//End Initialize Method

//Validate Method @29-C00B6508
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->atr_nombre->Validate() && $Validation);
        $Validation = ($this->atr_tipoValor->Validate() && $Validation);
        $Validation = ($this->atr_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->atr_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->atr_tipoValor->Errors->Count() == 0);
        $Validation =  $Validation && ($this->atr_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @29-06F6ECCD
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->atr_nombre->Errors->Count());
        $errors = ($errors || $this->atr_tipoValor->Errors->Count());
        $errors = ($errors || $this->atr_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @29-717FBBDF
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
        $Redirect = "atributos.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @29-46C33C31
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->atr_nombre->SetValue($this->atr_nombre->GetValue());
        $this->DataSource->atr_tipoValor->SetValue($this->atr_tipoValor->GetValue());
        $this->DataSource->atr_sw->SetValue($this->atr_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @29-329A3DDB
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->atr_nombre->SetValue($this->atr_nombre->GetValue());
        $this->DataSource->atr_tipoValor->SetValue($this->atr_tipoValor->GetValue());
        $this->DataSource->atr_sw->SetValue($this->atr_sw->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @29-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @29-ADAB241D
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->atr_tipoValor->Prepare();
        $this->atr_sw->Prepare();

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
                    $this->atr_nombre->SetValue($this->DataSource->atr_nombre->GetValue());
                    $this->atr_tipoValor->SetValue($this->DataSource->atr_tipoValor->GetValue());
                    $this->atr_sw->SetValue($this->DataSource->atr_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->atr_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->atr_tipoValor->Errors->ToString());
            $Error = ComposeStrings($Error, $this->atr_sw->Errors->ToString());
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

        $this->atr_nombre->Show();
        $this->atr_tipoValor->Show();
        $this->atr_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_atributos1 Class @29-FCB6E20C

class clstb_atributos1DataSource extends clsDBsiges {  //tb_atributos1DataSource Class @29-C9F2141E

//DataSource Variables @29-4657113F
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
    var $atr_nombre;
    var $atr_tipoValor;
    var $atr_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @29-06326F9E
    function clstb_atributos1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_atributos1/Error";
        $this->Initialize();
        $this->atr_nombre = new clsField("atr_nombre", ccsText, "");
        $this->atr_tipoValor = new clsField("atr_tipoValor", ccsText, "");
        $this->atr_sw = new clsField("atr_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @29-AD07D1E4
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlatr_id", ccsInteger, "", "", $this->Parameters["urlatr_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "atr_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @29-78C67968
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_atributos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @29-814CCB98
    function SetValues()
    {
        $this->atr_nombre->SetDBValue($this->f("atr_nombre"));
        $this->atr_tipoValor->SetDBValue($this->f("atr_tipoValor"));
        $this->atr_sw->SetDBValue($this->f("atr_sw"));
    }
//End SetValues Method

//Insert Method @29-8E7EB5A7
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_atributos ("
             . "atr_nombre, "
             . "atr_tipoValor, "
             . "atr_sw"
             . ") VALUES ("
             . $this->ToSQL($this->atr_nombre->GetDBValue(), $this->atr_nombre->DataType) . ", "
             . $this->ToSQL($this->atr_tipoValor->GetDBValue(), $this->atr_tipoValor->DataType) . ", "
             . $this->ToSQL($this->atr_sw->GetDBValue(), $this->atr_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @29-861C380E
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_atributos SET "
             . "atr_nombre=" . $this->ToSQL($this->atr_nombre->GetDBValue(), $this->atr_nombre->DataType) . ", "
             . "atr_tipoValor=" . $this->ToSQL($this->atr_tipoValor->GetDBValue(), $this->atr_tipoValor->DataType) . ", "
             . "atr_sw=" . $this->ToSQL($this->atr_sw->GetDBValue(), $this->atr_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @29-EBFD9B7E
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_atributos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_atributos1DataSource Class @29-FCB6E20C

//Initialize Page @1-0DD4E201
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
$TemplateFileName = "atributos.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-E2DFC390
include("./atributos_events.php");
//End Include events file

//Initialize Objects @1-E2A9DDA1
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_atributos = & new clsGridtb_atributos("", $MainPage);
$tb_atributos1 = & new clsRecordtb_atributos1("", $MainPage);
$MainPage->tb_atributos = & $tb_atributos;
$MainPage->tb_atributos1 = & $tb_atributos1;
$tb_atributos->Initialize();
$tb_atributos1->Initialize();

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

//Execute Components @1-209B9D9B
$tb_atributos1->Operation();
//End Execute Components

//Go to destination page @1-33F28B5C
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_atributos);
    unset($tb_atributos1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-E9656D1F
$tb_atributos->Show();
$tb_atributos1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-BCF2EDA8
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_atributos);
unset($tb_atributos1);
unset($Tpl);
//End Unload Page


?>

<?php
//Include Common Files @1-CE0BF77F
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "items.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files



class clsGridtb_items { //tb_items class @2-317E8AD2

//Variables @2-0737022B

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
    var $Sorter_ite_nombre;
    var $Sorter_ite_orden;
    var $Sorter_ite_etiqueta;
    var $Sorter_ite_id_itemSuperior;
    var $Sorter_ite_sw;
//End Variables

//Class_Initialize Event @2-77CC57C2
    function clsGridtb_items($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_items";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_items";
        $this->DataSource = new clstb_itemsDataSource($this);
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
        $this->SorterName = CCGetParam("tb_itemsOrder", "");
        $this->SorterDirection = CCGetParam("tb_itemsDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "items.php";
        $this->operaciones = & new clsControl(ccsLink, "operaciones", "operaciones", ccsText, "", CCGetRequestParam("operaciones", ccsGet), $this);
        $this->operaciones->Page = "operacionesatr.php";
        $this->ite_nombre = & new clsControl(ccsLabel, "ite_nombre", "ite_nombre", ccsText, "", CCGetRequestParam("ite_nombre", ccsGet), $this);
        $this->ite_orden = & new clsControl(ccsLabel, "ite_orden", "ite_orden", ccsInteger, "", CCGetRequestParam("ite_orden", ccsGet), $this);
        $this->ite_etiqueta = & new clsControl(ccsLabel, "ite_etiqueta", "ite_etiqueta", ccsText, "", CCGetRequestParam("ite_etiqueta", ccsGet), $this);
        $this->ite_id_itemSuperior = & new clsControl(ccsLabel, "ite_id_itemSuperior", "ite_id_itemSuperior", ccsInteger, "", CCGetRequestParam("ite_id_itemSuperior", ccsGet), $this);
        $this->ite_sw = & new clsControl(ccsLabel, "ite_sw", "ite_sw", ccsText, "", CCGetRequestParam("ite_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "items.php";
        $this->Alt_operaciones = & new clsControl(ccsLink, "Alt_operaciones", "Alt_operaciones", ccsText, "", CCGetRequestParam("Alt_operaciones", ccsGet), $this);
        $this->Alt_operaciones->Page = "operacionesatr.php";
        $this->Alt_ite_nombre = & new clsControl(ccsLabel, "Alt_ite_nombre", "Alt_ite_nombre", ccsText, "", CCGetRequestParam("Alt_ite_nombre", ccsGet), $this);
        $this->Alt_ite_orden = & new clsControl(ccsLabel, "Alt_ite_orden", "Alt_ite_orden", ccsInteger, "", CCGetRequestParam("Alt_ite_orden", ccsGet), $this);
        $this->Alt_ite_etiqueta = & new clsControl(ccsLabel, "Alt_ite_etiqueta", "Alt_ite_etiqueta", ccsText, "", CCGetRequestParam("Alt_ite_etiqueta", ccsGet), $this);
        $this->Alt_ite_id_itemSuperior = & new clsControl(ccsLabel, "Alt_ite_id_itemSuperior", "Alt_ite_id_itemSuperior", ccsInteger, "", CCGetRequestParam("Alt_ite_id_itemSuperior", ccsGet), $this);
        $this->Alt_ite_sw = & new clsControl(ccsLabel, "Alt_ite_sw", "Alt_ite_sw", ccsText, "", CCGetRequestParam("Alt_ite_sw", ccsGet), $this);
        $this->tb_items_TotalRecords = & new clsControl(ccsLabel, "tb_items_TotalRecords", "tb_items_TotalRecords", ccsText, "", CCGetRequestParam("tb_items_TotalRecords", ccsGet), $this);
        $this->Sorter_ite_nombre = & new clsSorter($this->ComponentName, "Sorter_ite_nombre", $FileName, $this);
        $this->Sorter_ite_orden = & new clsSorter($this->ComponentName, "Sorter_ite_orden", $FileName, $this);
        $this->Sorter_ite_etiqueta = & new clsSorter($this->ComponentName, "Sorter_ite_etiqueta", $FileName, $this);
        $this->Sorter_ite_id_itemSuperior = & new clsSorter($this->ComponentName, "Sorter_ite_id_itemSuperior", $FileName, $this);
        $this->Sorter_ite_sw = & new clsSorter($this->ComponentName, "Sorter_ite_sw", $FileName, $this);
        $this->tb_items_Insert = & new clsControl(ccsLink, "tb_items_Insert", "tb_items_Insert", ccsText, "", CCGetRequestParam("tb_items_Insert", ccsGet), $this);
        $this->tb_items_Insert->Parameters = CCGetQueryString("QueryString", array("ite_id", "ccsForm"));
        $this->tb_items_Insert->Page = "items.php";
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

//Show Method @2-9A6C6377
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_ite_nombre"] = CCGetFromGet("s_ite_nombre", "");

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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "ite_id", $this->DataSource->f("ite_id"));
                    $this->operaciones->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->operaciones->Parameters = CCAddParam($this->operaciones->Parameters, "ite_id", $this->DataSource->f("ite_id"));
                    $this->ite_nombre->SetValue($this->DataSource->ite_nombre->GetValue());
                    $this->ite_orden->SetValue($this->DataSource->ite_orden->GetValue());
                    $this->ite_etiqueta->SetValue($this->DataSource->ite_etiqueta->GetValue());
                    $this->ite_id_itemSuperior->SetValue($this->DataSource->ite_id_itemSuperior->GetValue());
                    $this->ite_sw->SetValue($this->DataSource->ite_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->operaciones->Show();
                    $this->ite_nombre->Show();
                    $this->ite_orden->Show();
                    $this->ite_etiqueta->Show();
                    $this->ite_id_itemSuperior->Show();
                    $this->ite_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "ite_id", $this->DataSource->f("ite_id"));
                    $this->Alt_operaciones->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_operaciones->Parameters = CCAddParam($this->Alt_operaciones->Parameters, "ite_id", $this->DataSource->f("ite_id"));
                    $this->Alt_ite_nombre->SetValue($this->DataSource->Alt_ite_nombre->GetValue());
                    $this->Alt_ite_orden->SetValue($this->DataSource->Alt_ite_orden->GetValue());
                    $this->Alt_ite_etiqueta->SetValue($this->DataSource->Alt_ite_etiqueta->GetValue());
                    $this->Alt_ite_id_itemSuperior->SetValue($this->DataSource->Alt_ite_id_itemSuperior->GetValue());
                    $this->Alt_ite_sw->SetValue($this->DataSource->Alt_ite_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_operaciones->Show();
                    $this->Alt_ite_nombre->Show();
                    $this->Alt_ite_orden->Show();
                    $this->Alt_ite_etiqueta->Show();
                    $this->Alt_ite_id_itemSuperior->Show();
                    $this->Alt_ite_sw->Show();
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
        $this->tb_items_TotalRecords->Show();
        $this->Sorter_ite_nombre->Show();
        $this->Sorter_ite_orden->Show();
        $this->Sorter_ite_etiqueta->Show();
        $this->Sorter_ite_id_itemSuperior->Show();
        $this->Sorter_ite_sw->Show();
        $this->tb_items_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-2C0CE1AD
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->operaciones->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ite_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ite_orden->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ite_etiqueta->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ite_id_itemSuperior->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ite_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_operaciones->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ite_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ite_orden->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ite_etiqueta->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ite_id_itemSuperior->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_ite_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_items Class @2-FCB6E20C

class clstb_itemsDataSource extends clsDBsiges {  //tb_itemsDataSource Class @2-3A90C0AD

//DataSource Variables @2-D94A9FA0
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ite_nombre;
    var $ite_orden;
    var $ite_etiqueta;
    var $ite_id_itemSuperior;
    var $ite_sw;
    var $Alt_ite_nombre;
    var $Alt_ite_orden;
    var $Alt_ite_etiqueta;
    var $Alt_ite_id_itemSuperior;
    var $Alt_ite_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-B9EF726E
    function clstb_itemsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_items";
        $this->Initialize();
        $this->ite_nombre = new clsField("ite_nombre", ccsText, "");
        $this->ite_orden = new clsField("ite_orden", ccsInteger, "");
        $this->ite_etiqueta = new clsField("ite_etiqueta", ccsText, "");
        $this->ite_id_itemSuperior = new clsField("ite_id_itemSuperior", ccsInteger, "");
        $this->ite_sw = new clsField("ite_sw", ccsText, "");
        $this->Alt_ite_nombre = new clsField("Alt_ite_nombre", ccsText, "");
        $this->Alt_ite_orden = new clsField("Alt_ite_orden", ccsInteger, "");
        $this->Alt_ite_etiqueta = new clsField("Alt_ite_etiqueta", ccsText, "");
        $this->Alt_ite_id_itemSuperior = new clsField("Alt_ite_id_itemSuperior", ccsInteger, "");
        $this->Alt_ite_sw = new clsField("Alt_ite_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-D5C8338D
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ite_orden";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ite_nombre" => array("ite_nombre", ""), 
            "Sorter_ite_orden" => array("ite_orden", ""), 
            "Sorter_ite_etiqueta" => array("ite_etiqueta", ""), 
            "Sorter_ite_id_itemSuperior" => array("ite_id_itemSuperior", ""), 
            "Sorter_ite_sw" => array("ite_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-AC123C3A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_ite_nombre", ccsText, "", "", $this->Parameters["urls_ite_nombre"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "ite_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = "ite_apl=2";
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-6B5C70AF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_items";
        $this->SQL = "SELECT *  " .
        "FROM tb_items {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-D762CC0E
    function SetValues()
    {
        $this->ite_nombre->SetDBValue($this->f("ite_nombre"));
        $this->ite_orden->SetDBValue(trim($this->f("ite_orden")));
        $this->ite_etiqueta->SetDBValue($this->f("ite_etiqueta"));
        $this->ite_id_itemSuperior->SetDBValue(trim($this->f("ite_id_itemSuperior")));
        $this->ite_sw->SetDBValue($this->f("ite_sw"));
        $this->Alt_ite_nombre->SetDBValue($this->f("ite_nombre"));
        $this->Alt_ite_orden->SetDBValue(trim($this->f("ite_orden")));
        $this->Alt_ite_etiqueta->SetDBValue($this->f("ite_etiqueta"));
        $this->Alt_ite_id_itemSuperior->SetDBValue(trim($this->f("ite_id_itemSuperior")));
        $this->Alt_ite_sw->SetDBValue($this->f("ite_sw"));
    }
//End SetValues Method

} //End tb_itemsDataSource Class @2-FCB6E20C

class clsRecordtb_items1 { //tb_items1 Class @31-C9945F72

//Variables @31-F607D3A5

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

//Class_Initialize Event @31-03AF19C9
    function clsRecordtb_items1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_items1/Error";
        $this->DataSource = new clstb_items1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_items1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->ite_nombre = & new clsControl(ccsTextBox, "ite_nombre", "Nombre", ccsText, "", CCGetRequestParam("ite_nombre", $Method), $this);
            $this->ite_nombre->Required = true;
            $this->ite_etiqueta = & new clsControl(ccsTextBox, "ite_etiqueta", "Etiqueta", ccsText, "", CCGetRequestParam("ite_etiqueta", $Method), $this);
            $this->apl = & new clsControl(ccsHidden, "apl", "Aplicación", ccsText, "", CCGetRequestParam("apl", $Method), $this);
            $this->apl->Required = true;
            $this->ite_orden = & new clsControl(ccsTextBox, "ite_orden", "Orden", ccsInteger, "", CCGetRequestParam("ite_orden", $Method), $this);
            $this->ite_orden->Required = true;
            $this->ite_id_itemSuperior = & new clsControl(ccsListBox, "ite_id_itemSuperior", "Item Superior", ccsInteger, "", CCGetRequestParam("ite_id_itemSuperior", $Method), $this);
            $this->ite_id_itemSuperior->DSType = dsTable;
            list($this->ite_id_itemSuperior->BoundColumn, $this->ite_id_itemSuperior->TextColumn, $this->ite_id_itemSuperior->DBFormat) = array("ite_id", "ite_nombre", "");
            $this->ite_id_itemSuperior->DataSource = new clsDBsiges();
            $this->ite_id_itemSuperior->ds = & $this->ite_id_itemSuperior->DataSource;
            $this->ite_id_itemSuperior->DataSource->SQL = "SELECT *  " .
"FROM tb_items {SQL_Where} {SQL_OrderBy}";
            $this->ite_id_itemSuperior->DataSource->wp = new clsSQLParameters();
            $this->ite_id_itemSuperior->DataSource->wp->Criterion[1] = "ite_id_itemSuperior is Null";
            $this->ite_id_itemSuperior->DataSource->wp->Criterion[2] = "ite_apl=2";
            $this->ite_id_itemSuperior->DataSource->Where = $this->ite_id_itemSuperior->DataSource->wp->opAND(
                 false, 
                 $this->ite_id_itemSuperior->DataSource->wp->Criterion[1], 
                 $this->ite_id_itemSuperior->DataSource->wp->Criterion[2]);
            $this->ite_sw = & new clsControl(ccsListBox, "ite_sw", "Estado", ccsText, "", CCGetRequestParam("ite_sw", $Method), $this);
            $this->ite_sw->DSType = dsListOfValues;
            $this->ite_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->ite_sw->Required = true;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->apl->Value) && !strlen($this->apl->Value) && $this->apl->Value !== false)
                    $this->apl->SetText(2);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @31-A50F428F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlite_id"] = CCGetFromGet("ite_id", "");
    }
//End Initialize Method

//Validate Method @31-B84FD914
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ite_nombre->Validate() && $Validation);
        $Validation = ($this->ite_etiqueta->Validate() && $Validation);
        $Validation = ($this->apl->Validate() && $Validation);
        $Validation = ($this->ite_orden->Validate() && $Validation);
        $Validation = ($this->ite_id_itemSuperior->Validate() && $Validation);
        $Validation = ($this->ite_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ite_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_etiqueta->Errors->Count() == 0);
        $Validation =  $Validation && ($this->apl->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_orden->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_id_itemSuperior->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ite_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @31-BD0404D0
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ite_nombre->Errors->Count());
        $errors = ($errors || $this->ite_etiqueta->Errors->Count());
        $errors = ($errors || $this->apl->Errors->Count());
        $errors = ($errors || $this->ite_orden->Errors->Count());
        $errors = ($errors || $this->ite_id_itemSuperior->Errors->Count());
        $errors = ($errors || $this->ite_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @31-0413858D
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
        $Redirect = "items.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @31-E1D61F5B
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ite_nombre->SetValue($this->ite_nombre->GetValue());
        $this->DataSource->ite_etiqueta->SetValue($this->ite_etiqueta->GetValue());
        $this->DataSource->apl->SetValue($this->apl->GetValue());
        $this->DataSource->ite_orden->SetValue($this->ite_orden->GetValue());
        $this->DataSource->ite_id_itemSuperior->SetValue($this->ite_id_itemSuperior->GetValue());
        $this->DataSource->ite_sw->SetValue($this->ite_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @31-2956E104
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->ite_nombre->SetValue($this->ite_nombre->GetValue());
        $this->DataSource->ite_etiqueta->SetValue($this->ite_etiqueta->GetValue());
        $this->DataSource->apl->SetValue($this->apl->GetValue());
        $this->DataSource->ite_orden->SetValue($this->ite_orden->GetValue());
        $this->DataSource->ite_id_itemSuperior->SetValue($this->ite_id_itemSuperior->GetValue());
        $this->DataSource->ite_sw->SetValue($this->ite_sw->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @31-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @31-65A22686
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->ite_id_itemSuperior->Prepare();
        $this->ite_sw->Prepare();

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
                    $this->ite_nombre->SetValue($this->DataSource->ite_nombre->GetValue());
                    $this->ite_etiqueta->SetValue($this->DataSource->ite_etiqueta->GetValue());
                    $this->apl->SetValue($this->DataSource->apl->GetValue());
                    $this->ite_orden->SetValue($this->DataSource->ite_orden->GetValue());
                    $this->ite_id_itemSuperior->SetValue($this->DataSource->ite_id_itemSuperior->GetValue());
                    $this->ite_sw->SetValue($this->DataSource->ite_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ite_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_etiqueta->Errors->ToString());
            $Error = ComposeStrings($Error, $this->apl->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_orden->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_id_itemSuperior->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ite_sw->Errors->ToString());
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

        $this->ite_nombre->Show();
        $this->ite_etiqueta->Show();
        $this->apl->Show();
        $this->ite_orden->Show();
        $this->ite_id_itemSuperior->Show();
        $this->ite_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_items1 Class @31-FCB6E20C

class clstb_items1DataSource extends clsDBsiges {  //tb_items1DataSource Class @31-5A370DF6

//DataSource Variables @31-75F419D8
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
    var $ite_nombre;
    var $ite_etiqueta;
    var $apl;
    var $ite_orden;
    var $ite_id_itemSuperior;
    var $ite_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @31-4C23D177
    function clstb_items1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_items1/Error";
        $this->Initialize();
        $this->ite_nombre = new clsField("ite_nombre", ccsText, "");
        $this->ite_etiqueta = new clsField("ite_etiqueta", ccsText, "");
        $this->apl = new clsField("apl", ccsText, "");
        $this->ite_orden = new clsField("ite_orden", ccsInteger, "");
        $this->ite_id_itemSuperior = new clsField("ite_id_itemSuperior", ccsInteger, "");
        $this->ite_sw = new clsField("ite_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @31-C74CB0A9
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlite_id", ccsInteger, "", "", $this->Parameters["urlite_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "ite_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @31-FDE16342
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_items {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @31-DD96603E
    function SetValues()
    {
        $this->ite_nombre->SetDBValue($this->f("ite_nombre"));
        $this->ite_etiqueta->SetDBValue($this->f("ite_etiqueta"));
        $this->apl->SetDBValue($this->f("ite_apl"));
        $this->ite_orden->SetDBValue(trim($this->f("ite_orden")));
        $this->ite_id_itemSuperior->SetDBValue(trim($this->f("ite_id_itemSuperior")));
        $this->ite_sw->SetDBValue($this->f("ite_sw"));
    }
//End SetValues Method

//Insert Method @31-49A7D786
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_items ("
             . "ite_nombre, "
             . "ite_etiqueta, "
             . "ite_apl, "
             . "ite_orden, "
             . "ite_id_itemSuperior, "
             . "ite_sw"
             . ") VALUES ("
             . $this->ToSQL($this->ite_nombre->GetDBValue(), $this->ite_nombre->DataType) . ", "
             . $this->ToSQL($this->ite_etiqueta->GetDBValue(), $this->ite_etiqueta->DataType) . ", "
             . $this->ToSQL($this->apl->GetDBValue(), $this->apl->DataType) . ", "
             . $this->ToSQL($this->ite_orden->GetDBValue(), $this->ite_orden->DataType) . ", "
             . $this->ToSQL($this->ite_id_itemSuperior->GetDBValue(), $this->ite_id_itemSuperior->DataType) . ", "
             . $this->ToSQL($this->ite_sw->GetDBValue(), $this->ite_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @31-93E37524
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_items SET "
             . "ite_nombre=" . $this->ToSQL($this->ite_nombre->GetDBValue(), $this->ite_nombre->DataType) . ", "
             . "ite_etiqueta=" . $this->ToSQL($this->ite_etiqueta->GetDBValue(), $this->ite_etiqueta->DataType) . ", "
             . "ite_apl=" . $this->ToSQL($this->apl->GetDBValue(), $this->apl->DataType) . ", "
             . "ite_orden=" . $this->ToSQL($this->ite_orden->GetDBValue(), $this->ite_orden->DataType) . ", "
             . "ite_id_itemSuperior=" . $this->ToSQL($this->ite_id_itemSuperior->GetDBValue(), $this->ite_id_itemSuperior->DataType) . ", "
             . "ite_sw=" . $this->ToSQL($this->ite_sw->GetDBValue(), $this->ite_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @31-120EFDD4
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_items";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_items1DataSource Class @31-FCB6E20C

//Initialize Page @1-29CCF3B2
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
$TemplateFileName = "items.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-6BFA97C2
include("./items_events.php");
//End Include events file

//Initialize Objects @1-34504456
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_items = & new clsGridtb_items("", $MainPage);
$tb_items1 = & new clsRecordtb_items1("", $MainPage);
$MainPage->tb_items = & $tb_items;
$MainPage->tb_items1 = & $tb_items1;
$tb_items->Initialize();
$tb_items1->Initialize();

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

//Execute Components @1-F05D1D3C
$tb_items1->Operation();
//End Execute Components

//Go to destination page @1-BEF91D25
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_items);
    unset($tb_items1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-24F6EB73
$tb_items->Show();
$tb_items1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-A21B994E
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_items);
unset($tb_items1);
unset($Tpl);
//End Unload Page


?>

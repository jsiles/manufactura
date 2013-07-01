<?php
//Include Common Files @1-DCCAD65E
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "trimestre.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtb_trimestresSearch { //tb_trimestresSearch Class @3-CFCEED90

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

//Class_Initialize Event @3-9DA73D61
    function clsRecordtb_trimestresSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_trimestresSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_trimestresSearch";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_tri_nombre = new clsControl(ccsTextBox, "s_tri_nombre", "s_tri_nombre", ccsText, "", CCGetRequestParam("s_tri_nombre", $Method), $this);
            $this->ClearParameters = new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_tri_nombre", "ccsForm"));
            $this->ClearParameters->Page = "trimestre.php";
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
        }
    }
//End Class_Initialize Event

//Validate Method @3-F0607ED4
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_tri_nombre->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_tri_nombre->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-A6D94573
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_tri_nombre->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-0590DE04
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
        $Redirect = "trimestre.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "trimestre.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-13D8D3AF
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
            $Error = ComposeStrings($Error, $this->s_tri_nombre->Errors->ToString());
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

        $this->s_tri_nombre->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tb_trimestresSearch Class @3-FCB6E20C

class clsGridtb_trimestres { //tb_trimestres class @2-09694BC7

//Variables @2-E39421FB

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
    var $Sorter_tri_nombre;
    var $Sorter_tri_sw;
//End Variables

//Class_Initialize Event @2-7449E473
    function clsGridtb_trimestres($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_trimestres";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_trimestres";
        $this->DataSource = new clstb_trimestresDataSource($this);
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
        $this->SorterName = CCGetParam("tb_trimestresOrder", "");
        $this->SorterDirection = CCGetParam("tb_trimestresDir", "");

        $this->Detail = new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "trimestre.php";
        $this->tri_nombre = new clsControl(ccsLabel, "tri_nombre", "tri_nombre", ccsText, "", CCGetRequestParam("tri_nombre", ccsGet), $this);
        $this->tri_sw = new clsControl(ccsLabel, "tri_sw", "tri_sw", ccsText, "", CCGetRequestParam("tri_sw", ccsGet), $this);
        $this->Alt_Detail = new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "trimestre.php";
        $this->Alt_tri_nombre = new clsControl(ccsLabel, "Alt_tri_nombre", "Alt_tri_nombre", ccsText, "", CCGetRequestParam("Alt_tri_nombre", ccsGet), $this);
        $this->Alt_tri_sw = new clsControl(ccsLabel, "Alt_tri_sw", "Alt_tri_sw", ccsText, "", CCGetRequestParam("Alt_tri_sw", ccsGet), $this);
        $this->tb_trimestres_TotalRecords = new clsControl(ccsLabel, "tb_trimestres_TotalRecords", "tb_trimestres_TotalRecords", ccsText, "", CCGetRequestParam("tb_trimestres_TotalRecords", ccsGet), $this);
        $this->Sorter_tri_nombre = new clsSorter($this->ComponentName, "Sorter_tri_nombre", $FileName, $this);
        $this->Sorter_tri_sw = new clsSorter($this->ComponentName, "Sorter_tri_sw", $FileName, $this);
        $this->tb_trimestres_Insert = new clsControl(ccsLink, "tb_trimestres_Insert", "tb_trimestres_Insert", ccsText, "", CCGetRequestParam("tb_trimestres_Insert", ccsGet), $this);
        $this->tb_trimestres_Insert->Parameters = CCGetQueryString("QueryString", array("tri_id", "ccsForm"));
        $this->tb_trimestres_Insert->Page = "trimestre.php";
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

//Show Method @2-A3C7F70F
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_tri_nombre"] = CCGetFromGet("s_tri_nombre", "");

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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "tri_id", $this->DataSource->f("tri_id"));
                    $this->tri_nombre->SetValue($this->DataSource->tri_nombre->GetValue());
                    $this->tri_sw->SetValue($this->DataSource->tri_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->tri_nombre->Show();
                    $this->tri_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "tri_id", $this->DataSource->f("tri_id"));
                    $this->Alt_tri_nombre->SetValue($this->DataSource->Alt_tri_nombre->GetValue());
                    $this->Alt_tri_sw->SetValue($this->DataSource->Alt_tri_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_tri_nombre->Show();
                    $this->Alt_tri_sw->Show();
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
        $this->tb_trimestres_TotalRecords->Show();
        $this->Sorter_tri_nombre->Show();
        $this->Sorter_tri_sw->Show();
        $this->tb_trimestres_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-5AE619A3
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->tri_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->tri_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_tri_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_tri_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_trimestres Class @2-FCB6E20C

class clstb_trimestresDataSource extends clsDBsiges {  //tb_trimestresDataSource Class @2-CFA8CD3C

//DataSource Variables @2-FEB0FA77
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $tri_nombre;
    var $tri_sw;
    var $Alt_tri_nombre;
    var $Alt_tri_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-FBBB74CF
    function clstb_trimestresDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_trimestres";
        $this->Initialize();
        $this->tri_nombre = new clsField("tri_nombre", ccsText, "");
        $this->tri_sw = new clsField("tri_sw", ccsText, "");
        $this->Alt_tri_nombre = new clsField("Alt_tri_nombre", ccsText, "");
        $this->Alt_tri_sw = new clsField("Alt_tri_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-139DD56C
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "tri_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_tri_nombre" => array("tri_nombre", ""), 
            "Sorter_tri_sw" => array("tri_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-4A4A614C
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_tri_nombre", ccsText, "", "", $this->Parameters["urls_tri_nombre"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "tri_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-B93B829C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_trimestres";
        $this->SQL = "SELECT *  " .
        "FROM tb_trimestres {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-DEB8AE87
    function SetValues()
    {
        $this->tri_nombre->SetDBValue($this->f("tri_nombre"));
        $this->tri_sw->SetDBValue($this->f("tri_sw"));
        $this->Alt_tri_nombre->SetDBValue($this->f("tri_nombre"));
        $this->Alt_tri_sw->SetDBValue($this->f("tri_sw"));
    }
//End SetValues Method

} //End tb_trimestresDataSource Class @2-FCB6E20C

class clsRecordtb_trimestres1 { //tb_trimestres1 Class @22-1683C20A

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

//Class_Initialize Event @22-57FF59C5
    function clsRecordtb_trimestres1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_trimestres1/Error";
        $this->DataSource = new clstb_trimestres1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_trimestres1";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->tri_nombre = new clsControl(ccsTextBox, "tri_nombre", "Tri Nombre", ccsText, "", CCGetRequestParam("tri_nombre", $Method), $this);
            $this->tri_nombre->Required = true;
            $this->tri_sw = new clsControl(ccsListBox, "tri_sw", "Tri Sw", ccsText, "", CCGetRequestParam("tri_sw", $Method), $this);
            $this->tri_sw->DSType = dsListOfValues;
            $this->tri_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->tri_sw->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @22-2E7F5D26
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urltri_id"] = CCGetFromGet("tri_id", "");
    }
//End Initialize Method

//Validate Method @22-03D24898
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->tri_nombre->Validate() && $Validation);
        $Validation = ($this->tri_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->tri_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->tri_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @22-16E9C78E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->tri_nombre->Errors->Count());
        $errors = ($errors || $this->tri_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @22-28AF6A2F
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
        $Redirect = "trimestre.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @22-DBF8E035
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->tri_nombre->SetValue($this->tri_nombre->GetValue());
        $this->DataSource->tri_sw->SetValue($this->tri_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @22-8628CC57
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->tri_nombre->SetValue($this->tri_nombre->GetValue());
        $this->DataSource->tri_sw->SetValue($this->tri_sw->GetValue());
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

//Show Method @22-4877581A
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->tri_sw->Prepare();

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
                    $this->tri_nombre->SetValue($this->DataSource->tri_nombre->GetValue());
                    $this->tri_sw->SetValue($this->DataSource->tri_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->tri_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->tri_sw->Errors->ToString());
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

        $this->tri_nombre->Show();
        $this->tri_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_trimestres1 Class @22-FCB6E20C

class clstb_trimestres1DataSource extends clsDBsiges {  //tb_trimestres1DataSource Class @22-DDCA9629

//DataSource Variables @22-25EF81AE
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
    var $tri_nombre;
    var $tri_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @22-9DA62CC3
    function clstb_trimestres1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_trimestres1/Error";
        $this->Initialize();
        $this->tri_nombre = new clsField("tri_nombre", ccsText, "");
        $this->tri_sw = new clsField("tri_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @22-AD0E3B2D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urltri_id", ccsInteger, "", "", $this->Parameters["urltri_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "tri_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @22-C0706043
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_trimestres {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @22-3D5A7AB9
    function SetValues()
    {
        $this->tri_nombre->SetDBValue($this->f("tri_nombre"));
        $this->tri_sw->SetDBValue($this->f("tri_sw"));
    }
//End SetValues Method

//Insert Method @22-9161343C
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_trimestres ("
             . "tri_nombre, "
             . "tri_sw"
             . ") VALUES ("
             . $this->ToSQL($this->tri_nombre->GetDBValue(), $this->tri_nombre->DataType) . ", "
             . $this->ToSQL($this->tri_sw->GetDBValue(), $this->tri_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @22-3DDF009B
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_trimestres SET "
             . "tri_nombre=" . $this->ToSQL($this->tri_nombre->GetDBValue(), $this->tri_nombre->DataType) . ", "
             . "tri_sw=" . $this->ToSQL($this->tri_sw->GetDBValue(), $this->tri_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @22-772BA234
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_trimestres";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_trimestres1DataSource Class @22-FCB6E20C

//Initialize Page @1-4ABC18D0
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
$TemplateFileName = "trimestre.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-9C21268F
include("./trimestre_events.php");
//End Include events file

//Initialize Objects @1-489B8768
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_trimestresSearch = new clsRecordtb_trimestresSearch("", $MainPage);
$tb_trimestres = new clsGridtb_trimestres("", $MainPage);
$tb_trimestres1 = new clsRecordtb_trimestres1("", $MainPage);
$MainPage->tb_trimestresSearch = & $tb_trimestresSearch;
$MainPage->tb_trimestres = & $tb_trimestres;
$MainPage->tb_trimestres1 = & $tb_trimestres1;
$tb_trimestres->Initialize();
$tb_trimestres1->Initialize();

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

//Execute Components @1-4F706BC2
$tb_trimestresSearch->Operation();
$tb_trimestres1->Operation();
//End Execute Components

//Go to destination page @1-B57F49D4
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_trimestresSearch);
    unset($tb_trimestres);
    unset($tb_trimestres1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-5863DB5C
$tb_trimestresSearch->Show();
$tb_trimestres->Show();
$tb_trimestres1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-95821F54
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_trimestresSearch);
unset($tb_trimestres);
unset($tb_trimestres1);
unset($Tpl);
//End Unload Page


?>

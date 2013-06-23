<?php
//Include Common Files @1-AB778BA6
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "usuarios.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files



class clsGridtb_usuarios { //tb_usuarios class @2-108E4876

//Variables @2-3026F9E7

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
    var $Sorter_usu_nombre;
    var $Sorter_usu_imagen;
    var $Sorter_usu_login;
    var $Sorter_usu_password;
    var $Sorter_usu_nivel;
    var $Sorter_usu_sw;
//End Variables

//Class_Initialize Event @2-BB0EEF37
    function clsGridtb_usuarios($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_usuarios";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_usuarios";
        $this->DataSource = new clstb_usuariosDataSource($this);
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
        $this->SorterName = CCGetParam("tb_usuariosOrder", "");
        $this->SorterDirection = CCGetParam("tb_usuariosDir", "");

        $this->Detail = new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "usuarios.php";
        $this->usu_nombre = new clsControl(ccsLabel, "usu_nombre", "usu_nombre", ccsText, "", CCGetRequestParam("usu_nombre", ccsGet), $this);
        $this->Imagen = new clsControl(ccsImage, "Imagen", "Imagen", ccsText, "", CCGetRequestParam("Imagen", ccsGet), $this);
        $this->usu_login = new clsControl(ccsLabel, "usu_login", "usu_login", ccsText, "", CCGetRequestParam("usu_login", ccsGet), $this);
        $this->usu_password = new clsControl(ccsLabel, "usu_password", "usu_password", ccsText, "", CCGetRequestParam("usu_password", ccsGet), $this);

        $this->usu_nivel = new clsControl(ccsLabel, "usu_nivel", "usu_nivel", ccsInteger, "", CCGetRequestParam("usu_nivel", ccsGet), $this);
        $this->usu_sw = new clsControl(ccsLabel, "usu_sw", "usu_sw", ccsText, "", CCGetRequestParam("usu_sw", ccsGet), $this);
        $this->Alt_Detail = new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "usuarios.php";
        $this->Alt_usu_nombre = new clsControl(ccsLabel, "Alt_usu_nombre", "Alt_usu_nombre", ccsText, "", CCGetRequestParam("Alt_usu_nombre", ccsGet), $this);
        $this->Alt_Imagen = new clsControl(ccsImage, "Alt_Imagen", "Alt_Imagen", ccsText, "", CCGetRequestParam("Alt_Imagen", ccsGet), $this);
        $this->Alt_usu_login = new clsControl(ccsLabel, "Alt_usu_login", "Alt_usu_login", ccsText, "", CCGetRequestParam("Alt_usu_login", ccsGet), $this);

 	$this->Alt_usu_password = new clsControl(ccsLabel, "Alt_usu_password", "Alt_usu_password", ccsText, "", CCGetRequestParam("Alt_usu_password", ccsGet), $this);
        $this->Alt_usu_nivel = new clsControl(ccsLabel, "Alt_usu_nivel", "Alt_usu_nivel", ccsInteger, "", CCGetRequestParam("Alt_usu_nivel", ccsGet), $this);
        $this->Alt_usu_sw = new clsControl(ccsLabel, "Alt_usu_sw", "Alt_usu_sw", ccsText, "", CCGetRequestParam("Alt_usu_sw", ccsGet), $this);
        $this->tb_usuarios_TotalRecords = new clsControl(ccsLabel, "tb_usuarios_TotalRecords", "tb_usuarios_TotalRecords", ccsText, "", CCGetRequestParam("tb_usuarios_TotalRecords", ccsGet), $this);
        $this->Sorter_usu_nombre = new clsSorter($this->ComponentName, "Sorter_usu_nombre", $FileName, $this);
        $this->Sorter_usu_imagen = new clsSorter($this->ComponentName, "Sorter_usu_imagen", $FileName, $this);
        $this->Sorter_usu_login = new clsSorter($this->ComponentName, "Sorter_usu_login", $FileName, $this);
        $this->Sorter_usu_password = new clsSorter($this->ComponentName, "Sorter_usu_password", $FileName, $this);
        $this->Sorter_usu_nivel = new clsSorter($this->ComponentName, "Sorter_usu_nivel", $FileName, $this);
        $this->Sorter_usu_sw = new clsSorter($this->ComponentName, "Sorter_usu_sw", $FileName, $this);
        $this->tb_usuarios_Insert = new clsControl(ccsLink, "tb_usuarios_Insert", "tb_usuarios_Insert", ccsText, "", CCGetRequestParam("tb_usuarios_Insert", ccsGet), $this);
        $this->tb_usuarios_Insert->Parameters = CCGetQueryString("QueryString", array("usu_id", "ccsForm"));
        $this->tb_usuarios_Insert->Page = "usuarios.php";
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

//Show Method @2-8D0CDB8B
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_usu_nombre"] = CCGetFromGet("s_usu_nombre", "");
        $this->DataSource->Parameters["urls_usu_login"] = CCGetFromGet("s_usu_login", "");
        $this->DataSource->Parameters["urls_usu_password"] = CCGetFromGet("s_usu_password", "");
        $this->DataSource->Parameters["urls_usu_nivel"] = CCGetFromGet("s_usu_nivel", "");
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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "usu_id", $this->DataSource->f("usu_id"));
                    $this->usu_nombre->SetValue($this->DataSource->usu_nombre->GetValue());
                    $this->Imagen->SetValue($this->DataSource->Imagen->GetValue());
                    $this->usu_login->SetValue($this->DataSource->usu_login->GetValue());
                    $this->usu_password->SetValue($this->DataSource->usu_password->GetValue());
                    $this->usu_nivel->SetValue($this->DataSource->usu_nivel->GetValue());
                    $this->usu_sw->SetValue($this->DataSource->usu_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    $this->usu_nombre->Show();
                    $this->Imagen->Show();
                    $this->usu_login->Show();
                    $this->usu_password->Show();
                    $this->usu_nivel->Show();
                    $this->usu_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "usu_id", $this->DataSource->f("usu_id"));
                    $this->Alt_usu_nombre->SetValue($this->DataSource->Alt_usu_nombre->GetValue());
                    $this->Alt_Imagen->SetValue($this->DataSource->Alt_Imagen->GetValue());
                    $this->Alt_usu_login->SetValue($this->DataSource->Alt_usu_login->GetValue());
                    $this->Alt_usu_password->SetValue($this->DataSource->Alt_usu_password->GetValue());
                    $this->Alt_usu_nivel->SetValue($this->DataSource->Alt_usu_nivel->GetValue());
                    $this->Alt_usu_sw->SetValue($this->DataSource->Alt_usu_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    $this->Alt_usu_nombre->Show();
                    $this->Alt_Imagen->Show();
                    $this->Alt_usu_login->Show();
                    $this->Alt_usu_password->Show();
                    $this->Alt_usu_nivel->Show();
                    $this->Alt_usu_sw->Show();
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
        $this->tb_usuarios_TotalRecords->Show();
        $this->Sorter_usu_nombre->Show();
        $this->Sorter_usu_imagen->Show();
        $this->Sorter_usu_login->Show();
        $this->Sorter_usu_password->Show();
        $this->Sorter_usu_nivel->Show();
        $this->Sorter_usu_sw->Show();
        $this->tb_usuarios_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-6CA13830
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->usu_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Imagen->Errors->ToString());
        $errors = ComposeStrings($errors, $this->usu_login->Errors->ToString());
        $errors = ComposeStrings($errors, $this->usu_password->Errors->ToString());
        $errors = ComposeStrings($errors, $this->usu_nivel->Errors->ToString());
        $errors = ComposeStrings($errors, $this->usu_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_usu_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Imagen->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_usu_login->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_usu_password->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_usu_nivel->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_usu_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_usuarios Class @2-FCB6E20C

class clstb_usuariosDataSource extends clsDBsiges {  //tb_usuariosDataSource Class @2-2C31A18F

//DataSource Variables @2-E556C89E
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $usu_nombre;
    var $Imagen;
    var $usu_login;
	var $usu_password;
    var $usu_nivel;
    var $usu_sw;
    var $Alt_usu_nombre;
    var $Alt_Imagen;
    var $Alt_usu_login;
    var $Alt_usu_password;
    var $Alt_usu_nivel;
    var $Alt_usu_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-CD4BC519
    function clstb_usuariosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_usuarios";
        $this->Initialize();
        $this->usu_nombre = new clsField("usu_nombre", ccsText, "");
        $this->Imagen = new clsField("Imagen", ccsText, "");
        $this->usu_login = new clsField("usu_login", ccsText, "");
        $this->usu_password = new clsField("usu_password", ccsText, "");
        $this->usu_nivel = new clsField("usu_nivel", ccsInteger, "");
        $this->usu_sw = new clsField("usu_sw", ccsText, "");
        $this->Alt_usu_nombre = new clsField("Alt_usu_nombre", ccsText, "");
        $this->Alt_Imagen = new clsField("Alt_Imagen", ccsText, "");
        $this->Alt_usu_login = new clsField("Alt_usu_login", ccsText, "");
        $this->Alt_usu_password = new clsField("Alt_usu_password", ccsText, "");
        $this->Alt_usu_nivel = new clsField("Alt_usu_nivel", ccsInteger, "");
        $this->Alt_usu_sw = new clsField("Alt_usu_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-EA8D33CB
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "usu_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_usu_nombre" => array("usu_nombre", ""), 
            "Sorter_usu_imagen" => array("usu_imagen", ""), 
            "Sorter_usu_login" => array("usu_login", ""), 
            "Sorter_usu_password" => array("usu_password", ""), 
            "Sorter_usu_nivel" => array("usu_nivel", ""), 
            "Sorter_usu_sw" => array("usu_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-570F4D80
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_usu_nombre", ccsText, "", "", $this->Parameters["urls_usu_nombre"], "", false);
        $this->wp->AddParameter("2", "urls_usu_login", ccsText, "", "", $this->Parameters["urls_usu_login"], "", false);
        $this->wp->AddParameter("5", "urls_usu_password", ccsText, "", "", $this->Parameters["urls_usu_password"], "", false);
        $this->wp->AddParameter("3", "urls_usu_nivel", ccsInteger, "", "", $this->Parameters["urls_usu_nivel"], "", false);
        $this->wp->AddParameter("4", "urljue_id", ccsInteger, "", "", $this->Parameters["urljue_id"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "usu_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "usu_login", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "usu_password", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);

        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "usu_nivel", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "usu_jue_id", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),true);
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

//Open Method @2-8EFC8D41
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_usuarios";
        $this->SQL = "SELECT *  " .
        "FROM tb_usuarios {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-D6C71491
    function SetValues()
    {
        $this->usu_nombre->SetDBValue($this->f("usu_nombre"));
        $this->Imagen->SetDBValue($this->f("usu_imagen"));
        $this->usu_login->SetDBValue($this->f("usu_login"));
        $this->usu_password->SetDBValue($this->f("usu_password"));
        $this->usu_nivel->SetDBValue(trim($this->f("usu_nivel")));
        $this->usu_sw->SetDBValue($this->f("usu_sw"));
        $this->Alt_usu_nombre->SetDBValue($this->f("usu_nombre"));
        $this->Alt_Imagen->SetDBValue($this->f("usu_imagen"));
        $this->Alt_usu_login->SetDBValue($this->f("usu_login"));
        $this->Alt_usu_password->SetDBValue($this->f("usu_password"));
        $this->Alt_usu_nivel->SetDBValue(trim($this->f("usu_nivel")));
        $this->Alt_usu_sw->SetDBValue($this->f("usu_sw"));
    }
//End SetValues Method

} //End tb_usuariosDataSource Class @2-FCB6E20C

class clsRecordtb_usuarios1 { //tb_usuarios1 Class @35-CBE4CABB

//Variables @35-F607D3A5

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

//Class_Initialize Event @35-E9B91200
    function clsRecordtb_usuarios1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_usuarios1/Error";
        $this->DataSource = new clstb_usuarios1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_usuarios1";
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "multipart/form-data";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->usu_nombre = new clsControl(ccsTextBox, "usu_nombre", "Nombre", ccsText, "", CCGetRequestParam("usu_nombre", $Method), $this);
            $this->usu_nombre->Required = true;
            $this->jue_id = new clsControl(ccsHidden, "jue_id", "Juego", ccsText, "", CCGetRequestParam("jue_id", $Method), $this);
            $this->jue_id->Required = true;
            $this->FileUpload1 = new clsFileUpload("FileUpload1", "Imagen", "./image/temp/", "./image/", "*.jpg;*.gif;*.bmp;*.png", "", 6500000, $this);
            $this->usu_login = new clsControl(ccsTextBox, "usu_login", "Login", ccsText, "", CCGetRequestParam("usu_login", $Method), $this);
            $this->usu_login->Required = true;
            $this->usu_password = new clsControl(ccsTextBox, "usu_password", "Password", ccsText, "", CCGetRequestParam("usu_password", $Method), $this);
            $this->usu_password->Required = true;
            $this->usu_nivel = new clsControl(ccsListBox, "usu_nivel", "Nivel", ccsInteger, "", CCGetRequestParam("usu_nivel", $Method), $this);
            $this->usu_nivel->DSType = dsListOfValues;
            $this->usu_nivel->Values = array(array("1", "Consulta"), array("2", "Usuario"), array("3", "Administrador"));
            $this->usu_nivel->Required = true;
            $this->usu_sw = new clsControl(ccsListBox, "usu_sw", "Estado", ccsText, "", CCGetRequestParam("usu_sw", $Method), $this);
            $this->usu_sw->DSType = dsListOfValues;
            $this->usu_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->usu_sw->Required = true;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->jue_id->Value) && !strlen($this->jue_id->Value) && $this->jue_id->Value !== false)
                    $this->jue_id->SetText(CCGetParam("jue_id"));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @35-DE928F17
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlusu_id"] = CCGetFromGet("usu_id", "");
    }
//End Initialize Method

//Validate Method @35-A7871361
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->usu_nombre->Validate() && $Validation);
        $Validation = ($this->jue_id->Validate() && $Validation);
        $Validation = ($this->FileUpload1->Validate() && $Validation);
        $Validation = ($this->usu_login->Validate() && $Validation);
        $Validation = ($this->usu_password->Validate() && $Validation);
        $Validation = ($this->usu_nivel->Validate() && $Validation);
        $Validation = ($this->usu_sw->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->usu_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->FileUpload1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->usu_login->Errors->Count() == 0);
        $Validation =  $Validation && ($this->usu_password->Errors->Count() == 0);
        $Validation =  $Validation && ($this->usu_nivel->Errors->Count() == 0);
        $Validation =  $Validation && ($this->usu_sw->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @35-D496D383
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->usu_nombre->Errors->Count());
        $errors = ($errors || $this->jue_id->Errors->Count());
        $errors = ($errors || $this->FileUpload1->Errors->Count());
        $errors = ($errors || $this->usu_login->Errors->Count());
        $errors = ($errors || $this->usu_password->Errors->Count());
        $errors = ($errors || $this->usu_nivel->Errors->Count());
        $errors = ($errors || $this->usu_sw->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @35-71405B6A
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

        $this->FileUpload1->Upload();

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
        $Redirect = "usuarios.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @35-89CC546B
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->usu_nombre->SetValue($this->usu_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->FileUpload1->SetValue($this->FileUpload1->GetValue());
        $this->DataSource->usu_login->SetValue($this->usu_login->GetValue());
        $this->DataSource->usu_password->SetValue($this->usu_password->GetValue());
        $this->DataSource->usu_nivel->SetValue($this->usu_nivel->GetValue());
        $this->DataSource->usu_sw->SetValue($this->usu_sw->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Move();
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @35-DCA4DCCF
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->usu_nombre->SetValue($this->usu_nombre->GetValue());
        $this->DataSource->jue_id->SetValue($this->jue_id->GetValue());
        $this->DataSource->FileUpload1->SetValue($this->FileUpload1->GetValue());
        $this->DataSource->usu_login->SetValue($this->usu_login->GetValue());
        $this->DataSource->usu_password->SetValue($this->usu_password->GetValue());
        $this->DataSource->usu_nivel->SetValue($this->usu_nivel->GetValue());
        $this->DataSource->usu_sw->SetValue($this->usu_sw->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Move();
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @35-2B077D44
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Delete();
        }
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @35-9A1A4EDB
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->usu_nivel->Prepare();
        $this->usu_sw->Prepare();

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
                    $this->usu_nombre->SetValue($this->DataSource->usu_nombre->GetValue());
                    $this->jue_id->SetValue($this->DataSource->jue_id->GetValue());
                    $this->FileUpload1->SetValue($this->DataSource->FileUpload1->GetValue());
                    $this->usu_login->SetValue($this->DataSource->usu_login->GetValue());
                    $this->usu_password->SetValue($this->DataSource->usu_password->GetValue());
                    $this->usu_nivel->SetValue($this->DataSource->usu_nivel->GetValue());
                    $this->usu_sw->SetValue($this->DataSource->usu_sw->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->usu_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->FileUpload1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->usu_login->Errors->ToString());
            $Error = ComposeStrings($Error, $this->usu_password->Errors->ToString());
            $Error = ComposeStrings($Error, $this->usu_nivel->Errors->ToString());
            $Error = ComposeStrings($Error, $this->usu_sw->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
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

        $this->usu_nombre->Show();
        $this->jue_id->Show();
        $this->FileUpload1->Show();
        $this->usu_login->Show();
        $this->usu_password->Show();
        $this->usu_nivel->Show();
        $this->usu_sw->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_usuarios1 Class @35-FCB6E20C

class clstb_usuarios1DataSource extends clsDBsiges {  //tb_usuarios1DataSource Class @35-8F41ED73

//DataSource Variables @35-823B4073
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
    var $usu_nombre;
    var $jue_id;
    var $FileUpload1;
    var $usu_login;
    var $usu_password;
    var $usu_nivel;
    var $usu_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @35-7DA331A0
    function clstb_usuarios1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_usuarios1/Error";
        $this->Initialize();
        $this->usu_nombre = new clsField("usu_nombre", ccsText, "");
        $this->jue_id = new clsField("jue_id", ccsText, "");
        $this->FileUpload1 = new clsField("FileUpload1", ccsText, "");
        $this->usu_login = new clsField("usu_login", ccsText, "");
        $this->usu_password = new clsField("usu_password", ccsText, "");
        $this->usu_nivel = new clsField("usu_nivel", ccsInteger, "");
        $this->usu_sw = new clsField("usu_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @35-5CACBC70
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlusu_id", ccsInteger, "", "", $this->Parameters["urlusu_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "usu_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @35-B196CDBE
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_usuarios {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @35-E8329757
    function SetValues()
    {
        $this->usu_nombre->SetDBValue($this->f("usu_nombre"));
        $this->jue_id->SetDBValue($this->f("usu_jue_id"));
        $this->FileUpload1->SetDBValue($this->f("usu_imagen"));
        $this->usu_login->SetDBValue($this->f("usu_login"));
        $this->usu_password->SetDBValue($this->f("usu_password"));
        $this->usu_nivel->SetDBValue(trim($this->f("usu_nivel")));
        $this->usu_sw->SetDBValue($this->f("usu_sw"));
    }
//End SetValues Method

//Insert Method @35-09CEB745
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_usuarios ("
             . "usu_nombre, "
             . "usu_jue_id, "
             . "usu_imagen, "
             . "usu_login, "
             . "usu_password, "
             . "usu_nivel, "
             . "usu_sw"
             . ") VALUES ("
             . $this->ToSQL($this->usu_nombre->GetDBValue(), $this->usu_nombre->DataType) . ", "
             . $this->ToSQL($this->jue_id->GetDBValue(), $this->jue_id->DataType) . ", "
             . $this->ToSQL($this->FileUpload1->GetDBValue(), $this->FileUpload1->DataType) . ", "
             . $this->ToSQL($this->usu_login->GetDBValue(), $this->usu_login->DataType) . ", "
             . $this->ToSQL($this->usu_password->GetDBValue(), $this->usu_password->DataType) . ", "
             . $this->ToSQL($this->usu_nivel->GetDBValue(), $this->usu_nivel->DataType) . ", "
             . $this->ToSQL($this->usu_sw->GetDBValue(), $this->usu_sw->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @35-9F82C543
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_usuarios SET "
             . "usu_nombre=" . $this->ToSQL($this->usu_nombre->GetDBValue(), $this->usu_nombre->DataType) . ", "
             . "usu_jue_id=" . $this->ToSQL($this->jue_id->GetDBValue(), $this->jue_id->DataType) . ", "
             . "usu_imagen=" . $this->ToSQL($this->FileUpload1->GetDBValue(), $this->FileUpload1->DataType) . ", "
             . "usu_login=" . $this->ToSQL($this->usu_login->GetDBValue(), $this->usu_login->DataType) . ", "
             . "usu_password=" . $this->ToSQL($this->usu_password->GetDBValue(), $this->usu_password->DataType) . ", "
             . "usu_nivel=" . $this->ToSQL($this->usu_nivel->GetDBValue(), $this->usu_nivel->DataType) . ", "
             . "usu_sw=" . $this->ToSQL($this->usu_sw->GetDBValue(), $this->usu_sw->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @35-17852217
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_usuarios";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_usuarios1DataSource Class @35-FCB6E20C

//Initialize Page @1-A73AAEA4
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
$TemplateFileName = "usuarios.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-9E264BC2
include("./usuarios_events.php");
//End Include events file

//Initialize Objects @1-07D65107
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_usuarios = new clsGridtb_usuarios("", $MainPage);
$tb_usuarios1 = new clsRecordtb_usuarios1("", $MainPage);
$MainPage->tb_usuarios = & $tb_usuarios;
$MainPage->tb_usuarios1 = & $tb_usuarios1;
$tb_usuarios->Initialize();
$tb_usuarios1->Initialize();

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

//Execute Components @1-52006282
$tb_usuarios1->Operation();
//End Execute Components

//Go to destination page @1-3EC18700
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_usuarios);
    unset($tb_usuarios1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-2E9C3C1E
$tb_usuarios->Show();
$tb_usuarios1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-5E304A48
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_usuarios);
unset($tb_usuarios1);
unset($Tpl);
//End Unload Page


?>

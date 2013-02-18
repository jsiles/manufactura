<?php
//Include Common Files @1-B9C2D336
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "juegos.php");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordtb_juegosSearch { //tb_juegosSearch Class @3-105BA948

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

//Class_Initialize Event @3-919F9FDE
    function clsRecordtb_juegosSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_juegosSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_juegosSearch";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_jue_nombre = & new clsControl(ccsTextBox, "s_jue_nombre", "s_jue_nombre", ccsText, "", CCGetRequestParam("s_jue_nombre", $Method), $this);
            $this->ClearParameters = & new clsControl(ccsLink, "ClearParameters", "ClearParameters", ccsText, "", CCGetRequestParam("ClearParameters", $Method), $this);
            $this->ClearParameters->Parameters = CCGetQueryString("QueryString", array("s_jue_nombre", "ccsForm"));
            $this->ClearParameters->Page = "juegos.php";
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
        }
    }
//End Class_Initialize Event

//Validate Method @3-60D269F1
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_jue_nombre->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_jue_nombre->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-D2D2CA70
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_jue_nombre->Errors->Count());
        $errors = ($errors || $this->ClearParameters->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @3-21A4DD47
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
        $Redirect = "juegos.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "juegos.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-E3F12E3A
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
            $Error = ComposeStrings($Error, $this->s_jue_nombre->Errors->ToString());
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

        $this->s_jue_nombre->Show();
        $this->ClearParameters->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End tb_juegosSearch Class @3-FCB6E20C

class clsGridtb_juegos { //tb_juegos class @2-35D5730B

//Variables @2-079BA260

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
    var $Sorter_jue_nombre;
    var $Sorter_jue_imagen;
    var $Sorter_jue_periodoInicial;
    var $Sorter_jue_cantidad;
    var $Sorter_jue_sw;
//End Variables

//Class_Initialize Event @2-7EB25FD9
    function clsGridtb_juegos($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tb_juegos";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->IsAltRow = false;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tb_juegos";
        $this->DataSource = new clstb_juegosDataSource($this);
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
        $this->SorterName = CCGetParam("tb_juegosOrder", "");
        $this->SorterDirection = CCGetParam("tb_juegosDir", "");

        $this->Detail = & new clsControl(ccsLink, "Detail", "Detail", ccsText, "", CCGetRequestParam("Detail", ccsGet), $this);
        $this->Detail->Page = "juegos.php";

        $this->Detail2 = & new clsControl(ccsLink, "Detail2", "Detail2", ccsText, "", CCGetRequestParam("Detail2", ccsGet), $this);
        $this->Detail2->Page = "nuevo.php";

        $this->usuarios = & new clsControl(ccsLink, "usuarios", "usuarios", ccsText, "", CCGetRequestParam("usuarios", ccsGet), $this);
        $this->usuarios->Page = "usuarios.php";
        $this->productos = & new clsControl(ccsLink, "productos", "productos", ccsText, "", CCGetRequestParam("productos", ccsGet), $this);
        $this->productos->Page = "productos.php";
        $this->mercados = & new clsControl(ccsLink, "mercados", "mercados", ccsText, "", CCGetRequestParam("mercados", ccsGet), $this);
        $this->mercados->Page = "mercados.php";
        $this->tipoClientes = & new clsControl(ccsLink, "tipoClientes", "tipoClientes", ccsText, "", CCGetRequestParam("tipoClientes", ccsGet), $this);
        $this->tipoClientes->Page = "tipoClientes.php";
        $this->conjuntos = & new clsControl(ccsLink, "conjuntos", "conjuntos", ccsText, "", CCGetRequestParam("conjuntos", ccsGet), $this);
        $this->conjuntos->Page = "conjuntos.php";
        $this->valoresIn = & new clsControl(ccsLink, "valoresIn", "valoresIn", ccsText, "", CCGetRequestParam("valoresIn", ccsGet), $this);
        $this->valoresIn->Page = "valoresIniciales.php";
        $this->items = & new clsControl(ccsLink, "items", "items", ccsText, "", CCGetRequestParam("items", ccsGet), $this);
        $this->items->Page = "grupos.php";
        $this->Link1 = & new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet), $this);
        $this->Link1->Page = "inicio.php";
        $this->Link3 = & new clsControl(ccsLink, "Link3", "Link3", ccsText, "", CCGetRequestParam("Link3", ccsGet), $this);
        $this->Link3->Page = "periodos.php";
        $this->Link5 = & new clsControl(ccsLink, "Link5", "Link5", ccsText, "", CCGetRequestParam("Link5", ccsGet), $this);
        $this->Link5->Page = "investigaciones.php";
        $this->Link7 = & new clsControl(ccsLink, "Link7", "Link7", ccsText, "", CCGetRequestParam("Link7", ccsGet), $this);
        $this->Link7->Page = "compras.php";
		$this->Link9 = & new clsControl(ccsLink, "Link9", "Link9", ccsText, "", CCGetRequestParam("Link9", ccsGet), $this);
        $this->Link9->Page = "celebridades.php";
		$this->Link11 = & new clsControl(ccsLink, "Link11", "Link11", ccsText, "", CCGetRequestParam("Link11", ccsGet), $this);
        $this->Link11->Page = "ventasonline.php";
		$this->Link13 = & new clsControl(ccsLink, "Link13", "Link13", ccsText, "", CCGetRequestParam("Link13", ccsGet), $this);
        $this->Link13->Page = "responsabilidad.php";
		$this->Link15 = & new clsControl(ccsLink, "Link15", "Link15", ccsText, "", CCGetRequestParam("Link15", ccsGet), $this);
        $this->Link15->Page = "compras3.php";


        $this->jue_nombre = & new clsControl(ccsLabel, "jue_nombre", "jue_nombre", ccsText, "", CCGetRequestParam("jue_nombre", ccsGet), $this);
        $this->Imagen = & new clsControl(ccsImage, "Imagen", "Imagen", ccsText, "", CCGetRequestParam("Imagen", ccsGet), $this);
        $this->jue_periodoInicial = & new clsControl(ccsLabel, "jue_periodoInicial", "jue_periodoInicial", ccsInteger, "", CCGetRequestParam("jue_periodoInicial", ccsGet), $this);
        $this->jue_cantidad = & new clsControl(ccsLabel, "jue_cantidad", "jue_cantidad", ccsInteger, "", CCGetRequestParam("jue_cantidad", ccsGet), $this);
        $this->jue_sw = & new clsControl(ccsLabel, "jue_sw", "jue_sw", ccsText, "", CCGetRequestParam("jue_sw", ccsGet), $this);
        $this->Alt_Detail = & new clsControl(ccsLink, "Alt_Detail", "Alt_Detail", ccsText, "", CCGetRequestParam("Alt_Detail", ccsGet), $this);
        $this->Alt_Detail->Page = "juegos.php";
        
        $this->Alt_Detail2 = & new clsControl(ccsLink, "Alt_Detail2", "Alt_Detail2", ccsText, "", CCGetRequestParam("Alt_Detail2", ccsGet), $this);
        $this->Alt_Detail2->Page = "nuevo.php";
        $this->Alt_usuarios = & new clsControl(ccsLink, "Alt_usuarios", "Alt_usuarios", ccsText, "", CCGetRequestParam("Alt_usuarios", ccsGet), $this);
        $this->Alt_usuarios->Page = "usuarios.php";
        $this->Alt_productos = & new clsControl(ccsLink, "Alt_productos", "Alt_productos", ccsText, "", CCGetRequestParam("Alt_productos", ccsGet), $this);
        $this->Alt_productos->Page = "productos.php";
        $this->Alt_mercados = & new clsControl(ccsLink, "Alt_mercados", "Alt_mercados", ccsText, "", CCGetRequestParam("Alt_mercados", ccsGet), $this);
        $this->Alt_mercados->Page = "mercados.php";
        $this->Alt_tipoClientes = & new clsControl(ccsLink, "Alt_tipoClientes", "Alt_tipoClientes", ccsText, "", CCGetRequestParam("Alt_tipoClientes", ccsGet), $this);
        $this->Alt_tipoClientes->Page = "tipoClientes.php";
        $this->Alt_conjuntos = & new clsControl(ccsLink, "Alt_conjuntos", "Alt_conjuntos", ccsText, "", CCGetRequestParam("Alt_conjuntos", ccsGet), $this);
        $this->Alt_conjuntos->Page = "conjuntos.php";
        $this->Alt_valoresIn = & new clsControl(ccsLink, "Alt_valoresIn", "Alt_valoresIn", ccsText, "", CCGetRequestParam("Alt_valoresIn", ccsGet), $this);
        $this->Alt_valoresIn->Page = "valoresIniciales.php";
        $this->Alt_items = & new clsControl(ccsLink, "Alt_items", "Alt_items", ccsText, "", CCGetRequestParam("Alt_items", ccsGet), $this);
        $this->Alt_items->Page = "grupos.php";
        $this->Link2 = & new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet), $this);
        $this->Link2->Page = "inicio.php";
        $this->Link4 = & new clsControl(ccsLink, "Link4", "Link4", ccsText, "", CCGetRequestParam("Link4", ccsGet), $this);
        $this->Link4->Page = "periodos.php";
        $this->Link6 = & new clsControl(ccsLink, "Link6", "Link6", ccsText, "", CCGetRequestParam("Link6", ccsGet), $this);
        $this->Link6->Page = "investigaciones.php";
        $this->Link8 = & new clsControl(ccsLink, "Link8", "Link8", ccsText, "", CCGetRequestParam("Link8", ccsGet), $this);
        $this->Link8->Page = "compras.php";
        $this->Link10 = & new clsControl(ccsLink, "Link10", "Link10", ccsText, "", CCGetRequestParam("Link10", ccsGet), $this);
        $this->Link10->Page = "celebridades.php";
        $this->Link12 = & new clsControl(ccsLink, "Link12", "Link12", ccsText, "", CCGetRequestParam("Link12", ccsGet), $this);
        $this->Link12->Page = "ventasonline.php";
        $this->Link14 = & new clsControl(ccsLink, "Link14", "Link14", ccsText, "", CCGetRequestParam("Link14", ccsGet), $this);
        $this->Link14->Page = "responsabilidad.php";
        $this->Link16 = & new clsControl(ccsLink, "Link16", "Link16", ccsText, "", CCGetRequestParam("Link16", ccsGet), $this);
        $this->Link16->Page = "compras3.php";        
		
        $this->Alt_jue_nombre = & new clsControl(ccsLabel, "Alt_jue_nombre", "Alt_jue_nombre", ccsText, "", CCGetRequestParam("Alt_jue_nombre", ccsGet), $this);
        $this->Alt_Imagen = & new clsControl(ccsImage, "Alt_Imagen", "Alt_Imagen", ccsText, "", CCGetRequestParam("Alt_Imagen", ccsGet), $this);
        $this->Alt_jue_periodoInicial = & new clsControl(ccsLabel, "Alt_jue_periodoInicial", "Alt_jue_periodoInicial", ccsInteger, "", CCGetRequestParam("Alt_jue_periodoInicial", ccsGet), $this);
        $this->Alt_jue_cantidad = & new clsControl(ccsLabel, "Alt_jue_cantidad", "Alt_jue_cantidad", ccsInteger, "", CCGetRequestParam("Alt_jue_cantidad", ccsGet), $this);
        $this->Alt_jue_sw = & new clsControl(ccsLabel, "Alt_jue_sw", "Alt_jue_sw", ccsText, "", CCGetRequestParam("Alt_jue_sw", ccsGet), $this);
        $this->tb_juegos_TotalRecords = & new clsControl(ccsLabel, "tb_juegos_TotalRecords", "tb_juegos_TotalRecords", ccsText, "", CCGetRequestParam("tb_juegos_TotalRecords", ccsGet), $this);
        $this->Sorter_jue_nombre = & new clsSorter($this->ComponentName, "Sorter_jue_nombre", $FileName, $this);
        $this->Sorter_jue_imagen = & new clsSorter($this->ComponentName, "Sorter_jue_imagen", $FileName, $this);
        $this->Sorter_jue_periodoInicial = & new clsSorter($this->ComponentName, "Sorter_jue_periodoInicial", $FileName, $this);
        $this->Sorter_jue_cantidad = & new clsSorter($this->ComponentName, "Sorter_jue_cantidad", $FileName, $this);
        $this->Sorter_jue_sw = & new clsSorter($this->ComponentName, "Sorter_jue_sw", $FileName, $this);
        $this->tb_juegos_Insert = & new clsControl(ccsLink, "tb_juegos_Insert", "tb_juegos_Insert", ccsText, "", CCGetRequestParam("tb_juegos_Insert", ccsGet), $this);
        $this->tb_juegos_Insert->Parameters = CCGetQueryString("QueryString", array("jue_id", "ccsForm"));
        $this->tb_juegos_Insert->Page = "juegos.php";
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

//Show Method @2-0DD2BC28
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->DataSource->Parameters["urls_jue_nombre"] = CCGetFromGet("s_jue_nombre", "");

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
                    $this->Detail->Parameters = CCAddParam($this->Detail->Parameters, "jue_id", $this->DataSource->f("jue_id"));

                    $this->Detail2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Detail2->Parameters = CCAddParam($this->Detail2->Parameters, "jue_id", $this->DataSource->f("jue_id"));

                    $this->usuarios->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->usuarios->Parameters = CCAddParam($this->usuarios->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->productos->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->productos->Parameters = CCAddParam($this->productos->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->mercados->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->mercados->Parameters = CCAddParam($this->mercados->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->tipoClientes->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->tipoClientes->Parameters = CCAddParam($this->tipoClientes->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->conjuntos->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->conjuntos->Parameters = CCAddParam($this->conjuntos->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->valoresIn->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->valoresIn->Parameters = CCAddParam($this->valoresIn->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->valoresIn->Parameters = CCAddParam($this->valoresIn->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->valoresIn->Parameters = CCAddParam($this->valoresIn->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
                    $this->items->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->items->Parameters = CCAddParam($this->items->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link3->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link3->Parameters = CCAddParam($this->Link3->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link3->Parameters = CCAddParam($this->Link3->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link3->Parameters = CCAddParam($this->Link3->Parameters, "cant", $this->DataSource->f("jue_cantidad"));

                    $this->Link5->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link5->Parameters = CCAddParam($this->Link5->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link5->Parameters = CCAddParam($this->Link5->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link5->Parameters = CCAddParam($this->Link5->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
					$this->Link7->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link7->Parameters = CCAddParam($this->Link7->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					
					$this->Link9->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link9->Parameters = CCAddParam($this->Link9->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link9->Parameters = CCAddParam($this->Link9->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link9->Parameters = CCAddParam($this->Link9->Parameters, "cant", $this->DataSource->f("jue_cantidad"));

					$this->Link11->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link11->Parameters = CCAddParam($this->Link11->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link11->Parameters = CCAddParam($this->Link11->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link11->Parameters = CCAddParam($this->Link11->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					                    
                    $this->Link13->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link13->Parameters = CCAddParam($this->Link13->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link13->Parameters = CCAddParam($this->Link13->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link13->Parameters = CCAddParam($this->Link13->Parameters, "cant", $this->DataSource->f("jue_cantidad"));

                    $this->Link15->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link15->Parameters = CCAddParam($this->Link15->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link15->Parameters = CCAddParam($this->Link15->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link15->Parameters = CCAddParam($this->Link15->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
                    $this->jue_nombre->SetValue($this->DataSource->jue_nombre->GetValue());
                    $this->Imagen->SetValue($this->DataSource->Imagen->GetValue());
                    $this->jue_periodoInicial->SetValue($this->DataSource->jue_periodoInicial->GetValue());
                    $this->jue_cantidad->SetValue($this->DataSource->jue_cantidad->GetValue());
                    $this->jue_sw->SetValue($this->DataSource->jue_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Detail->Show();
                    
                    $this->Detail2->Show();
                    
                    $this->usuarios->Show();
                    $this->productos->Show();
                    $this->mercados->Show();
                    $this->tipoClientes->Show();
                    $this->conjuntos->Show();
                    $this->valoresIn->Show();
                    $this->items->Show();
                    $this->Link1->Show();
                    $this->Link3->Show();
                    $this->Link5->Show();
                    $this->Link7->Show();
                    $this->Link9->Show();
					$this->Link11->Show();
					$this->Link13->Show();
					$this->Link15->Show();

                    $this->jue_nombre->Show();
                    $this->Imagen->Show();
                    $this->jue_periodoInicial->Show();
                    $this->jue_cantidad->Show();
                    $this->jue_sw->Show();
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                    $Tpl->parse("Row", true);
                }
                else
                {
                    $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/AltRow";
                    $this->Alt_Detail->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail->Parameters = CCAddParam($this->Alt_Detail->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    
                    $this->Alt_Detail2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_Detail2->Parameters = CCAddParam($this->Alt_Detail2->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    
                    $this->Alt_usuarios->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_usuarios->Parameters = CCAddParam($this->Alt_usuarios->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_productos->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_productos->Parameters = CCAddParam($this->Alt_productos->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_mercados->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_mercados->Parameters = CCAddParam($this->Alt_mercados->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_tipoClientes->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_tipoClientes->Parameters = CCAddParam($this->Alt_tipoClientes->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_conjuntos->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_conjuntos->Parameters = CCAddParam($this->Alt_conjuntos->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_valoresIn->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_valoresIn->Parameters = CCAddParam($this->Alt_valoresIn->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Alt_valoresIn->Parameters = CCAddParam($this->Alt_valoresIn->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Alt_valoresIn->Parameters = CCAddParam($this->Alt_valoresIn->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
                    $this->Alt_items->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Alt_items->Parameters = CCAddParam($this->Alt_items->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link4->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link4->Parameters = CCAddParam($this->Link4->Parameters, "jue_id", $this->DataSource->f("jue_id"));
                    $this->Link4->Parameters = CCAddParam($this->Link4->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link4->Parameters = CCAddParam($this->Link4->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
                    $this->Link6->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link6->Parameters = CCAddParam($this->Link6->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link6->Parameters = CCAddParam($this->Link6->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link6->Parameters = CCAddParam($this->Link6->Parameters, "cant", $this->DataSource->f("jue_cantidad"));

					$this->Link8->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link8->Parameters = CCAddParam($this->Link8->Parameters, "jue_id", $this->DataSource->f("jue_id"));

					$this->Link10->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link10->Parameters = CCAddParam($this->Link10->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link10->Parameters = CCAddParam($this->Link10->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link10->Parameters = CCAddParam($this->Link10->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
					$this->Link12->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link12->Parameters = CCAddParam($this->Link12->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link12->Parameters = CCAddParam($this->Link12->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link12->Parameters = CCAddParam($this->Link12->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
					$this->Link14->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link14->Parameters = CCAddParam($this->Link14->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link14->Parameters = CCAddParam($this->Link14->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link14->Parameters = CCAddParam($this->Link14->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
					$this->Link16->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                    $this->Link16->Parameters = CCAddParam($this->Link16->Parameters, "jue_id", $this->DataSource->f("jue_id"));
					$this->Link16->Parameters = CCAddParam($this->Link16->Parameters, "per_ini", $this->DataSource->f("jue_periodoInicial"));
                    $this->Link16->Parameters = CCAddParam($this->Link16->Parameters, "cant", $this->DataSource->f("jue_cantidad"));
					
					$this->Alt_jue_nombre->SetValue($this->DataSource->Alt_jue_nombre->GetValue());
                    $this->Alt_Imagen->SetValue($this->DataSource->Alt_Imagen->GetValue());
                    $this->Alt_jue_periodoInicial->SetValue($this->DataSource->Alt_jue_periodoInicial->GetValue());
                    $this->Alt_jue_cantidad->SetValue($this->DataSource->Alt_jue_cantidad->GetValue());
                    $this->Alt_jue_sw->SetValue($this->DataSource->Alt_jue_sw->GetValue());
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                    $this->Alt_Detail->Show();
                    
                    $this->Alt_Detail2->Show();
                    
                    $this->Alt_usuarios->Show();
                    $this->Alt_productos->Show();
                    $this->Alt_mercados->Show();
                    $this->Alt_tipoClientes->Show();
                    $this->Alt_conjuntos->Show();
                    $this->Alt_valoresIn->Show();
                    $this->Alt_items->Show();
                    $this->Link2->Show();
                    $this->Link4->Show();
                    $this->Link6->Show();
                    $this->Link8->Show();					
                    $this->Link10->Show();
					$this->Link12->Show();
					$this->Link14->Show();
					$this->Link16->Show();
										
                    $this->Alt_jue_nombre->Show();
                    $this->Alt_Imagen->Show();
                    $this->Alt_jue_periodoInicial->Show();
                    $this->Alt_jue_cantidad->Show();
                    $this->Alt_jue_sw->Show();
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
        $this->tb_juegos_TotalRecords->Show();
        $this->Sorter_jue_nombre->Show();
        $this->Sorter_jue_imagen->Show();
        $this->Sorter_jue_periodoInicial->Show();
        $this->Sorter_jue_cantidad->Show();
        $this->Sorter_jue_sw->Show();
        $this->tb_juegos_Insert->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-6BCD6E5E
    function GetErrors()
    {
        $errors = "";
        
        $errors = ComposeStrings($errors, $this->Detail->Errors->ToString());
        
        $errors = ComposeStrings($errors, $this->Detail2->Errors->ToString());
        
        $errors = ComposeStrings($errors, $this->usuarios->Errors->ToString());
        $errors = ComposeStrings($errors, $this->productos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->mercados->Errors->ToString());
        $errors = ComposeStrings($errors, $this->tipoClientes->Errors->ToString());
        $errors = ComposeStrings($errors, $this->conjuntos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->valoresIn->Errors->ToString());
        $errors = ComposeStrings($errors, $this->items->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link5->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link7->Errors->ToString());		
        $errors = ComposeStrings($errors, $this->jue_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Imagen->Errors->ToString());
        $errors = ComposeStrings($errors, $this->jue_periodoInicial->Errors->ToString());
        $errors = ComposeStrings($errors, $this->jue_cantidad->Errors->ToString());
        $errors = ComposeStrings($errors, $this->jue_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Detail->Errors->ToString());
        
        $errors = ComposeStrings($errors, $this->Alt_Detail2->Errors->ToString());
        
        $errors = ComposeStrings($errors, $this->Alt_usuarios->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_productos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_mercados->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_tipoClientes->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_conjuntos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_valoresIn->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_items->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link4->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link6->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link8->Errors->ToString());		
        $errors = ComposeStrings($errors, $this->Alt_jue_nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_Imagen->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_jue_periodoInicial->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_jue_cantidad->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Alt_jue_sw->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tb_juegos Class @2-FCB6E20C

class clstb_juegosDataSource extends clsDBsiges {  //tb_juegosDataSource Class @2-6214DBC9

//DataSource Variables @2-BFC09A7D
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $jue_nombre;
    var $Imagen;
    var $jue_periodoInicial;
    var $jue_cantidad;
    var $jue_sw;
    var $Alt_jue_nombre;
    var $Alt_Imagen;
    var $Alt_jue_periodoInicial;
    var $Alt_jue_cantidad;
    var $Alt_jue_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-1CDE9820
    function clstb_juegosDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tb_juegos";
        $this->Initialize();
        $this->jue_nombre = new clsField("jue_nombre", ccsText, "");
        $this->Imagen = new clsField("Imagen", ccsText, "");
        $this->jue_periodoInicial = new clsField("jue_periodoInicial", ccsInteger, "");
        $this->jue_cantidad = new clsField("jue_cantidad", ccsInteger, "");
        $this->jue_sw = new clsField("jue_sw", ccsText, "");
        $this->Alt_jue_nombre = new clsField("Alt_jue_nombre", ccsText, "");
        $this->Alt_Imagen = new clsField("Alt_Imagen", ccsText, "");
        $this->Alt_jue_periodoInicial = new clsField("Alt_jue_periodoInicial", ccsInteger, "");
        $this->Alt_jue_cantidad = new clsField("Alt_jue_cantidad", ccsInteger, "");
        $this->Alt_jue_sw = new clsField("Alt_jue_sw", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-1AE03A46
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "jue_id";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_jue_nombre" => array("jue_nombre", ""), 
            "Sorter_jue_imagen" => array("jue_imagen", ""), 
            "Sorter_jue_periodoInicial" => array("jue_periodoInicial", ""), 
            "Sorter_jue_cantidad" => array("jue_cantidad", ""), 
            "Sorter_jue_sw" => array("jue_sw", "")));
    }
//End SetOrder Method

//Prepare Method @2-EE75BA13
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_jue_nombre", ccsText, "", "", $this->Parameters["urls_jue_nombre"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "jue_nombre", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-666DCD71
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*) " .
        "FROM tb_juegos";
        $this->SQL = "SELECT *  " .
        "FROM tb_juegos {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-489D36C6
    function SetValues()
    {
        $this->jue_nombre->SetDBValue($this->f("jue_nombre"));
        $this->Imagen->SetDBValue($this->f("jue_imagen"));
        $this->jue_periodoInicial->SetDBValue(trim($this->f("jue_periodoInicial")));
        $this->jue_cantidad->SetDBValue(trim($this->f("jue_cantidad")));
        $this->jue_sw->SetDBValue($this->f("jue_sw"));
        $this->Alt_jue_nombre->SetDBValue($this->f("jue_nombre"));
        $this->Alt_Imagen->SetDBValue($this->f("jue_imagen"));
        $this->Alt_jue_periodoInicial->SetDBValue(trim($this->f("jue_periodoInicial")));
        $this->Alt_jue_cantidad->SetDBValue(trim($this->f("jue_cantidad")));
        $this->Alt_jue_sw->SetDBValue($this->f("jue_sw"));
    }
//End SetValues Method

} //End tb_juegosDataSource Class @2-FCB6E20C

class clsRecordtb_juegos1 { //tb_juegos1 Class @31-AA18C186

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

//Class_Initialize Event @31-09B3C066
    function clsRecordtb_juegos1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record tb_juegos1/Error";
        $this->DataSource = new clstb_juegos1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "tb_juegos1";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "multipart/form-data";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->jue_nombre = & new clsControl(ccsTextBox, "jue_nombre", "Nombre", ccsText, "", CCGetRequestParam("jue_nombre", $Method), $this);
            $this->jue_nombre->Required = true;
            $this->FileUpload1 = & new clsFileUpload("FileUpload1", "Imagen", "./image/temp/", "./image/", "*.jpg;*.gif;*.bmp;*.png", "", 2500000, $this);
            $this->jue_periodoInicial = & new clsControl(ccsTextBox, "jue_periodoInicial", "Periodo Inicial", ccsInteger, "", CCGetRequestParam("jue_periodoInicial", $Method), $this);
            $this->jue_periodoInicial->Required = true;
            $this->jue_cantidad = & new clsControl(ccsTextBox, "jue_cantidad", "Cantidad", ccsInteger, "", CCGetRequestParam("jue_cantidad", $Method), $this);
            $this->jue_cantidad->Required = true;
            $this->jue_sw = & new clsControl(ccsListBox, "jue_sw", "Estado", ccsText, "", CCGetRequestParam("jue_sw", $Method), $this);
            $this->jue_sw->DSType = dsListOfValues;
            $this->jue_sw->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->jue_sw->Required = true;
            
            $this->jue_resumen = & new clsControl(ccsListBox, "jue_resumen", "Estado", ccsText, "", CCGetRequestParam("jue_resumen", $Method), $this);
            $this->jue_resumen->DSType = dsListOfValues;
            $this->jue_resumen->Values = array(array("A", "Activo"), array("I", "Inactivo"));
            $this->jue_resumen->Required = true;
            
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = & new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @31-281E0E18
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urljue_id"] = CCGetFromGet("jue_id", "");
    }
//End Initialize Method

//Validate Method @31-F7A9432E
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->jue_nombre->Validate() && $Validation);
        $Validation = ($this->FileUpload1->Validate() && $Validation);
        $Validation = ($this->jue_periodoInicial->Validate() && $Validation);
        $Validation = ($this->jue_cantidad->Validate() && $Validation);
        $Validation = ($this->jue_sw->Validate() && $Validation);
        $Validation = ($this->jue_resumen->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->jue_nombre->Errors->Count() == 0);
        $Validation =  $Validation && ($this->FileUpload1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_periodoInicial->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_cantidad->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_sw->Errors->Count() == 0);
        $Validation =  $Validation && ($this->jue_resumen->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @31-3DAE6A60
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->jue_nombre->Errors->Count());
        $errors = ($errors || $this->FileUpload1->Errors->Count());
        $errors = ($errors || $this->jue_periodoInicial->Errors->Count());
        $errors = ($errors || $this->jue_cantidad->Errors->Count());
        $errors = ($errors || $this->jue_sw->Errors->Count());
        $errors = ($errors || $this->jue_resumen->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @31-6E508E67
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
        $Redirect = "juegos.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @31-1CA0607C
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->jue_nombre->SetValue($this->jue_nombre->GetValue());
        $this->DataSource->FileUpload1->SetValue($this->FileUpload1->GetValue());
        $this->DataSource->jue_periodoInicial->SetValue($this->jue_periodoInicial->GetValue());
        $this->DataSource->jue_cantidad->SetValue($this->jue_cantidad->GetValue());
        $this->DataSource->jue_sw->SetValue($this->jue_sw->GetValue());
        $this->DataSource->jue_resumen->SetValue($this->jue_resumen->GetValue());
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Move();
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @31-500B92B7
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->jue_nombre->SetValue($this->jue_nombre->GetValue());
        $this->DataSource->FileUpload1->SetValue($this->FileUpload1->GetValue());
        $this->DataSource->jue_periodoInicial->SetValue($this->jue_periodoInicial->GetValue());
        $this->DataSource->jue_cantidad->SetValue($this->jue_cantidad->GetValue());
        $this->DataSource->jue_sw->SetValue($this->jue_sw->GetValue());
        $this->DataSource->jue_resumen->SetValue($this->jue_resumen->GetValue());
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Move();
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @31-2B077D44
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

//Show Method @31-E183D1FC
    function Show()
    {
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->jue_sw->Prepare();

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
                    $this->jue_nombre->SetValue($this->DataSource->jue_nombre->GetValue());
                    $this->FileUpload1->SetValue($this->DataSource->FileUpload1->GetValue());
                    $this->jue_periodoInicial->SetValue($this->DataSource->jue_periodoInicial->GetValue());
                    $this->jue_cantidad->SetValue($this->DataSource->jue_cantidad->GetValue());
                    $this->jue_sw->SetValue($this->DataSource->jue_sw->GetValue());   
                    $this->jue_resumen->SetValue($this->DataSource->jue_resumen->GetValue());   
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->jue_nombre->Errors->ToString());
            $Error = ComposeStrings($Error, $this->FileUpload1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_periodoInicial->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_cantidad->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_sw->Errors->ToString());
            $Error = ComposeStrings($Error, $this->jue_resumen->Errors->ToString());
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

        $this->jue_nombre->Show();
        $this->FileUpload1->Show();
        $this->jue_periodoInicial->Show();
        $this->jue_cantidad->Show();
        $this->jue_sw->Show();
        $this->jue_resumen->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End tb_juegos1 Class @31-FCB6E20C

class clstb_juegos1DataSource extends clsDBsiges {  //tb_juegos1DataSource Class @31-10B02CAC

//DataSource Variables @31-5861C49F
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
    var $jue_nombre;
    var $FileUpload1;
    var $jue_periodoInicial;
    var $jue_cantidad;
    var $jue_sw;
//End DataSource Variables

//DataSourceClass_Initialize Event @31-473D031F
    function clstb_juegos1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record tb_juegos1/Error";
        $this->Initialize();
        $this->jue_nombre = new clsField("jue_nombre", ccsText, "");
        $this->FileUpload1 = new clsField("FileUpload1", ccsText, "");
        $this->jue_periodoInicial = new clsField("jue_periodoInicial", ccsInteger, "");
        $this->jue_cantidad = new clsField("jue_cantidad", ccsInteger, "");
        $this->jue_sw = new clsField("jue_sw", ccsText, "");
        $this->jue_resumen = new clsField("jue_resumen", ccsText, "");

    }
//End DataSourceClass_Initialize Event

//Prepare Method @31-0EE12CB7
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urljue_id", ccsInteger, "", "", $this->Parameters["urljue_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "jue_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @31-AE591BEB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT *  " .
        "FROM tb_juegos {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @31-C9E13616
    function SetValues()
    {
        $this->jue_nombre->SetDBValue($this->f("jue_nombre"));
        $this->FileUpload1->SetDBValue($this->f("jue_imagen"));
        $this->jue_periodoInicial->SetDBValue(trim($this->f("jue_periodoInicial")));
        $this->jue_cantidad->SetDBValue(trim($this->f("jue_cantidad")));
        $this->jue_sw->SetDBValue($this->f("jue_sw"));
        $this->jue_resumen->SetDBValue($this->f("jue_resumen"));
    }
//End SetValues Method

//Insert Method @31-343BEC44
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->SQL = "INSERT INTO tb_juegos ("
             . "jue_nombre, "
             . "jue_imagen, "
             . "jue_periodoInicial, "
             . "jue_cantidad, "
             . "jue_sw,"
             . "jue_resumen"
             . ") VALUES ("
             . $this->ToSQL($this->jue_nombre->GetDBValue(), $this->jue_nombre->DataType) . ", "
             . $this->ToSQL($this->FileUpload1->GetDBValue(), $this->FileUpload1->DataType) . ", "
             . $this->ToSQL($this->jue_periodoInicial->GetDBValue(), $this->jue_periodoInicial->DataType) . ", "
             . $this->ToSQL($this->jue_cantidad->GetDBValue(), $this->jue_cantidad->DataType) . ", "
             . $this->ToSQL($this->jue_sw->GetDBValue(), $this->jue_sw->DataType)
             . $this->ToSQL($this->jue_resumen->GetDBValue(), $this->jue_resumen->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @31-D09EC18A
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->SQL = "UPDATE tb_juegos SET "
             . "jue_nombre=" . $this->ToSQL($this->jue_nombre->GetDBValue(), $this->jue_nombre->DataType) . ", "
             . "jue_imagen=" . $this->ToSQL($this->FileUpload1->GetDBValue(), $this->FileUpload1->DataType) . ", "
             . "jue_periodoInicial=" . $this->ToSQL($this->jue_periodoInicial->GetDBValue(), $this->jue_periodoInicial->DataType) . ", "
             . "jue_cantidad=" . $this->ToSQL($this->jue_cantidad->GetDBValue(), $this->jue_cantidad->DataType) . ", "
             . "jue_sw=" . $this->ToSQL($this->jue_sw->GetDBValue(), $this->jue_sw->DataType) . ", "
             . "jue_resumen=" . $this->ToSQL($this->jue_resumen->GetDBValue(), $this->jue_resumen->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @31-5032EB5B
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tb_juegos";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End tb_juegos1DataSource Class @31-FCB6E20C

//Initialize Page @1-FA86ECFF
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
$TemplateFileName = "juegos.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$PathToRoot = "./";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-BB45D78D
include("./juegos_events.php");
//End Include events file

//Initialize Objects @1-0210B1E2
$DBsiges = new clsDBsiges();
$MainPage->Connections["siges"] = & $DBsiges;

// Controls
$tb_juegosSearch = & new clsRecordtb_juegosSearch("", $MainPage);
$tb_juegos = & new clsGridtb_juegos("", $MainPage);
$tb_juegos1 = & new clsRecordtb_juegos1("", $MainPage);
$MainPage->tb_juegosSearch = & $tb_juegosSearch;
$MainPage->tb_juegos = & $tb_juegos;
$MainPage->tb_juegos1 = & $tb_juegos1;
$tb_juegos->Initialize();
$tb_juegos1->Initialize();

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

//Execute Components @1-49882F3E
$tb_juegosSearch->Operation();
$tb_juegos1->Operation();
//End Execute Components

//Go to destination page @1-7A10A15E
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsiges->close();
    header("Location: " . $Redirect);
    unset($tb_juegosSearch);
    unset($tb_juegos);
    unset($tb_juegos1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-29518BDA
$tb_juegosSearch->Show();
$tb_juegos->Show();
$tb_juegos1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
$main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-9663940F
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsiges->close();
unset($tb_juegosSearch);
unset($tb_juegos);
unset($tb_juegos1);
unset($Tpl);
//End Unload Page


?>

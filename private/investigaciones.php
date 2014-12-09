<?php
include ("./common2.php");
@session_start();
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$svaloresRecordErr = "";
$sDeleteValue = get_param("deleteValue");

//print_r($_GET);

switch ($sForm) {
  case "valoresRecord":
    valoresRecord_action($sAction);
  break;
  case "eliminarFila":
  	eliminarFilaValue($sDeleteValue);
  break;
}
function eliminarFilaValue($sDeleteValue)
{
global $db;
$db->query("delete from tb_investigacion where inv_id=$sDeleteValue");
}
function valoresRecord_action($sAction)
{
  global $db;
  global $tpl;
  global $sForm;
  global $svaloresRecordErr;
  $bExecSQL = false;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $fldperiodo = get_param("dat_periodo");
  $fldcantidad = get_param("cant"); 
  $fldperiodoinicial = get_param("per_ini"); 
  $fldjuego = get_param("jue_id"); 
  $flddat_periodo = get_param("dat_periodo");
  $costo = get_param("costo");
  $costo_exclusividad = get_param("costo_exclusividad");
  $cantidad = get_param("cantidad");
  $pdf = get_param("pdf");
  $investigacion = get_param("investigacion");   
  $TMPFILES = $_FILES["pdf"]["tmp_name"];
  $FILES = $_FILES["pdf"]["name"];
  $idInv = get_param("idInv");
 // print_r($investigacion);
 // print_r($FILES);
 // print_r($costo);
  //print_r($costo_exclusividad);
//  print_r($idInv);
 
  $cantidadDat = get_db_value("select count(*) from tb_investigacion where inv_jue_id=$fldjuego and  inv_per_id=$fldperiodo");
  if($cantidadDat==0)
  foreach($investigacion as $key => $value)
  {
	if(!$value) $value='';
	if(!$costo[$key]) $costo[$key]=0;  
	if(!$costo_exclusividad[$key]) $costo_exclusividad[$key]=0;  
	if(!$cantidad[$key]) $cantidad[$key]=0;  
	if(strlen($value)>0)
	{
	$sSQL = "insert into tb_investigacion values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad[$key].", ".$cantidad[$key].", ".$cantidad[$key].",'',1) ";
	//echo $sSQL;
	$db->query($sSQL);  
	$uidInv =get_db_value("Select last_insert_id()");
	$db->query("delete from tb_compras where com_inv_id=$uidInv");
	
	//echo $FILES[$key];
	 if($FILES[$key]!='')
			{
			  $nombrepdf= 'temp/Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
			  $nombrepdf1= 'Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
			  
						$sSQL="update tb_investigacion set inv_pdf='$nombrepdf1' where inv_jue_id=$fldjuego and inv_per_id=$fldperiodo and inv_id=$uidInv";
						$db->query($sSQL);
						move_uploaded_file($TMPFILES[$key] , $nombrepdf);
						chmod( $nombrepdf , 0755 );
			}
		}
	}//END FOREACH
	else
	foreach($investigacion as $key => $value)
	  {
		  $nombrepdf= 'temp/Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
		  $nombrepdf1= 'Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
		 // if($cantidad[$key])
		 // echo "[key]".$key."[LEN]".strlen($idInv[$key])."inv:".$idInv[$key]."value:".$value."<br>";
		  
		  if(!$costo[$key]) $costo[$key]=0;  
	if(!$costo_exclusividad[$key]) $costo_exclusividad[$key]=0;  
	if(!$cantidad[$key]) $cantidad[$key]=0;  
	$sSQL="";
		  if(is_numeric($idInv[$key])&&(strlen($value)>0))//&&($costo[$key])&&($costo_exclusividad[$key])&&(strlen($cantidad[$key])>0))
		  $sSQL = "update tb_investigacion set  inv_investigacion='$value', inv_costo=".$costo[$key].", inv_costoexclusividad=".$costo_exclusividad[$key].", inv_cantidad=".$cantidad[$key].", inv_saldo=".$cantidad[$key]." where inv_jue_id=$fldjuego and inv_per_id=$fldperiodo and inv_id=".$idInv[$key];
		  elseif(strlen($value)>0)//&&($value)&&($costo[$key])&&($costo_exclusividad[$key])&&($cantidad[$key]))
		 $sSQL = "insert into tb_investigacion values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad[$key].", ".$cantidad[$key].", ".$cantidad[$key].",'',1) ";
		 //else $idInv[$key];
		if(strlen($sSQL)>0)
		{
		//echo $sSQL;
		$db->query($sSQL);  
		if($idInv[$key]) $uidInv = $idInv[$key];
		else $uidInv =get_db_value("Select last_insert_id()");
		//echo $FILES[$key]; 
		$db->query("delete from tb_compras where com_inv_id=$uidInv");
	
		 if($FILES[$key]!='')
			{
			  $nombrepdf= 'temp/Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
			  $nombrepdf1= 'Pdf'. $fldjuego."-".$value."-".$fldperiodo.'.pdf';
			  
						$sSQL="update tb_investigacion set inv_pdf='$nombrepdf1' where inv_jue_id=$fldjuego and inv_per_id=$fldperiodo and inv_id=$uidInv";
						$db->query($sSQL);
						//echo $sSQL;
						move_uploaded_file($TMPFILES[$key] , $nombrepdf);
						chmod( $nombrepdf , 0755 );
			}
		}
	}		//FOREACH 
			 
//  header("Location: investigaciones.php?jue_id=$fldjuego&per_ini=$fldperiodoinicial&cant=$fldcantidad");
//  exit;
  
}
  $fldperiodo = get_param("dat_periodo");
  if(!$fldperiodo) $fldperiodo = get_param("per_ini");

  $dat_periodo = get_param("dat_periodo");
  $fldvai_id = "";
  $fldvai_jue_id = "";
  $fldvai_atr_id = "";
  $fldvai_pro_id = "";
  $fldvai_mer_id = "";
  $fldvai_cli_id = "";
  $fldvai_monto = "";
  $fldvai_periodo = "";
  $fldvai_sw = "";
  $fldjuego = get_param("jue_id"); 
  $sFormTitle = "th_valoresiniciales";
  $fldFormvaloresGrid_Page = get_param("FormvaloresGrid_Page");
  $sWhere = "";
  $fldvai_jue_id= get_param("jue_id");
  $bPK = true;
  $periodoinicial = get_param("per_ini");
  $periodocantidad = get_param("cant") ;
  $juego = get_param("jue_id");
  $sSQL="select * from tb_investigacion where inv_jue_id=$fldjuego and  inv_per_id=$fldperiodo";
//  echo $sSQL;
  $db->query($sSQL);
  $k=$db->num_rows();
  //echo $k;
  //$k=0;
?>
<html>
<head>
<title>siges</title>
<meta name="Gen" content="Jsiles">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<link href="Themes/Clear/Style.css" type="text/css" rel="stylesheet">
<script type="text/javascript" language="javascript">
// Global vars
var k=<?=$k?>;
</script>
<script type="text/javascript" src="js/investigacion.js" language="javascript">
</script>
<script type="text/javascript" language="javascript" src="js/jquery.MultiFile.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
 $('.multi-pt').MultiFile({
  accept:'pdf', max:1, STRING: {
   remove:'Remover',
   selected:'Selecionado: $file',
   denied:'Invalido archivo de tipo $ext!'
  }
 });
});
function eliminarFila(e)
 {
	 document.valoresRecord.FormName.value='eliminarFila';
	 document.valoresRecord.deleteValue.value=e;
	 document.valoresRecord.submit();
	 }

</script>
</head>
<body class="PageBODY">
<table width="100%">
<tr><td>
<a href="adicionar" class="lnkAddfam" onClick="return addInv();">Adicionar informaci&oacute;n</a><br><br>

</td>
<td>Compras efectuadas<a href="../comprasResumen.php?jue_id=<?=$juego?>" target="_blank" title="Reporte resumen" ><img src="../image/excel.jpg" alt="Reporte resumen" border="0"></a></td>
</tr>
</table>

   <!--BeginFormvaloresRecord-->
  <form method="POST" enctype="multipart/form-data" action="investigaciones.php" name="valoresRecord">
  <table class="ClearFormTABLE" border="0">
     <tr>
     <td width="136"> Periodo:<select name="dat_periodo" onChange="submit();">
<?php
      for($i=0;$i<$periodocantidad;$i++)
        {            
            $periodo[$periodoinicial+$i] = $periodoinicial+$i;
        }
        if(is_array($periodo))
                {
                  reset($periodo);
                  $i=0;                                   
                  while(list($key, $value) = each($periodo))
                  {
                    if ($i==0&&$dat_periodo=='') $fldperiodo = $key;
					if($key == $dat_periodo)
                      $selected="SELECTED"; else $selected="";
?>
              <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
<?                    
                    $i++;
                  }
                }

  $fldvai_inicial = get_param("per_ini");
  $fldvai_cantidad = get_param("cant");
  $juego=dlookup("tb_juegos", "jue_nombre" , "jue_id=$fldvai_jue_id");
  $fldvai_final =   $fldvai_inicial + $fldvai_cantidad -1;

  $PK_inv_id=$pvai_id;

  $vai_final=tohtml($fldvai_final);
  $vai_cantidad=tohtml($fldvai_cantidad);
  $vai_id=tohtml($fldvai_id);
  $vai_jue_id=tohtml($fldvai_jue_id);
  


?>
              </select>
     </td>
      <td class="ClearFieldCaptionTD" colspan="6" align="center">Juego: <?=$juego?></td>
      </tr>
     <tr>
     <td class="ClearFieldCaptionTD" width="150" >Informaci&oacute;n
     </td>
      <!--BeginEtiquetas-->
     <td class="ClearFieldCaptionTD"  width="124">Costo&nbsp;
     </td>
     <td width="134" class="ClearFieldCaptionTD">Costo exclusividad&nbsp;
     </td>
     <td class="ClearFieldCaptionTD" width="94">Cantidad&nbsp;
     </td>
     <td width="193" class="ClearFieldCaptionTD">PDF&nbsp;
     </td>
     <td  width="50" class="ClearFieldCaptionTD"></td>
    <!-- <td width="163" class="ClearFieldCaptionTD"></td>-->
     <!--EndEtiquetas-->  
     </tr>
     <tr>  
	<!--BeginProductos-->
    <td colspan="6">
     <table id="dataInvestigacion" class="ClearFormTABLE">
     <?php
	 $l=1;
     while($db->next_record())
	 {
	?>
     <tr>
      <td width="150" class="ClearFieldCaptionTD"><input name="investigacion[]" onClick="clean(this);" id="investigacion<?=$l?>" type="text" size="20" value="<?=$db->f("inv_investigacion")?>"></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("inv_costo")?>">
      </td>
      <td width="140" class="ClearDataTD"  ><input name="costo_exclusividad[]" id="costo_exclusividad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("inv_costoexclusividad")?>">
      </td>
      <td width="94" class="ClearDataTD"  ><input name="cantidad[]" id="cantidad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("inv_cantidad")?>">
      </td>
      <td width="193" class="ClearDataTD"><input name="pdf[]" id="pdf<?=$l?>" class="multi-pt" type="file" size="15"><span id="pdflabel<?=$k?>"><?=$db->f("inv_pdf")?></span>
      <input name="idInv[]" id="idInv<?=$l?>" type="hidden" value="<?=$db->f("inv_id")?>" >
      </td>
      <td width="50" class="ClearDataTD">
       <img src="./lib/delete_es.gif" alt="Eliminar" style="cursor:pointer" title="Eliminar" border="0" onClick="eliminarFila(<?=$db->f("inv_id")?>);"> 
      </td>
     </tr>
	<?php
	$l++;
    }
	?>
	</table>
    <table id="dataInvestigacion0" class="ClearFormTABLE">
     <tr>
      <td width="150" class="ClearFieldCaptionTD"><input name="investigacion[]" onClick="clean(this);" id="investigacion0" type="text" size="20" value=""></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="140" class="ClearDataTD"  ><input name="costo_exclusividad[]" id="costo_exclusividad0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="94" class="ClearDataTD"  ><input name="cantidad[]" id="cantidad0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="193" class="ClearDataTD"><input name="pdf[]" id="pdf0" class="multi-pt" type="file" size="15"><span id="pdflabel0"></span>
      <input name="idInv[]" id="idInv0" type="hidden" value="" >
      </td>
       <td width="50" class="ClearDataTD">
       <img id="eliminar_off" src="./lib/delete_off_es.gif" alt="Eliminar" title="Eliminar" border="0" >
      </td>
       
     </tr>
	</table>
    
    <table id="dataInvestigacionX" class="ClearFormTABLE" style="display:none">
     <tr>
      <td width="150" class="ClearFieldCaptionTD"><input name="investigacion[]" onClick="clean(this);" id="investigacionX" type="text" size="20" value=""></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="140" class="ClearDataTD"  ><input name="costo_exclusividad[]" id="costo_exclusividadX" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="94" class="ClearDataTD"  ><input name="cantidad[]" id="cantidadX" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <td width="193" class="ClearDataTD"><input name="pdf[]" id="pdfX" class="multi-pt" type="file" size="15"><span id="pdflabel0"></span>
      <input name="idInv[]" id="idInvX" type="hidden" value="" >
      </td>
       <td width="50" class="ClearDataTD">
       <img id="eliminar_off" src="./lib/delete_off_es.gif" alt="Eliminar" title="Eliminar" border="0" >
      </td>       
     </tr>
	</table>
    
    <!--EndProductos-->
    </td>
    </tr>
    <tr>
    <td colspan="6">
    <table id="frmDinamico" class="ClearFormTABLE">
    </table>
    </td>
    </tr>
   <tr ><td height="50">&nbsp;</td>
        <td align="center" colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
            <input name="aceptar" type="submit" onClick="document.valoresRecord.FormName.value='valoresRecord'" value="Guardar">
            <input type="hidden" name="FormName" value=""/>
            <input type="hidden" name="deleteValue" value=""/>
            
      <input type="hidden" name="FormvaloresGrid_Page" value="<?=$FormvaloresGrid_Page?>"/> 
      <input type="hidden" name="vai_jue_id" value="<?=$vai_jue_id?>"/>
      <input type="hidden" name="jue_id" value="<?=$vai_jue_id?>"/>
      <input type="hidden" name="vai_cantidad" value="<?=$vai_cantidad?>"/>
      <input type="hidden" name="cant" value="<?=$vai_cantidad?>"/>
      <input type="hidden" name="vai_inicial" value="<?=$fldvai_inicial?>"/>
      <input type="hidden" name="per_ini" value="<?=$fldvai_inicial?>"/>
     <!-- <input type="text" name="dat_periodo" value="<?=$fldperiodo?>"/>-->
      <input type="hidden" name="PK_vai_id" value="<?=$PK_vai_id?>"/>
            </td>
      </tr>
      
   </table>
  </form>

<!--EndFormvaloresRecord-->
</body>
</html>
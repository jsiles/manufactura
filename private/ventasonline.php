<?php
include ("./common2.php");
include ("./thumb.php");
session_start();
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
	$db->query("delete from tb_ventas where ven_id=$sDeleteValue");
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
  $fldperiodoinicial = get_param("per_ini"); 
  $fldjuego = get_param("jue_id"); 
  $flddat_periodo = get_param("dat_periodo");
  $fldcantidad = get_param("cant"); 
  $idInv = get_param("idInv");
  $fechaI = get_param("fechaI");   
  $horaI = get_param("horaI");   
  $ven_nombre = get_param("ven_nombre"); //investigacion x ven_nombre
  $ven_unidad = get_param("ven_unidad"); //cantidad x ven_unidad
  $ven_precio = get_param("ven_precio"); //costo x ven_precio
  $ven_cantidad= get_param("ven_cantidad"); //costo_exclusividad x cantidad
  $ven_tiempo =  get_param("ven_tiempo"); //nuevo
  
//  print_r($ven_nombre);
//  print_r($idInv);
  $pdf = get_param("pdf");
  $TMPFILES = $_FILES["pdf"]["tmp_name"];
  $FILES = $_FILES["pdf"]["name"];
  
  
//  echo "POST:"; print_r($_POST); echo "END POST";
 
  $cantidadDat = get_db_value("select count(*) from tb_ventas where ven_jue_id=$fldjuego and ven_per_id=$fldperiodo");
  if($cantidadDat==0)
  foreach($ven_nombre as $key => $value)
  {
	if(!$value) $value='';
	if(!$ven_precio[$key]) $ven_precio[$key]=0;  
	if(!$ven_cantidad[$key]) $ven_cantidad[$key]=0;  
	if(!$ven_unidad[$key]) $ven_unidad[$key]=1;  
	if(!$ven_tiempo[$key]) $ven_tiempo[$key]=0;  

	$fechaInicio = changeFormatDate(trim($fechaI[$key]),1) . " " .trim($horaI[$key]);
			$yearFI = substr($fechaI[$key],6,4);
			$monthFI = substr($fechaInicio,5,2);
			$dayFI = substr($fechaInicio,8,2);
			$hourFI = substr($fechaInicio,11,2);
			$minuteFI = substr($fechaInicio,14,2);
			$secondFI = substr($fechaInicio,17,2);//echo $yearFI;exit;
			$fechaFin = date("Y-m-d H:i:s", mktime($hourFI,$minuteFI + $ven_tiempo[$key],$secondFI,$monthFI,$dayFI,$yearFI));

	$sSQL = "insert into tb_ventas values(null, $fldjuego, $fldperiodo, '$value', ".$ven_precio[$key].", ".$ven_cantidad[$key].", '".$ven_unidad[$key]."', ".$ven_tiempo[$key].", '$fechaInicio','$fechaFin','',1) ";
	//echo $sSQL;
	$db->query($sSQL);  
	$uidInv =get_db_value("Select last_insert_id()");
	//echo $FILES[$key];
	 if($FILES[$key]!='')
		{
			  $nombrepdf= 'temp/ven-'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  $nombrepdf1= 'ven-'.imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
		
		  
					$sSQL="update tb_ventas set ven_foto='$nombrepdf1' where ven_jue_id=$fldjuego and ven_per_id=$fldperiodo and ven_id=$uidInv";
					$db->query($sSQL);
					move_uploaded_file($TMPFILES[$key] , $nombrepdf);
  	    	$nombrepdfThumb= 'temp/thumb_ven-'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';

		$mythumb = new thumb(); 
	
		list($width, $height) = getimagesize($nombrepdf);

		if ($width >= $height) $Prioridad='width';
		else $Prioridad='height';
		$mythumb->loadImage($nombrepdf); 
		$mythumb->resize(132,$Prioridad); 
		$mythumb->save( $nombrepdf ,100 );	
		$mythumb->resize(47,$Prioridad); 
		$mythumb->save( $nombrepdfThumb ,100 );

		chmod( $nombrepdf , 0755 );
		chmod( $nombrepdfThumb , 0755 );
		}
	}
	else
	foreach($ven_nombre as $key => $value)
	  {
		  	if(!$value) $value='';
			if(!$ven_precio[$key]) $ven_precio[$key]=0;  
			if(!$ven_cantidad[$key]) $ven_cantidad[$key]=0;  
			if(!$ven_unidad[$key]) $ven_unidad[$key]=1;  
			if(!$ven_tiempo[$key]) $ven_tiempo[$key]=0;  

		  $fechaInicio = changeFormatDate(trim($fechaI[$key]),1) . " " .trim($horaI[$key]);
			$yearFI = substr($fechaI[$key],6,4);
			$monthFI = substr($fechaInicio,5,2);
			$dayFI = substr($fechaInicio,8,2);
			$hourFI = substr($fechaInicio,11,2);
			$minuteFI = substr($fechaInicio,14,2);
			$secondFI = substr($fechaInicio,17,2);//echo $yearFI;exit;
			$fechaFin = date("Y-m-d H:i:s", mktime($hourFI,$minuteFI + $ven_tiempo[$key],$secondFI,$monthFI,$dayFI,$yearFI));		  
		if(($idInv[$key])&&($value)&&($ven_precio[$key])&&($ven_cantidad[$key])&&($ven_tiempo[$key]))
		  {
			  $sSQL = "update tb_ventas set  ven_nombre='$value', ven_precio=".$ven_precio[$key].", ven_cantidad=".$ven_cantidad[$key].", ven_unidad='".$ven_unidad[$key]."', ven_fecha='$fechaInicio', ven_fechafin='$fechaFin', ven_tiempo=".$ven_tiempo[$key]." where ven_jue_id=$fldjuego and ven_per_id=$fldperiodo and ven_id=".$idInv[$key];
		 	 $sSQL1="delete from tb_ofertas where ofe_ven_id=".$idInv[$key];
	  		 $db->query($sSQL1);
		  }
		  elseif((!$idInv[$key])&&($value)&&($ven_precio[$key])&&($ven_cantidad[$key])&&($ven_tiempo[$key]))
		 $sSQL = "insert into tb_ventas values(null, $fldjuego, $fldperiodo, '$value', ".$ven_precio[$key].", ".$ven_cantidad[$key].", '".$ven_unidad[$key]."', ".$ven_tiempo[$key].", '$fechaInicio','$fechaFin','',1) ";
		
		//echo $sSQL;
		$db->query($sSQL);  
		
		
		if($idInv[$key]) $uidInv = $idInv[$key];
		else $uidInv =get_db_value("Select last_insert_id()");
		//echo $FILES[$key]; 
		 if($FILES[$key]!='')
			{
			  $nombrepdf= 'temp/ven-'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  $nombrepdf1= 'ven-'.imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  
						$sSQL="update tb_ventas set ven_foto='$nombrepdf1' where ven_jue_id=$fldjuego and ven_per_id=$fldperiodo and ven_id=$uidInv";
						$db->query($sSQL);
						//echo $sSQL;
						move_uploaded_file($TMPFILES[$key] , $nombrepdf);
						$nombrepdfThumb= 'temp/thumb_ven-'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
				
						$mythumb = new thumb(); 
					
						list($width, $height) = getimagesize($nombrepdf);
				
						if ($width >= $height) $Prioridad='width';
						else $Prioridad='height';
						$mythumb->loadImage($nombrepdf); 
						$mythumb->resize(132,$Prioridad); 
						$mythumb->save( $nombrepdf ,100 );	
						$mythumb->resize(47,$Prioridad); 
						$mythumb->save( $nombrepdfThumb ,100 );	
						chmod( $nombrepdf , 0755 );
						chmod( $nombrepdfThumb , 0755 );
			}
		
		 }		 
//  header("Location: investigaciones.php?jue_id=$fldjuego&per_ini=$fldperiodoinicial&cant=$fldcantidad");
//  exit;
//die;
} //END FUNCTION


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
  $sWhere = "";
  $fldvai_jue_id= get_param("jue_id");
  $bPK = true;
  $periodoinicial = get_param("per_ini");
  $periodocantidad = get_param("cant") ;
  $juego = get_param("jue_id");
  $arrayProyecto = db_fill_array("select pro_id, pro_descripcion from py_proyectos where pro_jue_id=$juego");
  $sSQL="select * from tb_ventas where ven_jue_id=$fldjuego and  ven_per_id=$fldperiodo";
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
<script type="text/javascript" src="js/ventasonline.js" language="javascript">
</script>
<script type="text/javascript" language="javascript" src="js/jquery.MultiFile.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
 $('.multi-pt').MultiFile({
  accept:'jpg', max:1, STRING: {
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
<table>
<tr>
  <td>
<a href="adicionar" class="lnkAddfam" onClick="return addVen();">Adicionar proyecto </a><br>
<br>

</td>
</tr>
</table>

   <!--BeginFormvaloresRecord-->
  <form method="POST" enctype="multipart/form-data" action="ventasonline.php" name="valoresRecord">
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
      <td class="ClearFieldCaptionTD" colspan="8" align="center">Juego: <?=$juego?></td>
      </tr>
     <tr>
     <td class="ClearFieldCaptionTD" width="153" >Proyecto
     </td>
      <!--BeginEtiquetas-->
   
     
     <!--<td width="134" class="ClearFieldCaptionTD">Unidad&nbsp; </td>-->
     <td width="134" class="ClearFieldCaptionTD">Cantidad Expertos&nbsp; </td> 
     
     <td class="ClearFieldCaptionTD"  width="124">Precio m&iacute;nimo&nbsp;
     </td> 
     <td width="163" class="ClearFieldCaptionTD">Fecha de inicio</td>
     <td width="163" class="ClearFieldCaptionTD">Hora de inicio</td>
     
     <td class="ClearFieldCaptionTD" width="94">Tiempo duraci&oacute;n&nbsp;
     </td>
    <td width="193" class="ClearFieldCaptionTD">Fotograf&iacute;a&nbsp;
     (jpg)</td>
    <td width="50" class="ClearFieldCaptionTD">
      </td> 
     
     <!--EndEtiquetas-->  
     </tr>
     <tr>  
	<!--BeginProductos-->
    <td colspan="8">
     <table id="dataInvestigacion" class="ClearFormTABLE" >
     <?php
	 $l=1;
     while($db->next_record())
	 {
	?>
     <tr>
      <td width="136" class="ClearFieldCaptionTD">
      <select name="ven_nombre[]"  id="ven_nombre<?=$l?>">
         <option value="">Seleccionar</option>
      <?php
		  foreach($arrayProyecto as $key=>$value)
		  {
			  if($key==$db->f("ven_nombre")) $selValue="Selected"; else $selValue="";
		  ?>
		  <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
		  <?
		  }
	  ?>
      </select>
      </td>
    
      
      <!--<td width="140" class="ClearDataTD"  ><input name="ven_unidad[]" id="ven_unidad<?=$l?>" onClick="clean(this);" type="text" size="10" value="<?=$db->f("ven_unidad")?>">
      </td>-->
  <td width="134" class="ClearDataTD"  ><input name="ven_cantidad[]" id="ven_cantidad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("ven_cantidad")?>">
      </td>
  <td width="124" class="ClearDataTD"><input name="ven_precio[]" id="ven_precio<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("ven_precio")?>">
      </td>
    

        <td width="163" class="ClearDataTD"><input name="fechaI[]" id="fechaI<?=$l?>" type="text" size="12" value="<?=changeFormatDate(substr($db->f("ven_fecha"),0,10),2)?>" readonly><a id="calendar<?=$l?>" href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI<?=$l?>);return false;" ><img border="0" src="calendario/icon_calendar.gif">				</a></td>
      <td width="163" class="ClearDataTD"><input name="horaI[]" id="horaI<?=$l?>"  type="text" size="8" value="<?=substr($db->f("ven_fecha"),11)?>"></td>
     
      <td width="94" class="ClearDataTD"  ><input name="ven_tiempo[]" id="ven_tiempo<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("ven_tiempo")?>">
      </td>
      
      <td width="193" class="ClearDataTD">
      <?php if(file_exists($nombrepdfThumb= 'temp/thumb_'.$db->f("ven_foto"))) 
	  {
	  ?>
      <img border="0" src="<?='temp/thumb_'.$db->f("ven_foto")?>">
      <?php
	  }
	  ?>
      <input name="pdf[]" id="pdf<?=$l?>" class="multi-pt" type="file" size="15"><span id="pdflabel<?=$k?>"><?=$db->f("ven_foto")?></span>
      <input name="idInv[]" id="idInv<?=$l?>" type="hidden" value="<?=$db->f("ven_id")?>" >
      </td>
    <td width="50" class="ClearDataTD">
       <img src="./lib/delete_es.gif" alt="Eliminar" style="cursor:pointer" title="Eliminar" border="0" onClick="eliminarFila(<?=$db->f("ven_id")?>);"> 
      </td>      
     </tr>
	<?php
	$l++;
    }
	?>
	</table>
    <table id="dataInvestigacion0" class="ClearFormTABLE">
     <tr>
      <td width="136" class="ClearFieldCaptionTD"><select name="ven_nombre[]"  id="ven_nombre0">
	      <option value="" selected>Seleccionar</option>
      <?php
		  foreach($arrayProyecto as $key=>$value)
		  {
		  ?>
		  <option value="<?=$key?>"><?=$value?></option>
		  <?
		  }
	  ?>
      </select></td>
      
      
      <!--<td width="140" class="ClearDataTD"  ><input name="ven_unidad[]" id="ven_unidad0" onClick="clean(this);" type="text" size="10" value="">
      </td>-->
      
      <td width="134" class="ClearDataTD"  ><input name="ven_cantidad[]" id="ven_cantidad0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      
      <td width="124" class="ClearDataTD"><input name="ven_precio[]" id="ven_precio0" onClick="clean(this);" type="text" size="3" value="">
      </td>
         
        <td width="163" class="ClearDataTD"><input name="fechaI[]" id="fechaI0" type="text" size="12" value="" readonly><a id="calendar0" href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI0);return false;" ><img border="0" src="calendario/icon_calendar.gif">				</a></td>
      <td width="163" class="ClearDataTD"><input name="horaI[]" id="horaI0"  type="text" size="8" value=""></td>
    
      <td width="94" class="ClearDataTD"  ><input name="ven_tiempo[]" id="ven_tiempo0" onClick="clean(this);" type="text" size="3" value="">
      <input name="idInv[]" id="idInv0" type="hidden" value="" >
      </td>
      <td width="193" class="ClearDataTD"><input name="pdf[]" id="pdf0" class="multi-pt" type="file" size="15"><span id="pdflabel0"></span>
      <input name="idInv[]" id="idInv0" type="hidden" value="" >
  <td width="50" class="ClearDataTD">
       <img id="eliminar_off" src="./lib/delete_off_es.gif" alt="Eliminar" title="Eliminar" border="0" >
      </td>
     </tr>
	</table>
    <!--EndProductos-->
    </td>
    </tr>
    <tr>
    <td colspan="8">
    <table id="frmDinamico" class="ClearFormTABLE">
    </table>
    </td>
    </tr>
   <tr ><td height="50">&nbsp;</td>
        <td align="center" colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
            <input name="aceptar" type="submit" onClick="document.valoresRecord.FormName.value='valoresRecord'" value="Guardar">
            <input type="hidden" name="FormName" value=""/>
            <input type="hidden" name="deleteValue" value=""/>
       <input type="hidden" name="vai_jue_id" value="<?=$vai_jue_id?>"/>
      <input type="hidden" name="jue_id" value="<?=$vai_jue_id?>"/>
      <input type="hidden" name="vai_cantidad" value="<?=$vai_cantidad?>"/>
      <input type="hidden" name="cant" value="<?=$vai_cantidad?>"/>
      <input type="hidden" name="vai_inicial" value="<?=$fldvai_inicial?>"/>
      <input type="hidden" name="per_ini" value="<?=$fldvai_inicial?>"/>
            </td>
      </tr>      
   </table>
  </form>

<!--EndFormvaloresRecord-->
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="calendario/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>

</body>
</html>
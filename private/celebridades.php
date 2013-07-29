<?php
include ("./common2.php");
include ("./thumb.php");
session_start();
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
$svaloresRecordErr = "";
$sDeleteValue = get_param("deleteValue");
$fldjuego = get_param("jue_id"); 

$arrayProyecto = db_fill_array("select pro_id, pro_descripcion from py_proyectos where pro_jue_id=$fldjuego");
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
	$db->query("delete from tb_celebridades where cel_id=$sDeleteValue");
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
  $fechaI = get_param("fechaI");   
  $horaI = get_param("horaI");   
  //print_r($investigacion);//
  //print_r($FILES);
  //print_r($costo);//
  //print_r($costo_exclusividad);
  //print_r($idInv);//
 
  $cantidadDat = get_db_value("select count(*) from tb_celebridades where cel_jue_id=$fldjuego and cel_per_id=$fldperiodo");
  if($cantidadDat==0)
  foreach($investigacion as $key => $value)
  {
	if(!$value) $value=0;
	if(!$costo[$key]) $costo[$key]=0;  
	if(!$costo_exclusividad[$key]) $costo_exclusividad[$key]=0;  
	if(!$cantidad[$key]) $cantidad[$key]=0;  
	 if(($fechaI[$key])&&($horaI[$key]))
		  {
	$fechaInicio = changeFormatDate(trim($fechaI[$key]),1) . " " .trim($horaI[$key]);
			$yearFI = substr($fechaI[$key],6,4);
			$monthFI = substr($fechaInicio,5,2);
			$dayFI = substr($fechaInicio,8,2);
			$dateFin = explode(":",$horaI[$key]);
			$hourFI = $dateFin[0];//substr($fechaInicio,11,2); //echo "hora:".$hourFI;
			$minuteFI = $dateFin[1];//substr($fechaInicio,14,2); //echo "minuto:".$minuteFI;
			$secondFI = $dateFin[2];//substr($fechaInicio,17,2);//echo $yearFI;exit;
			$fechaFin = date("Y-m-d H:i:s", mktime($hourFI,$minuteFI + $cantidad[$key],$secondFI,$monthFI,$dayFI,$yearFI));
			//echo $fechaInicio."$$".$fechaFin;
		  }
	$sSQL = "insert into tb_celebridades values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad[$key].", ".$cantidad[$key].", '$fechaInicio','$fechaFin','',1) ";
	$db->query($sSQL);  
	$uidInv =get_db_value("Select last_insert_id()");
	//echo $FILES[$key];
	 if($FILES[$key]!='')
		{
			  $nombrepdf= 'temp/'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  $nombrepdf1= imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
		
		  
					$sSQL="update tb_celebridades set cel_foto='$nombrepdf1' where cel_jue_id=$fldjuego and cel_per_id=$fldperiodo and cel_id=$uidInv";
					$db->query($sSQL);
					move_uploaded_file($TMPFILES[$key] , $nombrepdf);
  	    	$nombrepdfThumb= 'temp/thumb_'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';

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
	foreach($investigacion as $key => $value)
	  {
//		  $nombrepdf= 'temp/'. imageName($fldjuego)."-".$value."-".$fldperiodo.'.jpg';
	//		  $nombrepdf1= imageName($fldjuego)."-".$value."-".$fldperiodo.'.jpg';
			  
		 // if($cantidad[$key])
		 
		  if(!$costo_exclusividad[$key]) $costo_exclusividad[$key]=0;  
		  //echo $idInv[$key]."--".$value."--".$costo[$key]."--".$costo_exclusividad[$key]."--".$cantidad[$key]."::";
		  if(($fechaI[$key])&&($horaI[$key]))
		  {
		  $fechaInicio = changeFormatDate(trim($fechaI[$key]),1) . " " .trim($horaI[$key]);
			$yearFI = substr($fechaI[$key],6,4);
			$monthFI = substr($fechaInicio,5,2);
			$dayFI = substr($fechaInicio,8,2);
			$dateFin = explode(":",$horaI[$key]);
			$hourFI = $dateFin[0];//substr($fechaInicio,11,2); //echo "hora:".$hourFI;
			$minuteFI = $dateFin[1];//substr($fechaInicio,14,2); //echo "minuto:".$minuteFI;
			$secondFI = $dateFin[2];//substr($fechaInicio,17,2);//echo $yearFI;exit;
			$fechaFin = date("Y-m-d H:i:s", mktime($hourFI,$minuteFI + $cantidad[$key],$secondFI,$monthFI,$dayFI,$yearFI));//,$yearFI));//,$minuteFI+$cantidad[$key],$secondFI, $monthFI,$dayFI,$yearFI));
			//echo $yearFI."/".$monthFI."/".$dayFI." ".$hourFI.":".$minuteFI.":".$secondFI;die;	
			//echo $fechaInicio."$$".$fechaFin;
		  }
		  //if(($idInv[$key])&&($value)&&($costo[$key])&&($costo_exclusividad[$key])&&(strlen($cantidad[$key])>0))
		  if(($idInv[$key])&&($value)&&($costo[$key])&&(strlen($cantidad[$key])>0))
		  {
			  $sSQL = "update tb_celebridades set  cel_nombre='$value', cel_precio=".$costo[$key].", cel_beneficio=".$costo_exclusividad[$key].", cel_tiempo=".$cantidad[$key].", cel_fecha='$fechaInicio', cel_fechafin='$fechaFin' where cel_jue_id=$fldjuego and cel_per_id=$fldperiodo and cel_id=".$idInv[$key];
		  $sSQL1="delete from tb_pujas where puj_cel_id=".$idInv[$key];
		$db->query($sSQL1);
			//echo ";l;l;l;";
		  }
		  elseif((!$idInv[$key])&&($value)&&($costo[$key])&&($cantidad[$key]))
		 $sSQL = "insert into tb_celebridades values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad[$key].", ".$cantidad[$key].", '$fechaInicio','$fechaFin','',1)";
		 //else $idInv[$key];
		//echo $sSQL;
		$db->query($sSQL);  
		
		
		if($idInv[$key]) $uidInv = $idInv[$key];
		else $uidInv =get_db_value("Select last_insert_id()");
		//echo $FILES[$key]; 
		 if($FILES[$key]!='')
			{
			  $nombrepdf= 'temp/'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  $nombrepdf1= imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
			  
						$sSQL="update tb_celebridades set cel_foto='$nombrepdf1' where cel_jue_id=$fldjuego and cel_per_id=$fldperiodo and cel_id=$uidInv";
						$db->query($sSQL);
						//echo $sSQL;
						move_uploaded_file($TMPFILES[$key] , $nombrepdf);
						$nombrepdfThumb= 'temp/thumb_'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
				
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
  $sSQL="select * from tb_celebridades where cel_jue_id=$fldjuego and  cel_per_id=$fldperiodo";
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
<a href="adicionar" class="lnkAddfam" onClick="return addCel();">Adicionar proyecto</a><br><br>

</td>
</tr>
</table>

   <!--BeginFormvaloresRecord-->
  <form method="POST" enctype="multipart/form-data" action="celebridades.php" name="valoresRecord">
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
     <td class="ClearFieldCaptionTD" width="150" >Proyecto
     </td>
      <!--BeginEtiquetas-->
     <td class="ClearFieldCaptionTD"  width="124">Costo&nbsp;
     </td>
     <!--<td width="134" class="ClearFieldCaptionTD">Beneficio  %&nbsp; </td>-->
     <td width="163" class="ClearFieldCaptionTD">Fecha de inicio</td>
     <td width="163" class="ClearFieldCaptionTD">Hora de inicio</td>
     
     <td class="ClearFieldCaptionTD" width="94">Tiempo duraci&oacute;n&nbsp;
     </td>
     <td width="193" class="ClearFieldCaptionTD">Fotograf&iacute;a&nbsp;
     (jpg)</td>
     <td  width="50" class="ClearFieldCaptionTD"></td>
     <!--EndEtiquetas-->  
     </tr>
     <tr>  
	<!--BeginProductos-->
    <td colspan="8">
     <table id="dataInvestigacion" class="ClearFormTABLE">
     <?php
	 $l=1;
     while($db->next_record())
	 {
	?>
     <tr>
      <td width="150" class="ClearFieldCaptionTD">
      <select name="investigacion[]"  id="investigacion<?=$l?>">
         <option value="">Seleccionar</option>
      <?php
		  foreach($arrayProyecto as $key=>$value)
		  {
			  if($key==$db->f("cel_nombre")) $selValue="Selected"; else $selValue="";
		  ?>
		  <option value="<?=$key?>" <?=$selValue?>><?=$value?></option>
		  <?
		  }
	  ?>
      </select>
     <!-- <input name="investigacion[]" onClick="clean(this);" id="investigacion<?=$l?>" type="text" size="20" value="<?=$db->f("cel_nombre")?>">--></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("cel_precio")?>">
      </td>
      <!--<td width="140" class="ClearDataTD"  ><input name="costo_exclusividad[]" id="costo_exclusividad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("cel_beneficio")?>">
      </td>-->

        <td width="163" class="ClearDataTD"><input name="fechaI[]" id="fechaI<?=$l?>" type="text" size="8" value="<?=changeFormatDate(substr($db->f("cel_fecha"),0,10),2)?>" readonly><a id="calendar<?=$l?>" href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI<?=$l?>);return false;" ><img border="0" src="calendario/icon_calendar.gif">				</a></td>
      <td width="163" class="ClearDataTD"><input name="horaI[]" id="horaI<?=$l?>"  type="text" size="8" value="<?=substr($db->f("cel_fecha"),11)?>"></td>
     
      <td width="94" class="ClearDataTD"  ><input name="cantidad[]" id="cantidad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("cel_tiempo")?>">
      </td>
      <td width="193" class="ClearDataTD">
      <?php if(file_exists($nombrepdfThumb= 'temp/thumb_'.$db->f("cel_foto"))) 
	  {
	  ?>
      <img border="0" src="<?='temp/thumb_'.$db->f("cel_foto")?>">
      <?php
	  }
	  ?>
      <input name="pdf[]" id="pdf<?=$l?>" class="multi-pt" type="file" size="15"><span id="pdflabel<?=$k?>"><?=$db->f("cel_foto")?></span>
      <input name="idInv[]" id="idInv<?=$l?>" type="hidden" value="<?=$db->f("cel_id")?>" >
      </td>
      <td width="50" class="ClearDataTD">
       <img src="./lib/delete_es.gif" alt="Eliminar" style="cursor:pointer" title="Eliminar" border="0" onClick="eliminarFila(<?=$db->f("cel_id")?>);"> 
      </td>
     </tr>
	<?php
	$l++;
    }
	?>
	</table>
    <table id="dataInvestigacion0" class="ClearFormTABLE">
     <tr>
      <td width="150" class="ClearFieldCaptionTD">
      <select name="investigacion[]"  id="investigacion0">
	      <option value="" selected>Seleccionar</option>
      <?php
		  foreach($arrayProyecto as $key=>$value)
		  {
		  ?>
		  <option value="<?=$key?>"><?=$value?></option>
		  <?
		  }
	  ?>
      </select>
      <!--<input name="investigacion[]" onClick="clean(this);" id="investigacion0" type="text" size="20" value="">--></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo0" onClick="clean(this);" type="text" size="3" value="">
      </td>
      <!--<td width="140" class="ClearDataTD"  ><input name="costo_exclusividad[]" id="costo_exclusividad0" onClick="clean(this);" type="text" size="3" value="">
      </td>-->
      
        <td width="163" class="ClearDataTD"><input name="fechaI[]" id="fechaI0" type="text" size="8" value="" readonly><a id="calendar0" href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI0);return false;" ><img border="0" src="calendario/icon_calendar.gif">				</a></td>
      <td width="163" class="ClearDataTD"><input name="horaI[]" id="horaI0"  type="text" size="8" value=""></td>
    
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
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="calendario/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>

</body>
</html>
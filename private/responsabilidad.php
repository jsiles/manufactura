<?php
include ("./common2.php");
include ("./thumb.php");
session_start();
//check_security(1);
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
	$db->query("delete from tb_responsabilidad where res_id=$sDeleteValue");
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
  $costo_exclusividad0 = get_param("costo_exclusividad0");
  $costo_exclusividad1 = get_param("costo_exclusividad1");
  $costo_exclusividad2 = get_param("costo_exclusividad2");
  $costo_exclusividad3 = get_param("costo_exclusividad3");
  $costo_exclusividad4 = get_param("costo_exclusividad4");
  $costo_exclusividad5 = get_param("costo_exclusividad5");
  $costo_exclusividad6 = get_param("costo_exclusividad6");
  $usuarioNota = get_param("usuarioNota");
  $notaSimulacion = get_param("notaSimulacion");
  $cantidad = get_param("cantidad");
  $investigacion = get_param("investigacion");   
  $idInv = get_param("idInv");
  $fechaI = get_param("fechaI");   
  $horaI = get_param("horaI");   

  $fldindirecto1 = get_param("indirecto1");
  $fldindirecto2 = get_param("indirecto2");
  $fldindirecto3 = get_param("indirecto3");
  $fldindirecto4 = get_param("indirecto4");
  $fldbeneficiodirecto = get_param("porcentajeDirecto");
  $TMPFILES = $_FILES["pdf"]["tmp_name"];
  $FILES = $_FILES["pdf"]["name"];

/*
* NOTAS SIMULACION Y RANKNG SIMULACION
*
*/
 $empresa=db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$fldjuego order by usu_id asc");

 if (is_array($usuarioNota))
 {

  $cantNotasValues = get_db_value("select count(*) from tb_responsabilidadnotas where ren_jue_id=$fldjuego and ren_per_id=$fldperiodo");
  if($cantNotasValues > 0 ) 
  $db->query("delete from tb_responsabilidadnotas where ren_jue_id=$fldjuego and ren_per_id=$fldperiodo");
  $x=0;
  foreach($empresa as $key => $value)  
  {
  	$db->query("insert into tb_responsabilidadnotas values(null, $fldjuego, $fldperiodo, $key, $usuarioNota[$x])"); 
	$x++;
  }
 }

 if (is_array($notaSimulacion))
 {
 $cantNotasValues = get_db_value("select count(*) from tb_responsabilidadranking where rer_jue_id=$fldjuego and rer_per_id=$fldperiodo");
  if($cantNotasValues > 0 ) 
  $db->query("delete from tb_responsabilidadranking where rer_jue_id=$fldjuego and rer_per_id=$fldperiodo");
  $x=1;
  foreach($notaSimulacion as $value)  
  {
  	$db->query("insert into tb_responsabilidadranking values(null, $fldjuego, $fldperiodo, $x, $value)"); 
	$x++;
  }
 }
 /*
 * FIN NOTA Y RANKING SIMULACION
 */
 $cantidadDat0 = get_db_value("select count(*) from tb_responsabilidadgeneral where reg_jue_id=$fldjuego and reg_per_id=$fldperiodo");
 if(!(int)$fldindirecto1) $fldindirecto1 =0;
 if(!(int)$fldindirecto2) $fldindirecto2 =0;
 if(!(int)$fldindirecto3) $fldindirecto3 =0;
 if(!(int)$fldindirecto4) $fldindirecto4 =0;
 if($cantidadDat0==0)
 {	
				  $nombrepdf= 'temp/RS'. $fldjuego."-".$fldperiodo.'.pdf';
				  $nombrepdf1= 'RS'. $fldjuego."-".$fldperiodo.'.pdf';
				  
				  	$sSQL="insert into tb_responsabilidadgeneral values (null, $fldjuego, $fldperiodo, '$nombrepdf1', $fldindirecto1, $fldindirecto2, $fldindirecto3, $fldindirecto4, $fldbeneficiodirecto)";
							$db->query($sSQL);
												
					
 }else{
	 
				  $nombrepdf= 'temp/RS'. $fldjuego."-".$fldperiodo.'.pdf';
				  $nombrepdf1= 'RS'. $fldjuego."-".$fldperiodo.'.pdf';
				  
							$sSQL="update tb_responsabilidadgeneral set reg_pdf='$nombrepdf1', reg_beneficio1=$fldindirecto1, reg_beneficio2=$fldindirecto2, reg_beneficio3=$fldindirecto3, reg_beneficio4=$fldindirecto4 , reg_beneficioDirecto=$fldbeneficiodirecto where reg_jue_id=$fldjuego and reg_per_id=$fldperiodo ";
						
							$db->query($sSQL);
	 }
  $cantidadDat = get_db_value("select count(*) from tb_responsabilidad where res_jue_id=$fldjuego and res_per_id=$fldperiodo");
  if($cantidadDat==0)
  foreach($investigacion as $key => $value)
  {
	//if(!$value) $value=0;
	if(!$costo[$key]) $costo[$key]=0;  
	if(!$costo_exclusividad0[$key]) $costo_exclusividad0[$key]=0;  
	if(!$costo_exclusividad1[$key]) $costo_exclusividad1[$key]=0;  
	if(!$costo_exclusividad2[$key]) $costo_exclusividad2[$key]=0;  
	if(!$costo_exclusividad3[$key]) $costo_exclusividad3[$key]=0;  
	if(!$costo_exclusividad4[$key]) $costo_exclusividad4[$key]=0;  
	if(!$costo_exclusividad5[$key]) $costo_exclusividad5[$key]=0;  
	if(!$costo_exclusividad6[$key]) $costo_exclusividad6[$key]=0;  
	if(!$cantidad[$key]) $cantidad[$key]=0;  
	if(($fechaI[$key])&&($horaI[$key])&&(strlen($value)>0))
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
			$sSQL = "insert into tb_responsabilidad values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad0[$key].", ".$costo_exclusividad1[$key].", ".$costo_exclusividad2[$key].", ".$costo_exclusividad3[$key].", ".$costo_exclusividad4[$key].", ".$costo_exclusividad5[$key].", ".$costo_exclusividad6[$key].", ".$cantidad[$key].", '$fechaInicio','$fechaFin','',1) ";
			$db->query($sSQL);  
			$uidInv =get_db_value("Select last_insert_id()");
	 
		  }
	}
	else
	foreach($investigacion as $key => $value)
	  {
			if(!$costo[$key]) $costo[$key]=0;  
			if(!$costo_exclusividad0[$key]) $costo_exclusividad0[$key]=0;  
			if(!$costo_exclusividad1[$key]) $costo_exclusividad1[$key]=0;  
			if(!$costo_exclusividad2[$key]) $costo_exclusividad2[$key]=0;  
			if(!$costo_exclusividad3[$key]) $costo_exclusividad3[$key]=0;  
			if(!$costo_exclusividad4[$key]) $costo_exclusividad4[$key]=0;  
			if(!$costo_exclusividad5[$key]) $costo_exclusividad5[$key]=0;  
			if(!$costo_exclusividad6[$key]) $costo_exclusividad6[$key]=0;  
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
		 }
		  
		  if(($idInv[$key])&&(strlen($value)>0)&&($costo[$key])&&(strlen($costo_exclusividad0[$key])>0)&&(strlen($cantidad[$key])>0))
		  {
			  $sSQL = "update tb_responsabilidad set  res_nombre='$value', res_precio=".$costo[$key].", res_beneficio0=".$costo_exclusividad0[$key].", res_beneficio1=".$costo_exclusividad1[$key].", res_beneficio2=".$costo_exclusividad2[$key].", res_beneficio3=".$costo_exclusividad3[$key].", res_beneficio4=".$costo_exclusividad4[$key].", res_beneficio5=".$costo_exclusividad5[$key].", res_beneficio6=".$costo_exclusividad6[$key].", res_tiempo=".$cantidad[$key].", res_fecha='$fechaInicio', res_fechafin='$fechaFin' where res_jue_id=$fldjuego and res_per_id=$fldperiodo and res_id=".$idInv[$key];
		  
		  $sSQL1="delete from tb_inclusion where inc_res_id=".$idInv[$key];
		  $db->query($sSQL1);
		  //echo $sSQL."#2#";
		  }
		  elseif((!$idInv[$key])&&(strlen($value)>0)&&($costo[$key])&&(strlen($costo_exclusividad0[$key])>0)&&($cantidad[$key]))
		 {
		 $sSQL = "insert into tb_responsabilidad values(null, $fldjuego, $fldperiodo, '$value', ".$costo[$key].", ".$costo_exclusividad0[$key].", ".$costo_exclusividad1[$key].", ".$costo_exclusividad2[$key].", ".$costo_exclusividad3[$key].", ".$costo_exclusividad4[$key].", ".$costo_exclusividad5[$key].", ".$costo_exclusividad6[$key].", ".$cantidad[$key].", '$fechaInicio','$fechaFin','',1)";
		 }
		
		if((strlen($value)>0))
		{
			$db->query($sSQL);  
		}
			if($idInv[$key]) $uidInv = $idInv[$key];
			else $uidInv =get_db_value("Select last_insert_id()");
			
			if($FILES[$key]!='')
				{
					  $nombrepdf= 'temp/'. imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
					  $nombrepdf1= imageName($value)."-".$fldjuego."-".$fldperiodo.'.jpg';
				
				  
							$sSQL="update tb_responsabilidad set res_foto='$nombrepdf1' where res_jue_id=$fldjuego and res_per_id=$fldperiodo and res_id=$uidInv";
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
} //FIN FUNCTION



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
  $sSQL="select * from tb_responsabilidad where res_jue_id=$fldjuego and res_per_id=$fldperiodo order by res_id";
  $db->query($sSQL);
  
  
  $k=$db->num_rows();
  
  $sSQL="select * from tb_responsabilidadgeneral where reg_jue_id=$fldjuego and reg_per_id=$fldperiodo order by reg_id limit 1";
  $db1->query($sSQL);
  $db1->next_record();

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
<script type="text/javascript" src="js/responsabilidad.js" language="javascript">
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
<a href="adicionar" class="lnkAddfam" onClick="return addCel();">Adicionar responsabilidad social</a><br><br>

</td>
</tr>
</table>

   <!--BeginFormvaloresRecord-->
  <form method="POST" enctype="multipart/form-data" action="responsabilidad.php" name="valoresRecord">
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
    <!-- <td class="ClearFieldCaptionTD" width="150" >Archivo Responsabilidad (pdf)
     </td>
      <td  width="124" class="ClearDataTD" colspan="2"><input name="pdf" id="pdf" class="multi-pt" type="file" size="15"><span id="pdflabel"><?=$db1->f("reg_pdf")?></span></td>-->
     <td class="ClearFieldCaptionTD" width="150" >Beneficio Indirecto
     </td>
       <td width="300" class="ClearDataTD" colspan="3" >1<input name="indirecto1" id="indirecto1" onClick="clean(this);" type="text" size="1" value="<?=$db1->f("reg_beneficio1")?>">2<input name="indirecto2" id="indirecto2" onClick="clean(this);" type="text" size="1" value="<?=$db1->f("reg_beneficio2")?>">
       3 o m&aacute;s
         <input name="indirecto3" id="indirecto3" onClick="clean(this);" type="text" size="1" value="<?=$db1->f("reg_beneficio3")?>">
       &Uacute;ltimo lugar<input name="indirecto4" id="indirecto4" onClick="clean(this);" type="text" size="1" value="<?=$db1->f("reg_beneficio4")?>">
       </td>
      <td class="ClearFieldCaptionTD">% Imagen Beneficio Neto Directo</td>
      <td class="ClearDataTD" colspan="3"><input name="porcentajeDirecto" id="porcentajeDirecto" type="text" size="3" value="<?=$db1->f("reg_beneficioDirecto")?>">%</td>
      </tr>
      
     <tr>
     <td class="ClearFieldCaptionTD" width="150" >Responsabilidad social
     </td>
      <!--BeginEtiquetas-->
     <td class="ClearFieldCaptionTD"  width="124">Costo&nbsp;
     </td>
     <td width="300" class="ClearFieldCaptionTD">Beneficio directo en $M&nbsp; </td>
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
     <table id="dataInvestigacion" class="ClearFormTABLE">
     <?php
	 $l=1;
     while($db->next_record())
	 {
	?>
     <tr>
      <td width="150" class="ClearFieldCaptionTD"><input name="investigacion[]" onClick="clean(this);" id="investigacion<?=$l?>" type="text" size="20" value="<?=$db->f("res_nombre")?>"></td>
      <td width="124" class="ClearDataTD"><input name="costo[]" id="costo<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("res_precio")?>">
      </td>
      <td width="300" class="ClearDataTD"  ><input name="costo_exclusividad0[]" id="costo_exclusividad00" onClick="clean(this);" type="hidden" size="1" value="<?=$db->f("res_beneficio0")?>">1<input name="costo_exclusividad1[]" id="costo_exclusividad10" onClick="clean(this);" type="text" size="1" value="<?=$db->f("res_beneficio1")?>">2<input name="costo_exclusividad2[]" id="costo_exclusividad20" onClick="clean(this);" type="text" size="1" value="<?=$db->f("res_beneficio2")?>">3 o m&aacute;s<input name="costo_exclusividad3[]" id="costo_exclusividad30" onClick="clean(this);" type="text" size="1" value="<?=$db->f("res_beneficio3")?>">&Uacute;nico sin participar<input name="costo_exclusividad4[]" id="costo_exclusividad40" onClick="clean(this);" type="text" size="1" value="<?=$db->f("res_beneficio4")?>"><input name="costo_exclusividad5[]" id="costo_exclusividad50" onClick="clean(this);" type="hidden" size="1" value="<?=$db->f("res_beneficio5")?>"><input name="costo_exclusividad6[]" id="costo_exclusividad60" onClick="clean(this);" type="hidden" size="1" value="<?=$db->f("res_beneficio6")?>">
      </td>

        <td width="163" class="ClearDataTD"><input name="fechaI[]" id="fechaI<?=$l?>" type="text" size="8" value="<?=changeFormatDate(substr($db->f("res_fecha"),0,10),2)?>" readonly><a id="calendar<?=$l?>" href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI<?=$l?>);return false;" ><img border="0" src="calendario/icon_calendar.gif">				</a></td>
      <td width="163" class="ClearDataTD"><input name="horaI[]" id="horaI<?=$l?>"  type="text" size="8" value="<?=substr($db->f("res_fecha"),11)?>"></td>
     
      <td width="94" class="ClearDataTD"  ><input name="cantidad[]" id="cantidad<?=$l?>" onClick="clean(this);" type="text" size="3" value="<?=$db->f("res_tiempo")?>">
      
      </td>
      <td width="193" class="ClearDataTD">
      <?php if(file_exists($nombrepdfThumb= 'temp/thumb_'.$db->f("res_foto"))) 
	  {
	  ?>
      <img border="0" src="<?='temp/thumb_'.$db->f("res_foto")?>">
      <?php
	  }
	  ?>
      <input name="pdf[]" id="pdf<?=$l?>" class="multi-pt" type="file" size="15"><span id="pdflabel<?=$k?>"><?=$db->f("res_foto")?></span><input name="idInv[]" id="idInv<?=$l?>" type="hidden" value="<?=$db->f("res_id")?>" >
      </td>
      
     <td width="50" class="ClearDataTD">
       <img src="./lib/delete_es.gif" alt="Eliminar" style="cursor:pointer" title="Eliminar" border="0" onClick="eliminarFila(<?=$db->f("res_id")?>);"> 
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
      <td width="300" class="ClearDataTD"  ><input name="costo_exclusividad0[]" id="costo_exclusividad00" onClick="clean(this);" type="hidden" size="1" value="0">1<input name="costo_exclusividad1[]" id="costo_exclusividad10" onClick="clean(this);" type="text" size="1" value="">2<input name="costo_exclusividad2[]" id="costo_exclusividad20" onClick="clean(this);" type="text" size="1" value="">3 o m&aacute;s<input name="costo_exclusividad3[]" id="costo_exclusividad30" onClick="clean(this);" type="text" size="1" value="">&Uacute;nico sin participar<input name="costo_exclusividad4[]" id="costo_exclusividad40" onClick="clean(this);" type="text" size="1" value="">
        <input name="costo_exclusividad5[]" id="costo_exclusividad50" onClick="clean(this);" type="hidden" size="1" value="0"><input name="costo_exclusividad6[]" id="costo_exclusividad60" onClick="clean(this);" type="hidden" size="1" value="0">
      </td>
      
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
    <td colspan="7">
    <table id="frmDinamico" class="ClearFormTABLE">
    </table>
    </td>
    </tr>
    
    <tr>
        <td class="ClearFieldCaptionTD">Grupo</td>
        <td class="ClearFieldCaptionTD">Notas Trabajos %</td>
        <td class="ClearFieldCaptionTD">&nbsp;</td>
        <td class="ClearFieldCaptionTD">Ranking</td>
        <td class="ClearFieldCaptionTD">Nota simulaci&oacute;n ($M)</td>
        <td colspan="2" class="ClearFieldCaptionTD">&nbsp;</td>
    </tr>
    
	<?php
	$usuarioNota = db_fill_array("select ren_usu_id, ren_nota from tb_responsabilidadnotas where ren_jue_id=$fldvai_jue_id and ren_per_id=$fldperiodo order by ren_usu_id asc");
	$notaSimulacion= db_fill_array("select rer_posicion, rer_nota from tb_responsabilidadranking where rer_jue_id=$fldvai_jue_id and rer_per_id=$fldperiodo order by rer_posicion asc");
	$empresa=db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$fldvai_jue_id order by usu_id asc");
	$x=1;
	foreach($empresa as $key=>$value)
	{
		
	?>
    <tr>
    <td class="ClearFieldCaptionTD"><?=$value?></td><td class="ClearFieldCaptionTD"><input type="text" name="usuarioNota[]" value="<?=$usuarioNota[$key]?>" size="3" /></td><td class="ClearFieldCaptionTD"></td><td class="ClearFieldCaptionTD"><?=$x?></td><td class="ClearFieldCaptionTD"><input type="text" name="notaSimulacion[]" value="<?=$notaSimulacion[$x]?>" size="3" /></td><td colspan="2" class="ClearFieldCaptionTD"></td>
    </tr>
	<?php
	$x++;
	}
	?>
    
    
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
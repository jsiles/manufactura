<?php
include('common.php');
session_start();
check_security(1);
$dat_juego = get_param("id");
$per_periodo= get_param("per_periodo");
$userId= get_session("cliID");

$periodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'");
$dateGame = get_db_value("select per_datetime from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A'");
$difDate = time_diff($dateGame , date("Y-m-d H:i:s"));
if(!in_array($per_periodo,$periodo))
		$per_periodo = get_db_value("select per_periodo from tb_periodos where per_jue_id=$dat_juego and per_inv_estado='A' limit 1");
		
 $sSQL="select * from tb_responsabilidadgeneral where reg_jue_id=$dat_juego and reg_per_id=$per_periodo order by reg_id limit 1";
 // echo $sSQL;
  $db1->query($sSQL);
  $db1->next_record();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="CREATOR" content="Jsiles">
<meta http-equiv="content-type" content="text/html; charset=windows-1252">
<title>Investigaciones de mercado</title>
<meta content="JSILES" name="GENERATOR">
<link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script src="js/jquery.countdown.js" type="text/javascript"></script>
<script src="js/jquery.countdown-es.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function validar(e)
{
		var message="Para proceder a incluirse en el programa de responsabilidad social, la misma que debe ser confirmada. Está seguro de participar en el programa de responsabilidad social?";
			if(confirm(message)) {
				
					$.ajax({
					   type: "POST",
					   url: "valInclusion.php",
					   data: "id="+e,
					   success: function(html){
						// $("#boton"+e).attr('disabled','disabled');
						document.getElementById('boton'+e).disabled=true
						 $("#win"+e).html("<td colspan='2' class='title2'>Se incluyó en el programa de Responsabilidad Social, gracias por participar!.</td>");
						 $("#win"+e).show();	
						}
					 });
					 
				}else return false;
}

function listParticipantes(e)
{
		$.ajax({
				 type: "POST",
				 url: "listParticipantes.php",
				 data: "id="+e,
				 success: function(html){
					    $("#win"+e).html(html);
				  	    $("#win"+e).show();	
				        }
				});
}
function listGanadores(e,f)
{
		$.ajax({
				 type: "POST",
				 url: "listGanadores.php",
				 data: "jue_id="+e + "&per_id="+f,
				 success: function(html){
					    $("#ranking").html(html);
				  	    $("#ranking").show();	
				        }
				});
}
</script>
</head>
<body class="ClearPageBODY" text="#000000" vlink="#000099" alink="#ff0000" link="#000099" bgcolor="#ffffff">
<table width="100%" border="0">
<tr>
<td width="50%" align="right">Resumen<a href="RS2.php?jue_id=<?=$dat_juego?>&per_id=<?=$per_periodo?>" title="Compra resumen"><img src="./image/excel.jpg" alt="Reporte resumen" border="0"></a>&nbsp;</td>
    <td width="10%" align="center">
       
    </td>
</tr>
</table>
<form name="responsabilidadsocial" action="responsabilidad.php" method="get">
<table width="70%" class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
<tr><td class="title3" align="center" colspan="2">Responsabilidad Social</td></tr>

<tr> 
    <td width="50%">Gestión:
      <select name="per_periodo" onChange="submit();">
<?php
        if(is_array($periodo))
                {
                  reset($periodo);

                  while(list($key, $value) = each($periodo))
                  {
                    //$tpl->set_var("ID", $key);
                    //$tpl->set_var("Value", $value);
?>
      <option value="<?=$key?>" <?php echo (($key==$per_periodo)?"selected":"");?>><?=$value?></option>

<?php
                 }
                }
               
?>
      <!--BeginPeriodo-->
      <!--EndPeriodo-->
      </select></td><td>&nbsp;</td>
</tr>      

<?php
$dateAhora = date("Y-m-d H:i:s");
$sSQL= "select * from tb_responsabilidad where res_jue_id=$dat_juego and res_per_id=$per_periodo and res_sw=1";// and cel_fechafin > '$dateAhora' ";// between cel_fecha and cel_fechafin ";
$db->query($sSQL);
//echo $sSQL;
$next_record=$db->next_record();
$scripDin="";
$scripDin0="";
$scripDin1="";
	if($next_record)
	{
		?>
        <tr>
<td class="title2">Beneficios indirectos:<br />(Ranking final de IMAGEN RS)<br /><span id="ranking" class="title"><?=file_get_contents("http://".$_SERVER['HTTP_HOST']."/manufactura/listGanadores.php?jue_id=$dat_juego&per_id=$per_periodo")?></span></td>
<td class="title">Primer lugar:<?=$db1->f("reg_beneficio1");?><br />Segundo lugar:<?=$db1->f("reg_beneficio2");?><br />Tercer lugar:<?=$db1->f("reg_beneficio3");?><br />&Uacute;ltimo lugar:<?=$db1->f("reg_beneficio4");?><br /></td>
</tr><?php
		
		while($next_record)
		{
			$id = $db->f("res_id");
			$dateInicio = $db->f("res_fecha");
			$dateFin = $db->f("res_fechafin");
			$precioBase = $db->f("res_precio");
			$precioFlag = false;
			/*$valPrecioBase = get_db_value("select count(*) from tb_ofertas where ofe_ven_id=$id");
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_ofertas where puj_cel_id=$id");
				if ($precioBase<$maxPrecioPuja) {$precioBase = $maxPrecioPuja; $precioFlag=true;}
			}*/
			$timeInicio = time_diff($dateInicio,$dateAhora);
			$timeFin = time_diff($dateFin,$dateAhora);
			//echo $timeInicio;
			if($timeInicio>0)
			{
				$scripDin0 .= "var austDay$id = new Date();
			austDay$id = new Date(austDay$id.getFullYear() ,austDay$id.getMonth() ,austDay$id.getDate(), austDay$id.getHours(), austDay$id.getMinutes(),austDay$id.getSeconds()+$timeInicio);
	$('#defaultDown$id').countdown({until: austDay$id,format: 'HMS',onExpiry: subastaBegin$id});";
				$scripDin1 .= "
				function subastaBegin$id()
				{
					$(\"#leftTime$id\").show();
					$(\"#pastTime$id\").hide();
					//$(\"#boton$id\").attr('disabled','');
					document.getElementById('boton$id').disabled=false;					
				}
				";
			}
			$scripDin .= "var austDay$id = new Date();
			austDay$id = new Date(austDay$id.getFullYear() ,austDay$id.getMonth() ,austDay$id.getDate(), austDay$id.getHours(), austDay$id.getMinutes(),austDay$id.getSeconds()+$timeFin);
	$('#defaultCountdown$id').countdown({until: austDay$id,format: 'HMS',onExpiry: subastandose$id});";
			$scripDin2 .= "
				function subastandose$id()
				{
					//$(\"#boton$id\").attr('disabled','disabled');
					document.getElementById('boton$id').disabled=true;
					$(\"#win$id\").show();
					$(\"#win$id\").html(\"<td colspan='2' class='title2'>El programa de Responsabilidad Social concluyó, gracias por participar!.</td>\");
					listParticipantes($id);
					listGanadores($dat_juego, $per_periodo);
					
				}
				";
		?>
        
	<tr><td colspan="2"><hr></td></tr>
    <tr>
		<td class="title2"><?php
		if(file_exists("private/temp/".$db->f("res_foto"))&&(strlen($db->f("res_foto"))>0))
		{
		?> <img border="0" src="private/temp/<?=$db->f("res_foto")?>">
        <?php
		}
		?>&nbsp;<td width="50%" class="title2">Programa:&nbsp;<span class="title"><?=$db->f("res_nombre")?></span></td>
	</tr>
   	<tr>
		<td class="title2">Costo:&nbsp;</td><td class="title" align="left"><?=$db->f("res_precio")?></td>
	</tr>
    <tr>
    	<td class="title2">Beneficio directo:</td><td class="title" align="left">Primer lugar:<?=$db->f("res_beneficio1");?><br>Segundo lugar:<?=$db->f("res_beneficio2");?><br>Tercer lugar:<?=$db->f("res_beneficio3");?><br>
&Uacute;nico sin participar:<?=$db->f("res_beneficio4");?></td>
    </tr>
    <?php
	$valPrecioBaseUser = get_db_value("select count(*) from tb_inclusion where inc_res_id=$id and inc_usu_id =$userId ");
	if($valPrecioBaseUser>0) {$displayStatus ="disabled"; $mensajeHTML ="<td colspan='2' class='title2'>Se incluyó en el programa de Responsabilidad Social, gracias por participar!.</td>"; $displayBtn="";} else {$displayStatus ="";$mensajeHTML ="<td colspan='2' class='title2'></td>"; $displayBtn="none";}///aca mensaje
	if($timeFin<=0)
		{
			//echo "#";
			$valPrecioBase = get_db_value("select count(*) from tb_inclusion where inc_res_id=$id");
			if($valPrecioBase==0) {
				$etiqueta="<td colspan=\"2\" class=\"title2\">Responsabilidad desierta</td>";
				$includeFile='';
				}
				else
				{
				$etiqueta="<td colspan=\"2\" class=\"title2\">El programa de Responsabilidad Social concluyó, gracias por participar!. </td>";
				$displayStatus ="disabled";
				
				//$includeFile = file_get_contents("http://".$_SERVER['HTTP_HOST']."/inclusiontbl.php?id=$id");		
				}
				
		?>
		<tr>
		  <?=$etiqueta?>
		</tr>
        <tr>
        <td id="listOfert<?=$id?>">
        <!--<?=$includeFile?>--></td>
        </tr>
		<?php
		}
		?>
       
    	<?php
		if(($timeInicio>0)&&($timeFin>0))
		{
			$displayStatus ="disabled";
			?>
		<tr id="pastTime<?=$id?>">
		  <td height="30px" class="title2">Tiempo inicio:</td><td><div id="defaultDown<?=$id?>" class="defCountDown"></div></td>
		</tr>
         <tr id="leftTime<?=$id?>" style="display:none;">
		  <td height="30px" class="title2">Tiempo restante:</td><td><div id="defaultCountdown<?=$id?>" class="defCountDown"></div></td>
		</tr>
        
        <tr id="win<?=$id?>" style="display:none">
        </tr>
        <tr>
        <td id="listOfert<?=$id?>"></td>
        </tr>
        <tr id="bid<?=$id?>" style="display:;">
		<td class="title2">&nbsp;</td>
		<td class="title"><span class="title2"><input <?=$displayStatus?> type="button" class="compra" value="Inclusión" id="boton<?=$id?>" onClick="javascript:validar(<?=$id?>);"></td>
		</tr>
        
		</tr>
		<?php
		}elseif($timeFin>0)
		{
		?>
		 <tr id="leftTime<?=$id?>">
		  <td height="30px" class="title2">Tiempo restante:</td><td><div id="defaultCountdown<?=$id?>" class="defCountDown"></div></td>
		</tr>
        
        <tr id="win<?=$id?>" style="display:<?=$displayBtn?>">
        <?=$mensajeHTML?>
        </tr>
        <tr>
        <td id="listOfert<?=$id?>">
       </td>
        </tr>
        <tr id="bid<?=$id?>" style="display:">
		<td class="title2">&nbsp;</td>
		<td class="title"><span class="title2"><input <?=$displayStatus?> type="button" class="compra" value="Inclusión" id="boton<?=$id?>" onClick="javascript:validar(<?=$id?>);"></td>
		</tr>
    	<?php
		}
		?>
       
        <?php
		$next_record=$db->next_record();
		}
	}
	else
	{
			?>
			<tr>
			  <td colspan="2" class="title2">No existen programas de responsabilidad social para este periodo.</td></tr>
			
			<?php
	}
	
			//echo $scripDin;
		?>
</table>                             
<p><br>
<input name="id" id="id" value="<?=$dat_juego?>" type="hidden">
</form>
&nbsp;</p>

<script type="text/javascript" language="javascript">


$(function () {
<?=$scripDin?>
<?=$scripDin0?>
/*****************************************************/
/*    FUNCIONES										 */
/*****************************************************/
	
<?=$scripDin1?>
<?=$scripDin2?>



/*****************************************************/
/*    FUNCIONES										 */
/*****************************************************/
	});

</script>
</body>
</html>
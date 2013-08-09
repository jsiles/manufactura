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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="CREATOR" content="Jsiles">
<meta http-equiv="content-type" content="text/html; charset=windows-1252">
<title>Investigaciones de mercado</title>
<meta content="Jorge Siles" name="GENERATOR">
<link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script src="js/jquery.countdown.js" type="text/javascript"></script>
<script src="js/jquery.countdown-es.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function validar(e)
{
		var valueBid = $("#valor"+e).val()*1;
		var valueCantidad = $("#cantidad"+e).val()*1;
		var valuePrecio = $("#precio"+e).val()*1;
		if((valueBid>0)&&(valuePrecio<=valueBid)) {
			var message="Para proceder con su oferta de "+valueBid+" $M, la misma debe ser confirmada. Está seguro del monto ofertado?";
			if(confirm(message)) {
				
					$.ajax({
					   type: "POST",
					   url: "valOfert.php",
					   data: "id="+e+"&valueBid="+valueBid+"&valueCantidad="+valueCantidad,
					   success: function(html){
						 //$("#bid"+e).hide();
						 //$("#boton"+e).attr("disabled", "disabled");
						 $("#win"+e).show();	
						 $("#win"+e).html('<td colspan="2" class="title2">Su oferta es de '+valueBid+' $M </td>');
						 $("#win"+e).show();
						 //alert(html);
					   }
					 });
					 
				}else return false;
			} else alert("El monto ofertado, es un valor menor al Precio mínimo");
}
</script>
</head>
<body class="ClearPageBODY" text="#000000" vlink="#000099" alink="#ff0000" link="#000099" bgcolor="#ffffff">
<table width="100%" border="0">
<tr>
<td width="50%" align="right">Resumen<a href="ofertas.php?jue_id=<?=$dat_juego?>&per_id=<?=$per_periodo?>" title="Venta online resumen"><img src="./image/excel.jpg" alt="Venta online resumen" border="0"></a>&nbsp;</td>
    <td width="10%" align="center">
       
    </td>
</tr>
</table>
<form name="ventasonline" action="ventasonline.php" method="get">
<table width="70%" class="ClearFormTABLE" cellspacing="1" cellpadding="3" border="0">
<tr> 
    <td width="20%">Gestión:
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
      </select>
</tr>      
<tr>
<td height="30px">&nbsp;
</td>
</tr>
<?php
$dateAhora = date("Y-m-d H:i:s");
$sSQL= "select * from tb_ventas where ven_jue_id=$dat_juego and ven_per_id=$per_periodo and ven_sw=1";// and cel_fechafin > '$dateAhora' ";// between cel_fecha and cel_fechafin ";
$db->query($sSQL);
//echo $sSQL;
$next_record=$db->next_record();
$scripDin="";
$scripDin0="";
$scripDin1="";
	if($next_record)
		while($next_record)
		{
			$id = $db->f("ven_id");
			$dateInicio = $db->f("ven_fecha");
			$dateFin = $db->f("ven_fechafin");
			$precioBase = $db->f("ven_precio");
			$precioFlag = false;
			/*$valPrecioBase = get_db_value("select count(*) from tb_ofertas where ofe_ven_id=$id");
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_ofertas where puj_cel_id=$id");
				if ($precioBase<$maxPrecioPuja) {$precioBase = $maxPrecioPuja; $precioFlag=true;}
			}*/
			$timeInicio = time_diff($dateInicio,$dateAhora);
			$timeFin = time_diff($dateFin,$dateAhora);
			//echo $dateInicio."--".$dateAhora."--Dif=".$timeInicio;
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
					/*$(\"#bid$id\").show();*/
					$(\"#boton$id\").attr(\"disabled\", \"\");
				}
				";
			}
			$scripDin .= "var austDay$id = new Date();
			austDay$id = new Date(austDay$id.getFullYear() ,austDay$id.getMonth() ,austDay$id.getDate(), austDay$id.getHours(), austDay$id.getMinutes(),austDay$id.getSeconds()+$timeFin);
	$('#defaultCountdown$id').countdown({until: austDay$id,format: 'HMS',onExpiry: subastandose$id});";
			$scripDin2 .= "
				function subastandose$id()
				{
					/*$(\"#bid$id\").hide();*/
					$(\"#boton$id\").attr(\"disabled\", \"disabled\");
					$(\"#win$id\").html(\"<td colspan='2' class='title2'>La subasta fue concluida, gracias por participar!. \");
					$.ajax({
					   type: \"POST\",
					   url: \"ofertastbl.php\",
					   data: \"id=$id\",
					   success: function(result){
						 $(\"#listOfert$id\").html(result);
					   }
					 });
					
				}
				";
				//<a href='ofertas.php?id=$id' target='_blank'>Listado de Ofertas <img src='./image/excel.jpg' border=0></a></td>
		?>
        
	<tr><td colspan="2"><hr></td></tr>
		<tr>
		<td class="title2"><?php
		if(file_exists("private/temp/".$db->f("ven_foto"))&&(strlen($db->f("ven_foto"))>0))
		{
		?> <img border="0" src="private/temp/<?=$db->f("ven_foto")?>">
        <?php
		}
		?>&nbsp;</td><td class="title" align="left"><?=$db->f("ven_nombre")?></td>
	</tr>
    
    <?php
	$valPrecioBaseUser = get_db_value("select count(*) from tb_ofertas where ofe_ven_id=$id and ofe_usu_id =$userId ");
	if($valPrecioBaseUser>0) { $displayStatus =""; $enabledInput = "";//"disabled";
	$cantidadOfert = get_db_value("select ofe_cantidad from tb_ofertas where ofe_ven_id=$id and ofe_usu_id =$userId limit 1");
	$precioOfert = get_db_value("select ofe_monto from tb_ofertas where ofe_ven_id=$id and ofe_usu_id =$userId limit 1");
	$unidadOfert = get_db_value("select ven_unidad from tb_ventas where ven_id=$id");
	
	$winOfert ='<td colspan="2" class="title2">Su oferta es de '.$precioOfert.' $M</td>';}
	if($timeFin<=0)
		{
			//echo "#";
			$valPrecioBase = get_db_value("select count(*) from tb_ofertas where ofe_ven_id=$id");
			if($valPrecioBase==0) {
				$etiqueta="<td colspan=\"2\" class=\"title2\">Licitación de proyecto desierto</td>";
				$includeFile='';
				}
				else
				{
				$etiqueta="<td colspan=\"2\" class=\"title2\">La subasta fue concluida, gracias por participar!.";// <a href='ofertas.php?id=$id' target='_blank'>Listado de Ofertas <img src='./image/excel.jpg' border=0></a></td>
				$displayStatus ="";
				
				$includeFile = file_get_contents("http://".$_SERVER['HTTP_HOST']."/calidad/ofertastbl.php?id=$id&user_id=$userId");		
				//.$_SERVER['REQUEST_URI']
				}
				
		?>
		<tr>
		  <?=$etiqueta?>
		</tr>
        
        <tr>
        <td colspan="2" id="listOfert<?=$id?>">
        <?=$includeFile?></td>
        </tr>
		<?php
		}
		?>
       
    	<?php
		if(($timeInicio>0)&&($timeFin>0))
		{
			?>
		<tr id="pastTime<?=$id?>">
		  <td height="30px" class="title2">Tiempo inicio:</td><td><div id="defaultDown<?=$id?>" class="defCountDown"></div></td>
		</tr>
         <tr id="leftTime<?=$id?>" style="display:none;">
		  <td height="30px" class="title2">Tiempo restante:</td><td><div id="defaultCountdown<?=$id?>" class="defCountDown"></div></td>
		</tr>
        
        <tr id="bid<?=$id?>" style="display:;">
		<td class="title2">&nbsp;</td>
		<td class="title">
	    <span class="title2">Precio M&iacute;nimo ($M/<?=$db->f("ven_precio")?>):</span>
	    <input name="valor<?=$id?>" id="valor<?=$id?>" value="" size="11"><br />
	    <span class="title2">Cantidad Expertos:<span id="unidad<?=$id?>"><?=$db->f("ven_cantidad")?></span></span>
		  <input type="hidden" name="cantidad<?=$id?>" id="cantidad<?=$id?>" value="<?=$db->f("ven_cantidad")?>" size="11">
          <input type="hidden" name="precio<?=$id?>" id="precio<?=$id?>" value="<?=$db->f("ven_precio")?>" size="11">
          &nbsp;<input type="button" class="compra" value="Ofertar" id="boton<?=$id?>" disabled onClick="javascript:validar(<?=$id?>);"></td>
		</tr>
        <tr id="win<?=$id?>" style="display:"> 
        </tr>
        <tr>
        <td colspan="2" id="listOfert<?=$id?>"></td>
        </tr> 
		</tr>
		<?php
		}elseif($timeFin>0)
		{
		?>
		 <tr id="leftTime<?=$id?>">
		  <td height="30px" class="title2">Tiempo restante:</td><td><div id="defaultCountdown<?=$id?>" class="defCountDown"></div></td>
		</tr>
        
       
        <tr id="bid<?=$id?>" style="display:<?=$displayStatus?>" >
		<td class="title2">&nbsp;</td>
		<td class="title">
	    <span class="title2">Precio M&iacute;nimo ($M/<?=$db->f("ven_precio")?>):</span>
	    <input name="valor<?=$id?>" id="valor<?=$id?>" value="" size="11"><br />
        <span class="title2">Cantidad Expertos: <span id="unidad<?=$id?>"><?=$db->f("ven_cantidad")?></span></span>
		  <input type="hidden" name="cantidad<?=$id?>" id="cantidad<?=$id?>" value="<?=$db->f("ven_cantidad")?>" size="11">
          <input type="hidden" name="precio<?=$id?>" id="precio<?=$id?>" value="<?=$db->f("ven_precio")?>" size="11">
          &nbsp;<input type="button" class="compra" value="Ofertar" id="boton<?=$id?>" onClick="javascript:validar(<?=$id?>);" <?=$enabledInput?>></td>
		</tr>
    	
        <tr id="win<?=$id?>" style="display:"><?=$winOfert?>
        </tr>
         <tr>
        <td colspan="2" id="listOfert<?=$id?>">
       </td>
        </tr>
		<?php
		}
		?>
       
        <?php
		$next_record=$db->next_record();
		}
		else
		{
			?>
			<tr>
			  <td colspan="2" class="title2">No existen proyectos para esta gesti&oacute;n.</td>
			</tr>
			
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
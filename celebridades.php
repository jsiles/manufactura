<?php
include('common.php');
session_start();
check_security(1);
$dat_juego = get_param("id");
$per_periodo= get_param("per_periodo");
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
<meta content="CodeCharge Studio 3.0.1.6" name="GENERATOR">
<link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
<script>
function countdown_clock(year, month, day, hour, minute, format)
         {
         //I chose a div as the container for the timer, but
         //it can be an input tag inside a form, or anything
         //who's displayed content can be changed through
         //client-side scripting.
         html_code = '<div id="countdown"></div>';

         document.write(html_code);

         Today = new Date();
         Todays_Year = Today.getFullYear();
         Todays_Month = Today.getMonth();

         <?php
         $date = getDate();

         $second = $date["seconds"];
         $minute = $date["minutes"];
         $hour = $date["hours"];
         $day = $date["mday"];
         $month = $date["mon"];
         $month_name = $date["month"];
         $year = $date["year"];
         ?>

         //Computes the time difference between the client computer and the server.
         Server_Date = (new Date(<?= $year ?>, <?= $month ?>, <?= $day ?>,
                                 <?= $hour ?>, <?= $minute ?>, <?= $second ?>)).getTime();
         Todays_Date = (new Date(Todays_Year, Todays_Month, Today.getDate(),
                                 Today.getHours(), Today.getMinutes(), Today.getSeconds())).getTime();

         countdown(year, month, day, hour, minute, (Todays_Date - Server_Date), format);
         }

function countdown(year, month, day, hour, minute, time_difference, format)
         {
         Today = new Date();
         Todays_Year = Today.getFullYear();
         Todays_Month = Today.getMonth();

         //Convert today's date and the target date into miliseconds.

         Todays_Date = (new Date(Todays_Year, Todays_Month, Today.getDate(),
                                 Today.getHours(), Today.getMinutes(), Today.getSeconds())).getTime();
         Target_Date = (new Date(year, month, day, hour, minute, 00)).getTime();

         //Find their difference, and convert that into seconds.
         //Taking into account the time differential between the client computer and the server.
         Time_Left = Math.round((Target_Date - Todays_Date + time_difference) / 1000);

         if(Time_Left < 0)
            Time_Left = 0;

         switch(format)
               {
               case 0:
                    //The simplest way to display the time left.
                    document.all.countdown.innerHTML = Time_Left + ' seconds';
                    break;
               case 1:
                    //More datailed.
                    days = Math.floor(Time_Left / (60 * 60 * 24));
                    Time_Left %= (60 * 60 * 24);
                    hours = Math.floor(Time_Left / (60 * 60));
                    Time_Left %= (60 * 60);
                    minutes = Math.floor(Time_Left / 60);
                    Time_Left %= 60;
                    seconds = Time_Left;

                    dps = 's'; hps = 's'; mps = 's'; sps = 's';
                    //ps is short for plural suffix.
                    if(days == 1) dps ='';
                    if(hours == 1) hps ='';
                    if(minutes == 1) mps ='';
                    if(seconds == 1) sps ='';

                    document.all.countdown.innerHTML = days + ' day' + dps + ' ';
                    document.all.countdown.innerHTML += hours + ' hour' + hps + ' ';
                    document.all.countdown.innerHTML += minutes + ' minute' + mps + ' and ';
                    document.all.countdown.innerHTML += seconds + ' second' + sps;
                    break;
               default:
                    document.all.countdown.innerHTML = Time_Left + ' seconds';
               }

         //Recursive call, keeps the clock ticking.
         setTimeout('countdown(' + year + ',' + month + ',' + day + ',' + hour + ',' + minute + ',' +
                     time_difference + ', ' + format + ');', 1000);
         }
</script>
<!--HORA2:
<script type="text/javascript">countdown_clock(2011, 02, 15, 23, 30, 1);</script>-->

<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script src="js/jquery.countdown.js" type="text/javascript"></script>
<script src="js/jquery.countdown-es.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function validar(e)
{
		var valueBid = $("#valor"+e).val()*1;
		var valueBase = $("#price"+e).html()*1;
		if(valueBid>valueBase) {
			var message="Para proceder con su oferta de "+valueBid+" M$, la misma que debe ser confirmada. Está seguro del monto ofertado?";
			if(confirm(message)) {
				
					$.ajax({
					   type: "POST",
					   url: "valBids.php",
					   data: "id="+e+"&valueBid="+valueBid,
					   success: function(valBids){
						 $("#price"+e).html(valBids);
						 $("#valor"+e).val("");
						 
					   }
					 });
					 
				}else return false;
			} else alert("El monto ofertado, es menor al precio base");
}
function ganador(e){
			$.ajax({
					   type: "POST",
					   url: "valBids2.php",
					   data: "id="+e+"&",
					   success: function(valBids){
						 $("#win"+e).html(valBids);
					   }
					 });
return false;
}
function bidsControl()
{
	//var sw;
					$.ajax({
					   type: "POST",
					   url: "bidsControl.php",
					   data: "id=<?=$dat_juego?>&per_periodo=<?=$per_periodo?>",
					   success: function(valBids){
							if(valBids!=1)
							{   
							arrayValBids = valBids.split("|");
								for(i=0;i<arrayValBids.length-1;i=i+4)
								{
									if(i%2==0)
									{
										$("#price"+arrayValBids[i]).html(arrayValBids[i+1]);
									//	alert(arrayValBids[i]+' - '+arrayValBids[i+1]+' - '+arrayValBids[i+2]+' - '+arrayValBids[i+3]+' - '+i+'$'+arrayValBids.length);
										if(arrayValBids[i+3]==uidUser)
										$("#win"+arrayValBids[i]).html("<td class=\"title2\" colspan=\"2\">Su oferta esta ganando</td>");
										else if(arrayValBids[i+3]==0) $("#win"+arrayValBids[i]).html("<td class=\"title2\" colspan=\"2\">Realice una oferta!!</td>"); else $("#win"+arrayValBids[i]).html("<td class=\"title2\" colspan=\"2\">Su oferta fue superada</td>");
										
									}
								}
//							sw=true;
							}
							
						}
					 });
	setTimeout(function(){bidsControl();},2000);
}
</script>
</head>
<body class="ClearPageBODY" text="#000000" vlink="#000099" alink="#ff0000" link="#000099" bgcolor="#ffffff">
<form name="celebridades" action="celebridades.php" method="get">
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
$sSQL= "select * from tb_celebridades where cel_jue_id=$dat_juego and cel_per_id=$per_periodo and cel_sw=1";// and cel_fechafin > '$dateAhora' ";// between cel_fecha and cel_fechafin ";
$db->query($sSQL);
//echo $sSQL;
$next_record=$db->next_record();
$scripDin="";
$scripDin0="";
$scripDin1="";
	if($next_record)
		while($next_record)
		{
			$id = $db->f("cel_id");
			$dateInicio = $db->f("cel_fecha");
			$dateFin = $db->f("cel_fechafin");
			$precioBase = $db->f("cel_precio");
			$precioFlag = false;
			$celNombreProy = get_db_value("select pro_descripcion from py_proyectos where pro_id=".$db->f("cel_nombre")." and pro_jue_id=$dat_juego");
			$valPrecioBase = get_db_value("select count(*) from tb_pujas where puj_cel_id=$id");
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_pujas where puj_cel_id=$id");
				if ($precioBase<$maxPrecioPuja) {$precioBase = $maxPrecioPuja; $precioFlag=true;}
			}
			$timeInicio = time_diff($dateInicio,$dateAhora);
			$timeFin = time_diff($dateFin,$dateAhora);
			//echo "hora:".date("d-m-Y h:i:s");
			//echo "javascript:";
			//echo "Inicio:".$timeInicio." Fin:".$timeFin;
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
					$(\"#bid$id\").show();
				}
				";
			}
			$scripDin .= "var austDay$id = new Date();
			austDay$id = new Date(austDay$id.getFullYear() ,austDay$id.getMonth() ,austDay$id.getDate(), austDay$id.getHours(), austDay$id.getMinutes(),austDay$id.getSeconds()+$timeFin);
	$('#defaultCountdown$id').countdown({until: austDay$id,format: 'HMS',onExpiry: subastandose$id});";
			$scripDin2 .= "
				function subastandose$id()
				{
					$(\"#bid$id\").hide();
					$.ajax({
					   type: \"POST\",
					   url: \"bidsControl.php\",
					   data: \"id=$dat_juego&per_periodo=$per_periodo\",
					   success: function(valBids){
						arrayValBids = valBids.split(\"|\");
							for(i=0;i<arrayValBids.length-1;i=i+4)
							{
								if(i%2==0)
								{
									/*if($id==arrayValBids[i])
									$('#win$id').html('..'+ arrayValBids[i+2]);
									//alert(arrayValBids[i]+'-'+arrayValBids[i+2]);
									*/
								}
							}
						
						}
					 });
					$(\"#leftTime$id\").html(\"<td colspan='2' class='title2'>La subasta fue concluida, gracias por participar!</td>\");
					ganador($id);
					
				}
				";
		?>
        
	<tr><td colspan="2"><hr></td></tr>
		<tr>
		<td class="title2"><?php
		if(file_exists("private/temp/".$db->f("cel_foto"))&&(strlen($db->f("cel_foto"))>0))
		{
		?> <img border="0" src="private/temp/<?=$db->f("cel_foto")?>">
        <?php
		}
		?>&nbsp;</td><td class="title" align="left"><?=$celNombreProy?></td>
	</tr>
    
    <?php
	if($timeFin<=0)
		{
			
			$valPrecioBase = get_db_value("select count(*) from tb_pujas where puj_cel_id=$id");
			//revisar aca para el ganador nombre
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_pujas where puj_cel_id=$id");
				$uidGanador = get_db_value("select puj_usu_id from tb_pujas where puj_cel_id=$id and puj_monto=$maxPrecioPuja order by puj_id asc limit 1");
				$ganadorName = get_db_value("select usu_nombre from tb_usuarios where usu_id=$uidGanador");
				$etiqueta ="<td colspan=\"2\" class=\"title2\">Oferta ganadora: ".$ganadorName."</td>";
				if ($precioBase<$maxPrecioPuja) $precioBase = $maxPrecioPuja;
			}
			else {
				$etiqueta="<td colspan=\"2\" class=\"title2\">Subasta declarada desierta</td>";}
			//echo $etiqueta;
		?>
		 <tr>
		  <?=$etiqueta?>
		</tr>
		<?php
		}
		?>
       
     
		<tr>
		<td class="title2"> Precio <?php if($precioFlag) echo "Actual";else echo "Base"; ?>	      (M$):</td>
		<td class="title" id="price<?=$id?>"><?=$precioBase?></td>
		</tr>
		
		<tr style="display:;">
		<!--<td class="title2"> Beneficio Esperado:</td><td class="title"><?=$db->f("cel_beneficio")?>%</td>-->
		</tr>
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
		<tr id="bid<?=$id?>" style="display:none;">
		<td class="title">&nbsp;</td><td><input name="valor<?=$id?>" id="valor<?=$id?>" value="" size="11">&nbsp;<input type="button" class="compra" value="Pujar" onClick="javascript:validar(<?=$id?>);">
		</td>
		</tr>
		<?php
		}elseif($timeFin>0)
		{
		?>
		 <tr id="leftTime<?=$id?>">
		  <td height="30px" class="title2">Tiempo restante:</td><td><div id="defaultCountdown<?=$id?>" class="defCountDown"></div></td>
		</tr>
		<tr id="bid<?=$id?>" >
		<td class="title">&nbsp;</td><td><input name="valor<?=$id?>" id="valor<?=$id?>" value="" size="11" >&nbsp;<input type="button" class="compra" value="Pujar" onClick="javascript:validar(<?=$id?>);">
		</td>
		</tr>
		<?php
		}
		?>
       <tr id="win<?=$id?>"><td class="title2" colspan="2"></td>
		</tr>
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

var uidUser = <?=get_session("cliID");?>;

$(function () {
	bidsControl();
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
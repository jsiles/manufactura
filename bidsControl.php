<?php
include('common.php');
session_start();
check_security(1);
$dat_jue_id = get_param("id");
$per_periodo = get_param("per_periodo");
$arrayBids="";
$dateNow= date("Y-m-d H:i:s");
$sSQL="select * from tb_celebridades where cel_jue_id=$dat_jue_id and '$dateNow'>=cel_fecha  and '$dateNow'<=cel_fechafin and cel_per_id=$per_periodo";
//echo $sSQL;
$db->query($sSQL);
$next_record=$db->next_record();
if($next_record)
{
while($next_record)
{
	$id = $db->f("cel_id");
	$precioBase = $db->f("cel_precio");
	$valPrecioBase = get_db_value("select count(*) from tb_pujas where puj_cel_id=$id");
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_pujas where puj_cel_id=$id");
				$uidGanador = get_db_value("select puj_usu_id from tb_pujas where puj_cel_id=$id and puj_monto=$maxPrecioPuja order by puj_id asc limit 1");
				$ganadorName = get_db_value("select usu_nombre from tb_usuarios where usu_id=$uidGanador");
				$etiqueta ="<td colspan=\"2\" class=\"title2\">Mejor oferta de: ".$ganadorName."</td>";
				if ($precioBase<$maxPrecioPuja) $precioBase = $maxPrecioPuja;
			}
			else {$etiqueta="";$uidGanador=0;}
	$arrayBids .= $id."|".$precioBase."|".$etiqueta."|".$uidGanador."|";
	$next_record=$db->next_record();		
}//fin while
echo $arrayBids;
}else echo 1;
?>
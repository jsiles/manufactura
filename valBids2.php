<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$userId= get_session("cliID");
$ganadorId = get_db_value("select puj_usu_id from tb_pujas where puj_cel_id=$id and puj_monto=(select max(puj_monto) from tb_pujas where puj_cel_id=$id)");
			
			$ganador = get_db_value("select usu_nombre from tb_usuarios where usu_id=$ganadorId");
			
			echo "<td colspan=\"2\" class=\"title2\">El ganador de la subasta fue : ".$ganador."</td>";;
?>
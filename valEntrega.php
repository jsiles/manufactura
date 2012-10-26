<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$valEntrega= get_param("valEntrega");
$userId= get_session("cliID");
$db->query("update tb_ofertas set ofe_entrega=$valEntrega where ofe_id=$id and ofe_usu_id=$userId");
//echo "update tb_ofertas set ofe_entrega=$valEntrega where ofe_id=$id and ofe_usu_id=$userId";
//else $db->query("insert into tb_ofertas values(null, $id, $valueCantidad, $valueBid, $userId, now(), 1)");
?>
<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$userId= get_session("cliID");
$db->query("insert into tb_inclusion values(null, $id, $userId, now(), 1)");
echo "Se incluyo en el programa de responsabilidad social solicitado";
?>
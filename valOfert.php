<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$valueBid= get_param("valueBid");
$valueCantidad= get_param("valueCantidad");
$userId= get_session("cliID");
$cantOferta = get_db_value("select count(*) from tb_ofertas where ofe_ven_id=$id and ofe_usu_id=$userId");
if($cantOferta>0)
$db->query("update tb_ofertas set ofe_cantidad=$valueCantidad, ofe_monto=$valueBid, ofe_date_time=now() where ofe_ven_id=$id and ofe_usu_id=$userId");
else
$db->query("insert into tb_ofertas values(null, $id, $valueCantidad, $valueBid, $userId,0, now(), 1)");
?>
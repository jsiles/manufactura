<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$valueBid= get_param("valueBid");
$userId= get_session("cliID");
$db->query("insert into tb_pujas values(null, $id, $valueBid, $userId, now(), 1)");
$precioBase = get_db_value("select cel_precio from tb_celebridades where cel_id=$id");
			
			$valPrecioBase = get_db_value("select count(*) from tb_pujas where puj_cel_id=$id");
			if($valPrecioBase>0) {
				$maxPrecioPuja = get_db_value("select max(puj_monto) from tb_pujas where puj_cel_id=$id");
				if ($precioBase<$maxPrecioPuja) $precioBase = $maxPrecioPuja;
			}
echo $precioBase;
?>
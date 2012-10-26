<?php
include('common.php');
session_start();
check_security(1);
$id = get_param("id");
$usuarios = db_fill_array("select b.usu_id, b.usu_nombre from tb_inclusion a, tb_usuarios b where a.inc_res_id = $id and a.inc_usu_id = b.usu_id");
$html = "<td colspan=\"2\"><span class=\"title2\">Lista de participantes</span><br />";
foreach($usuarios as $value)
$html .= "<br /><span class=\"title\">$value</span>";
$html .= "</td>";
echo $html;
?>
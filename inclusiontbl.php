<?php
/**
 *
 *
 * @version Jorge Siles
 * @copyright 2006
 */
include ("./common.php");
//include_once "./Spreadsheet/Excel/Writer.php";
session_start();
$id = get_param("id");
if(isset($id))
{
?>
<table width="400px" class="Grid" border="1" cellpadding="0" cellspacing="0">
<tr class="Caption">
<td colspan="4">Listado de programas de Responsabilidad Social</td>
</tr>
<tr class="Caption">
<td>
Grupo
</td>
<td>
Programa RS
</td>
<td>
Costo
</td>
<td>
Beneficio directo</td>
</tr>


<?php
	$db->query("SELECT * FROM tb_responsabilidad, tb_inclusion where res_id=inc_res_id and res_id=$id order by res_id asc");
                      while($db->next_record())
					  {
					  					   $inc_usu_id = $db->f("inc_usu_id");
					   $res_precio = $db->f("res_precio");
					   $res_beneficio0 = $db->f("res_beneficio0");
					   $res_beneficio1 = $db->f("res_beneficio1");
					   $res_beneficio2 = $db->f("res_beneficio2");
					   $res_beneficio3 = $db->f("res_beneficio3");
					   $res_beneficio4 = $db->f("res_beneficio4");
					   $res_beneficio5 = $db->f("res_beneficio5");
					   $res_beneficio6 = $db->f("res_beneficio6");
					   $res_nombre = $db->f("res_nombre");
					   $nombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=$inc_usu_id");
					   $cantidadBen = get_db_value("select count(*) from tb_inclusion where inc_res_id=$id");
						switch($cantidadBen)
						{
							case 0:
							$res_beneficio=$res_beneficio0;
							break;
							case 1:
							$res_beneficio=$res_beneficio1;
							break;
							case 2:
							$res_beneficio=$res_beneficio2;
							break;
							case 3:
							$res_beneficio=$res_beneficio3;
							break;
							case 4:
							$res_beneficio=$res_beneficio4;
							break;
							case 5:
							$res_beneficio=$res_beneficio5;
							break;
							case 6:
							$res_beneficio=$res_beneficio6;
							break;
							default:
							$res_beneficio=$res_beneficio6;
							}	   

?>
<tr>
<td class="title"><?=$nombre?>
</td> 
<td class="title"><?=$res_nombre?>
</td>
<td class="title"><?=$res_precio?>
</td>
<td class="title"><?=$res_beneficio?>
</td>                    

</tr>
<?php
					  }
?>
</table>
<!--</body>
</html>-->
<?php
}
?>
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
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<table width="400px" class="Grid" border="1" cellpadding="0" cellspacing="0">
<tr class="Caption">
<td colspan="6">Listado de ofertas</td>
</tr>
<tr class="Caption">
<td>
Grupo
</td>
<td>
Precio Ofertado
</td>

</tr>


<?php
	$db->query("SELECT ofe_id, ofe_usu_id, ofe_cantidad, ofe_monto, ofe_entrega FROM tb_ofertas where ofe_ven_id=$id order by ofe_monto asc, ofe_id asc");
                    $i=3;
					$cantidadMaxAceptada=0;
					$precioMax = get_db_value("select ven_precio from tb_ventas where ven_id=$id");
					$cantidadMax = get_db_value("select ven_cantidad from tb_ventas where ven_id=$id");
					  while($db->next_record())
					  {
					   $ofe_id = $db->f("ofe_id"); 
					   $ofe_usu_id = $db->f("ofe_usu_id");
					   $ofe_cantidad = $db->f("ofe_cantidad");
					   $ofe_monto = $db->f("ofe_monto");
					   $ofe_entrega = $db->f("ofe_entrega");
					   $nombre = get_db_value("select usu_nombre from tb_usuarios where usu_id=$ofe_usu_id");
?>
<tr>
<td class="title"><?=$nombre?>
</td> 
<td class="title"><?=$ofe_monto?>
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
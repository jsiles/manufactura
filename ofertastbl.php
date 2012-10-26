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
<td>
Cantidad Ofertada
</td>
<td>
Cantidad aceptada</td>
<td>
Cantidad rechazada</td>
<td>
Cantidad Entregada</td>
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
<td class="title"><?=$ofe_cantidad?>
</td>
                           <?php
						   $cantidadTemp = $cantidadMaxAceptada + $ofe_cantidad;
						   if(($ofe_monto<=$precioMax)&&($cantidadTemp<=$cantidadMax)&&($ofe_monto!=0)&&($ofe_cantidad!=0))
						   {
?>
<td class="title"><?=$ofe_cantidad?></td><td class="title">0</td>
<?php
$sessionUserId = get_param("user_id");
if($sessionUserId==$ofe_usu_id)
{
?>
<script language="javascript">
function onClickEntrega<?=$ofe_id?>(e)
{
	var entrega = document.getElementById("ofe_entrega<?=$ofe_id?>").value;
	entrega = entrega*1;
		if(entrega><?=$ofe_cantidad?>)
		alert("La cantidad entregada no puede ser mayor a la cantidad aceptada.");
		else
		{
			$.ajax({
					   type: "POST",
					   url: "valEntrega.php",
					   data: "id="+e+"&valEntrega="+entrega,
					   success: function(html){
					  // $("#ofe_button<?=$ofe_id?>").disabled="disabled";
					   //$("#ofe_entregada<?=$ofe_id?>").value="";
					   }
					 });
		}
}
</script>
<td class="title"><input name="ofe_entregada" id="ofe_entrega<?=$ofe_id?>" size="2" value="<?=$ofe_entrega?>" type="text"/><input name="ofe_button<?=$ofe_id?>" id="ofe_button<?=$ofe_id?>" onclick="onClickEntrega<?=$ofe_id?>(<?=$ofe_id?>)" value="entrega" type="button"/></td>
<?php
}else
{
?>
<td class="title"><?=$ofe_entrega?>&nbsp;</td>
<?php	
}
						   $cantidadMaxAceptada += $ofe_cantidad;
						   }else
						   {

$cantidaParcialAcep = $cantidadMax - $cantidadMaxAceptada;
if(($ofe_monto<=$precioMax)&&($cantidaParcialAcep>0))
{
	$cantidaParcialRechazado = $ofe_cantidad - $cantidaParcialAcep;
	$cantidadMaxAceptada += $cantidaParcialAcep;

?>
<td class="title"><?=$cantidaParcialAcep?></td><td class="title"><?=$cantidaParcialRechazado?></td>
<?php
$sessionUserId = get_param("user_id");
if($sessionUserId==$ofe_usu_id)
{
?>
<script language="javascript">
function onClickEntrega<?=$ofe_id?>(e)
{
	var entrega = document.getElementById("ofe_entrega<?=$ofe_id?>").value;
	entrega = entrega*1;
		if(entrega><?=$ofe_cantidad?>)
		alert("La cantidad entregada no puede ser mayor a la cantidad aceptada.");
		else
		{
			$.ajax({
					   type: "POST",
					   url: "valEntrega.php",
					   data: "id="+e+"&valEntrega="+entrega,
					   success: function(html){
					   //$("#ofe_button<?=$ofe_id?>").disabled="";
					   $("#ofe_entregada<?=$ofe_id?>").value="";
					   }
					 });
		}
}
</script>
<td class="title"><input name="ofe_entregada" id="ofe_entrega<?=$ofe_id?>" size="2" value="<?=$ofe_entrega?>" type="text"/><input name="ofe_button<?=$ofe_id?>" id="ofe_button<?=$ofe_id?>" onclick="onClickEntrega<?=$ofe_id?>(<?=$ofe_id?>)" value="entrega" type="button"/></td>
<?php
}else
{
?>
<td class="title">&nbsp;</td>
<?php	
}
}else{
?>
<td class="title">0</td><td class="title"><?=$ofe_cantidad?></td>
<td class="title">0</td>
<?php	
	}

						      }
                        
   					   $i++;
?>
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
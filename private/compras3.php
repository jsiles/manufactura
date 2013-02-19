<?php
include ("./common2.php");
include ("./globals.php");
session_start();
$jue_id= get_param("jue_id");

if ($FormAction=='update') insert();



//print_r($arrayMateriales);

function insert ()
{
	global $db;
	$fldmaterial = get_param("material");
	$fldcalidad = get_param("calidad");
	$fldunidad = get_param("unidad");	
	$fldpedido_0 = get_param("pedido_0");
	$fldpedido_30 = get_param("pedido_30");
	$fldpedido_60 = get_param("pedido_60");
	$fldjue_id = get_param("jue_id");
	$fldper_periodo = get_param("per_periodo");
	for($i=0;$i<6;$i++)
	{
		if(!$fldpedido_0[$i]) $fldpedido_0[$i]=0;
		if(!$fldpedido_30[$i]) $fldpedido_30[$i]=0;
		if(!$fldpedido_60[$i]) $fldpedido_60[$i]=0;
		$valCantidad= get_db_value("select count(*) from tb_materiales where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i and mat_descripcion=$fldmaterial and mat_calidad=$fldcalidad and mat_unidad=$fldunidad");
		if ($valCantidad==0)
		$db->query("insert into tb_materiales values(null,$fldjue_id, $fldper_periodo, $fldmaterial, $fldcalidad, $fldunidad, ".$fldpedido_0[$i].",".$fldpedido_30[$i].",".$fldpedido_60[$i].",$i,'ACTIVO',now())");
		else $db->query("update tb_materiales set mat_diascero=".$fldpedido_0[$i].", mat_diastreinta=".$fldpedido_30[$i].", mat_diassesenta=".$fldpedido_60[$i].", mat_datetime=now() where mat_jue_id=$fldjue_id and mat_per_id=$fldper_periodo and mat_pedido=$i and mat_descripcion=$fldmaterial and mat_calidad=$fldcalidad and mat_unidad=$fldunidad");
	}
}
?>
<html>
        <head>
                <title>Compras</title>
                <link rel="stylesheet" href="./Themes/jquery-ui.css" />
                <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
                <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
               
                <link rel="stylesheet" href="./Themes/style.css" />
                <script>
                        $(function() {
                                $( "#tabs" ).tabs({
                                        beforeLoad: function( event, ui ) {
                                                ui.jqXHR.error(function() {
                                                        ui.panel.html(
                                                        "La página no se encuentra disponible, intente nuevamente." );
                                                });
                                        }
                                });
								
								$( "#tabs-1" ).tabs({
                                        beforeLoad: function( event, ui ) {
                                                ui.jqXHR.error(function() {
                                                        ui.panel.html(
                                                        "La página no se encuentra disponible, intente nuevamente." );
                                                });
                                        }
                                });
                        });
                </script>
        </head>
        <body>
                <div id="tabs">
                        <ul>
                                <li><a href="#tabs-1">Param&eacute;tricas</a></li>
                                <li><a href="ajax/content1.html">Mesa Proveedores</a></li>
                                <li><a href="ajax/content2.html">Descuentos</a></li>
                        </ul>
                        <div id="tabs-1">
                                <ul>
                                	<li><a href="#tabs-1-1">Productos</a></li>
                                	<li><a href="#tabs-1-2">Incoterms</a></li>
									<li><a href="#tabs-1-3">Tipo de transporte</a></li>
									<li><a href="#tabs-1-4">Proveedor</a></li>
									<li><a href="#tabs-1-5">Tipo Suministro</a></li>
                                </ul>
                                <div id="tabs-1-1" >
                                 <table
									<tr>
                                      <td class="ClearFieldCaptionTD">Id</td>
                                      <td class="ClearFieldCaptionTD">Productos</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_productos2 where pro_jue_id=$jue_id order by pro_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
									?>
                                            <tr>  
                                              <td class="ClearDataTD"><?= $db->f("pro_id")?></td>
                                              <td class="ClearDataTD"><?= $db->f("pro_name")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="2">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>
                                </div>
                                <div id="tabs-1-2" >
                                	Second tab
                                </div>
                        </div>
                </div>
        </body>
</html>

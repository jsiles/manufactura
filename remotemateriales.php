<?php
include ("./common.php");
include ("./private/globals.php");
session_start();
check_security(1);

$user_id = get_session("cliID");
$jue_id = get_param("id");
$inc_id = get_param("inc_id");
$tra_id = get_param("tra_id");


$sSQL = "select t.jue_periodoInicial as inicio, t.jue_cantidad as cantidad, ".
	"t.jue_id as id from tb_juegos t where t.jue_id=$jue_id ".
	"  and t.jue_sw='A' " ;
	$db->query($sSQL);
	$next_record = $db->next_record();
	$per_inicio = $db->f("inicio");
	$per_cantidad = $db->f("cantidad");
	$jue_id = $db->f("id");
	$per_in = $per_inicio;
	
	
$arrayPeriodo = db_fill_array("select per_periodo, per_periodo from tb_periodos where per_jue_id=$jue_id and per_compra='A' limit $per_cantidad");
$per_periodo = get_param("per_periodo");
	
$maxPeriodo2 = get_db_value("select max(per_periodo) from tb_periodos where per_jue_id=$jue_id and per_compra='A'"); //modif
    
if (!$per_periodo) $per_periodo=1;
	

?>
<html>
<head>
<title>siges</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
      <link rel="stylesheet" href="Themes/style.css" />
<!--                <link href="private/Themes/navmenu.css" type="text/css" rel="stylesheet">-->
<!--				<link href="private/Themes/style.css" type="text/css" rel="stylesheet">-->
<!--                <link href="private/Themes/Clear/Style.css" type="text/css" rel="stylesheet">-->
                <link href="Styles/Coco/Style1.css" type="text/css" rel="stylesheet">
</head>
<body class="PageBODY">
<p>
							<table class="Grid" cellspacing="0" cellpadding="0" border="1">
									<tr class="Caption2">
                                      <td class="ClearColumnTD" nowrap="nowrap">Incoterm</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Transporte</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Factor Transporte &amp; Aduana</td>
                                      <td class="ClearColumnTD" nowrap="nowrap">Tiempo Transporte &amp; Aduana</td>
                                    </tr>
                                    <?php
										$sSQL="select * from tb_incotran where int_jue_id=$jue_id and int_inc_id=".tosql($inc_id, "Number")." and int_tra_id=".tosql($tra_id, "Number")." order by int_id asc";
										$db->query($sSQL);
										if($db->num_rows()>0)
										{
											while($result=$db->next_record())
											{
												$fldIncoterms = get_db_value("select inc_name from tb_incoterms where  inc_id = ".tosql($db->f("int_inc_id"), "Number"));
												$fldTransporte = get_db_value("select tra_name from tb_transporte where  tra_id = ".tosql($db->f("int_tra_id"), "Number"));
									?>
                                            <tr class="Row">  
                                              <td class="ClearDataTD"><?= $fldIncoterms?></td>
                                              <td class="ClearDataTD"><?= $fldTransporte?></td>
                                              <td class="ClearDataTD"><?= $db->f("int_factorTra")?></td>
                                              <td class="ClearDataTD"><?= $db->f("int_tiempoTra")?></td>
                                             </tr>
									<?php
											}
										}
										else{
									?>
                                    		<tr>  
                                              <td class="ClearDataTD" colspan="8">No hay Registros</td>
                                             </tr>
                                    	
                                    <?php
										}
									?>
                                 </table>

</body>
</html>
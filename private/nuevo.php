<?php
include ("./common2.php");

session_start();
$filename = "nuevo.php";
$template_filename = "nuevo.html";
$FormAction = get_param("FormAction");
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//$tpl->load_file($header_filename, "header");
$tpl->set_var("FileName", $filename);
//header_show();
    if ($FormAction=='nuevo') 
        alta();
carga();
//$tpl->parse("header", false);
$tpl->pparse("main", false);

function alta()
{
    global $db, $db2, $tpl;
    $nombreJuego = get_param ("nuevo");
    $juego = get_param("jue_id");
    
    if ($nombreJuego && $juego)
    {
        
        /****************************************
                Máximo Juego
        ****************************************/
        

        
        $sSQL = "select jue_imagen, jue_periodoInicial, jue_cantidad from tb_juegos where jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $imagen = $db->f("jue_imagen");
            $periodoInicial = $db->f("jue_periodoInicial");
            $cantidad = $db->f("jue_cantidad");
            $sQuery = "insert into tb_juegos values (null,".tosql($nombreJuego, "Text").",'', $periodoInicial, $cantidad, 'A','I')";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }
        $max_juego = get_db_value("select last_insert_id()");
        
        /****************************************
                Alta de mercados
        ****************************************/
        
        $sSQL = "select mer_id, mer_nombre from tb_mercados where mer_jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $nombre_mercado = $db->f("mer_nombre");
			$mer_id = $db->f("mer_id");
            $sQuery = "insert into tb_mercados values ($mer_id , $max_juego,".tosql($nombre_mercado, "Text").",'A')";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }


        /****************************************
                Alta de productos
        ****************************************/
        
        $sSQL = "select pro_id, pro_nombre from tb_productos where pro_jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $nombre_producto = $db->f("pro_nombre");
			$pro_id = $db->f("pro_id");
            $sQuery = "insert into tb_productos values ($pro_id, $max_juego,".tosql($nombre_producto, "Text").",'A')";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }        
        
        /****************************************
                Alta de tipo_clientes
        ****************************************/
        
        $sSQL = "select cli_id,cli_nombre from tb_tipoclientes where cli_jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $nombre_clientes = $db->f("cli_nombre");
 	    $cli_id = $db->f("cli_id");
            $sQuery = "insert into tb_tipoclientes values ($cli_id, $max_juego,".tosql($nombre_clientes, "Text").",'A')";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }     
        
        /****************************************
                Alta de Grupos
        ****************************************/
        
        $sSQL = "select gru_ite_id, grp_apl from th_grupos where gru_jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $ite_id = $db->f("gru_ite_id");
            $apl = $db->f("grp_apl");
            $sQuery = "insert into th_grupos values ($max_juego, $ite_id, $apl,'A')";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }
        
        /****************************************
                Alta de Inicio
        ****************************************/
        
        $sSQL = "select ini_pro_id, ini_mer_id, ini_tic_id, ini_monto from th_inicio where ini_jue_id=$juego";
        $db->query($sSQL);
        $nex_record = $db->next_record();
        while ($nex_record)
        {
            $producto = $db->f("ini_pro_id");
            $mercado = $db->f("ini_mer_id");
            $tipo_cliente = $db->f("ini_tic_id");
            $monto =  $db->f("ini_monto");   
            $sQuery = "insert into th_inicio values (null, $max_juego, $producto, $mercado, $tipo_cliente, $monto)";
            $db2->query($sQuery);
            $nex_record = $db->next_record();
        }   
        
        /****************************************
                Alta de Valores Iniciales
        ****************************************/
        
        $sSQL = "insert into th_valoresiniciales (select null, $max_juego, vai_atr_id, vai_pro_id, vai_mer_id, vai_cli_id, vai_monto, vai_periodo,'A' from th_valoresiniciales where vai_sw='A' and vai_jue_id=$juego order by vai_id)";
        $db->query($sSQL);
		
		
		/****************************************
                Alta de Materiales
        ****************************************/
        
        $sSQL = "insert into tb_materiales (select null, $max_juego, mat_per_id, mat_descripcion, mat_calidad, mat_unidad, mat_diascero, mat_diastreinta, mat_diassesenta, mat_pedido, 'ACTIVO', now()  from tb_materiales where mat_estado='ACTIVO' and mat_jue_id=$juego order by mat_id)";
        $db->query($sSQL);
        
        /****************************************
                Alta de Periodos
        ****************************************/
        
        $sSQL = "insert into tb_periodos (select null, $max_juego, per_periodo, per_estado, per_inv_estado, per_compra, per_tiempo, per_datetime from tb_periodos where per_jue_id=$juego order by per_id)";
        $db->query($sSQL);      
        /****************************************
                Alta todos los modulos v3 David Cabrera
        ****************************************/
		
		/****************************************
                Alta Proyectos
        ****************************************/
		//COSTOS
		$sSQL="insert into py_costos (select cos_id, cos_mantenimiento, $max_juego from py_costos where cos_jue_id=$juego order by cos_id)";
    	$db->query($sSQL);

		//PARAMETROS
		$sSQL="insert into py_parametros (select par_id, par_descripcion, par_valor, $max_juego from py_parametros where par_jue_id=$juego order by par_id)";
    	$db->query($sSQL);
		
		//PROYECTOS
		$sSQL="insert into py_proyectos (select pro_id, pro_descripcion, pro_duracion, $max_juego from py_proyectos where pro_jue_id=$juego order by pro_id)";
    	$db->query($sSQL);

		$sSQL="insert into py_proypar (select prp_pro_id, prp_par_id, prp_valor, $max_juego from py_proypar where prp_jue_id=$juego order by prp_pro_id)";
    	$db->query($sSQL);
		
		/****************************************
                Alta INFORMACION DISPONIBLE
        ****************************************/
        //INFORMACION DISPONIBLE
		$sSQL = "insert into tb_investigacion (select null, $max_juego, inv_per_id, inv_investigacion, inv_costo, inv_costoexclusividad, inv_cantidad, inv_saldo, inv_pdf, inv_sw from  tb_investigacion where inv_jue_id=$juego order by inv_per_id) ";
		$db->query($sSQL);      
        
		
		/****************************************
                Alta de Compras Materiales V2
        ****************************************/
		//PRODUCTOS
		$sSQL = "insert into tb_productos2 (select pro_id, pro_name, $max_juego from tb_productos2 where pro_jue_id=$juego order by pro_id)";
        $db->query($sSQL);  
		//INCOTERMS
		$sSQL = "insert into tb_incoterms (select inc_id, inc_name, $max_juego from tb_incoterms where inc_jue_id=$juego order by inc_id)";
        $db->query($sSQL);  
		//TRANSPORTE
		$sSQL = "insert into tb_transporte (select tra_id, tra_name, $max_juego from tb_transporte where tra_jue_id=$juego order by tra_id)";
        $db->query($sSQL);  
		//PROVEEDOR
		$sSQL = "insert into tb_proveedor (select pro_id, pro_name, $max_juego from tb_proveedor where pro_jue_id=$juego order by pro_id)";
        $db->query($sSQL);  
		//SUMINISTRO ELIMNADO V3
//		$sSQL = "insert into tb_suministro (select sum_id, sum_name, sum_cost, sum_time, $max_juego from tb_suministro where sum_jue_id=$juego order by sum_id)";
//        $db->query($sSQL);
		//GESTION NO CORRESPONDE  
		//MESA PROVEEDORES
		$sSQL = "insert into tb_mesaproveedores (select mes_id, mes_com_id, mes_pro_id, mes_precio, mes_pedido, $max_juego, mes_inc_id, mes_tiempo from tb_mesaproveedores where mes_jue_id=$juego order by mes_id)"; //V3
        $db->query($sSQL);
		//ALTER TABLE `tb_mesaproveedores` CHANGE `mes_id` `mes_id` INT( 11 ) NOT NULL ; ALTER TABLE `calidad`.`tb_mesaproveedores` DROP PRIMARY KEY , ADD PRIMARY KEY ( `mes_id` , `mes_jue_id` ) 
		
		//DESCUENTOS NO CORRESPONDE
		//INCOTRAN
		$sSQL = "insert into tb_incotran (select int_id, int_inc_id, int_tra_id, int_factorTra, int_tiempoTra, $max_juego from tb_incotran where int_jue_id=$juego order by int_id)";   //V3
        $db->query($sSQL);
		//COMPRAS2 NO CORRESPONDE
		
		/****************************************
                Alta de Subastas
        ****************************************/		
		$sSQL = "insert into tb_celebridades (select null, $max_juego, cel_per_id, cel_nombre,  cel_precio, cel_beneficio, cel_tiempo, cel_fecha, cel_fechafin, cel_foto, cel_sw from tb_celebridades where cel_jue_id=$juego order by cel_per_id)";
        $db->query($sSQL);
		
		/****************************************
                Alta de Licitacion proyectos
        ****************************************/		
		$sSQL = "insert into tb_ventas (select null, $max_juego, ven_per_id, ven_nombre,  ven_precio, ven_cantidad, ven_unidad, ven_tiempo, ven_fecha, ven_fechafin, ven_foto, ven_sw from tb_ventas where ven_jue_id=$juego order by ven_per_id)";
        $db->query($sSQL);
		
		/****************************************
                Alta de Responsabilidad Social
        ****************************************/		
		$sSQL = "insert into tb_responsabilidad (select null, $max_juego, res_per_id, res_nombre,  res_precio, res_beneficio0, res_beneficio1, res_beneficio2, res_beneficio3, res_beneficio4, res_beneficio5, res_beneficio6, res_tiempo, res_fecha, res_fechafin, res_foto, res_sw from tb_responsabilidad where res_jue_id=$juego order by res_per_id)";
        $db->query($sSQL);
		
		$sSQL = "insert into tb_responsabilidadgeneral (select null, $max_juego, reg_per_id, reg_pdf,  reg_beneficio1, reg_beneficio2, reg_beneficio3, reg_beneficio4, reg_beneficioDirecto from tb_responsabilidadgeneral where reg_jue_id=$juego order by reg_per_id)";
        $db->query($sSQL);
		
		
        echo "<script>javascript:window.opener.location.reload();</script>";
    }
}
function carga()
{
    global $db, $tpl;
    $juego = get_param("jue_id");
   // echo $juego;
    $tpl->set_var("jue_id", $juego);
    
}
?>

<?php
include ("./common.php");
include ("./private/globals.php");
session_start();
//echo get_param("mat_id");
//print_r($arrayUnidades[get_param("mat_id")]);
$per_periodo = get_param("per_periodo");
$jue_id = get_param("jue_id");
?>
      <div id="listPedido">
      <select name="unidad" onchange="javascript:submit();"><option value="">Seleccionar Valor</option>
      <?php
      if(get_param("mat_id"))
	  {
		  foreach($arrayUnidades[get_param("mat_id")] as  $key=>$value)
		  {
			  ?>
			  <option value="<?=$key?>"><?=$value?></option>
			  <?php
		  }
	  }
	  else
	  {
		  foreach($arrayUnidades as $arrayUnidad)
		  {
			foreach($arrayUnidad as $key=>$value) 
				{
			  ?>
			  <option value="<?=$key?>"><?=$value?></option>
			  <?php
				}
		  }
	  }
	  ?>
      </select>
      </div>
// JavaScript Document
function objectAjax()
	{
	var xmlhttp=false;
	try 
		{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} 
	catch (e) 
		{
		try 
			{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} 
		catch (E) 
			{
			xmlhttp = false;
			}
		}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') 
		{
  		xmlhttp = new XMLHttpRequest();
  		}
  	return xmlhttp;
  	}

function changeMaterial(material,  per_periodo, jue_id)
  {
	  divx = document.getElementById('listPedido');
	  divx.innerHTML = '<img border="0" src="lib/loading.gif">';
	  ajax=objectAjax();
	
	  ajax.open("POST", "listPedido.php",true);
	  ajax.onreadystatechange=function() {
										  if (ajax.readyState==4) 
											{
											divx.innerHTML=ajax.responseText;
											}
										}  
	  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send("mat_id="+material.value+"&per_periodo="+ per_periodo+"&jue_id="+ jue_id);
  }
  
function changeData(unidad, per_periodo, jue_id)
{
	  var x = new Array();
      x['14'] ='$M/4 ROLLOS';
      x['18'] ='$M/8 ROLLOS';
      x['240'] ='$M/40 KITS';
      x['280'] ='$M/80 KITS';
	  x['340'] ='$M/40 KILOGRAMOS';
      x['380'] ='$M/80 KILOGRAMOS';
	var valMaterialObj = document.getElementById('material');
	var valMaterialSelIndex = valMaterialObj.selectedIndex;
	var valMaterialValue = valMaterialObj.options[valMaterialSelIndex].value;
	
	var valCalidadObj = document.getElementById('calidad');
	var valCalidadSelIndex = valCalidadObj.selectedIndex;
	var valCalidadValue = valCalidadObj.options[valCalidadSelIndex].value;
	
	if ((valMaterialValue!='')&&(valCalidadValue!='')&&(unidad.value!=''))
	{
	  divx = document.getElementById('tablaData');
	  divx.innerHTML = '<img border="0" src="lib/loading.gif">';
	  ajax=objectAjax();
	
	  ajax.open("POST", "listData.php",true);
	  ajax.onreadystatechange=function() {
										  if (ajax.readyState==4) 
											{
											divx.innerHTML=ajax.responseText;
											}
										}  
	  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send("mat_id="+valMaterialValue+"&uni_id="+unidad.value+"&cal_id="+valCalidadValue+"&per_periodo="+ per_periodo+"&jue_id="+ jue_id );
	}
} 
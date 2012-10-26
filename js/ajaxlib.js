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
		
		//alert(x[valMaterialValue+unidad.value]);
	/*	switch(valMaterialValue+unidad.value)
		{
			case '14':
			document.getElementById('titleData').innerHTML='$M/4 ROLLOS';
			document.getElementById('subTitle0').innerHTML='4 ROLLOS';
			document.getElementById('subTitle1').innerHTML='8 ROLLOS';
			document.getElementById('subTitle2').innerHTML='12 ROLLOS';
			document.getElementById('subTitle3').innerHTML='16 ROLLOS';
			document.getElementById('subTitle4').innerHTML='20 ROLLOS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 20 ROLLOS';			
			break;
			case '18':
			document.getElementById('titleData').innerHTML='$M/8 ROLLOS';
			document.getElementById('subTitle0').innerHTML='8 ROLLOS';
			document.getElementById('subTitle1').innerHTML='16 ROLLOS';
			document.getElementById('subTitle2').innerHTML='24 ROLLOS';
			document.getElementById('subTitle3').innerHTML='32 ROLLOS';
			document.getElementById('subTitle4').innerHTML='40 ROLLOS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 40 ROLLOS';			
			break;
			case '240':
			document.getElementById('titleData').innerHTML='$M/40 KITS';
			document.getElementById('subTitle0').innerHTML='40 KITS';
			document.getElementById('subTitle1').innerHTML='80 KITS';
			document.getElementById('subTitle2').innerHTML='120 KITS';
			document.getElementById('subTitle3').innerHTML='160 KITS';
			document.getElementById('subTitle4').innerHTML='200 KITS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 200 KITS';			
			break;
			case '280':
			document.getElementById('titleData').innerHTML='$M/80 KITS';
			document.getElementById('subTitle0').innerHTML='80 KITS';
			document.getElementById('subTitle1').innerHTML='160 KITS';
			document.getElementById('subTitle2').innerHTML='240 KITS';
			document.getElementById('subTitle3').innerHTML='320 KITS';
			document.getElementById('subTitle4').innerHTML='400 KITS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 400 KITS';			
			break;
			case '340':
			document.getElementById('titleData').innerHTML='$M/40 KILOGRAMOS';
			document.getElementById('subTitle0').innerHTML='40 KILOGRAMOS';
			document.getElementById('subTitle1').innerHTML='80 KILOGRAMOS';
			document.getElementById('subTitle2').innerHTML='120 KILOGRAMOS';
			document.getElementById('subTitle3').innerHTML='160 KILOGRAMOS';
			document.getElementById('subTitle4').innerHTML='200 KILOGRAMOS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 200 KILOGRAMOS';			
			break;
			case '380':
			document.getElementById('titleData').innerHTML='$M/80 KILOGRAMOS';
			document.getElementById('subTitle0').innerHTML='80 KILOGRAMOS';
			document.getElementById('subTitle1').innerHTML='160 KILOGRAMOS';
			document.getElementById('subTitle2').innerHTML='240 KILOGRAMOS';
			document.getElementById('subTitle3').innerHTML='320 KILOGRAMOS';
			document.getElementById('subTitle4').innerHTML='400 KILOGRAMOS';			
			document.getElementById('subTitle5').innerHTML='M&aacute;s de 400 KILOGRAMOS';			
			break;

		}*/
	}
	
  } 

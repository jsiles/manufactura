// JavaScript Document
function loadOption(jue_id, incoterm, transporte)
{
	//alert("Hello world");
	if(transporte){
		jQuery.facebox( {ajax: './remotemateriales.php?id=' + jue_id + '&inc_id=' + incoterm + '&tra_id=' + transporte });
	}
}
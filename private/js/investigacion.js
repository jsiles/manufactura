// JavaScript Document

function clean(e)
    {
        e.value="";
        return true;
    }
function addInv()
{
	k++;
	$("#frmDinamico").append($("#dataInvestigacionX").html());
	$("#frmDinamico").find("#investigacionX").each(function (x,el){$(el).attr("id","investigacion"+k)});
	$("#frmDinamico").find("#costoX").each(function (x,el){$(el).attr("id","costo"+k)});
	$("#frmDinamico").find("#costo_exclusividadX").each(function (x,el){$(el).attr("id","costo_exclusividad"+k)});
	$("#frmDinamico").find("#cantidadX").each(function (x,el){$(el).attr("id","cantidad"+k)});
	$("#frmDinamico").find("#pdfX").each(function (x,el){$(el).attr("id","pdf"+k)});
	$("#frmDinamico").find("#idInvX").each(function (x,el){$(el).attr("id","idInv"+k)});
	$("#frmDinamico").find("#pdflabelX").each(function (x,el){$(el).attr("id","pdflabel"+k)});


	$("#frmDinamico").find("#investigacion"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#cantidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#pdf"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#idInv"+k).each(function (x,el){$(el).val("")});
	$("#frmDinamico").find("#pdflabel"+k).each(function (x,el){$(el).html("")});
	
	 $('.multi-pt').MultiFile({
		  accept:'pdf', max:1, STRING: {
		   remove:'Remover',
		   selected:'Selecionado: $file',
		   denied:'Invalido archivo de tipo $ext!'
		  }
		 });
	
	return false;
	}	
function addCel()
{
	k++;
	$("#frmDinamico").append($("#dataInvestigacion0").html());
	$("#frmDinamico").find("#investigacion0").each(function (x,el){$(el).attr("id","investigacion"+k)});
	$("#frmDinamico").find("#costo0").each(function (x,el){$(el).attr("id","costo"+k)});
	$("#frmDinamico").find("#costo_exclusividad0").each(function (x,el){$(el).attr("id","costo_exclusividad"+k)});
	$("#frmDinamico").find("#cantidad0").each(function (x,el){$(el).attr("id","cantidad"+k)});
	$("#frmDinamico").find("#pdf0").each(function (x,el){$(el).attr("id","pdf"+k)});
	$("#frmDinamico").find("#idInv0").each(function (x,el){$(el).attr("id","idInv"+k)});
	$("#frmDinamico").find("#pdflabel0").each(function (x,el){$(el).attr("id","pdflabel"+k)});
	$("#frmDinamico").find("#fechaI0").each(function (x,el){$(el).attr("id","fechaI"+k)});
	$("#frmDinamico").find("#horaI0").each(function (x,el){$(el).attr("id","horaI"+k)});
	$("#frmDinamico").find("#calendar0").each(function (x,el){$(el).attr("id","calendar"+k); $(el).attr("onClick","if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI"+k+");return false;");});


	$("#frmDinamico").find("#investigacion"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#cantidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#pdf"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#idInv"+k).each(function (x,el){$(el).val("")});
	$("#frmDinamico").find("#pdflabel"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#fechaI"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#horaI"+k).each(function (x,el){$(el).html("")});
	
	return false;
	}	
		

// JavaScript Document
// JavaScript Document

function clean(e)
    {
        e.value="";
        return true;
    }
function addInv()
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


	$("#frmDinamico").find("#investigacion"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#cantidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#pdf"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#idInv"+k).each(function (x,el){$(el).val("")});
	$("#frmDinamico").find("#pdflabel"+k).each(function (x,el){$(el).html("")});
	return false;
	}	
function addCel()
{
	k++;
	$("#frmDinamico").append($("#dataInvestigacion0").html());
	$("#frmDinamico").find("#investigacion0").each(function (x,el){$(el).attr("id","investigacion"+k)});
	$("#frmDinamico").find("#costo0").each(function (x,el){$(el).attr("id","costo"+k)});
	$("#frmDinamico").find("#costo_exclusividad00").each(function (x,el){$(el).attr("id","costo_exclusividad0"+k)});
	$("#frmDinamico").find("#costo_exclusividad10").each(function (x,el){$(el).attr("id","costo_exclusividad1"+k)});
	$("#frmDinamico").find("#costo_exclusividad20").each(function (x,el){$(el).attr("id","costo_exclusividad2"+k)});
	$("#frmDinamico").find("#costo_exclusividad30").each(function (x,el){$(el).attr("id","costo_exclusividad3"+k)});
	$("#frmDinamico").find("#costo_exclusividad40").each(function (x,el){$(el).attr("id","costo_exclusividad4"+k)});
	$("#frmDinamico").find("#costo_exclusividad50").each(function (x,el){$(el).attr("id","costo_exclusividad5"+k)});
	$("#frmDinamico").find("#costo_exclusividad60").each(function (x,el){$(el).attr("id","costo_exclusividad6"+k)});
	$("#frmDinamico").find("#cantidad0").each(function (x,el){$(el).attr("id","cantidad"+k)});
	$("#frmDinamico").find("#idInv0").each(function (x,el){$(el).attr("id","idInv"+k)});
	$("#frmDinamico").find("#fechaI0").each(function (x,el){$(el).attr("id","fechaI"+k)});
	$("#frmDinamico").find("#horaI0").each(function (x,el){$(el).attr("id","horaI"+k)});
	$("#frmDinamico").find("#calendar0").each(function (x,el){$(el).attr("id","calendar"+k); $(el).attr("onClick","if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI"+k+");return false;");});
	$("#frmDinamico").find("#pdflabel0").each(function (x,el){$(el).attr("id","pdflabel"+k)});



	$("#frmDinamico").find("#investigacion"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad0"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad1"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad2"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad3"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad4"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad5"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#costo_exclusividad6"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#cantidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#idInv"+k).each(function (x,el){$(el).val("")});
	$("#frmDinamico").find("#fechaI"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#horaI"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#pdflabel"+k).each(function (x,el){$(el).html("")});
	return false;
	}	
		

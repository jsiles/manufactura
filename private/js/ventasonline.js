// JavaScript Document
// JavaScript Document

function clean(e)
    {
        e.value="";
        return true;
    }
function addVen()
{
	k++;
	$("#frmDinamico").append($("#dataInvestigacion0").html());
	$("#frmDinamico").find("#ven_nombre0").each(function (x,el){$(el).attr("id","ven_nombre"+k)});
	$("#frmDinamico").find("#ven_precio0").each(function (x,el){$(el).attr("id","ven_precio"+k)});
	$("#frmDinamico").find("#ven_cantidad0").each(function (x,el){$(el).attr("id","ven_cantidad"+k)});
	$("#frmDinamico").find("#ven_unidad0").each(function (x,el){$(el).attr("id","ven_unidad"+k)});
	$("#frmDinamico").find("#idInv0").each(function (x,el){$(el).attr("id","idInv"+k)});

	$("#frmDinamico").find("#fechaI0").each(function (x,el){$(el).attr("id","fechaI"+k)});
	$("#frmDinamico").find("#horaI0").each(function (x,el){$(el).attr("id","horaI"+k)});
	$("#frmDinamico").find("#calendar0").each(function (x,el){$(el).attr("id","calendar"+k); $(el).attr("onClick","if(self.gfPop)gfPop.fPopCalendar(document.valoresRecord.fechaI"+k+");return false;");});
	$("#frmDinamico").find("#ven_tiempo0").each(function (x,el){$(el).attr("id","ven_tiempo"+k)});


	$("#frmDinamico").find("#ven_nombre"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#ven_precio"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#ven_cantidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#ven_unidad"+k).each(function (x,el){$(el).val("")}); 
 	$("#frmDinamico").find("#idInv"+k).each(function (x,el){$(el).val("")});
	$("#frmDinamico").find("#fechaI"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#horaI"+k).each(function (x,el){$(el).html("")});
	$("#frmDinamico").find("#ven_tiempo"+k).each(function (x,el){$(el).val("")});
	return false;
	}	
		

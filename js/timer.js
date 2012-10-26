/*   Free Script provided by HIOXINDIA            */
/*   visit us at http://www.hscripts.com     */
/*   This is a copyright product of hioxindia.com */

var rTimer;
var sds ;

TimeTick();

function TimeTick()
{
	sds =  new Date();
	if (sds.getSeconds()<10) tsec= "0" +sds.getSeconds();
	else tsec= sds.getSeconds();
	if (sds.getMinutes()<10) tmin= "0" +sds.getMinutes();
	else tmin= sds.getMinutes();
	if (sds.getHours()<10) thour= "0" +sds.getHours();
	else thour= sds.getHours();
	document.getElementById("time").innerHTML=" "+thour+":"+ tmin +":"+ tsec;
	if(rTimer)
		{
			clearTimeout(rTimer);
		}

	rTimer = setTimeout('TimeTick()', 1000);
}



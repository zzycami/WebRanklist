/**
 * 
 */

var xmlHttp;
function ajaxRequest(url){
	if(window.XMLHttpRequest){
		xmlHttp = new XMLHttpRequest();
	}else if(window.ActiveXObject){
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange = ajaxResponse;
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function ajaxResponse(){
	if(xmlHttp.readyState == 4){
		if(xmlHttp.status == 200){
			//Change the json data that returned to object
			//eval_r('coms='+xmlHttp.responseText);
			var coms = eval_r('('+xmlHttp.responseText+')');
		}
	}
}
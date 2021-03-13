function ajax(url,post_values) {
	if(window.XMLHttpRequest) {
    	try {
			req = new XMLHttpRequest();
        } catch(e) {
			req = false;
        }
    // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
       	try {
        	req = new ActiveXObject("Msxml2.XMLHTTP");
      	} catch(e) {
        	try {
          		req = new ActiveXObject("Microsoft.XMLHTTP");
        	} catch(e) {
          		req = false;
        	}
		}
    }
	if(req) {
		req.open("POST", url, true);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.send(post_values);
		req.onreadystatechange = processRequest;
	} else {
		alert('This Browser is not supporting Ajax. Please use IE or FireFox');
	}
}

function processRequest() {
	if(req.readyState==4)	{
		if(req.status == 200) {
			parseMessagesTEXT();
		} else {
			alert("Problem in requesting XML "+req.statusText);
		}
	}
}
function parseMessagesTEXT() {
	/* if plain text */
	if(document.getElementById("display_layer")) {
		var displaylayer = document.getElementById("display_layer").value;
	} else {
		var displaylayer = "showdetails";
	}
	description = req.responseText;
	var i = document.getElementById(displaylayer);
	i.innerHTML= description;
	showmsg_close();
	if(displaylayer == 'show_msg') { 
		showmsgdiv();
	} else if(displaylayer == 'show_preview') { 
		showpreviewdiv();
	}
	return false;
}

function showmsgdiv()
{
	hideSelect();
	document.getElementById('show_msg').style.left 		= (screen.width-400)/2;
	document.getElementById('show_msg').style.top 		= (screen.height)/2;
	document.getElementById('show_msg').style.visibility	= 'visible';
	window.scroll (0,0);
}

function showpreviewdiv()
{
	hideSelect();
	document.getElementById('show_preview').style.left 		= (screen.width-400)/2;
	document.getElementById('show_preview').style.top 		= (screen.height)/4;
	document.getElementById('show_preview').style.visibility	= 'visible';
	window.scroll (0,0);
}

function showmsg_close() {
	showSelect();
	document.getElementById('show_msg').style.visibility	= '';
}

function showpreview_close() {
	showSelect();
	document.getElementById('show_preview').style.visibility	= '';
}

function hideSelect() {
	if (document.all)
	{
		for(var i = 0; i < document.all.tags("select").length; i++)
		{	obj = document.all.tags("select")[i];
			obj.style.visibility = 'hidden';
		}
	}
}

function showSelect() {
	if (document.all)
	{
		for(var i = 0; i < document.all.tags("select").length; i++)
		{	obj = document.all.tags("select")[i];
			obj.style.visibility = 'visible';
		}
	}
}
function postValues(myvalues) {
	var x;
	var post_values = '';
	var temp = '';
	for (x=0; x<myvalues.length; x++) {
		temp = document.getElementById(myvalues[x]).value.replace("&","*am*");
		temp = temp.replace(/&/g,"*am*");
		temp = temp.replace("'","*sq*");
		post_values += myvalues[x]+'='+temp+'&';
	}
	post_values = post_values.substring(0,post_values.length-1);
	return post_values;
}
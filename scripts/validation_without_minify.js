n=navigator.userAgent;
w=n.indexOf("MSIE");
if((w>0)&&(parseInt(n.charAt(w+5))>5))
{
	T=["object","embed","applet"];
	for(j=0;j<2;j++)
	{
		E=document.getElementsByTagName(T[j]);
		for(i=0;i<E.length;i++)
		{
			P=E[i].parentNode;
			H=P.innerHTML;
			P.removeChild(E[i]);
			P.innerHTML=H;
		}
	}
}
/*
// This Disable right mouse click Script . but fails when javascript is disabled 

var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("return false");	*/

function Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)
{
	if(!(fieldConfirm)) fieldConfirm = Array ();
	if(!(fieldConfirmDesc)) fieldConfirmDesc = Array();		
	if(!(fieldEmail)) fieldEmail = Array();	
	if(!(fieldNumeric)) fieldNumeric = Array();	
	if(!(fieldSpecChars)) fieldSpecChars = Array();
	if(!(fieldCharDesc)) fieldCharDesc = Array();	
	
//	var alertMsg =  "Please fill the following fields before you submit :\n\n";
    var alertMsg =  "Please Enter ";
   	var l_Msg = alertMsg.length;
	var re = /[<,>,",',%,&,;,^]/i;
	var e = / /g;
   	for (var i = 0; i < fieldRequired.length; i++)
   	{
  		var obj = frm.elements[fieldRequired[i]];
   		if (obj)
       		{
            	switch(obj.type)
        		{
               	case "select-one":
                				if (obj.selectedIndex == -1 || obj.options[obj.selectedIndex].text == "" || obj.options[obj.selectedIndex].value == "" || obj.options[obj.selectedIndex].value == 0)
                    					alertMsg += " - " + fieldDescription[i] + "\n";
                   				break;
       			case "select-multiple":
                    			if (obj.selectedIndex == -1)
                        					alertMsg += " - " + fieldDescription[i] + "\n";
                        		break;
                case "text":
								var temp_value = obj.value.replace(e,"");
								if (temp_value.length == 0 || obj.value == null)
                         					alertMsg += " - " + fieldDescription[i] + "\n";
								break;
				case "hidden":
								var temp_value = obj.value.replace(e,"");
								if (temp_value.length == 0 || obj.value == null)
                         					alertMsg += " - " + fieldDescription[i] + "\n";
								break;
                case "password":
								var temp_value = obj.value.replace(e,"");
                        		if (temp_value.length == 0 || obj.value == null) {
                         			alertMsg += " - " + fieldDescription[i] + "\n";
								} else {
									result = temp_value.search(re); // checks invalid characters in product code
									if( result > -1 ) 
									{
										alertMsg = " Invalid characters in the Password field\n Allowed character are (0-9,a-z,A-Z)";
									}
								}
								break;
                
				case "textarea":
								var temp_value = obj.value.replace(e,"");
                        		if (temp_value.length == 0 || obj.value == null)
                         					alertMsg += " - " + fieldDescription[i] + "\n";
                   				break;
				case "undefined":
								if (obj.value == "" || obj.value == null)
                         					alertMsg += " - " + fieldDescription[i] + "\n";
								break;				
                }   

			
				if (alertMsg.length != l_Msg)
				{
					alert(alertMsg);
					switch(obj.type)
        			{
        				case "text": obj.select();
									break;
                		case "password": obj.select();
									break;
						case "textarea": obj.select();
									break;
        			}
					obj.focus();
					return false;
				}	
		} // END IF (obj)
  	} // END FOR
	
	if (alertMsg.length == l_Msg)
   	{
		 /************ Special Chars Validation ************/
		for (var i = 0; i < fieldSpecChars.length; i++)
		{
			var obj = frm.elements[fieldSpecChars[i]];
			if (obj)	{
				var re = /[<,>,",',%,&,;]/i;	    
				var result = obj.value.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of '+fieldCharDesc[i]); 
					obj.focus();
					obj.select();
				   	return false;
				} 
			}	// END IF obj
		} // END IF FOR
		/************Special Chars Validation END ************/	
		/************ Email Validation ************/
		for (var i = 0; i < fieldEmail.length; i++)
	   	{
  			var obj = frm.elements[fieldEmail[i]];
			if (obj)
			{
				var res = obj.value.search(/^[^\.][A-Za-z0-9_\-\.]*[^\.]\@[^\.][A-Za-z0-9_\-\.]+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\.biz)|(\.us)|(\.bizz)|(\.coop)|(\.uk.com)|(\.co.uk)|(\..{2,2}))[ ]*$/gi);
				if(obj.length < 5 || res==-1)
		  		{
			 		var alertEmail = 'Enter a Valid Email Address';
					alert(alertEmail);
					obj.focus();
					obj.select();
		   			return false;
				 }
			}	// END IF obj
		} // END IF FOR
		/************ Email Validation END ************/	
		
	   /************ Password Confirmation ************/
		for (var i = 0; i < fieldConfirm.length; i++)
	   	{
			var obj1 = frm.elements[fieldConfirm[i]];
			var obj2 = frm.elements[fieldConfirm[i+1]];
			if (obj1 && obj2)
			{
			 if(obj1.value != obj2.value)
			  {
				 		alertConfirm  = fieldConfirmDesc[i] + " and " + fieldConfirmDesc[i+1] +" Not Matching";
						alert(alertConfirm);
						obj1.focus();
						obj1.select();
			 			return false;
			  } // END IF obj1.value
			} // END IF obj1
		} // END IF FOR	
		
	   /************ Password Confirmation END ************/
	
	   /************ Numeric Validation ************/
		for (var i = 0; i < fieldNumeric.length; i++)
	   	{
  			var obj = frm.elements[fieldNumeric[i]];
			if (obj)
			{
			  if(isNaN(obj.value))
			  		{
						alert('Enter A Numeric Value');
						obj.focus();
						obj.select();
			   			return false;
					 }
			}	// END IF obj
		} // END IF FOR
		/************ Numeric Validation END ************/	
		
		return true;	
  	 } // END IF (alertMsg.length == l_Msg)
	else
 	{
		alert(alertMsg);
   		return false;
   	}
}
function isValidEmail(email, required) {
    if (required==undefined) {   // if not specified, assume it's required
        required=true;
    }
    if (email==null) {
        if (required) {
            return false;
        }
        return true;
    }
    if (email.length==0) {  
        if (required) {
            return false;
        }
        return true;
    }
    if (! allValidChars(email)) {  // check to make sure all characters are valid
        return false;
    }
    if (email.indexOf("@") < 1) { //  must contain @, and it must not be the first character
        return false;
    } else if (email.lastIndexOf(".") <= email.indexOf("@")) {  // last dot must be after the @
        return false;
    } else if (email.indexOf("@") == email.length) {  // @ must not be the last character
        return false;
    } else if (email.indexOf("..") >=0) { // two periods in a row is not valid
	return false;
    } else if (email.indexOf(".") == email.length) {  // . must not be the last character
	return false;
    }
    return true;
}

function allValidChars(email)
{
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";
  for (var i=0; i < email.length; i++) {
    var letter = email.charAt(i).toLowerCase();
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}
function roundNumber(number,decimal_points)
{
	if(!decimal_points) return Math.round(number);
	if(number == 0) {
		var decimals = "";
		for(var i=0;i<decimal_points;i++) decimals += "0";
		return "0."+decimals;
	}

	var exponent = Math.pow(10,decimal_points);
	var num = Math.round((number * exponent)).toString();
	return num.slice(0,-1*decimal_points) + "." + num.slice(-1*decimal_points)
}
function download_pdf_common(host,uri)
{
	var vs = 'http://www.web2pdfconvert.com/convert.aspx?cURL=http://'+host+uri+'&outputmode=stream&allowactivex=yes&ref=form';
	
	document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/engine?curl=http://'+host+uri+'&outputmode=service">';

	show_processing();
	setTimeout('hide_processing()', 20000);
}
function download_pdf_stream(host,uri)
{
	
	document.getElementById('bw').innerHTML = '<iframe src="http://do.convertapi.com/web2pdf?curl=http://'+host+uri+'">';
	show_processing();
	setTimeout('hide_processing()', 10000);
}

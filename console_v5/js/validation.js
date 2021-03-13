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
	var re = /[<,>,",',%,&,*,;,^,(,)]/i;
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
				case "file":
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
				var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
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
				/*var res = obj.value.search(/^[^\.][A-Za-z0-9_\-\.]*[^\.]\@[^\.][A-Za-z0-9_\-\.]+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\.biz)|(\.us)|(\.bizz)|(\.coop)|(\.uk.com)|(\.co.uk)|(\..{2,2}))[ ]*$/gi);
				if(obj.length < 5 || res==-1)
		  		{
			 		var alertEmail = 'Enter a Valid Email Address';
					alert(alertEmail);
					obj.focus();
					obj.select();
		   			return false;
				 }*/
				 
			   	 
			
			if(!isValidEmailNew(obj.value))
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
function isValidEmailNew(email) {
    
    if (email.length==0) {  
        return false;
    }
    if (email.indexOf("@") < 1) { //  must contain @, and it must not be the first character
        return false;
    } else if (email.lastIndexOf(".") <= email.indexOf("@")) {  // last dot must be after the @
        return false;
    } else if (email.lastIndexOf("@.") == email.indexOf("@")) {  // should be some characters in between @ and last dot
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
function delete_confirm() {
	if(confirm("Are you sure that you want to delete this record?")) {
		return true;
	}
	return false;
}
function remove_confirm() {
	if(confirm("Are you sure that you want to remove this category from the current group?")) {
		return true;
	}
	return false;
}
function remove_PageGroup_confirm() {
	if(confirm("Are you sure that you want to remove this page  from the current Page group?")) {
		return true;
	}
	return false;
}
/* Function to show the processing diablog*/
function show_processing()
{
	if(document.getElementById('processing_div'))
	{
		document.getElementById('processing_div').style.display		= '';
		window.scroll (0,0);
	}
}
function hide_processing()
{
	if(document.getElementById('processing_div'))
	{
		document.getElementById('processing_div').style.display		= 'none';
	}
}
function show_request_alert(req)
{
	var msg;
	msg = 'Sorry!! Your session has been expired. Please Click on "Close Window" to login.';
	document.getElementById('ajax_error').innerHTML = '<div ><img src="images/error.gif" style="float:left;width:81px;"/></div><div style="float:left;width:319px;"><div style="border-bottom:1px solid #d6d6d6;width:319px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;padding-bottom:5px;">Error!! </div><div style="width:319px;font-family:Arial, Helvetica, sans-serif; font-size:12px;height:60px;padding-top:5px; overflow:auto">'+msg+'</div></div><div style="float:right;width:319px; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:normal;padding-top:20px;" align="right"><a href="#" onclick="close_ajaxerrordiv()" style="color: #CC0000;">Close window</a></div>';
	document.getElementById('ajax_error').style.display='';
	window.scroll (0,0);
}
function close_ajaxerrordiv()
{ 
	document.getElementById('ajax_error').style.display='none';
	if (window.location)
		window.location = window.location;
}
function checkBeforstatusUpdate(frm,msg)
{
	
	var atleastone = false;
	var len  = frm.elements.length;
	for (i=0;i<len;i++)
	{
		
		if (frm.elements[i].type== "checkbox" && frm.elements[i].name =='checkbox[]') 
		{
			if (frm.elements[i].checked)
				atleastone = true;
		}
	}
	if (atleastone==false)
	{
		alert(msg);
		return false;
	}
	show_processing();
}
	function select_all(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				if (frm.elements[i].checked==false)
					frm.elements[i].checked = true;
			}
		}
	}
	function select_none(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				frm.elements[i].checked=false;
			}
		}
	}
	function select_all_img(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				tdobj		= eval("document.getElementById('img_td_"+frm.elements[i].value+"')");
				if (frm.elements[i].checked==false)
				{
					if (tdobj)
					{
						tdobj.className 		= 'imagelistproducttabletd_sel';
					}
					frm.elements[i].checked = true;
				}
			}
		}
	}
	function select_none_img(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				frm.elements[i].checked	= false;
				tdobj					= eval("document.getElementById('img_td_"+frm.elements[i].value+"')");
				tdobj.className 		= 'imagelistproducttabletd';
			}
		}
	}
	
	function showImagePopup(fname,hostname)
	{
		win  = window.open( "http://"+hostname+"/console/popup_img.php?"+fname, "","resizable=1,HEIGHT=200,WIDTH=200,SCROLLBARS=yes");
		if (win) win.focus()
	}
	
	function showPopup(hostname,req,width,height)
	{
		win = window.open('http://'+hostname+'/console/help_html/index.php?f='+req,'console_help',"resizable=0,HEIGHT="+height+",WIDTH="+width+",SCROLLBARS=yes");
		if (win) win.focus()
	}
	
	
	/* 
	##################################################################################################
							Functions to handle Auto Tab on enter key
	##################################################################################################
	*/
	window.onload=function()
	{
		if (document.forms[0])
		{
			var coll=document.forms[0].elements;
			for(i=0;i<coll.length;i++)
			{
				if(document.all)
				{
						coll[i].attachEvent("onkeypress",tabOnEnter);
				}
				else
				{
						coll[i].addEventListener("keypress",tabOnEnter,false);
				}
			}
		}
	}

	function tabOnEnter(e)
	{
		if(document.all)
		{
			key=event.keyCode;
			inp=event.srcElement.name;
			typ= event.srcElement.type;
			idx=parseInt(event.srcElement.getAttribute("tabindex"));
		}
		else
		{
			key=e.which;
			inp=e.target.name;
			typ= e.target.type;
			idx=parseInt(e.target.getAttribute("tabindex"));
	}
	if(key==13)
	{
		if(typ!='submit' && typ!='textarea')
		{
			if(document.all)
			{
				event.cancelBubble=true;
			}
			else
			{
				e.stopPropagation();
			}
			var coll=document.forms[0].elements;
			for(i=0;i<coll.length;i++)
			{
				if(idx!="")
				{
					if(parseInt(coll[i].getAttribute("tabindex"))==idx+1)
					{
						if(coll[i])
						{
							if( coll[i].type != "hidden" && coll[i].style.display != "none"  && !coll[i].disabled ) 	
								coll[i].focus();
						}
					}
				}
				else
				{
					if(inp==coll[i].name)
					{
						nextel=i+1;
						if(coll[nextel])
						{
							if(coll[nextel])	
							{
								if(coll[nextel].type != "hidden" && coll[nextel].style.display != "none"  && !coll[nextel].disabled ) 
								{
									coll[nextel].focus();
								}
							}
						}
					}
				}
			}
			return false;
		}
	}
}


function checkdate(input,errorDesc){ //to check the date validation-- to comapare two dates ,
                                     //the code is written in the respective pages using this function.								 
var validformat=/^\d{2}\-\d{2}\-\d{4}$/ //Basic check for format validity
var returnval=false;
//alert("Please enter - "+errorDesc);
if(input.value == ''){
alert("Please enter - "+errorDesc);
input.select();
return returnval;
}
if (!validformat.test(input.value)){
alert("Invalid date format in "+errorDesc);
input.select();
return returnval;
}
else{ //Detailed check for valid date ranges
var dayfield=input.value.split("-")[0]
var monthfield=input.value.split("-")[1]
var yearfield=input.value.split("-")[2]
var dayobj = new Date(yearfield, monthfield-1, dayfield)
if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
alert("Invalid Day, Month, or Year range detected in "+errorDesc+". Please correct and submit again.")
else
returnval=true
}
if (returnval==false) input.select()
return returnval
} 


function compareDates(start_date,Start_Desc,end_date,End_Desc){
valstrtdate = checkdate(start_date,Start_Desc); 
var err = 0;
	if(valstrtdate ){
	valenddate = checkdate(end_date,End_Desc);
		if(valenddate){
			var arrstdate 	= start_date.value.split('-'); 
			var arrenddate 	= end_date.value.split('-'); 
			if(arrstdate[0].substr(0,1)==0)
				arrstdate[0] = arrstdate[0].substr(1,1);
			var st_day 		= parseInt(arrstdate[0]);
			if(arrstdate[1].substr(0,1)==0)
				arrstdate[1] = arrstdate[1].substr(1,1);
			var st_mon 		= parseInt(arrstdate[1]);
			if(arrstdate[2].substr(0,1)==0)
				arrstdate[2] = arrstdate[2].substr(1,1);
			var st_yr 		= parseInt(arrstdate[2]);
			if(arrenddate[0].substr(0,1)==0)
				arrenddate[0] = arrenddate[0].substr(1,1);
			var end_day 	= parseInt(arrenddate[0]);
			if(arrenddate[1].substr(0,1)==0)
				arrenddate[1] = arrenddate[1].substr(1,1);
			var end_mon 	= parseInt(arrenddate[1]);
			if(arrenddate[2].substr(0,1)==0)
				arrenddate[2] = arrenddate[2].substr(1,1);
			var end_yr 		= parseInt(arrenddate[2]);
			/*var mystdate 	= new Date(st_yr,st_mon,st_day);
			var myendate 	= new Date(end_yr,end_mon,end_day);
			
			var sttime		= mystdate.getTime();
			var endtime		= myendate.getTime();*/
			if (end_yr == st_yr)
			{
				if(end_mon<st_mon)
					err = 1;
				else if (end_mon==st_mon)
				{ 
				if(end_day<st_day)	
						err = 1;
				}
			}
			else if (end_yr<st_yr)
				err = 1;
			
			if(err==1)
			{
				alert("Invalid Date Range!\nEnd Date should be after Start Date!")
				end_date.select();
				return false;
			}
			else
				return true;
		}
		return false;
	}
	return false;
}
function compareDates_giftvoucher(start_date,Start_Desc,end_date,End_Desc){
valstrtdate = checkdate(start_date,Start_Desc); 
var err = 0;	
	if(valstrtdate ){
	valenddate = checkdate(end_date,End_Desc);
		if(valenddate){
		/*var startdate = start_date.value.replace(/-/g, "/");
		var enddate = end_date.value.replace(/-/g, "/");
		if (Date.parse(startdate) > Date.parse(enddate)) {
			alert("Invalid Date Range!\ Expires on Date should be after Active On Date!")
			end_date.select();
			return false;
			}else{
			return true;
			}*/
			var arrstdate 	= start_date.value.split('-'); 
			var arrenddate 	= end_date.value.split('-');
			if(arrstdate[0].substr(0,1)==0)
				arrstdate[0] = arrstdate[0].substr(1,1);
			var st_day 		= parseInt(arrstdate[0]);
			if(arrstdate[1].substr(0,1)==0)
				arrstdate[1] = arrstdate[1].substr(1,1);
			var st_mon 		= parseInt(arrstdate[1]);
			if(arrstdate[2].substr(0,1)==0)
				arrstdate[2] = arrstdate[2].substr(1,1);
			var st_yr 		= parseInt(arrstdate[2]);
			if(arrenddate[0].substr(0,1)==0)
				arrenddate[0] = arrenddate[0].substr(1,1);
			var end_day 	= parseInt(arrenddate[0]);
			if(arrenddate[1].substr(0,1)==0)
				arrenddate[1] = arrenddate[1].substr(1,1);
			var end_mon 	= parseInt(arrenddate[1]);
			if(arrenddate[2].substr(0,1)==0)
				arrenddate[2] = arrenddate[2].substr(1,1);
			var end_yr 		= parseInt(arrenddate[2]);
			
			if (end_yr == st_yr)
			{
				if(end_mon<st_mon)
					err = 1;
				else if (end_mon==st_mon)
				{
					if(end_day<st_day)	
						err = 1;
				}
			}
			else if (end_yr<st_yr)
				err = 1;
			if(err==1)
			{
				alert("Invalid Date Range!\nEnd Date should be after Start Date!")
				end_date.select();
				return false;
			}
			else
				return true;
		}
		return false;
	}
	return false;
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
function perc1()
{
	 a = document.discountcalc_form.a.value/100;
	 b = a*document.discountcalc_form.b.value;
	 document.discountcalc_form.total1.value = b
 }
function perc2() 
{
	 a = document.discountcalc_form.c.value;
	 b = document.discountcalc_form.d.value;
	 c = a/b;
	 d = c*100;
	 document.discountcalc_form.total2.value = d;
 }
 function show_discount_calculator()
 {
	if (document.getElementById('disccalc_div'))
		document.getElementById('disccalc_div').style.display = '';
	hideSelect();	
 }
 function hide_discount_calculator()
 {
	if (document.getElementById('disccalc_div'))
		document.getElementById('disccalc_div').style.display = 'none';
	showSelect()	;
 }
function getTop(extra)
{
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
		return scrollTop+extra;
}
function getLeft()
{
	var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft || 0;
	return scrollLeft;
}


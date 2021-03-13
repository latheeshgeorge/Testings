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
function delete_confirm() {
	if(confirm("Are you sure that you want to delete this record?")) {
		return true;
	}
	return false;
}
function select_all(objname,frm,mod)
{
	for (i=0;i<frm.elements.length;i++)
	{
		if(frm.elements[i].name==objname && frm.elements[i].type =='checkbox')
		{
			/*if (frm.elements[i].checked==true)
				frm.elements[i].checked= false;
			else*/
				frm.elements[i].checked= mod;
		}
	}
}

function isNumeric(frm)

{
   
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
  
   sText=frm.records_per_page.value;
  
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
	  if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
	  if(IsNumber==false)
	  {
		 alert('Invalid value');
		 return false;  
	  }
	  else
	  {
	  	return true;
	  }
  
   
   }

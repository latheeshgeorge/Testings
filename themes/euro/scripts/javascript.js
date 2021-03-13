// show / hide procession div 

function show_processing()

{

	document.getElementById('processing_div').style.display		= '';

	window.scroll (0,0);

}

function hide_processing()

{

	document.getElementById('processing_div').style.display		= 'none';

}

// Function to submit the drop down box with the selected option

function handle_dropdownval_sel(daTa)

{

	if (daTa!='')

		window.location = daTa; 

}

// Function to submit the drop down box with the selected option

function handle_categorydetailsdropdownval_sel(url,sortby,sortorder,page)

{

	sortbyval = eval('document.getElementById("'+sortby+'")');

	sortorderval = eval('document.getElementById("'+sortorder+'")');

	pageval = eval('document.getElementById("'+page+'")');

	var loc = url+'?catdet_pg=0&catdet_sortby='+sortbyval.value+'&catdet_sortorder='+sortorderval.value+'&catdet_prodperpage='+pageval.value;

	window.location = loc;

}



// Function to submit the drop down box with the selected option

function handle_shopdetailsdropdownval_sel(url,sortby,sortorder,page)

{

	sortbyval = eval('document.getElementById("'+sortby+'")');

	sortorderval = eval('document.getElementById("'+sortorder+'")');

	pageval = eval('document.getElementById("'+page+'")');

	var loc = url+'?shopdet_pg=0&shopdet_sortby='+sortbyval.value+'&shopdet_sortorder='+sortorderval.value+'&shopdet_prodperpage='+pageval.value;

	window.location = loc;

}



// Function to add to the compare lsit for the products

function handle_addtoCompare()

{	

		

		var str = '';

		var c = document.getElementsByTagName("input");

		for(i=0;i<c.length;i++)

		{

			

			if(c[i].type=='checkbox')

			{

				if (c[i].name.substr(0,12)=='compare_ids_')

				{

					if (c[i].checked)

					{

						if (str=='')

							str = c[i].value;

						else

							str = str + '~'+c[i].value;

					}		

				}	

			}

		}

		document.add_to_compare.compare_products.value = str;

		document.add_to_compare.submit();

		//alert(str);

}

// Function to add to the compare lsit for the products

function addtoCompare(prod_id)

{	

		

		/*var str = '';

		var c = document.getElementsByTagName("input");

		for(i=0;i<c.length;i++)

		{

			

			if(c[i].type=='checkbox')

			{

				if (c[i].name.substr(0,12)=='compare_ids_')

				{

					if (c[i].checked)

					{

						if (str=='')

							str = c[i].value;

						else

							str = str + '~'+c[i].value;

					}		

				}	

			}

		}*/

		document.frm_forcesubmit.compare_products.value = prod_id;

		document.frm_forcesubmit.submit();

		//alert(str);

}



// Function to submit the drop down box with the selected option

function handle_searchcatdropdownval_sel(url,sortby,sortorder,page)

{
	sortbyval = eval('document.getElementById("'+sortby+'")');

	sortorderval = eval('document.getElementById("'+sortorder+'")');

	pageval = eval('document.getElementById("'+page+'")');
	document.frm_forcesubmit.action='http://'+url+'/search.html?search_pg=0&searchcat_sortby='+sortbyval.value+'&searchcat_sortorder='+sortorderval.value+'&searchcat_perpage='+pageval.value;
    document.frm_forcesubmit.submit();
}

function handle_searchdropdownval_sel(url,sortby,sortorder,page)

{
	sortbyval = eval('document.getElementById("'+sortby+'")');

	sortorderval = eval('document.getElementById("'+sortorder+'")');

	pageval = eval('document.getElementById("'+page+'")');
	document.frm_forcesubmit.action='http://'+url+'/search.html?search_pg=0&search_sortby='+sortbyval.value+'&search_sortorder='+sortorderval.value+'&search_prodperpage='+pageval.value;
    document.frm_forcesubmit.submit();
}
// Function to decide whether the details of gift wrap is to be displayed or not

function handle_giftwrapreq(obj,divobj)

{

	if (obj.checked)

		divobj.style.display = '';

	else

		divobj.style.display = 'none';

}

// Function to decide whether the details of gift wrap message is to be displayed or not

function handle_giftwrapmessagereq(obj,divobj)

{

	if (obj.checked)

		divobj.style.display = '';

	else

		divobj.style.display = 'none';

}

// Function to decide whether to show or hide the image display section for giftwrap items

function handle_giftwrapimagediv(imgobj,host,divobj,typ,frm)

{

	var curmod = divobj.style.display;

	hideall_giftwrapimagediv(imgobj,host,frm,typ);	

	if (curmod=='')

	{

		imgobj.src = 'http://'+host+'/images/'+host+'/site_images/giftplus.gif';

	}

	else

	{

		imgobj.src = 'http://'+host+'/images/'+host+'/site_images/giftminus.gif';

		divobj.style.display = '';

	}

}

function hideall_giftwrapimagediv(imgobj,host,frm,typ)

{	

	var len = typ.length;

	var imgtyp = imgobj.id.substr(0,11);

	var allDivs = document.getElementsByTagName('div'); /*  get all divs*/

	var allImgs = document.getElementsByTagName('img'); /*  get all images*/

	

	for(i=0;i<allImgs.length; i++)

	{

	  var aIMG = allImgs[i];

	  var imID = aIMG.id;

	  if (imID.indexOf(imgtyp)==0)

	  {

		 aIMG.src = 'http://'+host+'/images/'+host+'/site_images/giftplus.gif';

	  }

	}

	for(i=0;i<allDivs.length; i++)

	{

	  var aDiv = allDivs[i];

	  var sID = aDiv.id;

	  if (sID.indexOf(typ)==0)

	  {

		aDiv.style.display='none';

	  }

	}

}

/* Function to validate the fields in newsletter*/

function newsletter_validation(frm)
{
	var atleastone		= false;	
	fieldRequired		= new Array();
	fieldDescription	= new Array();
	var i=0;
	if(document.getElementById('newsletter_title'))
	{
		fieldRequired[i] 	= 'newsletter_title';
		fieldDescription[i] = 'Title';
		i++;
	}
	if(document.getElementById('newsletter_name'))
	{
		fieldRequired[i] 	= 'newsletter_name';
		fieldDescription[i] = 'Name';
		i++;
	}
	if(document.getElementById('newsletter_email'))
	{
		fieldRequired[i] 	= 'newsletter_email';
		fieldDescription[i] = 'Email Id';
		i++;
	}
	if(document.getElementById('newsletter_Vimg'))
	{
		fieldRequired[i] 	= 'newsletter_Vimg';
		fieldDescription[i] = 'Image Verification Code';
		i++;	
	}
	fieldEmail 			= Array('newsletter_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(document.getElementById('sel_newsletter_group'))
		{
			obj = document.getElementById('sel_newsletter_group');
			for(i=0;i<obj.options.length;i++)
			{
				if (obj.options[i].selected == true)
					atleastone = true;
			}
			if (atleastone == false)
			{
				alert('Please select the newsletter groups');
				return false;
			}
			else
				return true;
		}
	}
	else
		return false;
}

/* Function to validate the login */

function validate_login(frm)

{

	fieldRequired 		= Array('custlogin_uname','custlogin_pass');

	fieldDescription 	= Array('Username','Password');

	fieldEmail 			= Array();

	fieldConfirm 		= Array();

	fieldConfirmDesc  	= Array();

	fieldNumeric 		= Array();

	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))

		return true;

	else

	{

		return false;

	}

}

/* Function to validate the survey */

function validate_survey(frm)

{

	var atleastone = false;

	for(i=0;i<frm.elements.length;i++)

	{

		if (frm.elements[i].type =='radio')

		{

			if(frm.elements[i].name =='survey_opt' && frm.elements[i].checked==true)

			{

					atleastone = true;

			}

		}

	}

	if (atleastone ==false)

	{

		alert('Please select your option for the survey');

		return false;

	}

	else

		return true;

}



/* Function to validate the small voucher */

function validate_smallvoucher (frm,imgvarname)

{

	if (imgvarname=='')

	{

		fieldRequired 		= Array('cart_promotionalcode');

		fieldDescription 	= Array('Gift Voucher Code');

	}

	else

	{

		fieldRequired 		= Array('cart_promotionalcode',imgvarname);

		fieldDescription 	= Array('Gift Voucher Code','Image Verification Code');

	}

	fieldEmail 			= Array();

	fieldConfirm 		= Array();

	fieldConfirmDesc  	= Array();

	fieldNumeric 		= Array();

	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))

		return true;

	else

	{

		return false;

	}

}



/* Function to handle the submission of addtocart or preorder */

function submit_form(frm,req,prodid)

{	

	obj = eval('document.getElementById("'+frm+'")');
	if (obj.fproduct_id)
		obj.fproduct_id.value = prodid;
	if (obj.fpurpose)
		obj.fpurpose.value = req;

	if (obj.qty)
	{
		var qty = obj.qty.value;
		if(qty!='')
		{
			if (isNaN(qty))	qty = 1;
			obj.qty.value = qty;
		}	
	}
	obj.submit();
}





/*Function to toggle the registration page according to account type*/

function showAccountTypeDetails(accountType){

	if(accountType.value=='business'){

	document.getElementById("companydetails").style.display='';

	}else{

	document.getElementById("companydetails").style.display='none';

	}

}

/* Function to submit the page with the given value for fpurpose*/

function handle_form_submit(frm,fp,ref)

{

	if (ref!='')

		frm.action = frm.action+'?nrm=1'+ref;

	if(frm.fpurpose)

		frm.fpurpose.value = fp;

	if(frm.hold_section)

		frm.hold_section.value = ref;	
	show_processing();
	frm.submit();

}

/* Function to submit the page with the given value for fpurpose*/

function handle_checkout_submit(host,mod)

{

	var url 			= 'http://'+host+'/checkout.html';

	var error_exists 	= false;

	var paymethod		= false;

	var paytype			= false;

	if(document.getElementById('frm_cart'))

	{

		var del_msg		= document.getElementById('del_msg_disp').value;

		var pay_msg		= document.getElementById('paysel_msg_disp').value;

		var gate_msg		= document.getElementById('gate_msg_disp').value;

		/* Section to decide whether to validate the various fields such as delivery, payment method and type*/ 

			/* case of delivery location */	

			if(document.frm_cart.cart_deliverylocation)

			{

				if(document.frm_cart.cart_deliverylocation.value==0)

				{

					alert(del_msg);

					document.frm_cart.cart_deliverylocation.focus();

					error_exists = true;

				}

			}

			if (error_exists==false && mod==0)

			{

				/* case of payment type */

				for(i=0;i<document.frm_cart.elements.length;i++)

				{

					if(document.frm_cart.elements[i].name.substr(0,12)=='cart_paytype')

					{

						if(document.frm_cart.elements[i].type=='radio')	

						{

							if(document.frm_cart.elements[i].checked==true)		

								paytype = true;

						}

						else if(document.frm_cart.elements[i].type=='hidden')

						{

							if(document.frm_cart.elements[i].value!='')		

								paytype = true;

						}

						else if(document.frm_cart.elements[i].type=='select-one')

						{

							if(document.frm_cart.elements[i].value!='')		

								paytype = true;

						}

					}

					if(document.frm_cart.elements[i].name.substr(0,14)=='cart_paymethod')

					{

						if(document.frm_cart.elements[i].type=='radio')	

						{

							if(document.frm_cart.elements[i].checked==true)		

								paymethod = true;

						}

						else if(document.frm_cart.elements[i].type=='hidden')

						{

							if(document.frm_cart.elements[i].value!='')		

								paymethod = true;

						}

					}

				}

				if (paytype==false)

				{

					error_exists = true;

					alert(pay_msg);

				}

				else

				{

					if (document.getElementById('cc_req_indicator'))

					{

						if(document.getElementById('cc_req_indicator').value==1)

						{

							if (paymethod==false)

							{

								error_exists = true;

								alert(gate_msg);

							}	

						}

					}

				}

			}

		if(error_exists==false)

		{

			if(mod==0)

			{

				if(document.getElementById('continue_checkout'))

				{

					show_wait_button(document.getElementById('continue_checkout'),'Please Wait...');

				}
				document.getElementById('frm_cart').action 						= url+'?nrm=1';
				document.getElementById('frm_cart').cart_mod.value 		= 'show_checkout';
				document.getElementById('frm_cart').submit();

			}

			else

			{

				/*if(document.getElementById('continue_checkout'))

				{

					show_wait_button(document.getElementById('continue_checkout'),'Please Wait...');

				}*/	

				return true;	

			}	

		}

		else

			return false;

	}

	else

	{

		window.location = url;

	}

}

/* Function to delete an item in cart*/

function confirm_message(msg)

{

	if (confirm(msg))

		return true;	

	else

		return false;

}

function showImagePopup(fname,hostname,themename)

{

	/*window.open( "http://"+hostname+"/themes/"+themename+"/html/popup_img.php?f="+fname+'&h='+hostname, "","resizable=1,HEIGHT=200,WIDTH=200,SCROLLBARS=yes");*/

	window.open( "http://"+hostname+"/themes/"+themename+"/html/popup_img.php?f="+fname+'&h='+hostname, "","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150");

	

}

function link_submit(tabid,detimgid,url,cat_anchor)

{

	if (tabid=='')

		tabid = 0;

	if (detimgid=='')

		detimgid = 0;

	if (url!='')

	{

		if (tabid !='' && cat_anchor)

			url = url+'#protabs';

		document.frm_forcesubmit.action 			= url;

	}	

	document.frm_forcesubmit.prodimgdet.value 		= detimgid;

	document.frm_forcesubmit.prod_curtab.value 		= tabid;

	document.frm_forcesubmit.submit();

}

function handle_deliveryaddress_change(obj)

{

	if(obj.value=='N') /* Delivery address Not Same as billing address */

	{

		/* Making the delivery address blank */

		if (document.getElementById('checkoutdelivery_title'))

		{

			document.getElementById('checkoutdelivery_title').value	= 'Mr.';

		}

		if (document.getElementById('checkout_comp_name') && document.getElementById('checkoutdelivery_comp_name'))

		{

			document.getElementById('checkoutdelivery_comp_name').value	= '';

		}

		if (document.getElementById('checkout_fname') && document.getElementById('checkoutdelivery_fname'))

		{

			document.getElementById('checkoutdelivery_fname').value	= '';

		}

		if (document.getElementById('checkout_mname') && document.getElementById('checkoutdelivery_mname'))

		{

			document.getElementById('checkoutdelivery_mname').value	= '';

		}

		if (document.getElementById('checkout_surname') && document.getElementById('checkoutdelivery_surname'))

		{

			document.getElementById('checkoutdelivery_surname').value	= '';

		}

		if (document.getElementById('checkout_building') && document.getElementById('checkoutdelivery_building'))

		{

			document.getElementById('checkoutdelivery_building').value	= '';

		}

		if (document.getElementById('checkout_street') && document.getElementById('checkoutdelivery_street'))

		{

			document.getElementById('checkoutdelivery_street').value	= '';

		}

		if (document.getElementById('checkout_city') && document.getElementById('checkoutdelivery_city'))

		{

			document.getElementById('checkoutdelivery_city').value	= '';

		}

		if (document.getElementById('checkout_state') && document.getElementById('checkoutdelivery_state'))

		{

			document.getElementById('checkoutdelivery_state').value	= '';

		}

		if (document.getElementById('checkout_country') && document.getElementById('checkoutdelivery_country'))

		{

			document.getElementById('checkoutdelivery_country').value	= '';

		}

		if (document.getElementById('checkout_zipcode') && document.getElementById('checkoutdelivery_zipcode'))

		{

			document.getElementById('checkoutdelivery_zipcode').value	= '';

		}

		if (document.getElementById('checkout_phone') && document.getElementById('checkoutdelivery_phone'))

		{

			document.getElementById('checkoutdelivery_phone').value	= '';

		}

		if (document.getElementById('checkout_mobile') && document.getElementById('checkoutdelivery_mobile'))

		{

			document.getElementById('checkoutdelivery_mobile').value	= '';

		}

		if (document.getElementById('checkout_fax') && document.getElementById('checkoutdelivery_fax'))

		{

			document.getElementById('checkoutdelivery_fax').value	= '';

		}

		if (document.getElementById('checkout_email') && document.getElementById('checkoutdelivery_email'))

		{

			document.getElementById('checkoutdelivery_email').value	= '';

		}	

	}

	else /* Delivery address same as that of billing address */ 

	{

		/* Making the values set for the billing address fields to respective field in delivery address */

		if (document.getElementById('checkout_title') && document.getElementById('checkoutdelivery_title'))

		{

			document.getElementById('checkoutdelivery_title').value	= document.getElementById('checkout_title').value;

		}

		if (document.getElementById('checkout_comp_name') && document.getElementById('checkoutdelivery_comp_name'))

		{

			document.getElementById('checkoutdelivery_comp_name').value	= document.getElementById('checkout_comp_name').value;

		}

		if (document.getElementById('checkout_fname') && document.getElementById('checkoutdelivery_fname'))

		{

			document.getElementById('checkoutdelivery_fname').value	= document.getElementById('checkout_fname').value;

		}

		if (document.getElementById('checkout_mname') && document.getElementById('checkoutdelivery_mname'))

		{

			document.getElementById('checkoutdelivery_mname').value	= document.getElementById('checkout_mname').value;

		}

		if (document.getElementById('checkout_surname') && document.getElementById('checkoutdelivery_surname'))

		{

			document.getElementById('checkoutdelivery_surname').value	= document.getElementById('checkout_surname').value;

		}

		if (document.getElementById('checkout_building') && document.getElementById('checkoutdelivery_building'))

		{

			document.getElementById('checkoutdelivery_building').value	= document.getElementById('checkout_building').value;

		}

		if (document.getElementById('checkout_street') && document.getElementById('checkoutdelivery_street'))

		{

			document.getElementById('checkoutdelivery_street').value	= document.getElementById('checkout_street').value;

		}

		if (document.getElementById('checkout_city') && document.getElementById('checkoutdelivery_city'))

		{

			document.getElementById('checkoutdelivery_city').value	= document.getElementById('checkout_city').value;

		}

		if (document.getElementById('checkout_state') && document.getElementById('checkoutdelivery_state'))

		{

			document.getElementById('checkoutdelivery_state').value	= document.getElementById('checkout_state').value;

		}

		if (document.getElementById('checkout_country') && document.getElementById('checkoutdelivery_country'))

		{

			document.getElementById('checkoutdelivery_country').value	= document.getElementById('checkout_country').value;

		}

		if (document.getElementById('checkout_zipcode') && document.getElementById('checkoutdelivery_zipcode'))

		{

			document.getElementById('checkoutdelivery_zipcode').value	= document.getElementById('checkout_zipcode').value;

		}

		if (document.getElementById('checkout_phone') && document.getElementById('checkoutdelivery_phone'))

		{

			document.getElementById('checkoutdelivery_phone').value	= document.getElementById('checkout_phone').value;

		}

		if (document.getElementById('checkout_mobile') && document.getElementById('checkoutdelivery_mobile'))

		{

			document.getElementById('checkoutdelivery_mobile').value	= document.getElementById('checkout_mobile').value;

		}

		if (document.getElementById('checkout_fax') && document.getElementById('checkoutdelivery_fax'))

		{

			document.getElementById('checkoutdelivery_fax').value	= document.getElementById('checkout_fax').value;

		}

		if (document.getElementById('checkout_email') && document.getElementById('checkoutdelivery_email'))

		{

			document.getElementById('checkoutdelivery_email').value	= document.getElementById('checkout_email').value;

		}

	}

}

/* Function to be triggered when selecting the credit card type*/

function sel_credit_card(obj)

{

	if (obj.value!='')

	{

		objarr = obj.value.split('_');

		if(objarr.length==4) /* if the value splitted to exactly 4 elements*/

		{

			var key 		= objarr[0];

			var issuereq 	= objarr[1];

			var seccount 	= objarr[2];

			var cc_count 	= objarr[3];

			if (issuereq==1)

			{

				document.frm_checkout.checkoutpay_issuenumber.className = 'inputissue_normal';

				document.frm_checkout.checkoutpay_issuenumber.disabled	= false;

			}

			else

			{

				document.frm_checkout.checkoutpay_issuenumber.className = 'inputissue_disabled';	

				document.frm_checkout.checkoutpay_issuenumber.disabled	= true;

			}

		}

	}

}

/* Function to go back to cart page from checkout page */

function gobackto_cart(hostname)
{
	if (document.frm_checkout)
	{
		/*document.frm_checkout.action = 'http://'+hostname+'/cart.html';	
		document.frm_checkout.action = 'cart_submit.php?bsessid='+hostname;*/
		document.frm_checkout.action = 'cart_submit.php';
		document.frm_checkout.save_checkoutdetails.value = 1;	
		document.frm_checkout.cart_mod.value = 'show_cart';
		document.frm_checkout.submit();
	}
	else
		window.location = 'http://'+hostname+'/cart.html';
}
function buy_combo()

{

	var pdt_ids = '';

	var quan_pdt = '';

	var c = document.getElementsByTagName("input");

		for(i=0;i<c.length;i++)

		{

			if(c[i].type=='text' ||c[i].type=='hidden' )

			{

				if (c[i].name.substr(0,4)=='qty_')

				{

				if(quan_pdt!='')

				quan_pdt +='~';

				quan_pdt += c[i].value;

				if(pdt_ids!='')

				pdt_ids +='~';

				pdt_ids += c[i].name.substr(4);

				}

			}

		}

	document.buyall_combo.product_qtys.value = quan_pdt;

	document.buyall_combo.product_ids.value = pdt_ids;

}

function showstate(cid)

{ 

	arrval = eval('countryval'+cid);

	arrkey = eval('countrykey'+cid);

	for(i=document.frm_registration.cbo_state.options.length-1;i>0;i--)

	{

		 document.frm_registration.cbo_state.remove(i);

	}

	for(i=0;i<arrkey.length;i++)

	{

		var lgth = document.frm_registration.cbo_state.options.length;

		document.frm_registration.cbo_state.options[lgth]= new Option(arrval[i],arrkey[i]);

	}

}

function ord_cancel(frm,val) {

	document.getElementById('cancelId').style.display = '';

	document.getElementById('cancelBut').style.display='none';

}

function cancelSubmit(frm,value) {

	if(frm.txt_cancel.value=="") {

		alert("Please Select Reason For Cancellation");

		frm.txt_cancel.focus();

	} else {

		frm.hid_ordid.value=value;

		alert(frm.hid_ordid.value);

		frm.submit();

	}

}







function handle_expansionall(imgobj,mod,sitename)

{

	var src 			= imgobj.src;

	var retindx 		= src.search('sel_tab_yes.gif');

	switch(mod)

	{

		case 'bill': /* Case of billing address*/

			if (retindx!=-1)

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_no.gif';

				if(document.getElementById('billDetails'))

					document.getElementById('billDetails').style.display = 'none';

			}	

			else

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_yes.gif';

				//imgobj.src = 'images/sel_tab_no.gif';

				if(document.getElementById('billDetails'))

					document.getElementById('billDetails').style.display = '';

			}	

		break;

		case 'delivery':

			if (retindx!=-1)

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_no.gif';

				if(document.getElementById('deliverDetails'))

					document.getElementById('deliverDetails').style.display = 'none';

			}	

			else

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_yes.gif';

				//imgobj.src = 'images/sel_tab_no.gif';

				if(document.getElementById('deliverDetails'))

					document.getElementById('deliverDetails').style.display = '';

			}	

		break;

		case 'enqDetails':

			if (retindx!=-1)

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_no.gif';

				if(document.getElementById('enqDetails'))

					document.getElementById('enqDetails').style.display = 'none';

			}	

			else

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_yes.gif';

				//imgobj.src = 'images/sel_tab_no.gif';

				if(document.getElementById('enqDetails'))

					document.getElementById('enqDetails').style.display = '';

			}	

		break;
		case 'downloads_table':

			if (retindx!=-1)

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_no.gif';

				if(document.getElementById('downloads_table'))

					document.getElementById('downloads_table').style.display = 'none';

			}	

			else

			{

				imgobj.src = 'http://'+sitename+'/images/'+sitename+'/site_images/sel_tab_yes.gif';

				//imgobj.src = 'images/sel_tab_no.gif';

				if(document.getElementById('downloads_table'))

					document.getElementById('downloads_table').style.display = '';

			}	

		break;

	}

}

function view_post(frm,cnt) {

	var value = 'view_'+cnt;

	if(document.getElementById('view_'+cnt).style.display=='') { 

		document.getElementById('view_'+cnt).style.display='none';

	} else {

		document.getElementById('view_'+cnt).style.display='';

	}

}

function handle_proddetail_variable(mod)

{

	if (mod=='var') /* case if variable tab clicked */

	{

		if (document.getElementById('proddet_var_table'))

			document.getElementById('proddet_var_table').style.display='';

		if (document.getElementById('proddet_label_table'))

			document.getElementById('proddet_label_table').style.display='none';

		if(document.getElementById('var_li'))

			document.getElementById('var_li').className = 'variableselected';

		if(document.getElementById('label_li'))

			document.getElementById('label_li').className = '';

		

	}

	else /* case if overview tab clicked*/

	{

		if (document.getElementById('proddet_var_table'))

			document.getElementById('proddet_var_table').style.display='none';

		if (document.getElementById('proddet_label_table'))

			document.getElementById('proddet_label_table').style.display='';

		if(document.getElementById('var_li'))

			document.getElementById('var_li').className = '';

		if(document.getElementById('label_li'))

			document.getElementById('label_li').className = 'variableselected';	

	}

}
function show_wait_button(obj,txt)

{

	obj.value=txt;

	obj.style.disabled=true;

}
function handle_downloadhistory(id)
{
	trobj 	= eval("document.getElementById('downloadhistory_tr_"+id+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
	}
	else
	{
		trobj.style.display ='';
	}
}
function handle_instocknotification(prodid,host)
{
	var url = 'http://'+host+'/stocknotify'+prodid+'.html';
	hide_instockmsg_div();
	document.frm_proddetails.action = url;
	document.frm_proddetails.submit();
}
function validate_stocknotify(frm)
{
	fieldRequired 			= Array('stock_email');
	fieldDescription 		= Array('Email Id');
	fieldEmail 				= Array('stock_email');
	fieldConfirm 			= Array();
	fieldConfirmDesc  		= Array();
	fieldNumeric 			= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
			frm.fpurpose.value='Prod_Add_Stockenquiry';
			frm.submit();
	}
	else
		return false;
}
function show_instockmsg_div()
{
	if(document.getElementById('instockmsg_div'))
	{
		document.getElementById('instockmsg_div').style.display='';
	}
}
function hide_instockmsg_div()
{
		if(document.getElementById('instockmsg_div'))
		document.getElementById('instockmsg_div').style.display='none';	
		if(document.getElementById('alert_main_div'))
		document.getElementById('alert_main_div').style.display='none';	
		
}
function handle_accountdetails(id)
{
	obj 	= eval("document.getElementById('accpaydet_"+id+"')");
	objdet= eval("document.getElementById('accpaydetdiv_"+id+"')");
	if (objdet.style.display =='none')
	{
		obj .innerHTML = '<strong>Hide details</strong> ';
		objdet.style.display = '';
	}
	else
	{
		obj .innerHTML = '<strong>Show details</strong> ';
		objdet.style.display = 'none';
	}
}
function product_enterkey(frm,prodid)
{
	var qty = frm.qty.value;
	if(qty!='')
	{
		if (isNaN(qty))	qty = 1;
		frm.qty.value = qty;
	}
	if(frm.prod_list_submit_common.value!='')
	{
		frm.fpurpose.value 		= frm.prod_list_submit_common.value;
		frm.fproduct_id.value	= prodid;
		return true;
	}
	else
	{
		var qty = frm.qty.value;
		if(qty!='')
		{
			if (isNaN(qty))	qty = 1;
			/*window.location 	= produrl+'?qty='+qty;*/
			frm.action = frm.fproduct_url.value;
			frm.submit();
		}
		return false;
	}
}

function submit_to_det_form(frm)
{
	obj = eval('document.'+frm);	
	var qty = obj.qty.value;
	if(qty!='')
	{
		if (isNaN(qty))	qty = 1;
		obj.qty.value = qty;
	}
	obj.action = obj.fproduct_url.value;
	obj.submit();
}
function prod_detail_submit(frm)
{
	if (frm.fpurpose.value!='')
		return true;
	else
		return false;
}
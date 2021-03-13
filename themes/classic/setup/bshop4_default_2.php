<?PHP 
if($frompreview != 1) {
	
	require("../../../functions/functions.php");
	require("../../../includes/urls.php");
	require("../../../includes/session.php");
	include("../../../config.php");
	
}	
$cssfile = $_GET['cssfile'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title> 
<link href="<? echo SITE_URL."/images/".$ecom_hostname."/setup/css/".$cssfile.""; ?>" media="screen" type="text/css" rel="stylesheet" />
</head>

<body style="padding:0px;"> 
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="main" >
  <tr >
    <td width="19%" height="50px" align="left" valign="middle"><img src="images/logo.gif" width="183" height="59" /></td>
    <td width="81%" class="topheader">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" class="maintopsearchtd">
	<div align="left" class="topsearch">
					  <table border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td class="searchfont_top">Search</td>
						  <td><input name="quick_search" type="text" class="inputA" id="quick_search"  value=""/></td>
						  <td><input name="button_submit_search" type="button" class="buttongray" id="button3" value="Go"  /></td>
						  <td class="searchfont" nowrap="nowrap"><a class="advancedsearch" title="Advanced Search >>">Advanced Search >></a></td>
						</tr>
					  </table>
   </div>
   </td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="maintoplink">
	<ul class="staticlink"> <li><h1> <a href="#" class="static">Home </a> </h1></li>
	 <li><h1><a href="#" class="static">Sitemap </a> </h1></li>
	 <li><h1> <a href="#" class="static">Help </a></h1></li> </ul></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td  align="left" valign="top" class="compleftcontainer">
		<ul class="category">
																								<li class="categoryheader">Category Group </li>
			  <li><h1><a href="#" class="catelink" title="Category Test DK">HP Notebooks</a></h1></li>

																							 <li><h1><a href="#" class="catelink" title="HCL LAPTOPS">Dell Laptops</a></h1></li>
                                                                                              <li><h1><a href="#" class="catelink" title="HCL LAPTOPS">IBM Thinkpads</a></h1></li>
                                                                                              <li><h1><a href="#" class="catelink" title="HCL LAPTOPS">Compaq Presario</a></h1></li>
			</ul>							
      
<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
									 											  <tr>
												<td class="shopbybrandheader">ShopGroupGreen</td>
											  </tr>
									  									  <tr>
										<td class="shopbybrandheader">
										  <label>

											<select name="prodshopgroup_12" >
												<option value="">-- Select -- </option>
												<option value="">ProductD</option>
												<option value="">ProductA</option>
										    </select>
										  </label>										</td>
									  </tr>
									</table>
		<table width="90%" border="0" cellpadding="0" cellspacing="0" class="surveytable">
          <tr>
            <td colspan="2" align="left" valign="middle" class="surveytableheader">Survey</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="middle" class="surveytablequst" >Which is Best Designed Laptop?</td>
            </tr>
          <tr>
            <td width="20%" align="right" valign="middle"  class="surveytabletd">&nbsp;
              <input name="radiobutton" type="radio" value="radiobutton" /></td>
            <td width="80%" align="left" valign="middle"  class="surveytabletd" >Compaq Presario</td>
          </tr>
          <tr>
            <td align="right" valign="middle"  class="surveytabletd">&nbsp;
			<input name="radiobutton" type="radio" value="radiobutton" /></td>
            <td align="left" valign="middle"  class="surveytabletd" >IBM Thinkpad</td>
          </tr>
               
               
          <tr>
            <td align="right" valign="middle"  class="surveytabletd">&nbsp;</td>
            <td align="left" valign="middle"  class="surveytabletd" ><input name="survey_Submit" class="buttongray" value="Vote" type="submit"></td>
          </tr>
        </table>

        		
    <ul class="bestsellers">   
				<li class="bestsellersheader">Best Sellers</li>
			  <li><h1><a class="bestsellerslink">HP Wireless  </a></h1></li>
              <li><h1><a class="bestsellerslink">Apple Ipod Shuffle</a></h1></li>
              <li><h1><a class="bestsellerslink">Altec Lansing </a></h1></li>
				<li><h1 align="right"> <a href="#" class="showall" title="Show Details">Show Details</a> </h1> </li>
			</ul> 
			
			<table border="0" cellpadding="0" cellspacing="0" class="newslettertable">
            <tr>
              <td colspan="2" class="newsletterheader" align="left">Newsletter Signup</td>
            </tr>
            <tr>
              <td class="newslettertd"></td>
              <td align="left" valign="top" class="newsletterinput"><select name="newsletter_title" class="regiinput" id="newsletter_title" >
                  <option value="">Select</option>
                  <option value="Mr.">Mr.</option>
                  <option value="Mrs.">Mrs.</option>
                  <option value="M/S.">M/S.</option>
                </select>              </td>
            </tr>
            <tr>
              <td class="newslettertd">Name</td>
              <td align="left" valign="top" class="newsletterinput"><input name="newsletter_name" type="text" class="inputA" id="newsletter_name" size="15" />              </td>
            </tr>
            <tr>
              <td class="newslettertd">Email:</td>
              <td align="left" valign="top" class="newsletterinput"><input name="newsletter_email" type="text" class="inputA" id="newsletter_email" size="15" />              </td>
            </tr>
            
            
            
            
            <tr>
              <td class="newslettertd" align="left" colspan="2" ><input name="newsletter_Submit" type="submit" class="buttongray" id="newsletter_Submit" value="Subscribe" />              </td>
            </tr>
            <tr>
              <td colspan="2" align="right">&nbsp;</td>
            </tr>
          </table>

          <table border="0" cellpadding="0" cellspacing="0" class="compshelftable">
            <tr>
              <td align="left" class="compshelfheader">Shelf Items </td>
            </tr>
            <tr>
              <td align="left" class="compshelfprodname"><h1><a class="compshelfprolink" >160 GB Ipod Classic</a></h1></td>
            </tr>
            <tr>
              <td  align="left"><img src="images/ipod.jpg" width="48" height="63" /> </td>
            </tr>
            <tr>
              <td align="left" class="compshelftable_boottom" ><ul class="shelfAul">
                <li class="shelfAstrikeprice">Original Price:£249.00</li>
                <li class="shelfAnormalprice">Offer Price:£230.66</li>
                <li class="shelfAyousaveprice">( Discount 10% )</li>
              </ul></td>
            </tr>
          </table>
  
          <table border="0" cellpadding="0" cellspacing="0" class="logintable">
            <tr>
              <td colspan="2" class="logintableheader">Customer Login</td>
            </tr>
            <tr>
              <td class="logintablecontent">Email: </td>
              <td align="right" valign="top" class="logintablecontentright"><input name="custlogin_uname" type="text" class="inputA" id="custlogin_uname" size="15" /></td>
            </tr>
            <tr>
              <td class="logintablecontent">Password: </td>
              <td align="right" valign="top" class="logintablecontentright"><input name="custlogin_pass" type="password" class="inputA" id="custlogin_pass" size="15" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td align="right" valign="top" class="logintablecontentright"><input name="custologin_Submit" type="submit" class="buttongray" id="custologin_Submit" value="Login" />              </td>
            </tr>
            <tr>
              <td colspan="2" align="right"><a href="#" class="loginlink" title="New User?">New User?</a> </td>
            </tr>
          </table></td>
		  
        <td  align="left" valign="top" class="compmiddlecontainer">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
			  <tr>
				<td colspan="5" align="left" valign="middle"><div class="treemenu"><a href="#">Home</a> >> Shopping Cart</div></td>
				<td width="9%" align="left" valign="middle" class="shoppingcartheader">&nbsp;
								  		<input name="clearcart_button" type="button" class="buttonred_cart" id="clearcart_button" value="Clear Cart" />
			    </td>
			  </tr>
			  <tr>
				<td align="left" valign="middle" class="shoppingcartheaderA">Item</td>
				<td align="left" valign="middle" class="shoppingcartheaderA">Price</td>
				<td align="center" valign="middle" class="shoppingcartheaderA">Availability</td>
				<td align="center" valign="middle" class="shoppingcartheaderA">Disc</td>
				<td align="center" valign="middle" class="shoppingcartheaderA">Qty</td>
				<td align="right" valign="middle" class="shoppingcartheaderA">Total</td>
			  </tr>
		  				  <tr>
					<td align="left" valign="middle" class="shoppingcartcontent">
											<a href="#" title="Sony Vaio VGN FJ250">
								<img src="images/110028phone.gif" alt="Sony Vaio VGN FJ250" title="Sony Vaio VGN FJ250" border="0"  />
							</a>
						<br />
										<a href="#" title="Sony Vaio VGN FJ250" class="shoppingcartprod_link">Sony Vaio VGN FJ250</a>					</td>
					<td align="left" valign="middle" class="shoppingcartpriceB">£50000.00</td>
					<td align="center" valign="middle" class="shoppingcartcontent">
					<span class="cartinstock">In Stock</span>					</td>
					<td align="center" valign="middle" class="shoppingcartpriceB">
					-£10000.00				</td>
					<td align="left" valign="middle" class="shoppingcartcontent">
					<div class="updatediv" align="center"><input name="cart_qty_821" type="text" id="cart_qty_821" size="1" maxlength="2" value="1" style="width:12px;" />
					</div> 
					<div class="updatediv" align="center"><a href="#"  title="Update"><img src='images/cart_update.gif' border="0" alt="Update"></a>  <a href="#" class="update_link" title="Remove"><img src='images/cart_delete.gif' border="0" alt="Remove"></a></div></td>
					<td align="right" valign="middle" class="shoppingcartpriceA">£40000.00</td>
				  </tr>
				  			 	<tr>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="right" valign="middle" class="shoppingcartcontent">&nbsp;</td>
				<td align="center" valign="middle" class="shoppingcartcontent">Total Price</td>
				<td align="right" valign="middle" class="shoppingcartpriceC">£40000.00</td>
			  </tr>
		  	
						<tr>
						<td colspan="4" align="left" valign="middle" class="shoppingcartcontent"><a name="a_gwrap">&nbsp;</a>Do You Want To Gift Wrap Your Order? (£10.00)</td>
						<td colspan="2" align="left" valign="middle" class="shoppingcartcontent"><input type="checkbox" name="chk_giftwrapreq" id="chk_giftwrapreq" value="1" checked /></td>
						</tr>
					
						<tr id="giftwrap_details_tr" >
							<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">
								<table width="100%" border="0" cellspacing="1" cellpadding="1">
								<tr>
								<td width="2%" align="left" valign="top">&nbsp;</td>
								<td width="53%" align="left" valign="top">Giftwrap Message Required?(£2.00)</td>
								<td width="45%" align="left" valign="top"><input name="giftwrap_message_req" type="checkbox" id="giftwrap_message_req" value="1"  /></td>
								</tr>
								
								</table>
								<table width="100%" border="0" cellpadding="1" cellspacing="1" class="shoppingcartgiftwrap_det">
										<tr>
										<td align="left" class="shoppingcartgiftwrap_detheading"><a name="a_gwrapopt">&nbsp;</a>Giftwrap Options</td>
										</tr>
							  </table>
										<table width="100%" border="0" cellpadding="1" cellspacing="1" class="shoppingcartgiftwrap_det">
										<tr>
																					<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading">Ribbons</span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="ribbon_radio" type="radio" class="shoppingcart_radio" id="ribbon_radio" value="0" checked="checked"  />
												 None<!--&nbsp; <img src="" onclick="hideall_giftwrapimagediv(document.frm_cart,'ribbonimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="ribbon_radio" id="ribbon_radio" value="12" class="shoppingcart_radio"   />Red Ribbon (£2.00)<br />
														<div id="ribbonimg_div_12" class="giftwrapimg_div" style="display:none">
																					  </div>
										  </td>
																					<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading">Papers</span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="paper_radio" type="radio" class="shoppingcart_radio" id="paper_radio" value="0" checked="checked" />
												None <!--<img src="" onclick="hideall_giftwrapimagediv(document.frm_cart,'paperimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="paper_radio" id="paper_radio" value="5" class="shoppingcart_radio"  />Paper Black (£0.00)<br />
														<div id="paperimg_div_5" class="giftwrapimg_div" style="display:none">
																					  </div>
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="paper_radio" id="paper_radio" value="6" class="shoppingcart_radio" />Paper 3x1 (£0.00)<br />
														<div id="paperimg_div_6" class="giftwrapimg_div" style="display:none">
																					  </div>
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="paper_radio" id="paper_radio" value="7" class="shoppingcart_radio" />Red Paper (£2.00)<br />
														<div id="paperimg_div_7" class="giftwrapimg_div" style="display:none">
																					  </div>
										  </td>
										</tr><tr>											<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading">Cards</span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="card_radio" type="radio" class="shoppingcart_radio" id="card_radio" value="0" checked="checked" />
												None<!-- <img src="" onclick="hideall_giftwrapimagediv(document.frm_cart,'cardimg_')" alt="Collapse All" title="Collapse All"/>--><br />
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="card_radio" id="card_radio" value="5" class="shoppingcart_radio"  />New Year (£0.00)<br />
														<div id="cardimg_div_5" class="giftwrapimg_div" style="display:none">
										  </div>
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="card_radio" id="card_radio" value="7" class="shoppingcart_radio"  />Birthday (£0.00)<br />
														<div id="cardimg_div_7" class="giftwrapimg_div" style="display:none">
										  </div>
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="card_radio" id="card_radio" value="6" class="shoppingcart_radio" />Christmas (£5.00)<br />
														<div id="cardimg_div_6" class="giftwrapimg_div" style="display:none">
										  </div>
												&nbsp;&nbsp;&nbsp;		
														
														<input type="radio" name="card_radio" id="card_radio" value="9" class="shoppingcart_radio" />Red card (£1.00)<br />
														<div id="cardimg_div_9" class="giftwrapimg_div" style="display:none">
										  </div>
																							</td>
																					<td width="25%" align="left" valign="top" class="shoppingcartgiftwrap_dettd">
												<span class="shoppingcartgiftwrap_detsubheading">Bows</span><br />
												&nbsp;&nbsp;&nbsp;&nbsp;<input name="bow_radio" type="radio" class="shoppingcart_radio" id="bow_radio" value="0" checked="checked" />
												None <!--<img src="" onclick="hideall_giftwrapimagediv(document.frm_cart,'bowimg_')" alt="Collapse All" title="Collapse All"/>--><br />
															&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bow_radio" id="bow_radio" value="13" class="shoppingcart_radio"  />Christmas (£0.00)<br />
														<div id="bowimg_div_13" class="giftwrapimg_div" style="display:none"></div>
												   		
														
														<input type="radio" name="bow_radio" id="bow_radio" value="12" class="shoppingcart_radio"  />Red Bow (£2.00)<br />
														<div id="bowimg_div_12" class="giftwrapimg_div" style="display:none">
																					  </div>
															&nbsp;&nbsp;&nbsp;&nbsp;
														
														
														<input type="radio" name="bow_radio" id="bow_radio" value="10" class="shoppingcart_radio"  />bow (£3.00)<br />
														<div id="bowimg_div_10" class="giftwrapimg_div" style="display:none"></div>
										  </td>
																				</tr>
										</table>
						  </td>
						</tr>
		  				<tr>
				<td colspan="6" align="right" valign="middle">
				<input type="hidden" name="cart_delivery_id" id="cart_delivery_id" value="2"  />	
				 <table width="100%" border="0" cellspacing="1" cellpadding="1">
				 
				 					   <tr>
						 <td align="left" class="shoppingcartcontent">Delivery Charge  (location) </td>
						 <td align="left" class="shoppingcartcontent">
						 <select name='cart_deliverylocation'  id='cart_deliverylocation'  ><option value='0' selected='selected'> -- Select --</option><option value='12'>UK Mainland</option><option value='21'>USA</option><option value='22'>India</option><option value='23'>South Africa</option></select>	
						 	 </td>
					   </tr>
					   
				 </table></td>
				</tr>
							<tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">Delivery Charge</td>
				<td align="right" valign="middle" class="shoppingcartpriceC">£15.00</td>
			  </tr>
								<tr>
						<td colspan="5" align="right" valign="top" class="shoppingcartcontent">
						Tax Charges  Applied						<br/>(VAT @ 10.00%)
								  </td>
						<td align="right" valign="top" class="shoppingcartpriceC">
						£5001.50 <br />
								  </td>
					</tr>
								  <tr>
					<td colspan="6" align="left" valign="middle"><div class="shopprodiv">
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td width="71%" align="left"><a name="a_prom">&nbsp;</a>
						  								  If you have a promotional code/ gift voucher number,  Enter it here						  						  </td>
						  <td width="29%" align="left">
							  	<input name="cart_promotionalcode" type="text" id="cart_promotionalcode" size="5" />&nbsp;&nbsp;
							  	<input name="submit_promotionalcode" type="button" class="buttongray" id="submit_promotionalcode" value="Go" />
						  </td>
						</tr>
					  </table>
					</div></td>
				  </tr>
		  			 
			  <tr>
				<td colspan="5" align="right" valign="middle" class="shoppingcartcontent">Total Final Cost&nbsp; </td>
				<td align="right" valign="middle" class="shoppingcartpriceC">£45016.50</td>
			  </tr>
          <tr>
            <td colspan="6" align="left" valign="middle">
			<a name="a_pay"></a>
										  
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td colspan="2" class="cart_payment_header">Select the payment type</td>
								  </tr>
								  <tr>
								  										<td width="25%" align="left">
																						<img src="images/cash.gif" alt="Payment Type"/>
																						<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="7"  />Pay on Phone										</td>
																		<td width="25%" align="left">
																						<img src="images/cash.gif" alt="Payment Type"/>
																						<input class="shoppingcart_radio" type="radio" name="cart_paytype" id="cart_paytype" value="4"  />Cash on Delivery										</td>
								</tr><tr><td colspan=2>&nbsp;</td>	
								  </tr>
								</table>
							
				</td>
          </tr>
           			  	
		
          <tr>
            <td colspan="6" align="right" valign="middle" class="shoppingcartcontent">
            
			   	
			  	
			  		<div class="cart_checkout_div" align='left' style="float:left; width:50%px;">
					 <input name="continue_submit" type="button" class="buttonred_cart" id="continue_submit" value="Continue Shopping"  />
					</div><div class="cart_checkout_div" align='right' style="float:left; width:50%px;">   
             		 <input name="continue_checkout" type="button" class="buttonred_cart" id="continue_checkout" value="Go to Checkout"  />
             		</div> 
	       	</td>
          </tr>
  		    </table>
		
		    				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
         	 	<tr>
            		<td colspan="6" align="right" valign="middle">&nbsp;</td>
          		</tr>
         	 	<tr>
            		<td colspan="6" align="left" valign="middle" class="shoppingcartcontent">
						<table width="100%" border="0" cellspacing="3" cellpadding="0">
						<tr>
							<td class="shoppingcarttextlogin"><strong>Customer Login</strong><a name="cartloginsec">&nbsp;</a></td>
							<td class="shoppingcarttextlogin">
														<span class="shoppingcartlogintdtext1"><strong>New User  ? </strong></span>
						  </td>
						</tr>
						<tr>
							<td width="48%" align="left" valign="top" class="shoppingcartlogintd">
								<table width="100%" border="0" cellspacing="3" cellpadding="0">
								<tr>
								  <td colspan="2" class="shoppingcartlogintdtext1">Shopped at before  </td>
								</tr>
								<tr>
								  <td width="29%" align="left" valign="middle">Email</td>
								  <td width="71%"><input name="custlogin_uname" id="custlogin_uname" type="text" class="inputA" /></td>
								</tr>
								<tr>
								  <td align="left" valign="middle">Password</td>
								  <td><input name="custlogin_pass" id="custlogin_pass" type="password" class="inputA" /></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td><input name="custcartlogin_Submit" type="submit" class="buttonred_cart" value="Login"  /></td>
								</tr>
								</table>
							</td>	
							<td width="52%" align="left" valign="top" class="shoppingcartlogintd">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td class="shoppingcartlogintdtext1">&nbsp;</td>
									</tr>
									<tr>
										<td class="shoppingcartlogintdtext2">If you have not already registered on this site and would like to do so, please click the link below.</td>
									</tr>
									<tr>
										<td align="left"><input name="new_user_cart" type="button" class="buttonred_cart" id="new_user_cart" value="Sign Up"  /></td>
									</tr>
									</table>
						  </td>
						</tr>
						</table>
					</td>
          </tr>
		  </table></td>
        <td  align="left" valign="top" class="comprighttcontainer">
		
		<table border="0" cellpadding="0" cellspacing="0" class="recentviwedtable">
							<tr>
					<td  class="recentviewheader">Recently Viewed Products</td>
				</tr>
				
			<tr>
				<td>
												<ul class="recentprod">  
									<li  class="proimage">
									
										<a href="#" class="recentprodlink" title="Sony Vaio VGN FJ250">Sony Vaio VGN FJ250</a>
									</li>
																	</ul> 
												    
					</td>
				</tr>
				<tr>
				<td align="right" >
				<ul class="compshelflist">
					<li>
						<h5 align="right"><a href="#" class="showall" title="Show All">Clear List</a></h5>
					</li>
				</ul>
				</td>
				</tr>
			</table>
			
		<ul class="categoryright">
			<li class="categoryheaderright">Category group</li>
			 <li><h1><a class="catelink" title="hello new one">Lenovo Y500 7761 </a></h1></li>
             <li><h1><a class="catelink" title="hello new one">Dell Wireless </a></h1></li>
             <li><h1><a class="catelink" title="hello new one">Laptop IBM </a></h1></li>
             <li><h1><a class="catelink" title="hello new one">	Compaq Presario </a></h1></li>
             <li><h1><a class="catelink" title="hello new one">	HP Notebook </a></h1></li>
		</ul>
		
          <div class="sitereviewconleft" align="right">
				<input class="sitereviewleft" value="Site Reviews" onclick="" type="button">
			</div>	

          <ul class="shopright">
								<li class="shopheaderright">Prod Shop 1</li>
								<li><h1><a href="#" class="shoplinkright" title="Shop1">Laptops</a></h1></li>
                                <li><h1><a href="#" class="shoplinkright" title="Shop1">Printers</a></h1></li>
                                <li><h1><a href="#" class="shoplinkright" title="Shop1">Scanners</a></h1></li>
                                <li><h1><a href="#" class="shoplinkright" title="Shop1">Digital Cammeras</a></h1></li>
			</ul>

		  <ul class="staticleft"> 
				<li class="staticleftheader">Staic Page Group</li>
				<li><h1><a href="#" class="staticleftlink" title="Home">Home</a></h1></li>
				<li><h1><a href="#" class="staticleftlink" title="Sitemap">Sitemap</a></h1></li>
				<li><h1><a href="#" class="staticleftlink" title="Help">Help</a></h1></li>
				<li><h1><a href="#" class="staticleftlink" title="Saved Searches">Saved Searches</a></h1></li>
		</ul>
		
         <table border="0" cellpadding="0" cellspacing="0" class="giftvouchertable">
			<tr>
				<td colspan="2" class="giftvoucherheader">Enter Voucher Number</td>
			</tr>
			<tr>
				<td>
					<input name="cart_promotionalcode" type="text" class="inputA" id="cart_promotionalcode_comp" size="15" />				</td>
				<td>
								<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="Go" />				</td>
			</tr>
						<tr>
				<td colspan="2"><a href="#" class="buygiftvoucherheader" title="Buy Gift Voucher">Buy Gift Voucher</a></td>
			</tr>
			</table>
				<table border="0" cellpadding="0" cellspacing="2" class="webstatisticstable">
							<tr>
					<td  class="webstatisticsheader" align="left">Hit Statistics</td>
				</tr>
				
						<tr>

				    <td align="center" class="webstatistics"><span class="webstatisticsA">"759"</span><br /> <span class="webstatisticsB">Visitors Till Now!</span></td>
				</tr>	
			</table>
			<ul class="userloginmenu">  
					<li><h1><a href="#" class="userloginmenulink">My Home</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Enquiries</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Wishlist</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Orders</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">Logout</a></h1></li>
				</ul>		  </td>
      </tr>
	  
      <tr>
        <td colspan="3" class="footer" align="right">e commerce solutions and Search engine optimisation from Business 1st. Copyright 2008.</td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>


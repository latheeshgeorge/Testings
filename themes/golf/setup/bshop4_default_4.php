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
        <td align="left" valign="top" class="compmiddlecontainer">
		<div class="treemenu"><a href="#">Home</a> >> Customer Registration</div>
		<table width="100%" border="0" cellpadding="0" cellspacing="8"  class="regitable">
				<tr>
					<td colspan="2" align="left" class="regifontnormal">If any textual data is to be displayed to the customer, it can be done through this section</td>
				</tr>
				
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent">Account Type</td>
			<td width="57%" align="left" valign="middle"><select name="customer_accounttype" class="regiinput" id="customer_accounttype" onchange="showAccountTypeDetails(this);" >
			<option value="personal">Personal Account</option>
			<option value="business">Business Account</option> 
			</select></td>
		</tr>
		<tr  id="companydetails" >
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="6" align="left" >
					<tr>
						<td colspan="2" class="regiheader">Add Company Details </td>
					</tr>
					<tr>
						<td width="43%" align="left" valign="middle" class="regiconent">Company Name</td>
					  <td width="57%" align="left" valign="middle"><input name="customer_compname" type="text" class="regiinput" id="customer_compname" size="25" value=""  /></td>
					</tr>
										<tr>
						<td align="left" valign="middle" class="regiconent">Company Type</td>
						<td align="left" valign="middle"><select name='customer_comptype'  id='customer_comptype'  ><option value='27'>Limited</option><option value='28'>Plc</option><option value='26'>Partership</option><option value='64'>$</option></select></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent">Company Registration No.</td>
						<td align="left" valign="middle"><input name="customer_compregno" type="text" class="regiinput" id="customer_compregno" size="25" value=""  /></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent">Company Vat  Registration No.</td>
						<td align="left" valign="middle"><input name="customer_compvatregno" type="text" class="regiinput" id="customer_compvatregno" size="25" value=""/></td>
					</tr>
				</table>			</td> 
		</tr>
		
		<tr>
			<td colspan="2" class="regiheader" align="left">Add Customer Details</td>
		</tr>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent">Customer Title <span class="redtext">*</span></td>
			<td width="57%" align="left" valign="middle">
				<select name="customer_title" class="regiinput" id="customer_title" >
				<option value="">Select</option>
				<option value="Mr." >Mr.</option>
				<option value="Mrs." >Mrs.</option> 
				<option value="M/S." >M/S.</option>
				</select>			</td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer First Name <span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_fname" type="text" class="regiinput" id="customer_fname" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Second Name</td>
			<td align="left" valign="middle">
		<input name="customer_mname" type="text" class="regiinput" id="customer_mname" size="25" value=""  />   </td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Sur Name </td>
			<td align="left" valign="middle"><input name="customer_surname" type="text" class="regiinput" id="customer_surname" size="25" value=""  /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer position</td>
			<td align="left" valign="middle"><input name="customer_position" type="text" class="regiinput" id="customer_position" size="25" value=""  /></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Building No.</td>
			<td align="left" valign="middle"><input name="customer_buildingname" type="text" class="regiinput" id="customer_buildingname" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Street Name</td>
			<td align="left" valign="middle"><input name="customer_streetname" type="text" class="regiinput" id="customer_streetname" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer City</td>
			<td align="left" valign="middle"><input name="customer_towncity" type="text" class="regiinput" id="customer_towncity" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Country</td>
			<td align="left" valign="middle"><select name='cbo_country'  id='cbo_country'  onchange='showstate(this.value)' ><option value='0' selected='selected'>-- Select Country --</option><option value='201'>China</option><option value='197'>India</option><option value='2619'>U S</option><option value='3146'>UK</option></select></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer State</td>
			<td align="left" valign="middle">
		<select name='cbo_state'  id='cbo_state'  ><option value='0' selected='selected'>-- Select State --</option><option value='-1'>--Other State--</option></select></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">customer Phone<span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_phone" type="text" class="regiinput" id="customer_phone" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Fax</td>
			<td align="left" valign="middle"><input name="customer_fax" type="text" class="regiinput" id="customer_fax" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Post Code<span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_postcode" type="text" class="regiinput" id="customer_postcode" size="25" value="" /></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Email<span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="25" value="" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">Customer Password<span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="25" value="" /></td>
		</tr>	
		<tr>
			<td align="left" valign="middle" class="regiconent">Confirm password<span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="25" value="" /></td>
		</tr>	
		
				
		<tr>
			<td align="left" valign="top" class="regiconentred" colspan="2">Receive newsletter from the following Areas</td>
		</tr>
		<tr>
		<td colspan="2" valign="top" align="left">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
										<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="3"  />						</td>
						
						<td  align="left" valign="middle" width="30%">Book History</td>
												<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="4"  />						</td>
						
						<td  align="left" valign="middle" width="30%">Indian Discovery</td>
												<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="11"  />						</td>
						
						<td  align="left" valign="middle" width="30%">Software</td>
						</tr><tr>						<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="12"  />						</td>
						
						<td  align="left" valign="middle" width="30%">Hardware</td>
												<td   align="left" valign="middle" width="3%">
							<input type="checkbox" name="newsletergroup[]" value="13"  />
													</td>
						<td  align="left" valign="middle" width="30%">Mobiles</td>
					   <td   align="left" valign="middle" width="3%">
							<input type="checkbox" name="newsletergroup[]" value="16"  />	
					   </td>
					   <td  align="left" valign="middle" width="30%">dfdf</td>
						</tr><tr>						<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="17"  />						</td>
						
						<td  align="left" valign="middle" width="30%">dfdfdf</td>
											</tr>
		  </table>		</td>
		</tr>
				<tr>
			<td align="left" valign="middle" class="regiconent">
			</td>
			<td align="left" valign="middle"><input name="registration_Submit" type="submit" class="buttongray" id="registration_Submit" value="Save" /></td>
		</tr>
	</table>  </td>
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


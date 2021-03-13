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
	     <div class="treemenu"> <a href='#' title='v4demo1.arys.net'>Home</a> >>  <a href='#' title='Nokia'>Nokia</a> >> newtest</div>
		<table border="0" cellpadding="0" cellspacing="0" class="productdeatilstable">
                    <tr>
            <td colspan="2" align="left" class="productdeheader">newtest</td>
          </tr>

		 				<tr>
					<td colspan="2" align="right" class="productdeheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="2%" align="left" valign="top" class="productdetails_img_td"><img src="images/pro_link_det_left.gif" title="left" alt="left"/></td>
					<td colspan="2" class="productdetdA" align="right">
								<a href="#" class="productdetailslink" >Compare Products</a>
															<a href="#" class="productdetailslink">Email a Friend</a>

																<a href="#" class="productdetailslink">Write Review</a>
									
									<a href="#" class="productdetailslink">Read Review</a>
																<a href="#" class="productdetailslink">Download PDF</a>												</td>
					<td width="2%" align="right" valign="top" class="productdetails_img_td"><img src="images/pro_link_det_right.gif" title="right" alt="right"/></td>
				</tr>
				</table></td>
				</tr>
                    <tr>
            <td valign="middle" class="productdetmain" align="center"><a href="#" title="Click to Zoom" ><img src="images/110344test1.jpg" alt="newtest" title="newtest" border="0"  /></a></td>
            <td width="52%"  class="productdetd" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
			                                <tr>

                  <td class="reviewscore">Average review score: <img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;<img src="images/reviewstar_off.gif" border="0"  alt="revscoreimg"/>&nbsp;                  </td>
                </tr>
                                <tr>
                  <td><span class="stockdetailstd">Available Stock: 50 </span></td>
                </tr>
                                <tr>
                  <td><span class="productdeposit_price">Bonus Points Available with this product: 11</span></td>
                </tr>
                                <tr>
                  <td><span class="productdeposit_price">Deposit Required: 2.00 %</span><br />
                      <span class="productdeposit_msg">Product Required Deposit <br />
                      </span></td>
                </tr>

                                <tr>
                  <td align="left" valign="top" class="productdetd"><ul class="prodeulprice"><li class="productdetstrikeprice">Price From £140.00 /-</li><li class="productdetnormalprice">Offer Price £126.00 /-</li><li class="productdetdiscountprice">( Discount 10% )</li></ul>		
				    		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="variable_bottom_border">
						<div class="variabletabcontainer">           
							<ul class="variabletab">
								<li id="var_li" class="variableselected" >Variables</li>	
							</ul>
						</div>					</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
														  <tr>
									<td align="left" valign="middle" class="productvariabletdA">color67</td>

									<td align="left" valign="middle" class="productvariabletdA">
																						<select name="var_68">
																										<option value="174">re (Add £2.00 )</option>
																										<option value="181">d (Add £4.00 )</option>
									  </select>																			</td>
								  </tr>
														  <tr>

									<td align="left" valign="middle" class="productvariabletdB">color</td>
									<td align="left" valign="middle" class="productvariabletdB">
																						<select name="var_72">
																										<option value="196">we (Add £1.00 )</option>
									  </select>																			</td>
								  </tr>
						</table>					</td>
				</tr>
			</table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
				  <tr>
					<td align="left" class="bulkdiscountheader">Bulk Discount Available</td>
				  </tr>
				  	
					   <tr>

						<td class="bulkdiscountcontent" align="left">4 for  
							£9.00 each						</td>
					  </tr>
				  	
					   <tr>
						<td class="bulkdiscountcontent" align="left">5 for  
							£8.00 each						</td>
					  </tr>
		  				</table>			                  </td>
                </tr>

                              </table>
                			  <div class="quantity_details">qty:<input type="text" class="quainput" name="qty" size="2"  value="1" maxlength="2" /></div>
					<input name="Submit_buy" type="submit" class="buttonblackbuy" id="Submit_buy" value="Buy Now" />
					
				<input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="Add to Enquiry"  />	                            </td>
          </tr>
                                        <tr>
            <td colspan="2" align="left" class="productdetd">	
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable"  >
								<tr>
				<td class="variable_bottom_border" colspan="2">
					<div class="variabletabcontainer">           
						<ul class="variabletab">
												
							<li class="variableselected">Key Features</li>
						</ul>
					</div>				</td>
				</tr>

											<tr>
								<td align="left" valign="middle" class="productvariabletdB">Product Label1</td>
								<td align="left" valign="middle" class="productvariabletdB">: my val</td>
							</tr>	
										</table>			            </td>
          </tr>
                    <tr>

            <td colspan="2" align="left" valign="middle" class="productdetd">            </td>
          </tr>
                    <tr>
            <td colspan="2" align="left" valign="middle" >
			<table width="100%" border="0" cellpadding="2" cellspacing="0">
                                <tr>
                  <td align="left"  class="productchartheader" colspan="4" >Sizing</td>
                </tr>
                <tr>

                                    <td align="center"  class="productsizechartheading" >Length</td>
                                    <td align="center"  class="productsizechartheading" >Waist</td>
                                    <td align="center"  class="productsizechartheading" >Hips</td>
                                    <td align="center"  class="productsizechartheading" >Header Xmas</td>
                                  </tr>
                                <tr>
                                    <td class="productsizechartvalueA" align="center" >1</td>

                                    <td class="productsizechartvalueA" align="center" >5</td>
                                    <td class="productsizechartvalueA" align="center" >5</td>
                                    <td class="productsizechartvalueA" align="center" >1</td>
                                  </tr>
                                <tr>
                                    <td class="productsizechartvalueB" align="center" >-</td>
                                    <td class="productsizechartvalueB" align="center" >-</td>

                                    <td class="productsizechartvalueB" align="center" >5</td>
                                    <td class="productsizechartvalueB" align="center" >-</td>
                                  </tr>
                                <tr>
                                    <td class="productsizechartvalueA" align="center" >4</td>
                                    <td class="productsizechartvalueA" align="center" >-</td>
                                    <td class="productsizechartvalueA" align="center" >-</td>

                                    <td class="productsizechartvalueA" align="center" >-</td>
                                  </tr>
                            </table></td>
          </tr>

                    <tr>
            <td colspan="2" align="left" class="productdetdtab"><a href="#" name="protabs"></a>
                <!--This href is to bring back the user to the tab section after reloadin on tab click -->
                <div class="protabcontainer" >
                  <ul class="protab">
                                        <li  class="selectedtab">Overview</li>
                                        <li  ><a href="#" class="tablink" title="ggg">Tab2</a></li>
                  </ul>
                </div></td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="productdetd_main">Overview of Product            </td>
          </tr>
                  </table>
		</td>
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


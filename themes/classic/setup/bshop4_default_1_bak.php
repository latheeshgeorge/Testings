<?PHP 
if($frompreview != 1) {
	
	require("../../../functions/functions.php");
	require("../../../includes/urls.php");
	require("../../../includes/session.php");
	include("../../../config.php");
}	

	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title> 
<link href="<? echo SITE_URL."/images/".$ecom_hostname."/setup/css/default.css"; ?>" media="screen" type="text/css" rel="stylesheet" />
</head>

<body style="padding:0px;"> 
<table width="100%" border="0" class="main" >
  <tr >
    <td width="19%" height="50px">Logo</td>
    <td width="81%">Image</td>
  </tr>
  
  <tr>
    <td colspan="2" class="maintopsearchtd">
	<div align="left" class="topsearch">
					  <table border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td class="searchfont_top">Search</td>
						  <td><input name="quick_search" type="text" class="inputA" id="quick_search"  value=""/></td>
						  <td><input name="button_submit_search" type="submit" class="buttongray" id="button3" value="Go" onclick="show_wait_button(this,'Please wait...')" /></td>
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
    <td colspan="2"><table width="100%" border="0">
      <tr>
        <td  align="center" class="compleftcontainer">
		<ul class="category">
																								<li class="categoryheader">Category Group Test DK</li>
																							 <li><h1><a href="http://v4demo1.arys.net/c48/category-test-dk.html" class="catelink" title="Category Test DK">Category Test DK</a></h1></li>

																							 <li><h1><a href="http://v4demo1.arys.net/c93/hcl-laptops.html" class="catelink" title="HCL LAPTOPS">HCL LAPTOPS</a></h1></li>
			</ul>							
          <br />
		  <table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
									 											  <tr>
												<td class="shopbybrandheader">ShopGroupGreen</td>
											  </tr>
									  									  <tr>
										<td class="shopbybrandheader">
										  <label>

											<select name="prodshopgroup_12" onchange="handle_dropdownval_sel(this.value)">
												<option value="">-- Select -- </option>
																					<option value="http://v4demo1.arys.net/shp46/productshopyellow.html" >ProductShopYellow</option>
																					<option value="http://v4demo1.arys.net/shp47/productshopblue.html" >ProductShopBlue</option>
																			</select>
										  </label>
										</td>

									  </tr>
									</table>
		<table width="90%" border="0" class="surveytable">
          <tr>
            <td colspan="2" align="left" valign="middle" class="surveytableheader">Survey</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="middle" class="surveytablequst" >&nbsp;Question</td>
            </tr>
          <tr>
            <td width="24%" align="left" valign="middle"  class="surveytabletd">&nbsp;
              <input name="radiobutton" type="radio" value="radiobutton" /></td>
            <td width="76%" align="left" valign="middle"  class="surveytabletd" >Data 1 </td>
          </tr>
          <tr>
            <td align="left" valign="middle"  class="surveytabletd">&nbsp;
			<input name="radiobutton" type="radio" value="radiobutton" /></td>
            <td align="left" valign="middle"  class="surveytabletd" >Data 2 </td>
          </tr>
          <tr>
            <td align="left" valign="middle"  class="surveytabletd">&nbsp;</td>
            <td align="left" valign="middle"  class="surveytabletd" >&nbsp;<input name="survey_Submit" class="buttongray" value="Vote" type="submit"></td>
          </tr>
        </table>
          <br />
        		
         	<ul class="bestsellers">   
				<li class="bestsellersheader">Best SellersLeft</li>
				<li><h1><a class="bestsellerslink">Item 1</a></h1></li>
				<li><h1 align="right"> <a href="#" class="showall" title="Show Details">Show Details</a> </h1> </li>
			</ul> 
			
			</ul>									  
			
          <br />
          <table border="0" cellpadding="0" cellspacing="0" class="newslettertable">
            <tr>
              <td colspan="2" class="newsletterheader" align="left">Newsletter</td>
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
              <td class="newslettertd">Phone</td>
              <td align="left" valign="top" class="newsletterinput"><input name="newsletter_phone" type="text" class="inputA" id="newsletter_phone" size="15" />              </td>
            </tr>
            
            
            
            <tr>
              <td class="newslettertd" align="left" colspan="2" ><input name="newsletter_Submit" type="submit" class="buttongray" id="newsletter_Submit" value="Subscribe" />              </td>
            </tr>
            <tr>
              <td colspan="2" align="right">&nbsp;</td>
            </tr>
          </table>
          <br />
          <table border="0" cellpadding="0" cellspacing="0" class="compshelftable">
            <tr>
              <td align="left" class="compshelfheader">Shelf Items </td>
            </tr>
            <tr>
              <td align="left" class="compshelfprodname"><h1><a class="compshelfprolink" >Item 1</a></h1></td>
            </tr>
            <tr>
              <td  align="left"><img src="../setup/images/dummyimage.gif" width="30" height="30" /> </td>
            </tr>
            <tr>
              <td align="left"><ul class="shelfAul">
                <li class="shelfAstrikeprice">Original Price</li>
                <li class="shelfAnormalprice">Offer Price</li>
                <li class="shelfAyousaveprice">You Save</li>
              </ul></td>
            </tr>
          </table>
          <br />
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
              <td align="right" valign="top" class="logintablecontentright"><input name="custologin_Submit" type="submit" class="buttongray" id="custologin_Submit" value="Login" />
              </td>
            </tr>
            <tr>
              <td colspan="2" align="right"><a href="http://v4demo1.arys.net/registration.html" class="loginlink" title="New User?">New User?</a> </td>
            </tr>
          </table></td>
        <td  align="center" valign="top" class="compmiddlecontainer">
			<table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
			<tr>
			  <td colspan="2" align="left" class="staticpageheader">Home page content</td>
			  </tr>
			<tr>
			  <td valign="top" class="homepagecontent">
			  This s the test home page.			  </td>
			</tr>
			
		  </table>	
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="featuredtable">
											<tr>
					<td colspan="2" align="left" valign="top" class="featuredheader">Featured Product</td>
				</tr>
								<tr>
						<td colspan="2" align="left" valign="top"><h1 class="featuredprodname"><a href="http://v4demo1.arys.net/p82/sony-ericsson-w950i.html" title="Sony-Ericsson W950i">Sony-Ericsson W950i</a></h1></td>

					</tr>
					
			<tr>
				
					<td align="left" valign="middle" class="featuredtabletd">
					<a href="http://v4demo1.arys.net/p82/sony-ericsson-w950i.html" title="Sony-Ericsson W950i">
							<img src="http://v4demo1.arys.net/images/v4demo1.arys.net/thumb/04235417a.jpg" alt="Sony-Ericsson W950i" title="Sony-Ericsson W950i" border="0"  />
					  </a>					</td>
								<td align="left" valign="top" class="featuredtabletd">
											<ul class="featured">

														<li>
										<h6 class="featuredproddes">Sony-Ericsson W950i  GSM 900 / 1800 / 1900</h6>
									</li>
					<li class="normalprice">Retail Price £100.00 /-</li>		<div class="bonus_point">100 </div>
						</ul>        
								<table border="0" cellspacing="0" cellpadding="0">
					  <tr>

						<td>
								<a href="http://v4demo1.arys.net/p82/sony-ericsson-w950i.html" title="" class="infolink">More Info</a>
						</td>
						<td>
						</td>
					  </tr>
					</table>				</td>
			</tr>

			</table>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
																					
											<tr>
												<td colspan="3" class="shelfBheader" align="left">Laptops!!</td>
											</tr>
																						<tr>
													<td colspan="3" class="shelfBproddes" align="left">ha ha</td>
												</tr>
																						<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
													<td align="left" valign="middle" class="shelfBtabletd">
												
																														<h1 class="shelfBprodname"><a href="http://v4demo1.arys.net/p86/sony-vaio-vgn-fj250.html" title="Sony Vaio VGN FJ250">Sony Vaio VGN FJ250</a></h1>
														
																
																<h6 class="shelfBproddes">About Sony Vaio VGN FJ250</h6>													</td>
													<td align="center" valign="middle" class="shelfBtabletd">
															
															<a href="http://v4demo1.arys.net/p86/sony-vaio-vgn-fj250.html" title="Sony Vaio VGN FJ250">

																	<img src="http://v4demo1.arys.net/images/v4demo1.arys.net/thumb/061117iphone.gif" alt="Sony Vaio VGN FJ250" title="Sony Vaio VGN FJ250" border="0"  />																</a>																											</td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<ul class="shelfBul"><li class="shelfBstrikeprice">Retail Price £50000.00 /-</li><li class="shelfBnormalprice">Offer Price £40000.00 /-</li><li class="shelfBdiscountprice">( Discount 20% )</li></ul>	
															<form method="post" action="http://v4demo1.arys.net/manage_products.html" name='shelf_489b08c2ea3c2' id="shelf_489b08c2ea3c2" class="frm_cls">
															<input type="hidden" name="fpurpose" value="" />
															<input type="hidden" name="fproduct_id" value="" />

															<input type="hidden" name="pass_url" value="/" />
															
															<div class="infodiv">
																<div class="infodivleft">		<a href="http://v4demo1.arys.net/p86/sony-vaio-vgn-fj250.html" title="" class="infolink">More Info</a>	</div>
																<div class="infodivright">																																</div>
															</div>
															</form>													</td>
											</tr>
			</table>
		<table width="100%" border="0">
		
          
          <tr>
            <td colspan="3" class="shelfAheader">LapTops </td>
            </tr>
          <tr>
            <td class="shelfAtabletd">	<ul class="shelfAul">
																														<li><h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">Type 1</a></h1></li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">Price</li><li class="shelfAnormalprice">Offer Price </li><li class="shelfAyousaveprice">You Save
				      <a href="http://v4demo1.arys.net/p83/hp-photosmart-c4100-all-in-one-series.html" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
																</li>
																
																<li><h6 class="shelfAproddes">Description</h6></li>
															
													</ul></td>
               <td class="shelfAtabletd">	<ul class="shelfAul">
																														<li>
																														  <h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">Type 2 </a></h1>
																														</li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">Price</li><li class="shelfAnormalprice">Offer Price </li><li class="shelfAyousaveprice">You Save
				      <a href="http://v4demo1.arys.net/p83/hp-photosmart-c4100-all-in-one-series.html" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
																</li>
																
																<li><h6 class="shelfAproddes">Description</h6></li>
															
													</ul></td>
               <td class="shelfAtabletd">	<ul class="shelfAul">
																														<li>
																														  <h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">Type 3 </a></h1>
																														</li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">Price</li><li class="shelfAnormalprice">Offer Price </li><li class="shelfAyousaveprice">You Save
				      <a href="http://v4demo1.arys.net/p83/hp-photosmart-c4100-all-in-one-series.html" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
					</li>
																
																<li><h6 class="shelfAproddes">Description</h6></li>
															
													</ul>                 </td>
          </tr>
          
          <tr>
            <td><span style="padding-left:3px"><img src="../setup/images/dummyimage.gif" width="30" height="30" /></span></td>
            <td><span style="padding-left:3px"><img src="../setup/images/dummyimage.gif" width="30" height="30" /></span></td>
            <td class="shelfAtabletd"><span style="padding-left:3px"><img src="../setup/images/dummyimage.gif" width="30" height="30" /></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="3"></td>
            </tr>
        </table>

		
		</td>
        <td  align="center" valign="top" class="comprighttcontainer">
		<ul class="categoryright">
			<li class="categoryheaderright">Category group</li>
			 <li><h1><a class="catelink" title="hello new one">new one</a></h1></li>
		</ul>
          <br />
          <div class="sitereviewconleft" align="left">
				<input class="sitereviewleft" value="Site Reviews" onclick="" type="button">
			</div>	
          <br />
          <ul class="shopright">
								<li class="shopheaderright">Prod Shop 1</li>
								<li><h1><a href="#" class="shoplinkright" title="Shop1">Shop1</a></h1></li>
			</ul>
          <br />
		  <ul class="staticleft"> 
				<li class="staticleftheader">Top</li>
				<li><h1><a href="#" class="staticleftlink" title="Home">Home</a></h1></li>
				<li><h1><a href="#" class="staticleftlink" title="First page" >First page</a></h1></li>
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
					<input name="cart_promotionalcode" type="text" class="inputA" id="cart_promotionalcode_comp" size="15" />
				</td>
				<td>
								<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="Go" />
				</td>
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

				    <td align="center" class="webstatistics"><span class="webstatisticsA">"759"</span><br /> Visitors Till Now!</td>
				</tr>	
			</table>
			<ul class="userloginmenu">  
					<li><h1><a href="#" class="userloginmenulink">My Home</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Profile</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Enquiries</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Wishlist</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Favorites</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Address Book</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">My Orders</a></h1></li>
					<li><h1><a href="#" class="userloginmenulink">Logout</a></h1></li>
				</ul> 
		  </td>
      </tr>
	  
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>

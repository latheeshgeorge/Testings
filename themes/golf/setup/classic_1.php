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
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<title></title> 
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
			  <li><h1><a href="#" class="bestsellerslink">HP Wireless  </a></h1></li>
              <li><h1><a href="#" class="bestsellerslink">Apple Ipod Shuffle</a></h1></li>
              <li><h1><a href="#" class="bestsellerslink">Altec Lansing </a></h1></li>
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
                <li class="shelfAstrikeprice">Original Price:�249.00</li>
                <li class="shelfAnormalprice">Offer Price:�230.66</li>
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
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
			<tr>
			  <td colspan="2" align="left" class="staticpageheader">bFirst is a specialist business to business services provider</td>
			  </tr>
			<tr>
			  <td valign="top" class="homepagecontent">
			  We offer a wealth of Internet services to suit most business. Our main expertise lies within the development of <strong>ecommerce solutions & search engine optimisation.</strong><br />
<br />


Our flagship<strong> BShop v4.0</strong> ecommerce solution is an advanced online shop website content management system for the management of dynamic updateable websites with shopping cart software suitable for small to medium size businesses.</td>
			</tr>
		  </table>	
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="featuredtable">
											<tr>
					<td colspan="2" align="left" valign="top" class="featuredheader">Featured Product</td>
				</tr>
								<tr>
						<td colspan="2" align="left" valign="top"><h1 class="featuredprodname"><a href="#" title="Sony-Ericsson W950i">Apple iMac DV Indigo Desktop PC</a></h1></td>
					</tr>
					
			<tr>
				
					<td align="left" valign="middle" class="featuredtabletd">
					<a href="#" title="Sony-Ericsson W950i">
					  <img src="images/featured.jpg" alt="Sony-Ericsson W950i" title="Sony-Ericsson W950i" border="0"  />					  </a>					</td>
<td align="left" valign="top" class="featuredtabletd">
											<ul class="featured">

														<li>
										<h6 class="featuredproddes">Apple has introduced a new model of PC named as Apple iMac DV Indigo Desktop PC which has been recommended for residential and small business needs.<br />
                                        &#8226;  Power utilisation up to 150 watts<br />
 &#8226; Weight - 15.8 Kgs
<br />
 &#8226; EPA Energy Star compliancy<br />
 &#8226;  Pre-installation of Apple MacOS 9<br />
 &#8226; One year warranty </h6></li>
					                      
					                             
		                                      <li class="normalprice">Retail Price �100.00 /-</li>		<div class="bonus_point">100 </div>
						</ul>        
			    <table border="0" cellspacing="0" cellpadding="0">
					  <tr>

						<td>
								<a href="#" title="" class="infolink">More Info</a>						</td>
						<td>						</td>
					  </tr>
				  </table>				</td>
			</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
																					
											<tr>
												<td colspan="3" class="shelfBheader" align="left">Printers</td>
											</tr>
																						<tr>
													<td colspan="3" class="shelfBproddes" align="left">ha ha</td>
												</tr>
																						<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
													<td align="left" valign="middle" class="shelfBtabletd">
												
																														<h1 class="shelfBprodname"><a href="#" title="Sony Vaio VGN FJ250">	HP Deskjet Printer</a></h1>
														
																
													  <h6 class="shelfBproddes">Easily print documents, web pages and more, </h6>													</td>
													<td align="center" valign="middle" class="shelfBtabletd"><img src="images/shell1A.jpg" width="82" height="76" /></td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<ul class="shelfBul">
													  <li class="shelfBstrikeprice">Retail Price �250.00 /-</li>
													<li class="shelfBnormalprice">Offer Price �234.00 /-</li>
													<li class="shelfBdiscountprice">( Discount 10% )</li>
													</ul>	
															
															
															<div class="infodiv">
																<div class="infodivleft">		<a href="#" title="" class="infolink">More Info</a>	</div>
																<div class="infodivright">																																</div>
															</div>
																									</td>
											</tr>
                                            
                                            <tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
													<td align="left" valign="middle" class="shelfBtabletd">
												
																														<h1 class="shelfBprodname"><a href="#" title="Sony Vaio VGN FJ250">Canon inkjet printer</a></h1>
														
																
																<h6 class="shelfBproddes">Print laser-quality text, colourful documents </h6>													</td>
													<td align="center" valign="middle" class="shelfBtabletd"><img src="images/shell1B.jpg" width="97" height="75" /></td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<ul class="shelfBul">
													  <li class="shelfBstrikeprice">Retail Price �360.00 /-</li>
													<li class="shelfBnormalprice">Offer Price �345.40 /-</li>
													<li class="shelfBdiscountprice">( Discount 15% )</li>
													</ul>	
															
															
															<div class="infodiv">
																<div class="infodivleft">		<a href="#" title="" class="infolink">More Info</a>	</div>
																<div class="infodivright">																																</div>
															</div>
																									</td>
											</tr>
                                            
                                            <tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
													<td align="left" valign="middle" class="shelfBtabletd">
												
																														<h1 class="shelfBprodname"><a href="#" title="Sony Vaio VGN FJ250"> 	
Epson Stylus T11</a></h1>
														
																
																<h6 class="shelfBproddes">The Epson Stylus Photo series range of inkjet printers offer</h6>													</td>
													<td align="center" valign="middle" class="shelfBtabletd"><img src="images/shell1C.jpg" width="95" height="92" /></td>
													<td align="left" valign="middle" class="shelfBtabletd">
													<ul class="shelfBul">
													  <li class="shelfBstrikeprice">Retail Price �230.00 /-</li>
													<li class="shelfBnormalprice">Offer Price �126.54 /-</li><li class="shelfBdiscountprice">( Discount 20% )</li></ul>	
															
															
															<div class="infodiv">
																<div class="infodivleft">		<a href="#" title="" class="infolink">More Info</a>	</div>
																<div class="infodivright">																																</div>
															</div>
																											</td>
											</tr>
			</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		
          
          <tr>
            <td colspan="3" class="shelfAheader">LapTops </td>
            </tr>
          <tr>
            <td class="shelfAtabletd">	<ul class="shelfAul">
																														<li><h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">Compaq  B1200</a></h1>
																														</li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">lPrice:�230.00</li><li class="shelfAnormalprice">Offer:�200.00 </li>
					  <li class="shelfAyousaveprice">( Discount 20% )
				      <a href="#" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
					</li>
																
																<li><h6 class="shelfAproddes">Be the trailblazer  </h6></li>
															
				</ul></td>
               <td class="shelfAtabletd">	<ul class="shelfAul">
			   	<li>
				<h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">HP Notebook </a></h1>
				</li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">lPrice:�430.00</li><li class="shelfAnormalprice">Offer :�320.00  </li>
					  <li class="shelfAyousaveprice">( Discount 30% )
				      <a href="#" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
					</li>
																
																<li><h6 class="shelfAproddes">Enhanced connectivity </h6>
					</li>
															
													</ul></td>
               <td class="shelfAtabletd">	<ul class="shelfAul">
																														<li>
																														  <h1 class="shelfAprodname"><a href="#" title="HP Photosmart C4100 All-in-One series">Toshiba S40</a></h1>
																														</li>
															
					<li>
					  <ul class="shelfBul"><li class="shelfAstrikeprice">Price:�730.00</li><li class="shelfAnormalprice">Offer:�465.00  </li>
					  <li class="shelfAyousaveprice">( Discount 50% )
				      <a href="#" title="HP Photosmart C4100 All-in-One series"></a> </li></ul>	
					</li>
																
																<li>
																  <h6 class="shelfAproddes">With a comfortable, </h6>
					</li>
															
													</ul>                 </td>
          </tr>
          
          <tr>
            <td align="left" valign="bottom" style="padding-top:8px"><img src="images/shell2A.jpg" width="80" height="69" /></td>
            <td align="left" valign="bottom" style="padding-top:8px"><img src="images/shell2B.jpg" width="64" height="63" /></td>
            <td align="left" valign="bottom" style="padding-top:8px"><img src="images/shell2C.jpg" width="80" height="69" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="3"></td>
            </tr>
        </table>		</td>
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
			 <li><h1><a href="#" class="catelinkright" title="hello new one">Lenovo Y500 7761 </a></h1></li>
             <li><h1><a href="#" class="catelinkright" title="hello new one">Dell Wireless </a></h1></li>
             <li><h1><a href="#" class="catelinkright" title="hello new one">Laptop IBM </a></h1></li>
             <li><h1><a href="#" class="catelinkright" title="hello new one">	Compaq Presario </a></h1></li>
             <li><h1><a href="#" class="catelinkright" title="hello new one">	HP Notebook </a></h1></li>
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


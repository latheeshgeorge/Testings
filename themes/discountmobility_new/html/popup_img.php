<?php 
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
	$url = $ecom_selfhttp.$_REQUEST['h'].'/images/'.$_REQUEST['h'].'/'.$_REQUEST['f'];
?>
<HTML>
<HEAD>
 <TITLE>Zoom</TITLE>
 <script language="javascript" type="text/javascript">
<!--
var i=0;
function resize() {
  i=0;
//  if (navigator.appName == 'Netscape') i=20;
  if (window.navigator.userAgent.indexOf('MSIE 6.0') != -1 && window.navigator.userAgent.indexOf('SV1') != -1) {
      i=30; //This browser is Internet Explorer 6.x on Windows XP SP2
  } else if (window.navigator.userAgent.indexOf('MSIE 6.0') != -1) {
      i=0; //This browser is Internet Explorer 6.x
  } else if (window.navigator.userAgent.indexOf('Firefox') != -1 && window.navigator.userAgent.indexOf("Windows") != -1) {
      i=25; //This browser is Firefox on Windows
  } else if (window.navigator.userAgent.indexOf('Mozilla') != -1 && window.navigator.userAgent.indexOf("Windows") != -1) {
      i=45; //This browser is Mozilla on Windows
  } else {
      i=80; //This is all other browsers including Mozilla on Linux
  }
 /* if (document.documentElement && document.documentElement.clientWidth) {*/
//    frameWidth = document.documentElement.clientWidth;
//    frameHeight = document.documentElement.clientHeight;

  imgHeight = document.images[0].height+40-i;
  imgWidth = document.images[0].width+20;

  var height = screen.height;
  var width = screen.width;
  var leftpos = width / 2 - imgWidth / 2;
  var toppos = height / 2 - imgHeight / 2;

    frameWidth = imgWidth;
    frameHeight = imgHeight+i;

  window.moveTo(leftpos, toppos);


//  window.resizeTo(imgWidth, imgHeight);
  window.resizeTo(frameWidth,frameHeight+i);
/*}
  else if (document.body) 
  {
		if (document.body && document.body.offsetWidth) {
		  links = screen.availWidth - document.body.offsetWidth;
		  oben = screen.availHeight - document.body.offsetHeight;
		} else {
		  links = screen.availWidth - window.outerWidth;
		  oben = screen.availHeight - window.outerHeight;
		}
	 	 var height = screen.height;
		  var width = screen.width;
		  var leftpos = width / 2 - imgWidth / 2;
		  var toppos = height / 2 - imgHeight / 2;
		
			frameWidth = imgWidth;
			frameHeight = imgHeight+i;

  window.moveTo(leftpos, toppos);
	
	
    window.resizeTo(document.body.clientWidth, document.body.clientHeight-i);
  }*/
  self.focus();
}
//--></script>
<style type="text/css">
centeredContent, TH, #cartEmptyText, #cartBoxGVButton, #cartBoxEmpty, #cartBoxVoucherBalance, #navCatTabsWrapper, #navEZPageNextPrev, #bannerOne, #bannerTwo, #bannerThree, #bannerFour, #bannerFive, #bannerSix, #siteinfoLegal, #siteinfoCredits, #siteinfoStatus, #siteinfoIP, .center, .cartRemoveItemDisplay, .cartQuantityUpdate, .cartQuantity, .cartTotalsDisplay, #cartBoxGVBalance, .leftBoxHeading, .centerBoxHeading,.rightBoxHeading, .productListing-data, .accountQuantityDisplay, .ratingRow, LABEL#textAreaReviews, #productMainImage, #reviewsInfoDefaultProductImage, #productReviewsDefaultProductImage, #reviewWriteMainImage, .centerBoxContents, .specialsListBoxContents, .categoryListBoxContents, .additionalImages, .centerBoxContentsSpecials, .centerBoxContentsAlsoPurch, .centerBoxContentsFeatured, .centerBoxContentsNew, .gvBal, .attribImg 
{
	text-align: center;
}
</style>
</head>
<body id="popupImage" class="centeredContent" onLoad="resize();">
<div>
 <a href='javascript:window.close()' title ='Click on Image to close'><img src='<?php echo $url?>' border=0 title='Click on Image to close' alt='Click on Image to close'></a>
 </div>
</body></html>

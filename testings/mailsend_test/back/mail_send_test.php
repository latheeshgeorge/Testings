<?
//$email = $_REQUEST["email"];
$headers = "From: test <online.orders@discount-mobility.co.uk>\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
ob_start();
?>
<p style="text-align: center;"><span style="color: #ff0000;"><strong>THIS DECEMBER DISCOUNT MOBILITY ARE OFFERING SOME AMAZING DEALS ON MOBILTY EQUIPMENT. TAKE A LOOK ONLINE TO VIEW THE LOWEST EVER PRICES!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CLICK <a title="VISIT DISCOUNT MOBILITY" href="http://www.discount-mobility.co.uk">HERE</a> TO VIEW MORE OFFERS.</strong></span></p>
<p style="text-align: center;"><span style="color: #808080; font-size: medium;"><strong>SAVE UP TO 70% IN ALL DEPARTMENTS</strong></span></p>
<p style="text-align: center;"><span style="color: #808080;"><strong><a title="Discount Mobility Scooters" href="http://www.discount-mobility.co.uk/c77690/travel-mobility-scooters.html"><span style="color: #808080;">MOBILITY SCOOTERS</span></a>&nbsp; -&nbsp; <a title="DISCOUNT WHEELCHAIRS" href="http://www.discount-mobility.co.uk/c77683/wheelchairs.html"><span style="color: #808080;">WHEELCHAIRS</span></a>&nbsp; -&nbsp; <a title="DISCOUNT POWERCHAIRS/ELECTRIC WHEELCHAIRS" href="http://www.discount-mobility.co.uk/c77703/powerchairs-electric-wheelchairs.html"><span style="color: #808080;">POWERCHAIRS/ELECTRIC WHEELCHAIRS</span></a>&nbsp; -&nbsp; <a title="RISER RECLINER CHAIRS" href="RISER%20RECLINER%20CHAIRS"><span style="color: #808080;">RISER RECLINER CHAIRS</span></a>&nbsp; -&nbsp; <a title="DISCOUNT ROLLATORS" href="http://www.discount-mobility.co.uk/c77684/walking-aids-walkers-rollators.html"><span style="color: #808080;">ROLLATORS</span></a></strong></span></p>
<p style="text-align: center;">&nbsp;</p>
<table style="width: 626px;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td align="left" valign="top"><a title="70% OFF SALE" href="http://www.discount-mobility.co.uk/pg50428/70-off-mobility-sale.html"><img src="http://www.thewebclinic.co.uk/temp/discount-mobility-newsletter/jul2016/15/images2/newstop.png" alt="" /></a></td>
</tr>
<tr>
<td align="left" valign="top"><img usemap="#Map" src="http://www.thewebclinic.co.uk/temp/discount-mobility-newsletter/jul2016/17/images/newsletter_02.jpg" border="0" alt="" width="626" height="317" /></td>
</tr>
<tr>
<td align="left" valign="top"><img usemap="#Map2" src="http://www.thewebclinic.co.uk/temp/discount-mobility-newsletter/jul2016/17/images/newsletter_03.jpg" border="0" alt="" width="626" height="324" /></td>
</tr>
<tr>
<td align="left" valign="top"><img usemap="#Map3" src="http://www.thewebclinic.co.uk/temp/discount-mobility-newsletter/jul2016/16/images/newsletter_04.jpg" border="0" alt="" width="626" height="309" /></td>
</tr>
<tr>
<td align="left" valign="top" bgcolor="#1688ad">
<table style="width: 600px;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td><span style="font: 12px Arial, Helvetica, sans-serif; color: #fff; padding: 4px; display: block; text-align: center;">Address: Discount Mobility, 7 Kings Arcade, Lancaster LA1 1LE, United Kingdom<br /> Phone: +44 1245 905144<br /> </span></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="font: normal 11px Arial, Helvetica, sans-serif; color: #989696; text-align: center;" height="30"><span style="font-size: x-small; color: #333333; font-family: Arial, Helvetica, sans-serif;"><strong><a title="Unsubscribe Me" href="mailto:marketing@discount-mobility.co.uk"><span style="font-family: Arial,Helvetica,sans-serif; font-size: 11px; color: #c94545;">Click here</span></a></strong><span style="font-family: Arial,Helvetica,sans-serif; font-size: 11px; color: #4e4e4e; font-weight: bold;"> to unsubscribe. (Please provide the email address you subscribed with &amp; our team will remove you)</span></span>&nbsp;</td>
</tr>
</tbody>
</table>
<p>
<map id="Map" name="Map">
<area shape="rect" coords="1,6,208,317" href="http://www.discount-mobility.co.uk/mobility-scooters-c77689.html" target="_blank" />
<area shape="rect" coords="211,8,417,317" href="http://www.discount-mobility.co.uk/c77703/powerchairs-electric-wheelchairs.html" target="_blank" />
<area shape="rect" coords="420,9,624,320" href="http://www.discount-mobility.co.uk/c77683/wheelchairs.html" target="_blank" /> 
</map>
<map id="Map2" name="Map2">
<area shape="rect" coords="1,10,207,320" href="http://www.discount-mobility.co.uk/c78010/powerstrolls.html" target="_blank" />
<area shape="rect" coords="210,10,417,318" href="http://www.discount-mobility.co.uk/c77819/single-motor-riser-recliner-chairs.html" target="_blank" />
<area shape="rect" coords="419,12,623,317" href="http://www.discount-mobility.co.uk/c77686/bath-lifts.html" target="_blank" /> 
</map>
<map id="Map3" name="Map3">
<area shape="rect" coords="1,1,309,307" href="http://www.discount-mobility.co.uk/c78009/4-wheel-walkers.html" target="_blank" />
<area shape="rect" coords="317,1,624,304" href="http://www.discount-mobility.co.uk/c77730/adjustable-beds.html" target="_blank" /> 
</map>
</p>
<?php
$message = ob_get_contents();
ob_end_clean();
	$subj = "Test Newsletter Discountmobility 06 Dec 2017 --- 5";
	//mail('sony.joy@calpinetech.com',$subj, $message,$headers);
	mail('latheesh.george@thewebclinic.co.uk', $subj, $message,$headers);
	mail('latheesh.george@calpinetech.com', $subj, $message,$headers);
	mail('padmaraj.payyur@calpinetech.com', $subj, $message,$headers);
	mail('manu.venketesh@gmail.com', $subj, $message,$headers);
	mail('latheeshgeorge@gmail.com', $subj, $message,$headers);
	mail('manuvprabhu@outlook.com', $subj, $message,$headers);
	mail('manu.venketesh@calpinetech.com', $subj, $message,$headers);

	//mail('sales-2@webclinicmailer.co.uk', $subj, $message,$headers);
	//mail('serversupport@thewebclinic.co.uk', $subj, $message,$headers);
	echo "
			<script type='text/javascript'>
			alert('Mail Send Successfully');
			</script>
		";
?>

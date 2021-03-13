<?php 
	$ecom_bypass_loggedin_check = 1; // done to bypass the "is logged in?" checking inside the session.php file
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");
	$ajax_return_function = 'ajax_return_events_details';
	include "ajax/ajax.php";
	
	
$month_Names = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
$Current_Month = $_REQUEST["month"];
$Current_Year = $_REQUEST["year"];
$prev_year = $Current_Year;
$next_year = $Current_Year;
$prev_month = $Current_Month-1;
$next_month = $Current_Month+1;
if ($prev_month == 0 )
 {
	$prev_month = 12;
	$prev_year = $Current_Year - 1;
}
if ($next_month == 13 ) {
	$next_month = 1;
	$next_year = $Current_Year + 1;
}
?>
<link href="css/event_calendar_css.css" rel="stylesheet" media="screen">
<script language="JavaScript" src="js/validation.js"></script>
<script type="text/javascript">
function close_event()
{
	if (document.getElementById('event_contentdiv'))
	{
		document.getElementById('event_contentdiv').style.display = 'none';
	}
	document.getElementById('reload_required').value = '';
}
function handle_ajax_manage_eventadd(day,month,year)
{
	var fpurpose 		= ''; 
	var qtystr 			= 'dy='+day+'&mon='+month+'&yr='+year;
	fpurpose 			='show_add_event';
	if(document.getElementById('calendar_erro_td'))
		document.getElementById('calendar_erro_td').style.display = 'none';
	retobj 				= document.getElementById('event_contentdiv');
	document.getElementById('ajax_div_hold').value = 'event_contentdiv';
	document.getElementById('reload_required').value = '';
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&'+qtystr);
}
function handle_ajax_manage_eventedit(id)
{
	var fpurpose 		= ''; 
	var qtystr 			= 'edit_id='+id;
	fpurpose 			='show_edit_event';
	if(document.getElementById('calendar_erro_td'))
		document.getElementById('calendar_erro_td').style.display = 'none';
	retobj 				= document.getElementById('event_contentdiv');
	document.getElementById('ajax_div_hold').value = 'event_contentdiv';
	document.getElementById('reload_required').value = '';
	
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&'+qtystr);
}
function ajax_return_events_details()
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('ajax_div_hold').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			targetobj.style.display = '';
			var alrt = '';
			if(document.getElementById('reload_required').value ==1)
			{
				if(document.getElementById('calendar_alert'))
				{
					if(document.getElementById('calendar_alert').value!='')
					{
						alrt =document.getElementById('calendar_alert').value;
					}	
				}
				document.getElementById('reload_required').value = '';
				var p_mon = '<?php echo $_REQUEST['month']?>';
				var p_year = '<?php echo $_REQUEST['year']?>';
				parent.handle_console_home('show_todo_list');
				document.location = 'event_calendar.php?month='+p_mon+'&year='+p_year+'&cal_alert='+alrt;
				/*location.reload(true);*/
			}	
		}
		else
		{
			if(req.status)
			{
				alert('Session Expired, Please refresh your browser and login again');
			}
		}
	}
}
function  handle_event_add_save(dy,mn,yr)
{
	var sid				= <?php echo $ecom_siteid?>;
	var fpurpose 		= ''; 
	var qtystr 			= 'dy='+dy+'&mn='+mn+'&yr='+yr+'&sid='+sid;
	if(document.getElementById('calendar_erro_td'))
		document.getElementById('calendar_erro_td').style.display = 'none';
	/* validating fields */
	frm = document.getElementById('calendar_event_add');
	fieldRequired = Array('txt_event_title','txt_event_desc');
	fieldDescription = Array('Title','Description');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) 
	{
		var title = document.getElementById('txt_event_title').value;
		var desc = document.getElementById('txt_event_desc').value;
		var order = document.getElementById('txt_event_order').value;
		var hr = document.getElementById('cbo_event_hr').value;
		var mns = document.getElementById('cbo_event_mn').value;
		qtystr				= qtystr +'&title='+title+'&desc='+desc+'&order='+order+'&hr='+hr+'&mns='+mns; 
		fpurpose 			= 'show_add_event_save';
		retobj 				= document.getElementById('event_contentdiv');
		document.getElementById('ajax_div_hold').value = 'event_contentdiv';
		document.getElementById('reload_required').value = '1';
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&'+qtystr);
	}
}
function  handle_event_edit_save(id,dy,mn,yr)
{
	var sid				= <?php echo $ecom_siteid?>;
	var fpurpose 		= ''; 
	var qtystr 			= 'edit_id='+id+'&dy='+dy+'&mn='+mn+'&yr='+yr+'&sid='+sid;
	if(document.getElementById('calendar_erro_td'))
		document.getElementById('calendar_erro_td').style.display = 'none';
	/* validating fields */
	frm = document.getElementById('calendar_event_edit');
	fieldRequired = Array('txt_event_title','txt_event_desc');
	fieldDescription = Array('Title','Description');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) 
	{
		var title = document.getElementById('txt_event_title').value;
		var desc = document.getElementById('txt_event_desc').value;
		var order = document.getElementById('txt_event_order').value;
		var hr = document.getElementById('cbo_event_hr').value;
		var mns = document.getElementById('cbo_event_mn').value;
		var susp  = 0;
		if(document.getElementById('chk_event_suspend').checked)
			susp = 1;
		qtystr				= qtystr +'&title='+title+'&desc='+desc+'&order='+order+'&hr='+hr+'&mns='+mns+'&susp='+susp; 
		fpurpose 			= 'show_edit_event_save';
		retobj 				= document.getElementById('event_contentdiv');
		document.getElementById('ajax_div_hold').value = 'event_contentdiv';
		document.getElementById('reload_required').value = '1';
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&'+qtystr);
	}
}
function event_delete(delid)
{
	if(confirm('Are you sure you want to delete this event?'))
	{
		var sid				= <?php echo $ecom_siteid?>;
		var fpurpose 		= ''; 
		var qtystr 			= 'd_id='+delid+'&sid='+sid;
		fpurpose 			= 'show_delete_event_save';
		retobj 				= document.getElementById('event_contentdiv');
		document.getElementById('ajax_div_hold').value = 'event_contentdiv';
		document.getElementById('reload_required').value = '1';
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/default_console_home.php','fpurpose='+fpurpose+'&'+qtystr);
	}	
}

</script>
<input type="hidden" name="ajax_div_hold" id="ajax_div_hold" value="">
<input type="hidden" name="reload_required" id="reload_required" value="">

<div id="event_contentdiv" style="display:none" class="event_editdiv_cls">

</div>
<div id="event_editdiv" style="display:none" class="event_editdiv_cls">

</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="calendar_outer_table">
<tr align="center">
<td class="calendar_error_msg" align="center" id="calendar_erro_td"><?php echo $_REQUEST['cal_alert']?></td>
</tr>

<tr align="center">
<td class="calendar_main_header">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="calendar_outer_inner_table">
<tr>
<td align="right">  <a href="<?php echo $_SERVER["PHP_SELF"] . "?month=". $prev_month . "&year=" . $prev_year; ?>" class="calendar_previous"><img src="images/calendar_l.png" border="0"></a><a href="<?php echo $_SERVER["PHP_SELF"] . "?month=". $next_month . "&year=" . $next_year; ?>" class="calendar_next"><img src="images/calendar_r.png" border="0"></a>  </td>
</tr>
</table>

</td>

</tr>

<tr>

<td align="center">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="calendar_inner_table_ot">

<tr align="center">

<td colspan="7" class="calendar_monthhead"><strong><?php echo $month_Names[$Current_Month-1].' '.$Current_Year; ?></strong></td>

</tr>

<tr>

<td align="center" class="calendar_weekday"><strong>Sun</strong></td>

<td align="center" class="calendar_weekday"><strong>Mon</strong></td>

<td align="center" class="calendar_weekday"><strong>Tue</strong></td>

<td align="center" class="calendar_weekday"><strong>Wed</strong></td>

<td align="center" class="calendar_weekday"><strong>Thu</strong></td>

<td align="center" class="calendar_weekday"><strong>Fri</strong></td>

<td align="center" class="calendar_weekday"><strong>Sat</strong></td>

</tr>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="calendar_inner_table">

<?php
$timestamp = mktime(0,0,0,$Current_Month,1,$Current_Year);
$maxday = date("t",$timestamp);
$thismonth = getdate ($timestamp);

// get the list of events for current month for current website

$startday = $thismonth['wday'];
for ($i=0; $i<($maxday+$startday); $i++) {
if(($i % 7) == 0 ) echo "<tr>\n";
if($i < $startday) echo "<td class='caleandar_td'></td>\n";

else 
{
	$events_str = '';
	// Get the list of events for the current day from table for current website
	$cday = $Current_Year.'-'.$Current_Month.'-'.($i - $startday + 1);
	$cdayonly = ($i - $startday + 1);
	$sql_events = "SELECT event_id,event_title,event_suspend FROM events_calendar WHERE sites_site_id = $ecom_siteid AND (event_date BETWEEN '$cday 00:00:00' AND '$cday 23:59:59') ORDER BY event_order"; 
	$ret_events = $db->query($sql_events);
	if($db->num_rows($ret_events))
	{
		while ($row_events = $db->fetch_array($ret_events))
		{
			$cls = ($row_events['event_suspend']==0)?'event_title':'event_title_suspended';
			$events_str .= "<div class='$cls' title='Click to manage' onclick='handle_ajax_manage_eventedit(".$row_events['event_id'].")'>".stripslashes($row_events['event_title'])."</div>";
		}
	}
	echo "<td class='caleandar_td' valign='top'>
	<div class='calendar_icons'><a title='Click to add event' href='javascript:handle_ajax_manage_eventadd(".$cdayonly.",".$Current_Month.','.$Current_Year.")'><img src='images/calendar_add.gif' border='0'></a></div>
	<div class='calendar_date'>". ($i - $startday + 1) . "</div>
	
	<div class='calendar_eventlist'>".$events_str."</div>
	</td>\n";
}	
if(($i % 7) == 6 ) echo "</tr>\n";

}
if($i%7!=0)
 echo "<td colspan='".(8-$i%7)."' class='caleandar_td'></tr>\n";
?></table>
</table>
</td>
</tr>
</table>
</body>
</html>

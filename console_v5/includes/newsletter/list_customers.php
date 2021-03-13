<?php
	/*#################################################################
	# Script Name 	: list_customers.php
	# Description 	: Page for listing customers for sending news letters
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
//Define constants for this page

$help_msg = get_help_messages('LIST_CUSTOMERS_ASS_NEWSLETTERS');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all1(document.frmlistNewsCustomers,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none1(document.frmlistNewsCustomers,\'checkbox[]\')"/>','Slno.','Customers','Customer email');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);

 $where_conditions = " ";

//#Search terms
$search_fields = array('news_custname');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
if(trim($_REQUEST[custgroupid])) {
	$custgroup_id = explode('~',$_REQUEST['custgroupid']);

} else {
	$custgroup_id = $_REQUEST['custgroup_id'];

}

if(trim($_REQUEST['corp_name'])) {

	$where_conditions = " WHERE customers.sites_site_id=$ecom_siteid AND customers.customer_activated='1' 
		                           AND customers.customer_hide='0'";
	$table_name = 'customers';
	$page_type  = 'Customers';
	$extractvar = 'customer_id AS custid, customer_title AS title, customer_fname AS fname, customer_email_7503 AS email ';
							   
	if($_REQUEST['corp_name']=='all') {
		
		
		$deptsql = "SELECT department_id FROM customers_corporation_department WHERE sites_site_id=$ecom_siteid";
		$deptres = $db->query($deptsql);
		$dept_id = '';
		while($deptrow = $db->fetch_array($deptres)) {

			$dept_id .= $deptrow['department_id'].","; 
			
		}
		$dept_id = substr($dept_id,0,strlen($dept_id)-1);
		
		$depart_id = "(".$dept_id.")";
		if($dept_id!='')
		$where_conditions .= " AND customers.customers_corporation_department_department_id IN ".$depart_id." " ;
		//#Search Options
	
		
	} else {
		$where_conditions = "WHERE customers.sites_site_id=$ecom_siteid ";
		$dept_id = '';
		if(is_array($_REQUEST['customer_dept'])) {
			foreach($_REQUEST['customer_dept'] AS $val) {
				$dept_id .= $val.","; 
			}
			$dept_id = substr($dept_id,0,strlen($dept_id)-1);
			$depart_id = "(".$dept_id.")";
			$where_conditions .= " AND customers.customers_corporation_department_department_id IN ".$depart_id." "; 
		} else {
		
			$deptsql = "SELECT department_id FROM customers_corporation_department WHERE sites_site_id=$ecom_siteid 
						AND customers_corporation_corporation_id=".$_REQUEST['corp_name'];
			$deptres = $db->query($deptsql);
			$dept_id = '';
			while($deptrow = $db->fetch_array($deptres)) {
				
				$dept_id .= $deptrow['department_id'].","; 
			}
			
			$dept_id = substr($dept_id,0,strlen($dept_id)-1);
			$depart_id = "(".$dept_id.")";
			if($dept_id!='')
			$where_conditions .= " AND customers.customers_corporation_department_department_id IN ".$depart_id." " ;
		}
	}
	//#Search Options
	
	if($_REQUEST['pass_search_name']) {
		$where_conditions .= "AND ( customer_fname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%') ";
	} 
	if($_REQUEST['pass_search_mail']) {
		$where_conditions .= "AND customer_email_7503 LIKE '%".add_slash($_REQUEST['pass_search_mail'])."%' ";
	}
//#Sort
$sort_by = (!$_REQUEST['pass_sort_by'])?'customer_fname':$_REQUEST['pass_sort_by'];
$sort_order = (!$_REQUEST['pass_sort_order'])?'ASC':$_REQUEST['pass_sort_order'];
$sort_options = array('customer_fname' => 'Subscriber Name');
$sort_option_txt = generateselectbox('pass_sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('pass_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

} 
else if($_REQUEST['ftype']=='regcustomer') {
	$where_conditions = "WHERE customers.sites_site_id=$ecom_siteid 
								AND customers.customer_activated='1' AND customers.customer_hide='0'";
	$table_name = 'customers';
	$page_type  = 'Customers';
	$extractvar = 'customer_id AS custid, customer_title AS title, customer_fname AS fname, customer_email_7503 AS email ';							
	
	if($_REQUEST['pass_search_name'])
	{
		//$where_conditions .= "AND ( customer_fname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%') ";
		$where_conditions .= " AND ( customer_fname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%' OR 
								customer_surname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%' OR
								concat(customer_fname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['pass_search_name'])."%')";	
	}	
	if($_REQUEST['pass_search_mail'])
	{
		$where_conditions .= " AND customer_email_7503 LIKE '%".add_slash($_REQUEST['pass_search_mail'])."%' ";
	}
//#Sort
$sort_by = (!$_REQUEST['pass_sort_by'])?'customer_fname':$_REQUEST['pass_sort_by'];
$sort_order = (!$_REQUEST['pass_sort_order'])?'ASC':$_REQUEST['pass_sort_order'];
$sort_options = array('customer_fname' => 'Subscriber Name');
$sort_option_txt = generateselectbox('pass_sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('pass_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
}
elseif($_REQUEST['newsletter_cust_all']){
	$table_name='newsletter_customers';
	$page_type='Customers';
	$extractvar = 'news_customer_id AS custid, news_title AS title, news_custname AS fname, news_custemail AS email ';		
	
	$where_conditions = " WHERE newsletter_customers.sites_site_id=$ecom_siteid AND news_custhide='0' ";
	
		//#Search Options
	if($_REQUEST['pass_search_name']) {
		$where_conditions .= " AND ( news_custname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%') ";
	}
	if($_REQUEST['pass_search_mail']) {
		$where_conditions .= " AND news_custemail LIKE '%".add_slash($_REQUEST['pass_search_mail'])."%' ";
	}
	//#Sort
$sort_by = (!$_REQUEST['pass_sort_by'])?'news_custname':$_REQUEST['pass_sort_by'];
$sort_order = (!$_REQUEST['pass_sort_order'])?'ASC':$_REQUEST['pass_sort_order'];
$sort_options = array('news_custname' => 'Subscriber Name');
$sort_option_txt = generateselectbox('pass_sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('pass_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
}
else if(is_array($custgroup_id)) {
    
	$custgroupid = ""; 
	$table_name='newsletter_customers';
	$page_type='Customers';
	$extractvar = ' news_customer_id AS custid, news_title AS title, news_custname AS fname, news_custemail AS email ';		
	
	$where_conditions = "WHERE newsletter_customers.sites_site_id=$ecom_siteid ";
	
	foreach($custgroup_id AS $val) {  //$_REQUEST['custgroup_id']
	    
		$custgroupid  .= $val."~";
		 
		$newsql = "SELECT customer_id
							FROM customer_newsletter_group_customers_map 
									WHERE custgroup_id=".$val;
		$newres = $db->query($newsql);
		while($newrow = $db->fetch_array($newres)) {
			$custid .= $newrow['customer_id'].",";
		}
	}					
		$custid = substr($custid,0,strlen($custid)-1);
		$custid = "(".$custid.")";
		
		$custgroupid = substr($custgroupid,0,strlen($custgroupid)-1);
		
		
		$where_conditions .= " AND newsletter_customers.news_customer_id IN ".$custid." AND news_custhide='0'" ;							

	//#Search Options
	
	if($_REQUEST['pass_search_name']) {
		$where_conditions .= " AND ( news_custname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%') ";
	}
	if($_REQUEST['pass_search_mail']) {
		$where_conditions .= " AND news_custemail LIKE '%".add_slash($_REQUEST['pass_search_mail'])."%' ";
	}
	//#Sort
$sort_by = (!$_REQUEST['pass_sort_by'])?'news_custname':$_REQUEST['pass_sort_by'];
$sort_order = (!$_REQUEST['pass_sort_order'])?'ASC':$_REQUEST['pass_sort_order'];
$sort_options = array('news_custname' => 'Subscriber Name');
$sort_option_txt = generateselectbox('pass_sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('pass_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

}
elseif($_REQUEST['filterorders']==1)
{
	
	$date_from_arr	= explode('-',$_REQUEST['newsfrom_date']);
	$date_to_arr 	= explode('-',$_REQUEST['newsto_date']);
	$date_from 		= $date_from_arr[2].'-'.$date_from_arr[1].'-'.$date_from_arr[0];
	$date_to 		= $date_to_arr[2].'-'.$date_to_arr[1].'-'.$date_to_arr[0];
	$selected_cats	= $_REQUEST['newscategory_id'];
	
}

if($_REQUEST['filterorders']!=1  and ($_REQUEST['filteimported']!=1))
{
	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions 	";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);#Getting total count of records

	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['pass_records_per_page']) and $_REQUEST['pass_records_per_page']>0)?intval($_REQUEST['pass_records_per_page']):10;#Total records shown in a page
	$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pass_pg'];

	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);#Getting the total pages
	if($pg > $pages) {
		$pg = $pages;
	}
	$start = ($pg - 1) * $records_per_page;#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}
/////////////////////////////////////////////////////////////////////////////////////
/* TO FIND THE NEWS LETTER NAME*/
$newsletter_id=($_REQUEST['newsletter_id']?$_REQUEST['newsletter_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT newsletter_id,newsletter_title FROM newsletters WHERE sites_site_id=$ecom_siteid AND newsletter_id=".$newsletter_id;
$res=$db->query($sql);
$newsletter=$db->fetch_array($res);
$newsletter_name = $newsletter['newsletter_title'];

if($_REQUEST['filteimported']==1)
{
?>
<form name="frmlistNewsCustomers" action="home.php" method="post" >	
	<input type="hidden" name="fpurpose" value="listcustomers" />
	<input type="hidden" name="request" value="newsletter" />
	<input type="hidden" name="newsletter_id" value="<?=$newsletter_id?>" />
	<input type="hidden" name="filteimported" value="<?=$_REQUEST['filteimported']?>" />
	<input  type="hidden" name="email_mode" id="email_mode" value="0"  />
	<table border="0" cellpadding="0" cellspacing="0" class="maintable">
	<tr>
		<td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a> <a href="home.php?request=newsletter&fpurpose=preview&newsletter_id=<?=$newsletter_id?>"> Preview Newsletter </a> <a href="home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?=$newsletter_id?>">List Customer Groups </a><span>Confirm the sending out the newsletter: "<b><?=$newsletter_name?></b>"</span></td>
	</tr>
	<tr>
	<td align="left" valign="middle" class="helpmsgtd_main">
		<?php 
		Display_Main_Help_msg($help_arr,$help_msg);
		?>
	</td>
	</tr>
	<tr>
	<td align="left" valign="middle" > <?php echo newsletter_tabs('custom_tab_td',$newsletter_id,1,$newsletter_name) ?></td>
	</tr>
	<?php 
	if($alert)
	{			
?>
  <tr>
    <td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" style="padding:20px 0 10px 0"><a class="smalllink" href="home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?php echo $newsletter_id?>"><strong>Please Click here to go back and refine your filter criteria.</strong></a></td>
  </tr>
<?php
	}
	else
	{
 ?>	
	<tr>
    <td height="48" class="sorttd" colspan="3" >
  <?php 
  // Check whether any newsletter schedule remains for current website
  $sql_check = "SELECT main_id  
  					FROM 
  						newsletter_cron_main 
  					WHERE 
  						hostname='".$ecom_hostname."' 
  					LIMIT 
  						1";
  $ret_check = $db->query($sql_check);
  if($db->num_rows($ret_check))
  { 
  ?>
    <div id='email_div'>
    <?php show_newsletter_schedule()?>
    </div>
  <?
  }
  ?>
   </td>
    </tr>
		<tr>
		<td align="center" style="padding-top:100px;">
		<input name="send_newsletter" type="button" class="red" id="send_newsletter" value="Click Here To Schedule The Newslettert to Imported Customers" onclick="handle_importedcust_send()" />
		</td>
		</tr>
<?php
	}
?>	
	</table>
	</form>	
	<script type="text/javascript">
	function handle_filterorder_send()
	{
		if(confirm('Are you sure you wanted to schedule the Newsletter?'))
		{
			show_processing();
			document.frmlistNewsCustomers.fpurpose.value='sendmail';
			document.frmlistNewsCustomers.submit();
		}	
	}	
	function handle_importedcust_send()
	{
		if(confirm('Are you sure you wanted to schedule the Newsletter to Imported Customers?'))
		{
			show_processing();
			document.frmlistNewsCustomers.fpurpose.value='sendmail';
			document.frmlistNewsCustomers.submit();
		}	
	}
	function call_ajax_deleteemail(delid)
	{
		var atleastone 	= 0;
		var del_ids 	= '';
		var qrystr		= '';
		if(confirm('Are you sure you want to delete the selected newsletter schedule?'))
		{
			document.getElementById('email_div').innerHTML='<center><img src="images/loading.gif" alt="Loading"></center>';
			document.getElementById('email_mode').value = 1;
			Handlewith_Ajax('services/newsletter.php','fpurpose=del_email_schedule&rmid='+delid+'&'+qrystr);
		}	
	}
	function ajax_return_contents() 
	{
		var ret_val='';
		if(req.readyState==4)
		{
			if(req.status==200)
			{
				ret_val = req.responseText;
				if(document.getElementById('email_mode').value==1)
				{
					document.getElementById('email_mode').value = 0;
					document.getElementById('email_div').innerHTML=ret_val;
				}
				else
					document.getElementById('maincontent').innerHTML=ret_val;
				hide_processing();
			}
			else
			{
				 show_request_alert(req.status);
				//alert("Problem in requesting XML :"+req.statusText);
			}
		}
	}	
	</script>
<?php
	exit;
}

if($_REQUEST['filterorders']==1)
{
?>
	<form name="frmlistNewsCustomers" action="home.php" method="post" >	
	<input type="hidden" name="fpurpose" value="listcustomers" />
	<input type="hidden" name="request" value="newsletter" />
	<input type="hidden" name="newsletter_id" value="<?=$newsletter_id?>" />
	<input type="hidden" name="filterorders" value="<?=$_REQUEST['filterorders']?>" />
	<input type="hidden" name="date_from" value="<?=$date_from?>" />
	<input type="hidden" name="date_to" value="<?=$date_to?>" />
	<input  type="hidden" name="email_mode" id="email_mode" value="0"  />
	<?php 
	if($_REQUEST['newscategory_id'])
	{
	?>
		<input type="hidden" name="selected_cats" value="<? echo implode(',',$_REQUEST['newscategory_id'])?>" />
	<?php
	}
	else
	{
	?>
	<input type="hidden" name="selected_cats" value="" />
	<?php	
	}
	?>

	<table border="0" cellpadding="0" cellspacing="0" class="maintable">
	<tr>
		<td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a> <a href="home.php?request=newsletter&fpurpose=preview&newsletter_id=<?=$newsletter_id?>"> Preview Newsletter </a> <a href="home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?=$newsletter_id?>">List Customer Groups </a><span>Confirm the sending out the newsletter: "<b><?=$newsletter_name?></b>"</span></td>
	</tr>
	
	
	
	<tr>
	<td align="left" valign="middle" class="helpmsgtd_main">
		<?php 
		Display_Main_Help_msg($help_arr,$help_msg);
		?>
	</td>
	</tr>
	<tr>
	<td align="left" valign="middle" > <?php echo newsletter_tabs('custom_tab_td',$newsletter_id,1,$newsletter_name) ?></td>
	</tr>
	<?php 
	if($alert)
	{			
?>
  <tr>
    <td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
  </tr>
  <tr>
    <td align="center" valign="middle" style="padding:20px 0 10px 0"><a class="smalllink" href="home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?php echo $newsletter_id?>"><strong>Please Click here to go back and refine your filter criteria.</strong></a></td>
  </tr>
<?php
	}
	else
	{
 ?>	
	<tr>
    <td height="48" class="sorttd" colspan="3" >
  <?php 
  // Check whether any newsletter schedule remains for current website
  $sql_check = "SELECT main_id  
  					FROM 
  						newsletter_cron_main 
  					WHERE 
  						hostname='".$ecom_hostname."' 
  					LIMIT 
  						1";
  $ret_check = $db->query($sql_check);
  if($db->num_rows($ret_check))
  { 
  ?>
    <div id='email_div'>
    <?php show_newsletter_schedule()?>
    </div>
  <?
  }
  ?>
   </td>
    </tr>
		<tr>
		<td align="center" style="padding-top:100px;">
		<input name="send_newsletter" type="button" class="red" id="send_newsletter" value="Click Here To Schedule The Newsletter" onclick="handle_filterorder_send()" />
		</td>
		</tr>
<?php
	}
?>	
	</table>
	</form>	
	<script type="text/javascript">
	function handle_filterorder_send()
	{
		if(confirm('Are you sure you wanted to schedule the Newsletter?'))
		{
			show_processing();
			document.frmlistNewsCustomers.fpurpose.value='sendmail';
			document.frmlistNewsCustomers.submit();
		}	
	}	
	function call_ajax_deleteemail(delid)
	{
		var atleastone 	= 0;
		var del_ids 	= '';
		var qrystr		= '';
		if(confirm('Are you sure you want to delete the selected newsletter schedule?'))
		{
			document.getElementById('email_div').innerHTML='<center><img src="images/loading.gif" alt="Loading"></center>';
			document.getElementById('email_mode').value = 1;
			Handlewith_Ajax('services/newsletter.php','fpurpose=del_email_schedule&rmid='+delid+'&'+qrystr);
		}	
	}
	function ajax_return_contents() 
	{
		var ret_val='';
		if(req.readyState==4)
		{
			if(req.status==200)
			{
				ret_val = req.responseText;
				if(document.getElementById('email_mode').value==1)
				{
					document.getElementById('email_mode').value = 0;
					document.getElementById('email_div').innerHTML=ret_val;
				}
				else
					document.getElementById('maincontent').innerHTML=ret_val;
				hide_processing();
			}
			else
			{
				 show_request_alert(req.status);
				//alert("Problem in requesting XML :"+req.statusText);
			}
		}
	}	
	</script>
<?php
	exit;
}

$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=newsletter&fpurpose=listcustomers&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function select_all1(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		var selectedcust='';
	
		//if (frm.selected_customers.value!='' )
			//frm.selected_customers.value = '';
			//if (frm.selected_customers.value!='' ){
			var already_selected = frm.selected_customers.value;
			var selected_cust_arr = frm.selected_customers.value.split("~");
			var length= selected_cust_arr.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
				for(k=0;k<length;k++){
				var status_flag=0;
					if(frm.elements[i].value==selected_cust_arr[k]){
					status_flag=1;	
						break;				
					}
				}
					if(status_flag==0){
						selectedcust+= frm.elements[i].value+'~';
						}
				if (frm.elements[i].checked==false)
					frm.elements[i].checked = true;		
				if (frm.selected_customers.value=='' ){
				//frm.selected_customers.value += '~';
				frm.selected_customers.value += frm.elements[i].value+'~';
				}
			}
			//alert(already_selected+'~'+selectedcust);
			
			if(selectedcust!='')
			frm.selected_customers.value= already_selected+'~'+selectedcust;	
			}
			/////////////////////////////////////////////////
			
			////////////////////////////////
		//}
		//frm.selected_customers.value= selectedcust;
	}
	function select_none1(frm,obj)
	{
		var atleastone = false;
		var len  = frm.elements.length;
		for (i=0;i<len;i++)
		{
			
			if (frm.elements[i].type== "checkbox" && frm.elements[i].name ==obj) 
			{
			if (frm.selected_customers.value!='' ){
			var selected_cust_arr = frm.selected_customers.value.split("~");
			var length= selected_cust_arr.length;
			var selectedcust='';
			//alert(length);
				for(k=0;k<length;k++){
				//alert(selected_cust_arr[k]);
				if((selected_cust_arr[k]!=frm.elements[i].value) && selected_cust_arr[k]!='' ){
					//if (frm.elements[i].checked!=true){
					selectedcust+=selected_cust_arr[k]+'~';
					//alert(selected_cust_arr[k]);
					
					}
				}
				}
				
				frm.selected_customers.value=selectedcust;
				frm.elements[i].checked=false;
			//addSelected(frm.elements[i],document.frmlistNewsCustomers.selected_customers,'checkbox[]');
			}
		}
	}
function checkSelected()
{
	len=document.frmlistNewsCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNewsCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one advert ');
		return false;
	}
	show_processing();
	return true;
}
function addSelected(checkbox,arrayfeild,checkboxname){
	var cnt=0;		
	el=checkbox;
	if (el!=null && el.name== checkboxname )
	if(el.checked) {
		cnt++;
		len =  arrayfeild.value.length;
		if (arrayfeild.value!='' )
		if(arrayfeild.value.charAt(len-1)!='~')
		arrayfeild.value += '~';
		arrayfeild.value += el.value;
	}if(el.checked==false) {
		cnt--;
		if (arrayfeild.value!=''){
			var selected_cust_arr=arrayfeild.value.split("~");
			var arr_cnt = selected_cust_arr.length;
			var newcust_selected = '';
			for(i=0;i<arr_cnt;i++){
				if(selected_cust_arr[i]!=''){
					if(selected_cust_arr[i] != el.value){
					 newcust_selected += selected_cust_arr[i]+'~';
					}
				}
			}
		arrayfeild.value= newcust_selected;
		}	
	}

	//if(cnt==0) {
	//	alert('Please select atleast one advert ');
	//	return false;
	//}
	//show_processing();
	return true;
}

function edit_selected()
{
	
	len=document.frmlistNewsCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNewsCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one News Letter');
	}
	else if(cnt>1 ){
		alert('Please select only one News Letter to edit');
	}
	else
	{
		show_processing();
		document.frmlistNewsCustomers.fpurpose.value='edit';
		document.frmlistNewsCustomers.submit();
	}
	
	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			if(document.getElementById('email_mode').value==1)
			{
				document.getElementById('email_mode').value = 0;
				document.getElementById('email_div').innerHTML=ret_val;
			}
			else
				document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistNewsCustomers.elements.length;i++)
	{
		if (document.frmlistNewsCustomers.elements[i].type =='checkbox' && document.frmlistNewsCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistNewsCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistNewsCustomers.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Advert to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Advert?'))
		{
			show_processing();
			Handlewith_Ajax('services/adverts.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_deleteemail(delid)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr		= '';
	if(confirm('Are you sure you want to delete the selected newsletter schedule?'))
	{
		document.getElementById('email_div').innerHTML='<center><img src="images/loading.gif" alt="Loading"></center>';
		document.getElementById('email_mode').value = 1;
		Handlewith_Ajax('services/newsletter.php','fpurpose=del_email_schedule&rmid='+delid+'&'+qrystr);
	}	
}
 function Check_customer()
 {
 	len=document.frmlistNewsCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistNewsCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
		   }
		   if (el!=null && el.name== "allcustomers" )
		   if(el.checked) {
		   		cnt++;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Customer  ');
		return false;
	}
 	if(confirm('Are you Sure You want to send the mail'))
		{
			show_processing();
			document.frmlistNewsCustomers.fpurpose.value='sendmail';
			document.frmlistNewsCustomers.submit();
		}
		
 }
</script>
<form name="frmlistNewsCustomers" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="listcustomers" />
<input type="hidden" name="request" value="newsletter" />
<input type="hidden" name="newsletter_id" value="<?=$newsletter_id?>" />
<input type="hidden" name="pass_start" value="<?=$pass_start?>" />
<input type="hidden" name="pass_pg" value="<?=$pass_pg?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$sort_by?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$sort_order?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$search_name?>"  />

  
<input  type="hidden" name="table_name" id="table_name" value="<?=$table_name?>"  />
<input  type="hidden" name="extractvar" id="extractvar" value="<?=$extractvar?>"  />

<input  type="hidden" name="corp_name" id="corp_name" value="<?=$_REQUEST['corp_name']?>"  />
<input  type="hidden" name="dept_id" id="dept_id" value="<?=$dept_id?>"  />

<input  type="hidden" name="ftype" id="ftype" value="<?=$_REQUEST['ftype']?>"  />
<input  type="hidden" name="email_mode" id="email_mode" value="0"  />
<input  type="hidden" name="custgroupid" id="custgroupid" value="<?=$custgroupid?>"  /> 
<input  type="hidden" name="newsletter_cust_all" id="newsletter_cust_all" value="<?=$_REQUEST['newsletter_cust_all']?>"  /> 


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
  <tr>
    <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a> <a href="home.php?request=newsletter&fpurpose=preview&newsletter_id=<?=$newsletter_id?>"> Preview Newsletter </a> <a href="home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?=$newsletter_id?>">List Customer Groups </a><span>List Customers to send the news letter: "<b><?=$newsletter_name?></b>"</span></td>
  </tr>
 <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
  <tr>
          <td colspan="5" align="left" valign="middle" > <?PHP echo newsletter_tabs('custom_tab_td',$newsletter_id,1,$newsletter_name) ?></td>
    </tr>
  <?php 
	if($alert)
	{			
?>
  <tr>
    <td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
  </tr>
<?php
	}
 ?>
  <tr>
    <td height="48" class="sorttd" colspan="3" >
	<div class="sorttd_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td colspan="2"></td>
      </tr>
    </table>
        <table width="100%" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td width="13%" align="left" valign="middle">Customer Name </td>
            <td width="25%" align="left" valign="middle"><input name="pass_search_name" type="text" class="textfeild" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>"  /></td>
            <td width="12%" align="left" valign="middle">Customer Email </td>
            <td colspan="2" align="left" valign="middle"><input name="pass_search_mail" type="text" class="textfeild" id="pass_search_mail" value="<?=$_REQUEST['pass_search_mail']?>"></td>
          </tr>
          <tr>
            <td align="left" valign="middle">Show</td>
            <td align="left" valign="middle"><input name="pass_records_per_page2" type="text" class="textfeild" id="pass_records_per_page2" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
              <?=$page_type?>
Per Page</td>
            <td align="left" valign="middle">Sort By</td>
            <td width="42%" align="left" valign="middle"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?></td>
            <td width="8%" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_ASS_NEWSLETT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
        </table>
      </div></td>
  </tr>
  
  <?php 
  // Check whether any newsletter schedule remains for current website
  $sql_check = "SELECT main_id  
  					FROM 
  						newsletter_cron_main 
  					WHERE 
  						hostname='".$ecom_hostname."' 
  					LIMIT 
  						1";
  $ret_check = $db->query($sql_check);
  if($db->num_rows($ret_check))
  { 
  ?>
   <tr>
    <td height="48" class="sorttd" colspan="3" >
    <div id='email_div'>
    <?php show_newsletter_schedule()?>
    </div>
	  </td>
    </tr>
  <?
  }
  ?>
 
 
  <tr>
    <td colspan="3" class="listingarea">
	<?php 
	    $selected_customers_arr = array();
		 $already_selected_customers_arr = array();
	  if($_REQUEST['selected_customers']){
	  $selected_customers_arr = explode('~', $_REQUEST['selected_customers']);
	  }
	  ?>
	  <div class="listingarea_div">
        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		 <?php 
  if($numcount)
  {
  ?>
  <tr>
    <td class="listeditd" align="left" colspan="2">Send to All Customers <input name="allcustomers" type="checkbox" value="1" /></td>
    <td width="32%" align="right" class="listeditd">
	<?
	  if($numcount)
	  {
	   // paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); echo $pages;
	  ?>Pages
        <select name="pass_pg" onchange="document.frmlistNewsCustomers.submit()">
        <?php
	  		for($k=1;$k<=$pages;$k++)
			{
	?>
        	  <option value="<?=$k?>" <?=($k==$pg)?'selected=selected':'' ?> ><?=$k?></option>
    <?php 
		  	}
	?>
      	</select>   
	<? 
	 }
	?>
      </td>
    	<td width="51%" align="right" class="listeditd">
	<?
	  if($numcount)
	  {
	  ?>
      &nbsp;
      <input name="send_newsletter" type="button" class="red" id="send_newsletter" value="Send Newsletter" onclick="Check_customer()" />
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_ASS_NEWSLETT_ASS_NEWSLETT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
    <?
	}
	?>
		</td>
  </tr>
  <? }?>
       <?  
	 echo table_header($table_headers,$header_positions);
	 if($numcount)
	   { 
		$srno = 1;
		
	   $sql_group = "SELECT $extractvar 
	   							FROM $table_name 
									 $where_conditions 
	   										ORDER BY $sort_by $sort_order 
	   												LIMIT $start,$records_per_page ";
	    $res = $db->query($sql_group);
	    
	    $selected_customers='';
		
			while($row = $db->fetch_array($res))
			{
				$count_no++;
				$array_values = array();
				if($count_no %2 == 0)
					$class_val="listingtablestyleA";
				else
				$class_val="listingtablestyleB";
				
				
			 ?>
			  <tr>
				<td align="left" valign="middle" class="<?=$class_val;?>"  width="7%"><input name="checkbox[]" value="<? echo $row['custid']?>" id="checkbox[]" type="checkbox" 
					<?php if(is_array($selected_customers_arr))
						 {
							  if(in_array($row['custid'],$selected_customers_arr)) 
							  {
								  echo 'checked';
							  }
						 }?> onclick="addSelected(this,document.frmlistNewsCustomers.selected_customers,'checkbox[]');" />
				</td>
				<td align="left" valign="middle" class="<?=$class_val;?>"  width="10%"><?php echo $srno++?></td>
				<td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['title']."&nbsp;".$row['fname']?></td>
				<td align="left" valign="middle" class="<?=$class_val;?>"><?=mask_emails($row['email'])?></td>
			  </tr>
			  <?
			}
	  }
	  else
	  {
	  ?>
          <tr>
            <td align="center" valign="middle" class="norecordredtext"  colspan="<?=$colspan?>"> No Customers  exists. </td>
          </tr>
          <?
		}
		$customselId = substr($customselId,0,strlen($customselId)-1);
		?>
		<input  type="hidden" name="customselId" id="customselId" value="<?=$customselId?>"  />
		<input type="hidden" name="selected_customers" id="selected_customers" value="<?=$_REQUEST['selected_customers']?>" />
		<input type="hidden" name="already_selected_customers" id="already_selected_customers" value="<?=$alreadyselected_cust?>" />
      </table>
	  </div></td>
  </tr>
  
</table>
</form>
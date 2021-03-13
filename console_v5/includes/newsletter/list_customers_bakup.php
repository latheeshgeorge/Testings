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
$table_name='newsletter_customers';
$page_type='Customers';
$help_msg = get_help_messages('LIST_CUSTOMERS_ASS_NEWSLETTERS');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all1(document.frmlistNewsCustomers,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none1(document.frmlistNewsCustomers,\'checkbox[]\')"/>','Slno.','Customers','Customer email');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('news_custname');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['pass_sort_by'])?'news_custname':$_REQUEST['pass_sort_by'];
$sort_order = (!$_REQUEST['pass_sort_order'])?'ASC':$_REQUEST['pass_sort_order'];
$sort_options = array('news_custname' => 'Subscriber Name');
$sort_option_txt = generateselectbox('pass_sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('pass_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['pass_search_name']) {
	$where_conditions .= "AND ( news_custname LIKE '%".add_slash($_REQUEST['pass_search_name'])."%') OR ( news_custemail LIKE '%".add_slash($_REQUEST['pass_search_name'])."%')";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['pass_records_per_page']) and $_REQUEST['pass_records_per_page'])?$_REQUEST['pass_records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pass_pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
/* TO FIND THE NEWS LETTER NAME*/
$newsletter_id=($_REQUEST['newsletter_id']?$_REQUEST['newsletter_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT newsletter_id,newsletter_title FROM newsletters WHERE sites_site_id=$ecom_siteid AND newsletter_id=".$newsletter_id;
$res=$db->query($sql);
$newsletter=$db->fetch_array($res);
$newsletter_name = $newsletter['newsletter_title'];
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
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var newsletter_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistNewsCustomers.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistNewsCustomers.elements.length;i++)
	{
		if (document.frmlistNewsCustomers.elements[i].type =='checkbox' && document.frmlistNewsCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistNewsCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (newsletter_ids!='')
					newsletter_ids += '~';
				 newsletter_ids += document.frmlistNewsCustomers.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Adverts to change the status');
	}
	else
	{
		if(confirm('Change Status of Selected Advert(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/adverts.php','fpurpose=change_hide&'+qrystr+'&newsletter_ids='+newsletter_ids);
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
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
  <tr>
    <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Newsletter </a> >> List Customers to send the news letter: "<b><?=$newsletter_name?></b>"</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="middle" class="helpmsgtd"><div class="helpmsg_divcls"><?=$help_msg?></div></td>
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
  
      <?php 
	  $sql_listCustomerGroups = "SELECT
	  									 custgroup_name,cg.custgroup_id,count(cgcm.customer_id) as custcnt 
								 FROM 
										customer_newsletter_group cg,customer_newsletter_group_customers_map cgcm  
								 WHERE 
										 cg.sites_site_id=$ecom_siteid
								  AND
										cgcm.custgroup_id=cg.custgroup_id group by cgcm.custgroup_id";

$ret_listCustomerGroups = $db->query($sql_listCustomerGroups);
      $row=$db->fetch_array($ret_listCustomerGroups);
	  if($row['custcnt']>0){
	  ?>
	  <tr>
     <td height="48" class="sorttd" colspan="3" ><table width="100%" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td colspan="3" valign="top" align="left"><b>Customer Groups</b>
              <input name="selected_custgroups" type="hidden" class="textfeild" id="selected_custgroups" value="<?=$_REQUEST['selected_custgroups']?>" /></td>
      </tr>
      <tr>
        <?php 
		$cnt=0;
		$ret_listCustomerGroups = $db->query($sql_listCustomerGroups);
		$selectedGrouparr=explode('~',$_REQUEST['selected_custgroups']);
		while($listCustomerGroups = $db->fetch_array($ret_listCustomerGroups)) {
		$cnt++;
		?>
        <td width="3%"  align="left" valign="middle"><input name="custgroup_id[]" type="checkbox" class="textfeild" id="custgroup_id[]" <?=(in_array($listCustomerGroups['custgroup_id'],$selectedGrouparr))?'checked':''?> value="<?=$listCustomerGroups['custgroup_id']?>" onclick="addSelected(this,document.frmlistNewsCustomers.selected_custgroups,'custgroup_id[]');" /></td>
        <td  colspan="3" align="left" valign="middle"><?=$listCustomerGroups['custgroup_name']?>
          &nbsp;&nbsp;(
          <?=$listCustomerGroups['custcnt']?>
          )</td>
        <?
		  if($cnt == 3){
		  echo '</tr><tr>';
		  $cnt=0;
		  }
		  
		   }?>
      </tr>
    </table></td>
  </tr>
  <? }?>
  <tr class="sorttd" >
    <td width="311" style="padding-left:20px;" align="left"><b>Customers</b></td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td colspan="3" align="left" class="helpmsgtd" ><div class="helpmsg_divcls">Send to All Customers
      <input name="allcustomers" type="checkbox" value="1" /></div></td>
    </tr>
  <tr>
    <td height="48" class="sorttd" colspan="3" ><table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td colspan="2"></td>
      </tr>
    </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
          <tr>
            <td width="22%" align="left" valign="middle">Customer Name </td>
            <td colspan="3" align="left" valign="middle"><input name="pass_search_name" type="text" class="textfeild" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>"  /></td>
          </tr>
        </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
          <tr>
            <td width="12%" align="left">Show</td>
            <td width="41%" align="left"><input name="pass_records_per_page" type="text" class="textfeild" id="pass_records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                <?=$page_type?>
              Per Page</td>
            <td width="47%" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td align="left">Sort By</td>
            <td align="left" nowrap="nowrap"><?=$sort_option_txt?>
              in
              <?=$sort_by_txt?>            </td>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="button5" type="submit" class="red" id="button5" value="Go" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_ASS_NEWSLETT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table></td>
  </tr>
  <?
	  if($numcount)
	  {
	  ?>
  <tr>
    <td width="311" class="listeditd"></td>
    <td class="listeditd" width="133" align="center"><?
	  if($numcount)
	  {
	   // paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); echo $pages;
	  
	
	  ?>Pages
        <select name="pass_pg" onchange="document.frmlistNewsCustomers.submit()">
          <?php
	  for($k=1;$k<=$pages;$k++){?>
          <option value="<?=$k?>" <?=($k==$pg)?'selected=selected':'' ?> >
            <?=$k?>
          </option>
          <?php } ?>
      </select>   
	  <? }?>
	   </td>
    <td width="514"  align="right" class="listeditd"><?
	  if($numcount)
	  {
	  ?>
      &nbsp;
      <input name="send_newsletter" type="button" class="red" id="send_newsletter" value="Send Newsletter" onclick="if(confirm('Are you Sure You want to send the mail')){document.frmlistNewsCustomers.fpurpose.value='sendmail';document.frmlistNewsCustomers.submit();}" />
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_ASS_NEWSLETT_ASS_NEWSLETT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
      <?
		}
		?>    </td>
  </tr>
  <? }?>
  <tr>
    <td colspan="3" class="listingarea"><?php /*if($_REQUEST['already_selected_customers'] && $_REQUEST['selected_customers']){
	  $alreadyselected_arr = explode('~', $_REQUEST['already_selected_customers']);
	  
	   $alreadyselected_cust = $_REQUEST['already_selected_customers'].'~'.$_REQUEST['selected_customers'];
	  }elseif($_REQUEST['selected_customers'] && $_REQUEST['already_selected_customers']==''){
	   $alreadyselected_cust = $_REQUEST['selected_customers'];
	    $alreadyselected_arr = explode('~', $_REQUEST['already_selected_customers']);
	  }elseif($_REQUEST['selected_customers']=='' && $_REQUEST['already_selected_customers']){
	$alreadyselected_cust = $_REQUEST['already_selected_customers'];
	  $alreadyselected_arr = explode('~', $_REQUEST['already_selected_customers']);
	  }
	  print_r($alreadyselected_arr);*/
	    $selected_customers_arr = array();
		 $already_selected_customers_arr = array();
	  if($_REQUEST['selected_customers']){
	  $selected_customers_arr = explode('~', $_REQUEST['selected_customers']);
	  }
	 /* if($_REQUEST['already_selected_customers']){
	  $already_selected_customers_arr = explode('~', $_REQUEST['already_selected_customers']);
	  }
	  if(is_array($already_selected_customers_arr) && is_array($selected_customers_arr)){
	  $merge_result = array_diff($already_selected_customers_arr,$selected_customers_arr);
	  $unique_result = array_unique($merge_result);
	  $alreadyselected_cust = implode('~',$unique_result);
	   }*/
	  //$alreadyselected_cust = $_REQUEST['already_selected_customers'].'~'.$_REQUEST['selected_customers'];
	  ?>
        <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?  
	   echo table_header($table_headers,$header_positions);
	 if($numcount)
	   { 
		  $srno = 1;
	    $sql_group = "SELECT news_title,news_customer_id,news_custname,news_custemail FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
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
          <tr >
            <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['news_customer_id']?>" id="checkbox[]" type="checkbox" <?php if(is_array($selected_customers_arr)){
		  if(in_array($row['news_customer_id'],$selected_customers_arr)) 
		  {
		//  $selected_customers.=$row['news_customer_id'].'~';
		  echo 'checked';
		  }
		  }?> onclick="addSelected(this,document.frmlistNewsCustomers.selected_customers,'checkbox[]');" /></td>
            <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
            <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['news_title']."&nbsp;".$row['news_custname']?></td>
            <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['news_custemail']?></td>
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
		?>
      </table></td>
  </tr>
  <tr>
    <td class="listeditd"><input type="hidden" name="selected_customers" id="selected_customers" value="<?=$_REQUEST['selected_customers']?>" />
        <input type="hidden" name="already_selected_customers" id="already_selected_customers" value="<?=$alreadyselected_cust?>" />    </td>
    <td class="listeditd" width="133"  align="center"><?
	  if($numcount)
	  {
	  //  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    <td class="listeditd" align="right">&nbsp;</td>
  </tr>
</table>
</form>

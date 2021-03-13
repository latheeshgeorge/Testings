<?php
	/*#################################################################
	# Script Name 	: list_newsletter.php
	# Description 	: Page for listing Newsletters
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='newsletters';
$page_type='News Letter';
$help_msg = get_help_messages('LIST_NEWSLETTER_GRP_MESS');
 $mes = get_help_messages('CUSTOM_CORP_NEWSLETTER');
$newsletter_id=($_REQUEST['newsletter_id']?$_REQUEST['newsletter_id']:$_REQUEST['checkbox'][0]);
/*
//#Sort
$sort_options = array('newsletter_title' => 'News Letter Title','newsletter_lastupdate' => 'Last Updated Date');
$sort_by = (!array_key_exists($_REQUEST['sort_by'],$sort_options))?'newsletter_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( newsletter_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=newsletter&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
*/
?>
<script language="javascript">


function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}

function changedept(corp_id,dept_id)
{
	var retdivid='department_tr';
	var fpurpose;
	if(corp_id==0)
	{
		corp_id=document.customerForm.corp_name.value;
	}
	if(corp_id!="all") {
		document.getElementById('departId').style.display = '';
	} else {
		document.getElementById('departId').style.display = 'none';
	}
	if(corp_id!="" && corp_id!="all")
	{
		document.getElementById('department_tr').style.display='';
		fpurpose	= 'list_dept';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/newsletter.php','fpurpose='+fpurpose+'&'+qrystr+'&corp_id='+corp_id);
	}
	else
	{
		
		document.getElementById('department_tr').style.display='none';
	}
}
</script>

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd" nowrap="nowrap"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a> <a href="home.php?request=newsletter&fpurpose=preview&newsletter_id=<?=$newsletter_id?>"> Preview Newsletter </a><span> List Customer Groups</span></div></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	  <tr>
          <td colspan="5" align="left" valign="middle" > <?PHP echo newsletter_tabs('customer_tab_td',$newsletter_id) ?></td>
      </tr>
		<tr>
	  <td colspan="3" align="left" valign="middle" >
	  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td align="left" valign="middle">
	 <fieldset class="tdcolorgray" style="margin-top:20px;">
     <legend  style="background-color:#355686; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:11px;padding:4px; font-weight:bold">Business Customers</legend>
	  <form action="home.php?request=newsletter" name="customerForm" method="post"> 
	   <input type="hidden" name="fpurpose" id="fpurpose" value="listcustomers" />
	  <table width="100%" border="0" >
        <tr>
          <td colspan="2" class="tdcolorgray">
		  <?php
		  $corpSql = "SELECT  corporation_id, corporation_name 
							FROM 
								customers_corporation 
							WHERE 
								corporation_hide='0' 
								AND sites_site_id=".$ecom_siteid;
			$corpRes = $db->query($corpSql);	
			if($db->num_rows($corpRes))
			{
		  ?>
		  	If this newsletter is to be send to business customers only, then using this section. <?php /*?>&nbsp;<strong>Business Customers</strong> <?php */?>  
		  <?php
		  	}
		  ?>
            <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		   <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?PHP echo $newsletter_id; ?>" />		  </td>
        </tr>
        <?PHP
			$corp_id_arr = array();
		    $cnt_corp = 0;
			$corp_arr = array();
			$corp_arr['all'] = '  All  ';
			
			while($corpRow = $db->fetch_array($corpRes))
			{
				$cnt_corp ++;
				$corpid   = $corpRow['corporation_id'];
				$corpname = $corpRow['corporation_name'];
				$corp_arr[$corpid] = stripslashes($corpname);
			}
			$onchange = "javascript:changedept(0,0)";
			$catSET_WIDTH = '170px';
			if($cnt_corp>0){
			?>
		  <tr class="tdcolorgray">
			  <td width="31%" >&nbsp;Corporation </td>
			  <td width="69%">
		  <?
			echo generateselectbox('corp_name',$corp_arr,$_REQUEST['corp_name'],'',$onchange);		
		  ?></td>
        </tr>
		
        <tr id="departId" style="display:none;" class="tdcolorgray">
		 <td width="31%" valign="top" >&nbsp;Department </td>
         <td width="69%" > <div id="department_tr" style="display:none" align="left" >		</div></td>
        </tr>
		<tr  >
          <td colspan="2" align="right"> 
		  <input type="submit" name="Submit" value=" Proceed " class="red" />&nbsp;
		  <?php /*?><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CUSTOM_CORP_NEWSLETTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>
		  </td>
        </tr><?
		 }
		 else
		 {
		   ?>
		   <tr>
            <td align="center" valign="middle" class="norecordredtext"  colspan="2"> No Business Customers exists. </td>
          </tr>
		   <?
		 }
		?>
      </table>
	  </form>
	  </fieldset>
	  	  </td>
    </tr>
	<? 
	$sql_cust = "SELECT count(customer_id) as cnt FROM customers WHERE sites_site_id=$ecom_siteid AND customer_activated=1 AND customer_hide=0 LIMIT 1";
	$res_cust = $db->query($sql_cust);
	$row_cust = $db->fetch_array($res_cust);
	?>
	<tr>
	  <td colspan="3" align="left" valign="middle" >
	  <fieldset style="margin-top:20px" class="tdcolorgray">
     <legend style="background-color:#355686; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:11px;padding:4px; font-weight:bold">Registered Customers</legend>
	  <form action="home.php?request=newsletter&ftype=regcustomer" method="post" name="registerForm">
	  <input type="hidden" name="fpurpose" id="fpurpose" value="listcustomers" />
	  <table width="100%" border="0" >
        <tr>
          <td width="100%" colspan="2" class="tdcolorgray">
		  If this newsletter is to be send only to registered customers, then using this section.
		  <?php /*?>&nbsp;<strong>Registered Customers</strong> <?php */?>
		  <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?PHP echo $newsletter_id; ?>" /></td>
        </tr>
	<?
	if($row_cust['cnt']>0)
	{
	?>
	
        <tr >
          <td colspan="2" align="right" class="tdcolorgray" >
		  <input type="submit" name="Submit2" value=" Proceed " class="red" />&nbsp;
		  <?php /*?><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CUSTOM_REGT_NEWSLETTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>		  </td>
        </tr>
      
<? }else
{
?>
		 <tr>
            <td align="center" valign="middle" class="norecordredtext"  colspan="12"> No Customers exists. </td>
          </tr>
<?
}?></table></form>
</fieldset>
</td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" > 
	 <fieldset style="margin-top:20px;margin-bottom:20px;" class="tdcolorgray">
     <legend style="background-color:#355686; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:11px;padding:4px; font-weight:bold">Newsletter Groups / Newsletter Customers</legend>
	  <form id="form1" name="customForm" method="post" action="home.php?request=newsletter" onsubmit="return validate()">
	   <script language="javascript">
	   function validate() 
	   {
			len=document.customForm.length;
			var cnt=0;		
			for (var j = 1; j <= len; j++) {
				el = document.customForm.elements[j];
				if (el!=null && el.name== "custgroup_id[]" )
					if(el.checked) {
					cnt++;
					}
					if (el!=null && el.name== "newsletter_cust_all" )
					if(el.checked) {
					cnt++;
					}			
			}
				if(cnt==0) {
					alert('Please select atleast one Check box for Customer Group or All newletter customers');
					return false;
				}
	   }
	   function addSelected(checkbox,arrayfeild,checkboxname)
	   {
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
	   </script>
	    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
<?php 
	   $sql_listCustomerGroups = "SELECT
	  									 custgroup_name,cg.custgroup_id,count(cgcm.customer_id) as custcnt 
								 FROM 
										customer_newsletter_group cg,customer_newsletter_group_customers_map cgcm  
								 WHERE 
										cg.sites_site_id=$ecom_siteid
								  		AND cgcm.custgroup_id=cg.custgroup_id 
								  		AND cg.custgroup_active = '1'	
										AND cgcm.customer_id != '0' 
								  GROUP BY 
								  		cgcm.custgroup_id ";

	  $ret_listCustomerGroups = $db->query($sql_listCustomerGroups);
      $row=$db->fetch_array($ret_listCustomerGroups);
	  $sql_newslettercustomers= "SELECT news_customer_id FROM  newsletter_customers WHERE  sites_site_id=$ecom_siteid LIMIT 1";
	  $ret_newslettercustomers=$db->query($sql_newslettercustomers);
	  ?>
	  <tr>
        <td colspan="6" valign="top" align="left"  class="tdcolorgray">
		&nbsp;If this newsletter is to be send to customers in newsletter groups or  to newsletter customers directly, then use this section.		</td>
	</tr>	
	  <tr class="seperationtd">
        <td colspan="6" valign="top" align="left"  class="tdcolorgray">
		<?php /*?>&nbsp;<b>Newsletter Groups</b><?php */?>
		<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?PHP echo $newsletter_id; ?>" />
		<input type="hidden" name="fpurpose" id="fpurpose" value="listcustomers" />
		
        <input name="selected_custgroups" type="hidden" class="textfeild" id="selected_custgroups" value="<?=$_REQUEST['selected_custgroups']?>" />
		
		</td> 
		<td colspan="6" valign="top" align="left"  class="tdcolorgray">
		<? if($db->num_rows($ret_newslettercustomers)>0){?>
		Send To All Newsletter Customers
		<input type="checkbox" name="newsletter_cust_all"  id="newsletter_cust_all" value="1"  />
		<? }?></td>
		
      </tr>
       
	  <?
	  if($row['custcnt']>0){
	  ?>
      
      <tr class="tdcolorgray">
        <?php 
		$cnt=0;
		$ret_listCustomerGroups = $db->query($sql_listCustomerGroups);
		$selectedGrouparr=explode('~',$_REQUEST['selected_custgroups']);
		while($listCustomerGroups = $db->fetch_array($ret_listCustomerGroups)) {
		$cnt++;
		?>
        <td width="3%"  align="left" valign="middle" class="tdcolorgray"><input name="custgroup_id[]" type="checkbox" class="textfeild" id="custgroup_id[]" <?=(in_array($listCustomerGroups['custgroup_id'],$selectedGrouparr))?'checked':''?> value="<?=$listCustomerGroups['custgroup_id']?>" onclick="addSelected(this,document.customForm.selected_custgroups,'custgroup_id[]');" /></td>
        <td  colspan="3" align="left" valign="middle" class="tdcolorgray"><?=$listCustomerGroups['custgroup_name']?>&nbsp;&nbsp;(<?=$listCustomerGroups['custcnt']?>)</td>
        <?
		  if($cnt == 3){
		  echo '</tr><tr class=\"tdcolorgray\">';
		  $cnt=0;
		  }
	  }?>
      </tr>
	  <? }
		else
		{
		?>
		  <tr>
            <td align="center" valign="middle" class="norecordredtext"  colspan="12"> No Customer Group exists. </td>
          </tr>
		<?
		}
	  if($row['custcnt']>0 or $db->num_rows($ret_newslettercustomers)>0){?>
     <tr >
        <td  align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        <td  colspan="11" align="right" valign="middle" class="tdcolorgray">
		<input type="submit" name="Submitcustom" value=" Proceed " class="red" />
		 <?php /*?><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CUSTOM_GROUP_NEWSLETTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>
		&nbsp;</td>
      </tr>
      <? }?>
     	
	 
    </table>
      </form>
	  </fieldset>
	  </td>
    </tr>
    <tr>
	  <td colspan="3" align="left" valign="middle" >
	  <fieldset style="margin-top:2px" class="tdcolorgray">
     <legend style="background-color:#355686; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:11px;padding:4px; font-weight:bold">Filter By Purchase</legend>
     <form id="form1" name="orderfilterForm" method="post" action="home.php?request=newsletter" onsubmit="return validateorderfilter()">
		<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?PHP echo $newsletter_id; ?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="listcustomers" />
			<input type="hidden" name="filterorders" id="filterorders" value="1" />
		<table width="100%" cellpadding="2" cellspacing="2" border="0">
		<tr>
			<td align="left" colspan="6">If you wanted to get the list of customers based on the orders placed by them, then use this section.</td>
		</tr>
		<tr>
		<td align="left" style="padding:10px 0 10px 0" width="24%">
		<?php 
		$fromdate = date('d-m-Y',strtotime('today - 90 days'));
		$todate = date('d-m-Y');
		?>
		Order Placed Between <input class="textfeild" type="text" size="12"  name="newsfrom_date" value="<?php echo $fromdate;?>" id="newsfrom_date" readonly="true"/></td>
		<td align="left" style="padding:10px 0 10px 0" width="1%"> </td>
		<td align="left" style="padding:10px 0 10px 0" width="3%"> &nbsp;&nbsp;and</td>
		<td align="left" style="padding:10px 0 10px 0" width="1%">  <input class="textfeild" type="text" size="12"  name="newsto_date" id="newsto_date" value="<?php echo $todate;?>" readonly="true"/></td>
		<td align="left" style="padding:10px 0 10px 0" width="1%"> </td> 
		<td align="left" style="padding:10px 0 10px 0" width="70%">&nbsp;</td>
		</tr>
		<tr>
		<td align="left" style="padding:10px 0 10px 0;" colspan="6"  valign="top">
		<div style="float:left;">Order Placed from Categories&nbsp;&nbsp;</div>
		<div style="float:left;">
		<?php
			$holdcatSET_WIDTH = $catSET_WIDTH;
			$catSET_WIDTH = '400px';
			$cat_arr = generate_category_tree(0,0,true,false);
			echo generateselectbox('newscategory_id[]',$cat_arr,$extcat_arr,'','',35,array(),'');
			$catSET_WIDTH = $holdcatSET_WIDTH;
		?>
		</div>
		</td>
		</tr>
		<tr>
		<td align="right" colspan="6" style="padding-bottom:6px">
		<input type="submit" name="Submitcustom" value=" Proceed " class="red" />&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		</tr>
		</table>
     </form>
     </fieldset>
     
		<script type="text/javascript">
		$(function() {
			$( "#newsfrom_date" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
			$( "#newsto_date" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true,changeYear: true, numberOfMonths: 1,showButtonPanel: false });
		});
		</script>
     
     </td>
     
     </tr>
	
	<? 
	$sql_custimp = "SELECT imported_id FROM imported_customers WHERE sites_site_id=$ecom_siteid LIMIT 1";
	$res_custimp = $db->query($sql_custimp);
	if($db->num_rows($res_custimp))
	{
	?>
		<tr>
		<td colspan="3" align="left" valign="middle" >
			<fieldset style="margin-top:20px" class="tdcolorgray">
			<legend style="background-color:#355686; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:11px;padding:4px; font-weight:bold">Imported Customers Only</legend>
			<form action="home.php?request=newsletter&ftype=imported_cust" method="post" name="registerForm">
			<input type="hidden" name="fpurpose" id="fpurpose" value="listcustomers" />
			<input type="hidden" name="filteimported" id="filteimported" value="1" />
			<table width="100%" border="0" >
			<tr>
			<td width="100%" colspan="2" class="tdcolorgray">
			If this newsletter is to be send only to Imported customers, then using this section.
			<?php /*?>&nbsp;<strong>Registered Customers</strong> <?php */?>
			<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?PHP echo $newsletter_id; ?>" /></td>
			</tr>


			<tr >
			<td colspan="2" align="right" class="tdcolorgray" >
			<input type="submit" name="Submit2" value=" Proceed " class="red" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*?><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CUSTOM_REGT_NEWSLETTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a><?php */?>		  </td>
			</tr>
			</table></form>
			</fieldset>
		</td>
		</tr>
<? 
	}
?>
	
	
	
	</table>
	</div>
	</td>
	</tr>
	
</table>








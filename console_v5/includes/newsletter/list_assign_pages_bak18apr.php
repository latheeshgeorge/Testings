<?php
	/*#################################################################
	# Script Name 	: list_assign_products.php
	# Description 	: Page for listing Products for assiging into the Advert
	# Coded by 		: ANU
	# Created on	: 12-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='static_pages';
$page_type='Static Pages';
$help_msg = 'This section lists the Static Pages that can be assigned to the Adverts.';
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistAssignPage,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistAssignPage,\'checkbox[]\')"/>','Slno.','Title','Adverts assigned for the Page','Active');
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

// Finding already assigned Pages in the Advert
$sql_assigned_pages = "SELECT static_pages_page_id FROM advert_display_static where adverts_advert_id =".$_REQUEST['advert_id']." AND sites_site_id=".$ecom_siteid;
$res_assigned_pages = $db->query($sql_assigned_pages);
$assigned_pages_str = '';
while($assigned_pages = $db->fetch_array($res_assigned_pages)){
if($assigned_pages_str !=''){
$assigned_pages_str= $assigned_pages_str.',';
}
$assigned_pages_str .=  $assigned_pages['static_pages_page_id'];
}
//#Search terms.
$search_fields = array('title');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort.
$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options.
// for displaying the selected value in the category tree
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

if($assigned_pages_str!=''){
$where_conditions .=" AND page_id NOT IN ($assigned_pages_str)";
}
if($_REQUEST['search_name']) {
	$where_conditions .= "AND (title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

//#Select condition for getting total count.
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records.
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages.
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=adverts&fpurpose=list_assign_pages&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&advert_id=".$_REQUEST['advert_id']."&advert_title=".$_REQUEST['advert_title']."&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_search_name=".$_REQUEST['pass_search_name']."";

?>
<script language="javascript">
function assignpages()
{ 
	var atleastone 			= 0;
	var curid				= 0;
	var page_ids     		= '';
	var cat_orders			= '';
	//var ch_status			= document.frmlistPage.cbo_changehide.value;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistAssignPage.elements.length;i++)
	{
		if (document.frmlistAssignPage.elements[i].type =='checkbox' && document.frmlistAssignPage.elements[i].name=='checkbox[]')
		{

			if (document.frmlistAssignPage.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (page_ids!='')
					page_ids += '~';
				 page_ids += document.frmlistAssignPage.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Page(s) to assign to the Advert');
		return false;
	}
	else
	{
		if(confirm('Assign Pages to the Advert?'))
		{
				show_processing();
				//alert('fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				//Handlewith_Ajax('services/static_group.php','fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				document.frmlistAssignPage.page_ids.value=page_ids;
				document.frmlistAssignPage.fpurpose.value='assign_pages_to_Advert';
				document.frmlistAssignPage.submit();
		}	
	}	
}
</script>
<form name="frmlistAssignPage" action="home.php?request=adverts" method="post" >	
<input type="hidden" name="fpurpose" value="list_assign_pages" />
<input type="hidden" name="request" value="adverts" />
  <input type="hidden" name="page_ids" id="page_ids" value="" />
  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />  
  <input type="hidden" name="advert_id" id="advert_id" value="<?=$_REQUEST['advert_id']?>" />  
  <input type="hidden" name="advert_title" id="advert_title" value="<?=$_REQUEST['advert_title']?>" />  
   <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&advert_id=<?=$_REQUEST['advert_id']?>">List Adverts</a> >> <a href="home.php?request=adverts&fpurpose=edit&advert_id=<?=$_REQUEST['advert_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Edit Advert</a> &gt;&gt; Assign Static Pages to the Advert: <b>  '<?=$_REQUEST['advert_title']?>' </b></td>
    </tr>
	 <tr>
          <td colspan="3" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=$help_msg ?></div></td>
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
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Title </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
		  
		
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="12%" align="left">Show</td>
          <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          <td width="47%" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">Sort By</td>
          <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for Static Pages that can be assigned to Adverts.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd">&nbsp;
	  </td>
	   <td class="listeditd" width="162" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">
	   
	  <?
	  if($numcount)
	  {
	  ?>
        
		
		<input name="assign_pages" type="button" class="red" id="assign_pages" value="Assign Pages" onclick="return assignpages();" />
		  <a href="#" onmouseover ="ddrivetip('Use This button to assign pages to the Advert. Select the pages to be assigned to the Advert and press \'Assign Pages\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<?
		}
		?>
   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   
	   if($numcount)
	   {
	   echo table_header($table_headers,$header_positions);
	  $sql_pages = "SELECT page_id,title FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_pages);
	   $srno = 1; 
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['page_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row['page_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['title']?>" class="edittextlink" onclick="show_processing()"><? echo $row['title']?></a></td>
         <td align="left" valign="middle" class="<?=$class_val;?>">
		 <?
		 $sql_adverts="SELECT advert_title FROM adverts a,advert_display_static b WHERE b.static_pages_page_id=".$row['page_id']." AND a.advert_id=b.adverts_advert_id";
		 $res_adverts=$db->query($sql_adverts);
		 $num_adverts=mysql_num_rows($res_adverts);
		  if($num_adverts) {
		  ?>
		  <select name="adverts">
		  <?
		  while($row_adverts=$db->fetch_array($res_adverts))
		  {
		  	echo "<option value=$row_adverts[advert_title]>$row_adverts[advert_title]</option>";
		  }
		  ?>
		  </select>
		 <?
		 }
		 else
		 {
		 	echo "No Adverts assigned";
		 }
		 ?>
		 </td>
        
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 0)?'Yes':'No'; ?></td>
         
        </tr>
      	<?
	  	}
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No UnAssigned Pages exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td class="listeditd">&nbsp;
	  </td>
	   <td class="listeditd" width="162"  align="center">
	  <?
	  if($numcount) {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
      <td class="listeditd" align="right">&nbsp;
	 	
      </td>
    </tr>
    </table>
</form>

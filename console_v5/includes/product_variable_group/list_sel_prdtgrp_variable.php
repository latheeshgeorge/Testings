<?php
	/*#################################################################
	# Script Name 	: list_sel_prdtgrp_variable.php
	# Description 	: Page for listing variables to assign to Product Variable Group
	# Coded by 		: Sobin Babu
	# Created on	: 26-July-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_preset_variables';
$page_type='Variables';
$help_msg = get_help_messages('LIST_VARS_PRDT_VAR_GROUP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistVariables,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistVariables,\'checkbox[]\')"/>','Slno.','Variable Name','Hide');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);
$pass_prdt_var_grp_id=($_REQUEST['pass_prdt_var_grp_id']?$_REQUEST['pass_prdt_var_grp_id']:'0');

$tabale = "product_variables_group";
$where  = "var_group_id=".$pass_prdt_var_grp_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	

//#Search terms
$search_fields = array('search_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'var_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('var_name' => 'Variable Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
//#Avoiding already assigned product
//$sql_assigned="SELECT 
$sql_assigned="SELECT product_variables_id FROM product_variables_group_variables_map WHERE sites_site_id =  ".$ecom_siteid." AND product_variables_group_id = ".$pass_prdt_var_grp_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned 	.= ','.$row_assigned['product_variables_id'];
}
$str_assigned 		= '('.$str_assigned.')';	
$where_conditions 	.= " AND var_id NOT IN $str_assigned";

if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( var_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=product_variable_group&fpurpose=add_variables&records_per_page=$records_per_page&start=$start";
$query_string .= "&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_group_name=".$_REQUEST['pass_group_name']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."";
$query_string .="&pass_prdt_var_grp_id=$pass_prdt_var_grp_id";

?>
<script>
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frmlistVariables.elements.length;i++)
	{
		if (document.frmlistVariables.elements[i].type =='checkbox' && document.frmlistVariables.elements[i].name=='checkbox[]')
		{

			if (document.frmlistVariables.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Variables to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Variables ?'))
		{
			show_processing();
			document.frmlistVariables.fpurpose.value='save_add_variables';
			document.frmlistVariables.submit();
		}	
	}	

}

</script>
<form name="frmlistVariables" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="add_variables" />
<input type="hidden" name="request" value="product_variable_group" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_prdt_var_grp_id" value="<?=$pass_prdt_var_grp_id?>" />
<input type="hidden" name="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" /> 
 <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
   <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_variable_group&pass_prdt_var_grp_id=<?=$pass_prdt_var_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Variables Groups</a> <a href="home.php?request=product_variable_group&fpurpose=edit&checkbox[0]=<?=$pass_prdt_var_grp_id?>&pass_prdt_var_grp_id=<?=$pass_prdt_var_grp_id?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=varmenu_tab_td">Edit Product Variables Group </a><span>Assign Variable</span></div></td>
        </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" class="sorttd" colspan="2" >
      <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">Variable Name </td>
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
          <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRDT_VAR_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div>
      </td>
    </tr>
    
     
    <tr>
      <td colspan="2" class="listingarea">
      <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
      <tr>
      <td align="left" class="listeditd" colspan="2">	  
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td colspan="2" align="right" class="listeditd">
	 <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
      &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_VARS_PRDT_VAR_GROUP_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_variables = "SELECT var_id,var_name,var_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_variables);
	    $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
				if($row['product_discount']>0)
				{
					$disctype	= ($row['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row_qry['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row['product_discount'];
					$disc		= $discval.$disctype;
					if(($row['product_discount_enteredasval']==1))
					{
					 $disc = display_price($disc);
					}
				}	
				else
					$disctype = $disc = '--';
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['var_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=preset_var&fpurpose=edit&checkbox[0]=<?php echo $row['var_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['var_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['var_name']?></a></td>
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['var_hide'] == '1')?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Variables to add in this GROUP exists.				  </td>
			</tr>
		<?
		}
		?>	
        <tr>
      <td align="left" class="listeditd" colspan="2">	  
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right" colspan="2">&nbsp;   	   </td>
    </tr>
      </table>
      </div></td>
    </tr>
	
  
    </table>
</form>

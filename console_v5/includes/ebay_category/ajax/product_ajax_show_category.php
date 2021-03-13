<?php
	/*#################################################################
	# Script Name 	: product_ajax_show_category.php
	# Description 		: Page to hold the functions to be called using ajax
	# Coded by 		: Sny
	# Created on		: 28-Jun-2012

  	# Modified by		: LSH
	# Modified On		: 22-Sep-2012
	#################################################################*/
	// ###############################################################################################################
	// 				Function which holds the display logic of product variables to be shown when called using ajax;
	// ###############################################################################################################
	function show_categories($edit_id=0,$cat_arr=array(),$pg=0,$alert='')
	{
		global $db,$ecom_siteid;
	// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
	$ext_str =0;
	
	if(count($cat_arr))
	{
		$ext_str = implode(",",$cat_arr);
	}
	$form = "frm_prodcat";
	//Define constants for this page
	$table_name='ebay_categories';
	$page_type='Ebay Category';
	$help_msg 	= get_help_messages('LIST_SEL_EBAY_CAT_GROUP_MESS1');
	/*$help_msg = 'This section lists the Product Categories available on the site which are not yet assigned to current product category group. Here there is provision for adding a Product Category, editing, & deleting it.';*/
	$table_headers = array('Select','Slno.','Category Name');
	$header_positions=array('left','left','left','left','center');
	$colspan = count($table_headers);

	//#Search terms
	$search_fields = array('ebay_catname','sort_order','parentid','perpage');
	
	$query_string = "";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	//#Sort
	$sort_by = (!$_REQUEST['ebay_sort_by'])?'cat_name':$_REQUEST['ebay_sort_by'];
	$sort_order = (!$_REQUEST['ebay_sort_order'])?'ASC':$_REQUEST['ebay_sort_order'];
	$sort_options = array('cat_name' => 'Name');
	$sort_option_txt = generateselectbox('ebay_sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('ebay_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions .= " WHERE expired=0 AND leafcat=1 ";
	
	
	if($_REQUEST['catname_pop'])
	{
		$where_conditions .= " AND cat_name LIKE '%".add_slash($_REQUEST['catname_pop'])."%' ";
	}
	
	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['perpage_pop']) and $_REQUEST['perpage_pop'])?$_REQUEST['perpage_pop']:20;//#Total records shown in a page
	$pg = !($pg)?1:$pg;		
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
	
	if($pg >= 1)
	{
	   $page = $pg ;
	   $start = $records_per_page * ($pg-1) ;
	}
	else
	{
	   $page = 0;
	   $start = 0;
	}
	$page  = $pg;
	$next  = $pg+1;
	$prev  = $pg-1;
	/*
	if($pg > $pages) {
		$pg = $pages;
	}
	if ($pg>=1)
	{
		$start = ($pg - 1) * $records_per_page;//#Starting record.
		$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
	}	
	else
	{
		$start = $count_no = 0;	
	}
	$page = $pg;
	*/ 
	/////////////////////////////////////////////////////////////////////////////////////
	
	$sql_qry = "SELECT * FROM $table_name 
	$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);	
	?>	
	<div class="popup_category_scrolldiv">
	<table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">		
		<tr>
		  <td  colspan="2" class="sorttd">
		  <div class="sorttd_div" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr class="">		
			  <td  align="left" valign="middle" class="shoppingcartheader"> <div class="treemenutd_div1"> EBAY CATEGORIES</div></td>
			  <td  align="left" valign="middle" class="shoppingcartheader">
			 		  </td>
			  <td colspan="3"  align="left" valign="middle" class="shoppingcartheader"><div class="close_pop_div" ><img src="images/close_cal.png" onclick="call_cancel()" title="Click here to close" /></div></td>
		    </tr>
			<tr>
			  <td align="left" valign="middle">Category Name </td>
			  <td  align="left" valign="middle"><input name="catname_pop" type="text" class="textfeild" id="catname_pop" value="<?php echo $_REQUEST['catname_pop']?>" /></td>
			 
			  <td  align="left" valign="middle">Records Per page</td>
			  <td  align="left" valign="middle">
			 <input name="perpage_pop" type="text"  id="perpage_pop" class="textfeild" size="4" value="<?php echo $_REQUEST['perpage_pop'] ?>"  />

			  <input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="call_ajax_search('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $start?>','<?php echo $pg?>')" />
			  <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_CAT_GROUP_GO') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				  </td>
			  <td  align="left" valign="middle">&nbsp;</td>
			</tr>
			
		  </table>		 
      	</div>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" class="listingarea">
		  <div class="sorttd_div" >
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">	
		 
		  <?php
				if ($db->num_rows($ret_qry))
				{
					?>
					<tr>
					<td colspan="<?php echo $colspan?>" align="center" valign="middle" class="sorttd">
					<?php 
					if( $page > 0 )
					{
					   $prev = $page - 1;
					   $first = 0;
					   $last  = $pages;
					   $next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($next,'$_REQUEST[catname]',$_REQUEST[parentid],'$sort_by','$sort_order',$_REQUEST[records_per_page],$start,$pg)\">";
					   $prev_s = "<input type=\"button\"  value=\"Prev\" id=\"prev\" class=\"red\" name=\"prev\" onclick=\"call_ajax_page($prev,'$_REQUEST[catname]',$_REQUEST[parentid],'$sort_by','$sort_order',$_REQUEST[records_per_page],$start,$pg)\">&nbsp;";
					   $first = "<input type=\"button\"  value=\"First\" id=\"First\" class=\"red\" name=\"First\" onclick=\"call_ajax_page($first,'$_REQUEST[catname]',$_REQUEST[parentid],'$sort_by','$sort_order',$_REQUEST[records_per_page],$start,$pg)\">";
					   $last = "<input type=\"button\"  value=\"Last\" id=\"Last\" class=\"red\" name=\"Last\" onclick=\"call_ajax_page($last,'$_REQUEST[catname]',$_REQUEST[parentid],'$sort_by','$sort_order',$_REQUEST[records_per_page],$start,$pg)\">&nbsp;";

					  if( $prev>0 )
					  	echo $first."&nbsp";
					  if( $prev>0)
					  	echo $prev_s;
					}?>
&nbsp;&nbsp;
					<?php echo $numcount;?> Ebay Categoreis found. Page
<b><?php echo $page;?></b>
of
<b><?php echo $pages ?></b>&nbsp;&nbsp;<?php
if( $page > 0 )
{
   $next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($next,'$_REQUEST[catname]',$_REQUEST[parentid],'$sort_by','$sort_order',$_REQUEST[records_per_page],$start,$pg)\">";
   if($pages>=$next)
   {
   echo $next_s."&nbsp";
   echo $last;
   }
}
else if( $page == 0 )
{
	   echo $next_s;
}
?>
<div class="assign_cat_button_popdiv_class">
<input name="Assign_selected" type="button" class="red" id="Assign_selected" value="Assign Selected" onClick="call_ajax_assign_category('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $start?>','<?php echo $pg?>')" /> <a href="#" onmouseover ="ddrivetip('<? echo get_help_messages('LIST_SEL_EBAY_ASSIGN') ?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
</div>
					</td>
					</tr>
<?php
/*
		?>
		<tr><td colspan="6" align="center" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
		<?php
		*/ 
				}	
		?>
		<tr>
		  <td align="right" valign="middle" colspan="<?php echo $colspan?>" class="listeditd">
 		</td>
	    </tr>
		 <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{   
				if($start==0)
				{
				$srno = 1;
				}
				else
				{
				$srno = $start+1;
				}
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="10%">
					  <input type="radio" name="checkbox_assigncat" id="checkbox_assigncat" value="<?php echo $row_qry['cat_id']?>" />					  </td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width=""><?php echo stripslashes($row_qry['cat_str'])?></td>					 
					</tr>
				<?php
				}
				if(count($cat_arr))
					{
						foreach($cat_arr as $k=>$v) 
						{
						?>
							<input type="hidden" name="passcheckbox_assigncat[]" id="passcheckbox_assigncat[]" value="<?php echo $v ?>" />
						<?php
						}
					}									
			}
			else
			{
		?>		<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Product Categories found.	</td>
				</tr>	  
		<?php
			}
			/*
		?>
		<tr>
		  <td class="listeditd" align="right" valign="middle" colspan="<?php echo $colspan?>"><?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>
		*/
		?>
		  </table>
		  </div>
		  </td>
		</tr>
		
		</table>
	</div>	
	<?php	
	}
	?>

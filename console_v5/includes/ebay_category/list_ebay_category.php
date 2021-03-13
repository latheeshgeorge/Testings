<?php
	/*#################################################################
	# Script Name 	: list_ebay_category.php
	# Description 	: Page for listing Ebay Categories
	# Coded by 		: LH
	# Created on	: 01-Feb-2013
	
	#################################################################*/

	
// Check whether category groups exists for the site. If not give appropriate message and redirect user to category group add section.
$sql_check = "SELECT count(catgroup_id) FROM product_categorygroup WHERE sites_site_id=$ecom_siteid";
$ret_check = $db->query($sql_check);
if ($db->num_rows($ret_check))
{
	list($grp_cnt) = $db->fetch_array($ret_check);
}	
if ($grp_cnt==0) // Case if category groups not added yet. So giving instruction to go to product category groups list or add page
{
?>
	<br />
	<span class='errormsg'> Category Menu not added yet. Please add Category Menu First.</span><br />
	<br /><br />
	<a class="smalllink" href="home.php?request=prod_cat_group">Go to the Category Menu Listing page</a><br />
	<br />
	<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=&start=0&pg=1&records_per_page=10">Go to Add Category Menu Page</a>
<?php
}
else // case if product category group exists
{
	//Define constants for this page
	$table_name='product_categories';
	$page_type='Product Category';
	$help_msg =get_help_messages('LIST_PROD_EBAY_CAT1');
		$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_prodcat,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_prodcat,\'checkbox[]\')"/>','Slno.','Category Name','Parent','Ebay Category','Hide');
		$header_positions=array('center','left','left','left','left','center');
		
	$colspan = count($table_headers);
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$search_in_mobile_app = (!$_REQUEST['search_in_mobile_application'])?0:1;
	
	$sort_options = array('category_name' => 'Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	if($_REQUEST['sort_by'])
	{
		if(!in_array($_REQUEST['sort_by'],$sort_options))
		{
		  $sort_by = 'category_name';
		}
	}
	//#Search terms
	$search_fields = array('catname','parentid','catgroupid');
	
	$query_string = "request=ebay_category&sort_by=$sort_by&sort_order=$sort_order&search_in_mobile_application=$search_in_mobile_app";
	foreach($search_fields as $v) {
		$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
	}
		
	
	
	//#Search Options
	$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
	if($_REQUEST['catname'])
	{
		$where_conditions .= " AND ( category_name LIKE '%".add_slash($_REQUEST['catname'])."%') ";
	}
	if($_REQUEST['parentid']=='')
		$_REQUEST['parentid'] = -1;
	if($_REQUEST['parentid']!=-1)
	{
		if($_REQUEST['parentid']!='')
		{
			$where_conditions .= " AND parent_id= ".$_REQUEST['parentid'];
		}	
	}
	if($search_in_mobile_app)
	{
		$where_conditions .= " AND in_mobile_api_sites = 1";
	}
	
	
	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:50;//#Total records shown in a page
	$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];
	
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
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
	/////////////////////////////////////////////////////////////////////////////////////
	
	$sql_qry = "SELECT category_id,category_name,parent_id,category_hide,mobile_api_parent_id,ebay_category_id FROM $table_name 
						$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	$ret_qry = $db->query($sql_qry);
	?>
	<script type="text/javascript">	
	function call_ajax_changeparent(cname,parentid,catgroupid,sortby,sortorder,recs,start,pg)
	{
		var atleastone 			= 0;
		var curid				= 0;
		var cat_ids 			= '';
		var cat_orders			= '';
		var ch_parent			= document.frm_prodcat.change_parentid.value;
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&catgroupid='+catgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_parent='+ch_parent+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (cat_ids!='')
						cat_ids += '~';
					 cat_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0) 
		{
			alert('Please select the product categories to change the parent');
		}
		else
		{
			if(confirm('Change the parent of Seleted Product categories?'))
			{
					show_processing();
					Handlewith_Ajax('services/product_category.php','fpurpose=change_parent&'+qrystr+'&catids='+cat_ids);
			}	
		}	
	}	
	function assign_ebay_category(cname,parentid,sortby,sortorder,recs,start,pg)
	{
	    var atleastone 			= 0;
		var del_ids 			= '';
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (del_ids!='')
						del_ids += '~';
					 del_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the product categories to map with ebay category');
		}
		else
		{		
				//show_processing();
				document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
				retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
				Handlewith_Ajax('services/ebay_category.php','fpurpose=show_category_popup&del_ids='+del_ids+'&'+qrystr);				
		}	
   	document.getElementById('moveto_showcategory_div').style.display ='none';

	}
	function unassign_ebay_category(cname,parentid,sortby,sortorder,recs,start,pg)
	{
	    var atleastone 			= 0;
		var del_ids 			= '';
		var qrystr				= 'catname='+cname+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
		/* check whether any checkbox is ticked */
		for(i=0;i<document.frm_prodcat.elements.length;i++)
		{
			if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{
	
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone = 1;
					if (del_ids!='')
						del_ids += '~';
					 del_ids += document.frm_prodcat.elements[i].value;
				}	
			}
		}
		if (atleastone==0)
		{
			alert('Please select the product categories to unassign from ebay category');
		}
		else
		{		
			if(confirm('Are you sure you want to unassign ebay category?'))
			{
				show_processing();
				document.getElementById('retdiv_id').value 	= 'maincontent';
				retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");			
				//retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
				Handlewith_Ajax('services/ebay_category.php','fpurpose=uassign_ebay_category&del_ids='+del_ids+'&'+qrystr);				
			}	
		}	
   	///document.getElementById('moveto_showcategory_div').style.display ='none';

	}
	function call_ajax_assign_category(cname,parentid,sortby,sortorder,recs,start,pg)
	{ 
	var ch_ids     ='';
	var qrystr     = '';
	var atleastone = 0;
	var eb_ids		='';
	var fpurpose													= 'assign_category_product_popup';
	var defval;
	var qrystr				= 'catname='+cname+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;

	var atleastmsg = 'Please select atleast one category';
	for(i=0;i<document.frm_prodcat.elements.length;i++)
	{
	   if (document.frm_prodcat.elements[i].type =='checkbox')
		{
			if(document.frm_prodcat.elements[i].name== 'checkbox[]')
			{
				if (document.frm_prodcat.elements[i].checked==true)
				{
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frm_prodcat.elements[i].value;
				} 
			}
			
		}
	}
	 var inputs = document.getElementsByName("checkbox_assigncat");
            for (var i = 0; i < inputs.length; i++) {
              if (inputs[i].checked) {
                eb_ids =  inputs[i].value;
              }
            }
		
	if (atleastone==0)
	{
		alert(atleastmsg);
		return false;
	}
	else
	{	
		if(confirm('Are you sure you want to Assign selected ebay category?'))
			{	
		        document.getElementById('retdiv_id').value 	= 'maincontent';
				retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");			
			Handlewith_Ajax('services/ebay_category.php','fpurpose='+fpurpose+'&ch_ids='+ch_ids+'&eb_ids='+eb_ids+'&'+qrystr);
		}
	}
	document.getElementById('div_defaultFlash_outer').style.display='none';	
   	document.getElementById('moveto_showcategory_div').style.display ='none';
}
function call_cancel()
{
	document.getElementById('moveto_showcategory_div').style.display ='none';
	document.getElementById('div_defaultFlash_outer').style.display='none';

}	
function call_ajax_page(page,cname,parentid,sortby,sortorder,recs,start,pg)
{
	var qrystr														= '';
	var fpurpose													= 'show_category_popup';
	var catname_pop      												= document.getElementById('catname_pop').value ;
    var perpage_pop														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	for(i=0;i<document.frm_prodcat.elements.length;i++)
	{
	   
	  if (document.frm_prodcat.elements[i].type =='checkbox' && document.frm_prodcat.elements[i].name=='checkbox[]')
			{	
				if (document.frm_prodcat.elements[i].checked==true)
				{	
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frm_prodcat.elements[i].value;				
			}
		}
	}	
	qrystr = 'catname_pop='+catname_pop+'&perpage_pop='+perpage_pop+'&catname='+cname+'&parentid='+parentid+'&records_per_page='+recs+'&ch_ids='+ch_ids;;

	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/ebay_category.php','fpurpose='+fpurpose+'&page='+page+'&'+qrystr);

}
function call_ajax_search(cname,parentid,sortby,sortorder,recs,start,pg)
{
	var qrystr														= '';
	var fpurpose													= 'show_category_popup';
	var catname_pop      												= document.getElementById('catname_pop').value ;
    var perpage_pop														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';

    for(i=0;i<document.frm_prodcat.elements.length;i++)
	{
	   
	   if ((document.frm_prodcat.elements[i].type =='hidden') )
		{
			if(document.frm_prodcat.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						atleastone ++;
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frm_prodcat.elements[i].value;				
			}
		}
	}		
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	qrystr = 'catname_pop='+catname_pop+'&perpage_pop='+perpage_pop+'&catname='+cname+'&parentid='+parentid+'&records_per_page='+recs+'&ch_ids='+ch_ids;

	document.getElementById('retdiv_id').value 						= 'moveto_showcategory_div';
	obj																= eval("document.getElementById('moveto_showcategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/ebay_category.php','fpurpose='+fpurpose+'&'+qrystr);

}

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
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'moveto_showcategory_div':				
					document.getElementById('div_defaultFlash_outer').style.display='';
					document.getElementById('moveto_showcategory_div').style.display='';
					//document.getElementById('retdiv_id').value ='maincontent';	
				break;				
				
			};
			if (disp!='no')
			{ //alert('tetet');
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}
				
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}

	jQuery.noConflict();
		var $ajax_j = jQuery; 
	     $ajax_j(function () { 
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#moveto_showcategory_div")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#moveto_showcategory_div")[0].offsetWidth / 2, 0);
		$ajax_j("#moveto_showcategory_div").css('top', top-275 + "px");
		$ajax_j("#moveto_showcategory_div").css('right', (left-200) + "px");
		$ajax_j("#moveto_showcategory_div").css('position', 'fixed');
	});	
	function showme(id)
	{		
		$ajax_j(id).show();
	}
	function hideme(id)
	{
		$ajax_j(id).hide();
		$ajax_j(id).hide();
	}
	</script>
	<form method="post" name="frm_prodcat" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="ebay_category" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="ids" value="" />
    <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
     
     <div style="top: 0px; left: 0px; position: fixed; display: none;" class="flashvideo_outer" id="div_defaultFlash_outer"></div>
          <div id="moveto_showcategory_div" class="processing_divcls_big_heightA" style="display:none" >
	</div>	
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=ebay_export"> Ebay Export</a><span> List Product Categories</span></div></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
						<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
					</tr>
			 <?php
				}
			 ?> 
		<?php
				if ($db->num_rows($ret_qry))
				{
		?>
		<tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
		<?php
				}	
		?>
		<tr>
		  <td height="48" colspan="3" class="sorttd">
		  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td width="9%" align="left" valign="middle">Category Name </td>
			  <td width="15%" align="left" valign="middle"><input name="catname" type="text" class="textfeild" id="catname" value="<?php echo $_REQUEST['catname']?>" /></td>
		      <td width="10%" align="center" valign="middle">&nbsp;Parent Category</td>
		      <td width="32%" align="left" valign="middle"><?php
			  	$parent_arr = generate_category_tree_special(0,0,true,false,false,true);
				if(is_array($parent_arr))
				{
					echo generateselectbox('parentid',$parent_arr,$_REQUEST['parentid']);
				}
			  ?></td>
			  <td colspan="3" align="left" valign="middle">
			  
				</td>
		    </tr>
				<tr>
			  	<td align="left" valign="middle"></td>
			  	<td colspan="2" align="left" valign="middle"></td>
			  	<td align="left" valign="middle">&nbsp;Show
                  <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
Product Categories Per Page</td>
			  	<td width="5%" align="left" valign="middle">Sort By</td>
			  	<td width="23%" align="left" valign="middle"><?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
				<td width="6%" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
			 </table>
		    </div></td>
		</tr>
		
		<tr>
		  <td colspan="3" class="listingarea1">
		  <div class="listingarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
			  <td  align="right" valign="middle" colspan="<?php echo $colspan?>" class="listeditd"><input name="Assign_ebay" type="button" class="red" id="Search_go" value="Assign Ebay Category" onclick="assign_ebay_category('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_EBAY_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;<input name="unassign_ebay" type="button" class="red" id="unassign" value="Unassign Ebay Category" onclick="unassign_ebay_category('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_CAT_EBAY_UNASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
	 
		 <?php  
			echo table_header($table_headers,$header_positions); 
		
			if ($db->num_rows($ret_qry))
			{ 
			
				//$srno = 1;
				$srno = getStartOfPageno($records_per_page,$pg);
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
					if($row_qry['ebay_category_id']>0)
					{
					$ebay_sql = "SELECT cat_str FROM ebay_categories WHERE cat_id =".$row_qry['ebay_category_id'];
					$retebay_sql  = $db->query($ebay_sql);
					$row_ebaysql = $db->fetch_array($retebay_sql);
					$ebay_cat    = $row_ebaysql['cat_str'];
					}
					else
					{
						$ebay_cat    = 	 '<span class="redtext">-- Not Assigned --</span> ';
					}
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
					  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['category_id']?>" /></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_qry['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&search_in_mobile_application=<?php echo $search_in_mobile_app?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['category_name'])?></a></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
					  	if ($row_qry['parent_id']!=0)
						{
							//Find the name of the parent category
							$sql_parent = "SELECT category_name FROM product_categories WHERE category_id=".$row_qry['parent_id'];
							$ret_parent = $db->query($sql_parent);
							if ($db->num_rows($ret_parent))
							{
								$row_parent = $db->fetch_array($ret_parent);
								echo stripslashes($row_parent['category_name']);
							}
						}
						else
							echo '-- Root --';
					  ?>					  </td>
					  <td align="left" valign="middle" class="<?php echo $cls?>">
					  <?php
					  echo $ebay_cat;
					  	?>			  </td>
					  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['category_hide']==0)?'No':'Yes'?></td>
					</tr>
		<?php
				}
			}
			else
			{
			 	?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Product Categories found.					</td>
				</tr>	  
		<?php
			}
		?>
		
		  </table>
		  </div></td>
		</tr>
		<tr>
		  <td class="listing_bottom_paging" align="right" valign="middle" colspan="2"><?php 
				if ($db->num_rows($ret_qry))
				{
					paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
				}	
			?></td>
		</tr>		
	  </table>
	</form>
<?php
}
?>

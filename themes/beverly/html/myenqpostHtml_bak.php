<?php
	/*############################################################################
	# Script Name 	: myordersHtml.php
	# Description 	: Page which holds the display logic for My Address Book
	# Coded by 		: Randeep
	# Created on	: 02-May-2008
	##########################################################################*/
	/*
	class myenqpost_Html
	{
	  function enqpost_Showpost()
	  {
	    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$inspost;
		$session_id = session_id();	// Get the session id for the current section
		$customer_id = get_session_var('ecom_login_customer');
		$Captions_arr['ORDERS'] 	= getCaptions('ORDERS');
		
		$sort_by 			=  $Settings_arr['orders_orderfield_enqposts']; //(!$_REQUEST['post_sort_by'])?'post_date':$_REQUEST['post_sort_by'];
		$sort_order 		=  $Settings_arr['orders_orderby_enqposts'];  //(!$_REQUEST['post_sort_order'])?'ASC':$_REQUEST['post_sort_order'];
//		$sort_options 		= array('order_date' => 'Order Date','order_status'=>'Order Status','order_pre_order'=>'Preorder');
//		$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
//		$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//##########################################################################################################
// Building the query to be used to display the orders
//##########################################################################################################

//##########################################################################################################
// Check whether order id is given

if($_REQUEST['enqid'])
{
	$queryId = $_REQUEST['enqid'];
	$where_conditions 	= "WHERE order_queries_query_id='".$queryId."'";
}
//##########################################################################################################
//#Select condition for getting total count
$sql_count 			= "SELECT count(*) as cnt 
						FROM 
							order_queries_posts  
							$where_conditions";
$res_count 			= $db->query($sql_count);

list($tot_cnt) 	= $db->fetch_array($res_count);//#Getting total count of records
$ordperpage	= $Settings_arr['orders_maxcntperpage_enqposts'];// product per page
/////////////////////////////////For paging///////////////////////////////////////////
$sql = "SELECT query_subject, query_content FROM order_queries WHERE query_id='".$queryId."'";
$res = $db->query($sql);
$row = $db->fetch_array($res);

$query_subject = $row['query_subject'];
$querycontent = $row['query_content'];

// Call the function which prepares variables to implement paging
						$ret_arr 		= array();
						$pg_variable	= 'ord_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$ordperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$ordperpage;
						}	
						else
							$Limit = '';
// Get the details of current order
 	$sql_ord = "SELECT * FROM 
					order_queries_posts 
					$where_conditions 
				ORDER BY 
					$sort_by $sort_order ";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_select_ord = $db->fetch_array($ret_ord);
	}
	
	?>
 <form method="post" name="frm_enqposts" class="frm_cls">
 <input type="hidden" name="hid_qryid" />		
 <?PHP if(trim($inspost)) { ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="userorderheader">
      <td colspan="5" align="center"><? echo $inspost; ?></td>
    </tr>
</table>
<? } ?>	
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="4" align="left" valign="middle" class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="index.php?req=orders"><?php echo $Captions_arr['ORDERS']['ORDER_MAINHEADING']; ?></a> >> <a href="index.php?req=orders&reqtype=order_det&order_id=<?php echo $_REQUEST['order_id']?>"><?php echo $Captions_arr['ORDERS']['ORDER_DETAILS']; ?></a>  >> <?php echo $Captions_arr['ORDERS']['ORDER_ENQ_POSTS']; ?>  </td>
  </tr>
  <tr><td>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="5" align="left" class="userorderheader"><?=$Captions_arr['ORDERS']['ORDER_ENQ_POSTHEAD']; ?></td>
    </tr>
    <tr>
      <td colspan="5" align="left" class="usermenucontentbold"><?=$query_subject; ?></td>
    </tr>
    <tr>
      <td colspan="5" align="left" class="userordercontent"><?PHP echo nl2br($querycontent); ?></td>
    </tr>
    <tr>
      <td colspan="5" align="right" class="userorderheader"><a href="javascript:postDisplay(document.frm_enqposts)" class="edithreflink" > 
        <input type="hidden" name="hid_post" />
        Add New Post </a>&nbsp;</td>
      </tr>
    
    <tr id="postDet4" style="display:none;"  class="userorderheader">
      <td colspan="2">&nbsp;<strong><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_PLACEPOST']?></strong></td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr id="postDet1"  class="ordertableheader" style="display:none;" >
      <td colspan="2">&nbsp;Post Details </td>
      <td colspan="3"><br/><textarea name="txt_post" cols="35" rows="6"></textarea>        &nbsp;</td>
    </tr>
    <tr id="postDet2" style="display:none;"  class="ordertableheader">
      <td colspan="2">&nbsp;</td>
      <td colspan="3"><input type="button" name="Submit" value="Add New Post" onclick="javascript:postSub(document.frm_enqposts)" class="buttonred_cart" />
        <input type="hidden" name="hidpost" />
		<input type="hidden" name="hidview" /></td>
      </tr>
      <tr id="postDet3" style="display:none;"  class="ordertableheader">
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
	  <?PHP if(is_array($row_select_ord)) { ?>
      <tr>
        <td colspan="5" class="userorderheader"> <?php echo $Captions_arr['ORDERS']['ORDER_ENQPOST_EXIST']?> </td>
        </tr>
      <tr>
      <td width="9%" class="ordertableheader"><div align="center"><strong><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_SLNO']?></strong></div></td>
      <td width="18%" class="ordertableheader"><div align="center"><strong><?php echo $Captions_arr['ORDERS']['ORDER_DATE']?></strong></div></td>
      <td width="14%" class="ordertableheader"><div align="center"><strong><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_POSTBY']?></strong></div></td>
      <td width="8%" class="ordertableheader"><div align="center"><strong><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_POSTNR']?></strong></div></td>
      <td width="8%" class="ordertableheader"><div align="center"><strong><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_POSTDET']?></strong></div></td>
    </tr>
	<?PHP
			$postsql = "SELECT DATE_FORMAT(post_date,'%d-%b-%Y') AS post_date, post_details, post_source, post_userid, post_status 
							FROM order_queries_posts 
							    WHERE order_queries_query_id= '".$_REQUEST['enqid']."' 
								ORDER BY 
					$sort_by $sort_order 
				
				    $Limit
			";
														
			$postres = $db->query($postsql);
			while($postrow = $db->fetch_array($postres)) {
						
		$count++;
	 ($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';

	?>
    <tr>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $count; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $postrow['post_date']; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $postedby = ($postrow['post_source']=='C')?'Customer':'Administrator'; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $post_status = ($postrow['post_status']=='N')?'New':'Read'; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>" align="center">&nbsp;<a href="#" onclick="javascript:view_post(document.frm_enqposts,'<?PHP echo $count; ?>')" class="edithreflink"> View </a></td>
    </tr>
	<tr id="view_<?PHP echo $count; ?>" style="display:none;">
      <td colspan="5" class="viewPostdetails" nowrap="nowrap"><div align="left"><?PHP echo nl2br($postrow['post_details']); ?></div></td>
    </tr>
	<?PHP } ?>
		   <tr>
      <td colspan="5" class="pagingcontainertd" align="center">
	  <?php 
	     $path = '';
	     $query_string .= "";
	     $query_string .= "&amp;req=orders&amp;reqtype=enqposts&amp;enqid=".$_REQUEST['enqid']."&amp;order_id=".$_REQUEST['order_id']."";
	     paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
?></td>
      </tr>
	  <? } ?>
  </table></td></tr>
  </table>
  
 </form>
<?
	  } 
	}  */
	  ?>	
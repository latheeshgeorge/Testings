<?php
	/*#################################################################
	# Script Name 	: list_site_reviews.php
	# Description 	: Page for listing Site Reviews 
	# Coded by 		: LAtheesh
	# Created on	: 14-May-2014
	# Modified by	: Latheesh
	# Modified On	: 114-May-2014
	#################################################################*/
//Define constants for this page
$table_name='listof_console_useractions a';
$page_type='Log Details';
$help_msg = get_help_messages('LIST_USEACT_MESS1');
$table_headers = array('Slno.','Action Performed By','Action Performed On','Action','Date');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('done_for');


$query_string = "request=listof_console_useractions";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	//echo $query_string;
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'action_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('done_for' => 'User Modified');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = " WHERE  a.sites_site_id=$ecom_siteid ";
if($_REQUEST['done_for']) {
	$where_conditionsa .= " AND ( done_for_fname LIKE '%".add_slash($_REQUEST['done_for'])."%' OR done_for_fname LIKE '%".add_slash($_REQUEST['done_for'])."%') ";
    $where_conditions .= $where_conditionsa;
}

//#Select condition for getting total count
 $sql_count = "SELECT count(a.id) as cnt FROM $table_name  $where_conditions";
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
$query_string .= "&sort_by=$sort_by&sort_order=$sort_order&request=listof_console_useractions&records_per_page=$records_per_page&start=$start";

$sql_qry = "SELECT *  
 					FROM $table_name 
							$where_conditions 
								ORDER BY $sort_by $sort_order 
									LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>

<form method="post" name="frmlistSitelogintrack" class="frmcls" action="home.php">
<input type="hidden" name="request" value="listof_console_useractions" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />  
  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=console_user">List Console Users</a> <span>  List Console User Action</span> </div></td>
    </tr>
	 <tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
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
    <tr>
      <td height="48" colspan="3" class="sorttd">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td  align="left" valign="top">
			  	  <div class="sorttd_div">

		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
           
            <tr>
              <td  align="left">Modified User
                <input class="textfeild" type="text" name="done_for" size="8" value="<?=$_REQUEST['done_for']?>"  />
             </td>
              <td  align="left">Show
                <input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                <?php echo $page_type?> Per Page</td>
              
              <td align="left">Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </td>
              <td align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frmlistSitelogintrack.search_click.value=1"  />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SITE_LOGIN_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>
          </div>
		  </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td width="113" class="listeditd">
	  	</td>
      <td width="137" align="center" class="listeditd">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td  align="right" class="listeditd"></td>
    </tr>
    <tr>
      <td colspan="3" class="listingarea">
		  	  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_site_reviews = "SELECT a.done_by,a.done_for,a.action_made,a.action_date,a.done_for_fname,a.done_for_lname     
	 							FROM $table_name 
									$where_conditions 
										ORDER BY $sort_by $sort_order 
											LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_site_reviews);
	   $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
			//print_r($exp_review_date);
            $userid = $row['done_by'];
            $useridfor = $row['done_for'];
          $sqlby = "SELECT user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id='$useridfor' AND sites_site_id=".$ecom_siteid;
		$resby = $db->query($sqlby);
		if($db->num_rows($resby)>0)
		{
		 $rowby = $db->fetch_array($resby);
		 $user_name = $rowby['user_fname']." ".$rowby['user_lname'];;
		}
		else
		{
		 $user_name = $row['done_for_fname'];;
		}
         $action = $row['action_made'];
         $sqln = "SELECT user_id,user_fname,user_lname FROM sites_users_7584 WHERE user_id='$userid' AND sites_site_id=".$ecom_siteid;
		$resn = $db->query($sqln);
		if($db->num_rows($resn)>0)
		{
		 $rown = $db->fetch_array($resn);
		 $user_name_by = $rown['user_fname']." ".$rown['user_lname'];;
		}
		else
		{
		 $user_name_by = $row['done_for_lname'];
		}
		
	    $date = '';
		if($row['action_date']!='0000-00-00 00:00:00')
		{
	      $dateA = new DateTime($row['action_date']);
	      $date = $dateA->format('d-m-Y H:i:s');
		}
	   ?>
        <tr >
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		  
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $user_name_by;?></td>


        
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $user_name?></td>
         		    <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $action;
         		    ?></td>
         		    <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $date;
         		    ?></td>

        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="7" >
				  	No logs found.				  </td>
			</tr>
		<?
		}
		?>	
      
    </tr>
	<tr>
      <td class="listeditd">
	   </td>
      <td width="137" align="center" class="listeditd" >
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
      <td class="listeditd">&nbsp;</td>
    </tr>
    </table>
    </div>
    </td>
    </tr>
    </table>
</form>

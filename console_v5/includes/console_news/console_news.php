<?php
	/*#################################################################
	# Script Name 	: console_news.php
	# Description 	: Page for listing the news
	# Coded by 		: Lathhesh
	# Created on	: 03-Jul-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
?>
<script language="javascript">
function display_prodtext(imgobj,id)
{
	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	
		
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('news_details_'+id))
					document.getElementById('news_details_'+id).style.display = '';
					call_ajax_showlistall(id);	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('news_details_'+id))
					document.getElementById('news_details_'+id).style.display = 'none';
			}	
			
	}
function call_ajax_showlistall(id)
{  
	var atleastone 										= 0;
	var news_id										= id;
	var fpurpose										= '';
	var retdivid	= '';
	
			retdivid   	= 'newsDetails_div_'+id;
			fpurpose	= 'list_newsdetails';
			 document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
			var qrystr									= '';
			Handlewith_Ajax('services/console_news.php','fpurpose='+fpurpose+'&news_id='+news_id);	
}		
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			//alert(targetdiv );
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
</script>
<?
//#Select condition for getting total count
$table_name  = 'console_news';
$page_type = 'Console News';
$order_by = " ORDER BY 
    					 sites_site_id DESC,news_priority DESC,news_add_date DESC";
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'news_add_date':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('news_title' => 'Title','news_add_date' => 'Date Added');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//#Search Options
$where_conditions 	= " WHERE 
							(sites_site_id=$ecom_siteid OR sites_site_id=0) AND news_hide=0 ";
if($_REQUEST['search_name']) {
$where_conditions .= " AND ( news_title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
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
 $query_string .= "&request=console_news&records_per_page=$records_per_page&status=$status&start=$start&search_name=".$_REQUEST['search_name']."";
?>	
<form name='frmListNews' action='home.php' method="post">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="console_news" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php">Home</a> &gt;&gt; Console News </td>
        </tr>
		<tr>
      <td height="48" class="sorttd" colspan="4" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
			 <tr>
				  <td width="22%" align="left" valign="middle">News Title </td>
				  <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /> 
				  </td>
			 </tr>
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
			<tr>
			  <td width="12%" align="left">Show</td>
			  <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
				<?=$page_type?> Per Page</td>
			  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CONSOLE_NEWS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
      </table>
	  </td>
    </tr>
	  <?
	  if($numcount)
	  {
	  ?>
	  <tr>
		 <td class="listeditd"  align="center" colspan="2" >
	  <?
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
		?>
		</td>
	  </tr>
	  <? 
	  }
	  ?>
	  <? if($help_msg){?>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<? } 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
	if($numcount)
	  {
    	$sql_news = "SELECT 
							news_id,sites_site_id,news_priority,news_title,news_text,news_activeperiod,news_fromdate,news_todate,news_hide ,news_add_date
    		 		FROM 
			   				$table_name $where_conditions $order_by LIMIT $start,$records_per_page";
	    $ret_news = $db->query($sql_news);
	    	if ($db->num_rows($ret_news))
	    	{
	    		while ($row_news = $db->fetch_array($ret_news))
	    		{
	    			$valid_news = true;
	    			if ($row_news['news_activeperiod']==1)
	    			{
	    				$st_date = explode('-',$row_news['news_fromdate']);
	    				$fr_date = explode('-',$row_news['news_todate']);
	    				$start		= mktime(0,0,0,$st_date[1],$st_date[2],$st_date[0]);
	    				$end		= mktime(0,0,0,$fr_date[1],$fr_date[2],$fr_date[0]);
	    				$today		= mktime(0,0,0,date('m'),date('d'),date('Y'));
	    				if ($today<$start or $today>$end)
	    					$valid_news = false;
	    			}
	    			if($valid_news)
	    				$news_arr[] = $row_news;
	    		}
    	}
    	if(count($news_arr))
    	{
		
		?>
		 <tr>
		  <td colspan="2" class="listingarea">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <?php
	          	$count_no =1;	
				for($i=0;$i<count($news_arr);$i++)
	          	{
				
				if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
	          ?>
		   <tr >
		   <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="display_prodtext(this,<?=$news_arr[$i]['news_id']?>);" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd"><?=$news_arr[$i]['news_title']?> </td>
			  </tr>
			 <tr  id="news_details_<?=$news_arr[$i]['news_id']?>" style="display:none" >
          		<td align="left" valign="top" class="listingtablestyleA" width="3%">
			  </td><td  align="left" valign="middle" class="listingtablestyleA" >
				<div id="newsDetails_div_<?=$news_arr[$i]['news_id']?>" style="text-align:center">			    </div>				</td>
			</tr>
			  <? $count_no++;
			  }?>
		  </table>
		  </td>
		  </tr>
	  <? 
	  }
	  }
	  else
	  {
	    ?>
		 <tr>
			  <td align="center" valign="top" class="norecordredtext"  colspan="2">No News Found</td>
		</tr>
		<?   
	  }
	  ?>
		<tr>
		<td colspan="2"  class="tdcolorgray" >&nbsp;</td>
		</tr>
		<tr>
          <td width="47%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="53%" align="left" valign="middle" class="tdcolorgray">
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
			<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
        </tr>
      </table>
</form>	  


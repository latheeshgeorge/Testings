<?php
	/*#################################################################
	# Script Name 	: seo_ga_details.php
	# Description 	: Show GA details from our DB
	# Coded by 		: Sony
	# Created on	: 21-Jan-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
$sql_ga = "SELECT *,DATE_FORMAT(date_from,'%d/%M/%Y') as datefrom,DATE_FORMAT(date_to,'%d/%M/%Y') as dateto FROM seo_ga_data WHERE sites_site_id = $ecom_siteid LIMIT 1";
$ret_ga = $db->query($sql_ga);
if($db->num_rows($ret_ga))
{
	$row_ga = $db->fetch_array($ret_ga);
}

$sql_ga = "SELECT * FROM seo_ga_visit_details WHERE sites_site_id = $ecom_siteid ORDER BY orders ASC";
$ret_gas = $db->query($sql_ga);
$str2 ='';
if($db->num_rows($ret_ga))
{
	while($row_gas = $db->fetch_array($ret_gas))
	{
		$dates 	= $row_gas['date'];
		$visits	= $row_gas['visits'];
		if($str2!='')
			$str2 .=",";
		$str2 .="['".date('M d',mktime(0,0,0,substr($dates,4,2),substr($dates,-2),substr($dates,2,2)))."',  ".$visits."]";
	}
}
?>	
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
	    google.setOnLoadCallback(drawChartline);
     function drawChartline() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Visits'],
          <?php echo $str2?>
        ]);

        var options = {
         width: 1200,
   		 height: 400,
		 areaOpacity:0.4,
		 backgroundColor:{fill:'#FFFFFF'},
          vAxis: {title: 'Visits',  titleTextStyle: {color: '#FF6600'},minValue:'0',gridlines:{count:8},viewWindowMode:'pretty'},
		  hAxis: {title: 'Days',  titleTextStyle: {color: '#FF6600'}},
		  chartArea:{top:'10'},
		  legend:{position:'none'},
		  series:{0:{color: '#9ac2c1', visibleInLegend: false}}
        };

        var chart_new = new google.visualization.AreaChart(document.getElementById('chartline_div'));
        chart_new.draw(data, options);
      }
	  
	  

    </script>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" c>
        <tr>
          	<td  align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>Google Analytics Details</span></div></td>
        </tr>
      
		<?php 
		if($alert)
		{			
		?>
        <tr>
          	<td  align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		 <tr>
			<td colspan="2" class="tdcolorgray" align="left">    
		<div class="listingarea_div">
		<table width="100%" cellpadding="0" cellspacing="0">
		 <tr>
			<td colspan="2" align="left" class="seperationtd"><strong>Visits Between <?php echo $row_ga['datefrom'] ?> And <?php echo $row_ga['dateto'] ?> </strong></td>
		</tr>
		<tr>
		<td valign="top"  class="tdcolorgray" colspan="2">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
			<tr>
			  <td align="center" valign="middle" class="tdcolorgray" ><div id="chartline_div" style="width: 1200px; height: 400px;"></div></td>
			  </tr>
		  </table></td>
		</tr>
		
		<tr>
		  <td width="50%" align="left" valign="top"  class="tdcolorgray">
		  <div class="visitarea_div">
		  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="visitarea_table">
            <tr>
              <td width="47%"><span class="ga_caption_new">Visits:</span><span class='ga_details_new'> <?php echo $row_ga['visits']?></span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">Unique Visitors:</span><span class='ga_details_new'> <?php echo $row_ga['firsttime_visitors']?></span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">Page views:</span><span class='ga_details_new'> <?php echo $row_ga['pageviews']?></span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">Pages/Visit:</span> <span class='ga_details_new'><?php echo $row_ga['pages_visits']?></span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">Avg. Visit Duration:</span><span class='ga_details_new'> <?php echo $row_ga['avg_visit_duration']?></span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">Bounce Rate:</span><span class='ga_details_new'> <?php echo $row_ga['bounce_rate']?>%</span></td>
              </tr>
            <tr>
              <td><span class="ga_caption_new">New Visits Percentage:</span><span class='ga_details_new'> <?php echo $row_ga['new_visit_percetage']?>%</span></td>
              </tr>
          </table>
		  </div>
		  </td>
		  <td width="50%" align="center" valign="top">
		  <div class="traffic_source_div">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td class="traffic_souce_heading_td" align="left"><span class="traffic_source_heading">Traffic Sources </span></td>
            </tr>
            <tr>
              <td align="center" valign="top"><iframe id="gagraph_iframe" height="200px" allowtransparency="true" frameborder="0" scrolling="no" src="do_show_ga_traffic_source.php" title=""></iframe></td>
            </tr>
          </table>
		  </div>
		  </td>
		  </tr>
		<tr>
		  <td align="left" valign="top"  class="page_view_heading">Page View Details </td>
		  <td align="left" valign="top" class="keyword_heading">Keyword Details </td>
		  </tr>
		<tr>
		  <td align="left" valign="top" class="pageareaseo_td">
		  <?php 
		  $sql_det = "SELECT * FROM seo_ga_content_details WHERE sites_site_id = $ecom_siteid ORDER BY page_views DESC";
		  $ret_det = $db->query($sql_det);
		  ?>
		  <div class="pagearea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="4%" align="left" class="listingtableheader">#</td>
              <td width="58%" align="left" class="listingtableheader">Pages</td>
              <td width="12%" align="right" class="listingtableheader">Page Views </td>
              <td width="16%" align="right" class="listingtableheader">% Page Views </td>
            </tr>
			<?php
			$cnt = 1;
			if($db->num_rows($ret_det))
			{
				while ($row_det = $db->fetch_array($ret_det))
				{
					$cls = ($cnt%2==0)?'listingtablestyleB':'listingtablestyleA';
			?>
            <tr>
              <td align="left" class="<?php echo $cls?>"><?php echo $cnt;$cnt++;?></td>
              <td align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_det['page'])?></td>
              <td align="right" class="<?php echo $cls?>"><?php echo stripslashes($row_det['page_views'])?></td>
              <td align="right" class="<?php echo $cls?>"><?php echo stripslashes($row_det['page_views_per'])?></td>
            </tr>
			<?php
				}
			}
			else
			{
			?>
				 <tr>
              		<td align="center" colspan="4">-- No Details Found --</td>
				</tr>	
			<?php
			}
			?>
          </table>
		  </div>
		  </td>
		  <td align="left" valign="top" class="kwareaseo_td">
		   <?php 
		  $sql_det = "SELECT * FROM seo_ga_kw_details WHERE sites_site_id = $ecom_siteid ORDER BY visits DESC";
		  $ret_det = $db->query($sql_det);
		  ?>
		  <div class="keywordarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="4%" align="left" class="listingtableheader">#</td>
              <td width="57%" align="left" class="listingtableheader">Keywords</td>
              <td width="34%" align="right" class="listingtableheader">Visits</td>
              <td width="5%" align="right" class="listingtableheader">&nbsp;</td>
            </tr>
           <?php
			$cnt = 1;
			if($db->num_rows($ret_det))
			{
				while ($row_det = $db->fetch_array($ret_det))
				{
					$cls = ($cnt%2==0)?'listingtablestyleB':'listingtablestyleA';
			?>
					<tr>
					  <td align="left" class="<?php echo $cls?>"><?php echo $cnt;$cnt++;?></td>
					  <td align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_det['keyword'])?></td>
					  <td align="right" class="<?php echo $cls?>"><?php echo stripslashes($row_det['visits'])?></td>
					  <td align="right" class="<?php echo $cls?>">&nbsp;</td>
					</tr>
			<?php
				}
			}
			else
			{
			?>
				 <tr>
              		<td align="center" colspan="4">-- No Details Found --</td>
				</tr>	
			<?php
			}
			?>
          </table>
		  </div>
		  </td>
		  </tr>
		</table>
		</div>
		</td>
		</tr>
  </table>


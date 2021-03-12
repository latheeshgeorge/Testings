<?php
require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/classes/gapi.class.php");;
require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php"); 

//require_once("/var/www/html/webclinic/bshop4/classes/gapi.class.php");;
//require_once("/var/www/html/webclinic/bshop4/config_db.php"); 

$date_from 	= date('Y-m-d',mktime(0,0,0,date("m"),date("d")-31,date('Y')));
$date_to	= date('Y-m-d',mktime(23,59,59,date("m"),date("d")-1,date('Y'))); 
echo "From $date_from -- To $date_to";
// get the list of websites for which the ga details is to be picked.
$sql_main_list = "SELECT sites_site_id,ga_account,ga_password,ga_profile_id FROM sites_ga_main WHERE ga_active = 1";
$ret_main_list = $db->query($sql_main_list);
if($db->num_rows($ret_main_list))
{
	while ($row_main_list = $db->fetch_array($ret_main_list))
	{
		$site_id 		= $row_main_list['sites_site_id'];
		$ga_account		= $row_main_list['ga_account'];
		$ga_password	= $row_main_list['ga_password'];
		$ga_profile_id	= $row_main_list['ga_profile_id'];
		
		$ga 	= new gapi($ga_account,$ga_password);
		$ga1 	= new gapi($ga_account,$ga_password);
		
		$dimensions = array('source','medium','hostname','visitorType','visitCount','year');
		$metrics    = array('visits','visitBounceRate','pageviews','avgTimeOnSite','pageviewsPerVisit','percentNewVisits','newVisits','visitors');
		 
		/* We will sort the result be desending order of visits, 
			and hence the '-' sign before the 'visits' string */
		$ga->requestReportData($ga_profile_id, $dimensions, $metrics,'-visits','',$date_from,$date_to,1,50);
		
		$gaResults = $ga->getResults();
		
		$dimensions1 = array('year');
		$metrics1    = array('visitors');
		 
		/* We will sort the result be desending order of visits, 
			and hence the '-' sign before the 'visits' string */
		$ga1->requestReportData($ga_profile_id, $dimensions1, $metrics1,'','',$date_from,$date_to);
		
		$sr_arr = $pg_arr = array();
		$str2 =''; 
		$new = 0;
		foreach($gaResults as $result)
		{
			if($str2!='')
				$str2 .=',';
			$sr_arr[$result->getmedium()] += $result->getVisits();
		}
		
		
		$sql_sel = "SELECT sites_site_id FROM seo_ga_data WHERE sites_site_id = $site_id LIMIT 1";
		$ret_sel = $db->query($sql_sel);
		
		$visits			= $ga->getVisits();
		$firstvisits	= $ga1->getVisitors();
		$pgviews		= $ga->getpageviews();
		$pgvisits		= roundme($ga->getpageviewsPerVisit(),2);
		$avgdur			= date('H:i:s',mktime(0,0,$ga->getavgTimeOnSite()));
		$bnrate			= roundme($ga->getvisitBounceRate(),2);
		$nwper			= roundme($ga->getpercentNewVisits(),2);
		$sr_total		= $sr_arr['organic'];
		$dr_total		= $sr_arr['(none)'];
		$rf_total		= $sr_arr['referral'];
		
		// saving general information regarind the website 
		if($db->num_rows($ret_sel))
		{
			$update_array 						= array();
			$update_array['date_from']			= $date_from;
			$update_array['date_to']			= $date_to;
			$update_array['last_fetched_on']	= 'curdate()';
			$update_array['visits']				= $visits	;
			$update_array['firsttime_visitors']	= $firstvisits	;
			$update_array['pageviews']			= $pgviews	;
			$update_array['pages_visits']		= $pgvisits	;
			$update_array['avg_visit_duration']	= $avgdur	;
			$update_array['bounce_rate']		= $bnrate	;
			$update_array['new_visit_percetage']= $nwper	;
			$update_array['searchengine_total']	= $sr_total	;
			$update_array['direct_total']		= $dr_total	;
			$update_array['refering_total']		= $rf_total	;
			$db->update_from_array($update_array,'seo_ga_data',array('sites_site_id'=>$site_id));
		}
		else
		{
			$insert_array						= array();
			$insert_array['sites_site_id']		= $site_id;
			$insert_array['date_from']			= $date_from;
			$insert_array['date_to']			= $date_to;
			$insert_array['last_fetched_on']	= 'curdate()';
			$insert_array['visits']				= $visits	;
			$insert_array['firsttime_visitors']	= $firstvisits	;
			$insert_array['pageviews']			= $pgviews	;
			$insert_array['pages_visits']		= $pgvisits	;
			$insert_array['avg_visit_duration']	= $avgdur	;
			$insert_array['bounce_rate']		= $bnrate	;
			$insert_array['new_visit_percetage']= $nwper	;
			$insert_array['searchengine_total']	= $sr_total	;
			$insert_array['direct_total']		= $dr_total	;
			$insert_array['refering_total']		= $rf_total	;
			$db->insert_from_array($insert_array,'seo_ga_data');
		}
		
		// fetch and save content details
		$dimensions = array('pagePath');
		$metrics    = array('pageviews');
		$ga->requestReportData($ga_profile_id, $dimensions, $metrics,'-pageviews','',$date_from,$date_to,1,10);
		$gaResultsnew = $ga->getResults();
		$pg_arr 	= array();
		$sql_del = "DELETE FROM seo_ga_content_details WHERE sites_site_id = $site_id";
		$db->query($sql_del);
		foreach($gaResultsnew as $resultnew)
		{
			$pg = $resultnew->getpagePath();
			$vs = $resultnew->getpageviews();
			$pr = round(($resultnew->getpageviews()/$ga->getpageviews())*100,2);
			
			$insert_array					= array();
			$insert_array['sites_site_id']	= $site_id;
			$insert_array['page']			= $pg;
			$insert_array['page_views']		= $vs;
			$insert_array['page_views_per']	= $pr;
			$db->insert_from_array($insert_array,'seo_ga_content_details');
		}
		
		// fetch and save keyword details
		$dimensions = array('medium','keyword');
		$metrics    = array('visits');
		
		$ga->requestReportData($ga_profile_id, $dimensions, $metrics,'-visits','',$date_from,$date_to,4,10);
		$gaResults = $ga->getResults();
		$sql_del = "DELETE FROM seo_ga_kw_details WHERE sites_site_id = $site_id";
		$db->query($sql_del);
		foreach($gaResults as $result)
		{
			$kw = $result->getkeyword();
			$md = $result->getmedium();
			$vs = $result->getvisits();
			$insert_array					= array();
			$insert_array['sites_site_id']	= $site_id;
			$insert_array['keyword']		= $kw;
			$insert_array['visits']			= $vs;
			$db->insert_from_array($insert_array,'seo_ga_kw_details');
		}
		
		// fetch and save keyword details
		$dimensions = array('date');
		$metrics    = array('visits');
		
		$ga->requestReportData($ga_profile_id, $dimensions, $metrics,'date','',$date_from,$date_to,1,48);
		$gaResults = $ga->getResults();
		$sql_del = "DELETE FROM seo_ga_visit_details WHERE sites_site_id = $site_id";
		$db->query($sql_del);
		$cur_row = 1;
		foreach($gaResults as $result)
		{
			$date 							= $result->getDate();
			$vs 							= $result->getVisits();
			$insert_array					= array();
			$insert_array['sites_site_id']	= $site_id;
			$insert_array['date	']			= $date;
			$insert_array['visits']			= $vs;
			$insert_array['orders']			= $cur_row;
			$cur_row++;
			$db->insert_from_array($insert_array,'seo_ga_visit_details');
		}
	}	
}	
function roundme($val,$dec)
{
	return round($val,$dec);
}
echo "Done";
?>

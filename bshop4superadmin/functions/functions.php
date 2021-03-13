<?php
#################################################################
	# Script Name 	: functions.php
	# Description 	: General Functions page
	# Coded by 		: SG
	# Created on	: 09-Jun-2006
	# Modified by	: SG
	# Modified On	: 12-June-2006
#################################################################
function redirect($url,$arg = "")
{
	if($arg) {
		@header("Location: ".$url."?".session_name()."=".session_id()."&".$arg);
	} else {
		@header("Location: ".$url."?".session_name()."=".session_id());
	}
	exit;
}
function generateselectbox($name,$option_values,$selected,$onblur='',$onchange='') {
	$return_value = "<select name='$name' id='$name'";
	if($onblur) {
		$return_value .= " onblur='$onblur'";
	}
	if($onchange) {
		$return_value .= " onchange='$onchange'";
	}
	$return_value .= ">";
	foreach($option_values as $k => $v) {
		if($selected == $k) {
			$return_value .= "<option value='$k' selected>$v</option>";
		} else {
			$return_value .= "<option value='$k'>$v</option>";
		}
	}
	
	$return_value .= "</select>";
	return $return_value;
}

function add_slash($varial,$strip_tags=true)
{
    #checking whether magic quotes are on
	if (!get_magic_quotes_gpc()){
		$ret=addslashes(trim($varial));
	} else {
		$ret=trim($varial);
	}
	if($strip_tags==true)
	$ret = strip_tags($ret);
	return $ret;
	#checking whether magic quotes are on
	/*if (!get_magic_quotes_gpc()){
		$ret=addslashes(trim($varial));
	} else {
		$ret=trim($varial);
	}
	return $ret;*/
}
function serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc) {
	global $alert;
	foreach($fieldRequired as $k => $v) {
		if(trim($v) == "" || $v == '0') {
			$alert = "Enter ".$fieldDescription[$k];
			return false;
		}
	}
	foreach($fieldEmail as $v) {
		if(!ereg("^[-a-z0-9_.]+@[-a-z0-9]+\.([a-z.]{2,15})",trim($v))) {
			$alert = "Enter a valid Email address";
			return false;
		}
	}
	if (isset($fieldConfirm[0])) {
		if($fieldConfirm[0] != $fieldConfirm[1]) {
			$alert = "Your ".$fieldConfirmDesc[0]." and ".$fieldConfirmDesc[1]." does not match";
			return false;
		}
	}
	foreach($fieldNumeric as $k => $v) {
		if(!is_numeric($v)) {
			$alert = "Enter numeric value for ".$fieldNumericDesc[$k];
			return false;
		}
	}
	return true;
}
/****************Check date difference***********************/
function check_date_difference($from,$to)
{
	$from_date_array = explode("-",$from);
	$from_date_time = mktime(0,0,0,$from_date_array[1],$from_date_array[2],$from_date_array[0]);
	$to_date_array = explode("-",$to);
	$to_date_time = mktime(0,0,0,$to_date_array[1],$to_date_array[2],$to_date_array[0]);
	if($from_date_time <= $to_date_time) {
		return true;
	} else {
		return false;
	}
}
/*******************Function to validate Password**********************/
function check_password($password)
{
	If (ereg('^([a-zA-Z0-9_]{4,12})$', $password)) {
		return true;
	} else {
		return false;
	}
}
function dateFormat($passdt, $type = "default") {
	#############fromat of displaying date '4:21pm -Mar 11 Sat'
	//$arow[orddt] in yyyy-mm-dd hh:mm:sec format
	$sp_dt1=explode(" ",$passdt);
	$sp_dt = explode("-",$sp_dt1[0]);
	$rt_year=$sp_dt[0];
	$rt_month=$sp_dt[1];
	$rt_day=$sp_dt[2];
	$sp_dt2 = explode(":",$sp_dt1[1]);
	$rt_hr = $sp_dt2[0];
	$rt_min = $sp_dt2[1];
	$rt_sec = $sp_dt2[2];
	$unixstamp=mktime ($rt_hr,$rt_min,$rt_sec,$rt_month,$rt_day,$rt_year);
	// $dtdisp=@date("h :i a"." - "."M d Y D",$unixstamp);
	if($type == 'time') {
		$dtdisp = @date("h :i a",$unixstamp);
	} else {
		$dtdisp = @date("d-M-Y",$unixstamp);
	}
	return $dtdisp;
	//int mktime (int hour, int minute, int second, int month, int day, int year [, int is_dst])
}
//Function for urldecode
function urlExtract( $foo ) {
	$temp = array();
	$foo = urldecode(base64_decode($foo));
	$vars = explode('&',$foo);
	$i = 0;
	while ($i < count($vars)) {
		$b = split('=', $vars[$i]);
		$temp[$b[0]] = $b[1];
		$i++;
	}
	return $temp;
}
//Functions for Paging
function pageNavApp ($pagenum, $pages, $query_str='') {
	global $pg, $records_per_page;
	// offset = (page - 1) * thumbs
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
		if($query_str) {
			$a = "<a href='home.php?$query_str&records_per_page=$records_per_page&pg=";
		} else {
			$a = "<a href='home.php?records_per_page=$records_per_page&pg=";
		}
	$b = "'>";
	$c = "</a>\n";
	$nav = ""; // init page nav string
	if ($pagenum == 1) {
		
			//$nav .= "<img src='images/paging/left2_disabled.gif' border='0'>[First]&nbsp;&nbsp;";
			$nav .= "<img src='images/paging/left2_disabled.gif' border='0' alt='First'>&nbsp;&nbsp;";
			//$nav .= "<img src='images/paging/left_disabled.gif' border='0'>[Prev]&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= "<img src='images/paging/left_disabled.gif' border='0' alt='Prev'>&nbsp;&nbsp;&nbsp;&nbsp;";
		
	} else {
		
			//$nav .= $a."1".$b."<img src='images/paging/left2.gif' border='0'>[First]".$c."&nbsp;&nbsp;";
			$nav .= $a."1".$b."<img src='images/paging/left2.gif' border='0' alt='First'>".$c."&nbsp;&nbsp;";
			//$nav .= $a.($pagenum - 1).$b."<img src='images/paging/left.gif' border='0'>[Prev]".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= $a.($pagenum - 1).$b."<img src='images/paging/left.gif' border='0' alt='Prev'>".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
		
	}
	if ($pagenum == $pages) {
		
			//$nav .= "<img src='images/paging/right_disabled.gif' border='0'>[Next]&nbsp;&nbsp;";
			$nav .= "<img src='images/paging/right_disabled.gif' border='0' alt='Next'>&nbsp;&nbsp;";
			//$nav .= "<img src='images/paging/right2_disabled.gif' border='0'>[Last]<br>";
			$nav .= "<img src='images/paging/right2_disabled.gif' border='0' alt='Last'><br>";
			
	} else {
			//$nav .= $a.($pagenum +1).$b."<img src='images/paging/right.gif' border='0'>[Next]".$c."&nbsp;&nbsp;";
			$nav .= $a.($pagenum +1).$b."<img src='images/paging/right.gif' border='0' alt='Next'>".$c."&nbsp;&nbsp;";
			//$nav .= $a.($pages).$b."<img src='images/paging/right2.gif' border='0'>[Last]".$c."<br>";
			$nav .= $a.($pages).$b."<img src='images/paging/right2.gif' border='0' alt='Last'>".$c."<br>";
		
	}
	$nav .= makeNavApp ($pages, $pagenum, $query_str, $javascript_fn);
	return $nav;
}
function makeNavApp ($pages, $pagenum, $query_str='', $nav = "", $mag = 1) {
	global $pg, $records_per_page, $theme_folder;
	$n = 10; // Number of pages or groupings
	$m = 10; // Order of magnitude of groupings
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if($query_str) {
		$a = "<a href='home.php?$query_str&records_per_page=$records_per_page&pg=";
	} else {
		$a = "<a href='home.php?records_per_page=$records_per_page&pg=";
	}
	$b = "'>";
	$c = "</a>\n";
	if ($mag == 1) {
		// single page level
		$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
		for ($i = $minpage; $i < $pagenum; $i++) {
			if ( isset($nav[1]) ) {
				$nav[1] .= $a.($i).$b;
			} else {
				$nav[1] = $a.($i).$b;
			}
			$nav[1] .= "$i";
			$nav[1] .= $c;
		}
		if ( isset($nav[1]) ) {
			$nav[1] .= "$pagenum ";
		} else {
			$nav[1] = "$pagenum ";
		}
		$maxpage = ceil ($pagenum/$n) * $n;
		if ( $pages >= $maxpage ) {
			for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br>";
		} else {
			for ($i = ($pagenum+1); $i <= $pages; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br>";
		}
		if ( $minpage > 1 || $pages > $n ) {
			// go to next level
			$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $n);
		}
		// Construct outgoing string from pieces in the array
		$out = $nav[1];
		for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
			if (isset($nav[$i][1]) && isset($nav[$i][2])) {
				$out = $nav[$i][1].$out.$nav[$i][2];
			} else if (isset($nav[$i][1])) {
				$out = $nav[$i][1].$out;
			} else if (isset($nav[$i][2])) {
				$out = $out.$nav[$i][2];
			} else {
				$out = $out;
			}
		}
		return $out;
	}
	$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
	$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
	if ( $prevpage > $minpage ) {
		for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
			if (isset($nav[$mag][1])) {
				$nav[$mag][1] .= $a.($i+1).$b;
			} else {
				$nav[$mag][1] = $a.($i+1).$b;
			}
			$nav[$mag][1] .= $a.($i+1).$b;
			$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
			$nav[$mag][1] .= $c;
		}
		$nav[$mag][1] .= "<br>";
	} // Otherwise, it's this page's group, which is handled the mag level below, so skip
	$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
	if ( $pages >= $maxpage ) {
		// If there are more pages than we are accounting for here
		$nextpage = ceil ($pagenum/$mag) * $mag;
		if ($maxpage > $nextpage) {
			for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= "<br>";
		}
	} else {
		// This is the end
		if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
			// If there are more pages than just this page's group
			for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= $a.($i+1).$b;
			$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
			$nav[$mag][2] .= $c;
			$nav[$mag][2] .= "<br>";
		}
	}
	if ( $minpage > 1 || $pages >= $maxpage ) {
		$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $mag * $m);
	}
	return $nav;
}

function paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan) {
	echo "<tr class=\"maininnertabletd1\">
		<td colspan=\"$colspan\" align=\"center\">$numcount $page_type found. 
			Page <b>$pg</b> of <b>$pages</b>
		</td>
	</tr>";
	if($numcount) {
		echo "<tr class=\"maininnertabletd1\">
			<td colspan=\"$colspan\" align=\"center\">";
			echo pageNavApp ($pg, $pages, $query_string,$directpage);
			echo '</td>
		</tr>';
	}
}
function table_header($array,$header_positions) {

	$return_value = '<tr class="maininnertabletd1">';
	foreach ($array as $k=>$value) {
		$align='center';
		if($header_positions[$k])
		{
			$align=$header_positions[$k];
		}
		$return_value .= '<td align="'.$align.'" valign="middle" class="maininnertabletd1"><strong class="fontredheading">'.$value.'</strong></td>';
	}
	$return_value .= '</tr>';
	return $return_value;
}

function writeSiteList() {
		global $db;
		$rstSiteList=$db->query("SELECT site_domain FROM sites ORDER BY domain");
		$siteList = "";
		while($siteListR = $db->fetch_array($rstSiteList)) {
			$siteList .= $siteListR['site_domain']."\n";
		}

		$fileHandle = fopen(SITE_DOCUMENT_ROOT."/conf/siteList", "w");
		fwrite($fileHandle,trim($siteList));
		fclose($fileHandle);

		if(file_exists(SITE_DOCUMENT_ROOT."/conf/siteList")) {
			touch(SITE_DOCUMENT_ROOT."/conf/siteList_isUpdated.temp");
		}

	}
function headed_email($to, $subject, $content, $attachments = array())
{
		global $insert_siteid;
		$logo = 'logo.gif';
			
		/*$m = new Mime("Business 1st <support@bfirst.uk.com>",	// From
					  $to,											// To
					  "Support <support@bfirst.uk.com>",		// CC
					  $subject, 									// Subject
					  "multipart/mixed");							// Content-type*/
		$m = new Mime("Business 1st <sony.joy@calpinetech.com>",	// From
					  $to,											// To
					  "Support <sony.joy@calpinetech.com>",		// CC
					  $subject, 									// Subject
					  "multipart/mixed");
		//$b1st_logo_id = $m->generate_cid();
		//$msoft_logo_id = $m->generate_cid();

		$message = file("emails/template.html");
		$message = implode("", $message);
		$message = str_replace("{ title }", $subject, $message);
		$message = str_replace("{ content }", $content, $message);
		//$message = str_replace("{ b1st_logo }", "cid:$b1st_logo_id", $message);
		//$message = str_replace("{ msoft_logo }", "cid:$msoft_logo_id", $message);

		$m->start_multipart("related");
		$m->insert_text("html", $message);
		//m->insert_image("emails/b1st-logo.jpg", $b1st_logo_id);
		//$m->insert_image("emails/$logo", $bshop_logo_id);
		$m->end_multipart();

		foreach($attachments as $attach) $m->insert_attachment($attach["type"], $attach["filename"]);
		$m->send();
}
function generate_console_menu($site_id)
{
	global $db;
	$show_tree = '';
	// Find all root level menu items for current site from mod_menus table
	$sql_mod = "SELECT distinct a.service_id,a.service_name FROM services a,mod_menu b WHERE b.sites_site_id=$site_id 
				AND a.service_id = b.services_service_id ORDER BY a.ordering";
	$ret_mod = $db->query($sql_mod);
	if ($db->num_rows($ret_mod))
	{
		echo "<table width='100%' cellpadding=1 cellspacing=1 border='0'>";
		while ($row_mod = $db->fetch_array($ret_mod))
		{
			echo "<tr><td align='left' class='maininnertabletd4'>&nbsp;<strong>".stripslashes($row_mod['service_name'])."</strong></td></tr>";
			// Find the first level features under this service
			$sql_feat = "SELECT a.feature_id,b.menu_title FROM features a,mod_menu b WHERE 
						a.feature_id = b.features_feature_id AND b.sites_site_id=$site_id 
						AND a.parent_id=0 AND b.services_service_id=".$row_mod['service_id']." ORDER BY menu_order";
			$ret_feat = $db->query($sql_feat);
			while ($row_feat = $db->fetch_array($ret_feat))
			{
				echo "<tr><td align='left'>&nbsp;&nbsp; <img src='../../images/leftline.gif' title=''>".stripslashes($row_feat['menu_title'])."</td></tr>";
				$ret_tree = generate_console_menu_items($site_id,$row_feat['feature_id'],1);
			}			
		}	
	}
	echo "<tr><td align='center'><br><br><br><input type='button' value='Close' class='input-button' onclick='window.close();'></td></tr>";
}
function generate_service_hierarchy($service_id)
{
	global $db;
	$show_tree = '';
	$sql_service="SELECT service_name FROM services WHERE service_id=".$service_id;
	$ret_service=$db->query($sql_service);
	$row_service = $db->fetch_array($ret_service);
	
	echo "<table width='100%' cellpadding=1 cellspacing=1 border='0'>";
	echo "<tr><td align='left' class='maininnertabletd4'>&nbsp;<strong>".stripslashes($row_service['service_name'])."</strong></td></tr>";
	// Find the first level features under this service
			$sql_feat = "SELECT feature_id,feature_title FROM features WHERE 
						parent_id=0 AND services_service_id=".$service_id;
						
			$ret_feat = $db->query($sql_feat);
			if(mysql_num_rows($ret_feat))
			{
				while ($row_feat = $db->fetch_array($ret_feat))
				{
					echo "<tr><td align='left'>&nbsp;&nbsp; <img src='../../images/leftline.gif' title=''>".stripslashes($row_feat['feature_title'])."</td></tr>";
					$ret_tree = generate_hierarchy_menu_items($row_feat['feature_id'],1);
				}			
			}
			else
			{
					echo "<tr><td align='left'>&nbsp;&nbsp;Sorry..No Features Listed</td></tr>";
			}
			
	
	echo "<tr><td align='center'><br><br><br><input type='button' value='Close' class='input-button' onclick='window.close();'></td></tr>";
}
function generate_hierarchy_menu_items($id,$level=0)
{
	global $db;
	$query = "SELECT feature_id,feature_title FROM features WHERE 
			  parent_id=$id";
		  
	$result = $db->query($query);
	while(list($id,$title) = $db->fetch_array($result))
	{
		$space = '';
		for($i=0; $i<=$level-1; $i++) {
			$space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		echo  "<tr><td align='left'>". $space."<img src='../../images/leftline.gif' title=''>".$title."</td></tr>";
		$features = generate_hierarchy_menu_items($id,$level+1);
		if(is_array($features))
		{
			$space = '';
			for($i=0; $i<=$level-1; $i++) {
				$space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			foreach($features as $k => $v)
			{
				echo "<tr><td align='left'>".$space."<img src='../../images/leftline.gif' title=''>".$v."</td></tr>";
			}
		}
	}
}
/*function generate_parent_menu_items($id,$level,$str='')
{
	
	global $db;
	$query = "SELECT parent_id FROM features  WHERE 
			  feature_id=$id";
		  
	
	//echo $query;
	$result = $db->query($query);
	list($parent_id)=$db->fetch_array($result);
	
	if($parent_id<>0)
	{
		$query_parent="SELECT feature_title FROM features WHERE feature_id=".$parent_id;
		$result_parent = $db->query($query_parent);
		list($parent_name)=$db->fetch_array($result_parent);
		//$str.=$parent_name;
		$level++;
		
		for($i=1;$i<=$level;$i++)
		{
			$space.="&nbsp;";
		}
		
		$str=$parent_name."<br>".$str;
			
			
		
		generate_parent_menu_items($parent_id,$level);
		
	}	
	
	echo $str;
	
	
}*/
function getParentTree($id,$level=0,$arr)
{
	global $db;
	
	$query = "SELECT parent_id FROM features  WHERE 
			  feature_id=$id";
	
	$result = $db->query($query);
	list($parent_id)=$db->fetch_array($result);
	$arr[$level]=$id;
	if($parent_id>0)
	{
		$level++;
		getParentTree($parent_id,$level,$arr);
	}
	else
	{
		generateFatureTree($arr);
    }
}
function generateFatureTree($arr)
{
	global $db;
	$res_arr=array_reverse($arr);
	
	$space='';
	$img='';	
	foreach($res_arr as $v)
	{
		$query="SELECT feature_name FROM features WHERE feature_id=".$v;
		$result = $db->query($query);
		list($feature_name)=$db->fetch_array($result);
		$space.="&nbsp;&nbsp;&nbsp;";
		echo  "<tr><td align='left'>".$space.$img.$feature_name."</td></tr>";
		$img="<img src='../../images/leftline.gif' title=''>";
	}
	
	echo "<tr><td align='center'><br><br><br><input type='button' value='Close' class='input-button' onclick='window.close();'></td></tr>";
}
function getParentTreeCat($id,$level=0,$arr)
{
	global $db;
	
	$query = "SELECT parent_id FROM product_categories  WHERE 
			  category_id=$id";
	
	$result = $db->query($query);
	list($parent_id)=$db->fetch_array($result);
	$arr[$level]=$id;
	if($parent_id>0)
	{
		$level++;
		getParentTreeCat($parent_id,$level,$arr);
	}
	else
	{
		generateCatTree($arr);
    }
}
function generateCatTree($arr)
{
	global $db;
	$res_arr=array_reverse($arr);
	
	$space='';
	$img='';	
	foreach($res_arr as $v)
	{
		$query="SELECT category_name FROM product_categories WHERE category_id=".$v;
		$result = $db->query($query);
		list($cat_name)=$db->fetch_array($result);
		$space.="&nbsp;&nbsp;&nbsp;";
		echo  "<tr><td align='left'>".$space.$img.$cat_name."</td></tr>";
		$img="<img src='../../images/leftline.gif' title=''>";
	}
	
	echo "<tr><td align='center'><br><br><br><input type='button' value='Close' class='input-button' onclick='window.close();'></td></tr>";
}
function generate_console_menu_items($site_id,$id,$level=0)
{
	global $db;
	$query = "SELECT a.feature_id,b.menu_title FROM features a,mod_menu b WHERE 
						a.feature_id = b.features_feature_id AND b.sites_site_id=$site_id 
						AND a.parent_id=$id ORDER BY menu_order";
	$result = $db->query($query);
	while(list($id,$title) = $db->fetch_array($result))
	{
		$space = '';
		for($i=0; $i<=$level-1; $i++) {
			$space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		echo  "<tr><td align='left'>". $space."<img src='../../images/leftline.gif' title=''>".$title."</td></tr>";
		$features = generate_console_menu_items($site_id,$id,$level+1);
		if(is_array($features))
		{
			$space = '';
			for($i=0; $i<=$level-1; $i++) {
				$space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			foreach($features as $k => $v)
			{
				echo "<tr><td align='left'>".$space."<img src='../../images/leftline.gif' title=''>".$v."</td></tr>";
			}
		}
	}
}

function generate_category_tree($id,$level=0,$all=false,$only_cat=false,$select=false)
{
	global $db,$cbo_sites;
	if($id == 0) {
		if(!$only_cat) {
			if($all)  $categories[0] = '--- All ---';
			elseif($select) $categories[0] = '--- Select ---';
			else $categories[0] = '--- Root Level ---';
		}
	}
	$query = "select category_id,category_name from product_categories where parent_id=$id AND sites_site_id=$cbo_sites";
	$result = $db->query($query);
	while(list($id,$name) = $db->fetch_array($result))
	{
		$space = '';
		for($i=0; $i<=$level-1; $i++) {
			$space .= '--';
		}
		$categories[$id] = $space.$name;
		$subcategories = generate_category_tree($id,$level+1);
		if(is_array($subcategories))
		{
			$space = '';
			for($i=0; $i<=$level-1; $i++) {
				$space .= '--';
			}
			foreach($subcategories as $k => $v)
			{
				$categories[$k] = $space.$v;
			}
		}
	}
	return $categories;
}


/* Function to create cache settings files when ever a setting is saved in general settings section in console*/
function create_GeneralSettings_CacheFile($image_path,$site_id)
{
	global $db;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
	$file_name = $file_path.'/general_settings.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_sites_common
	$sql_common = "SELECT * 
								FROM 
									general_settings_sites_common 
								WHERE 
									sites_site_id=$site_id 
								LIMIT 
									1";
	$ret_common = $db->query($sql_common);
	if ($db->num_rows($ret_common))
	{
		$row_common = $db->fetch_assoc($ret_common);
		foreach ($row_common as $k=>$v)
		{
			if ($k!=='listing_id' and $k!=='sites_site_id')
				fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	
	// get the details from general_settings_sites_common_onoff
	$sql_common = "SELECT * 
							FROM 
								general_settings_sites_common_onoff 
							WHERE 
								sites_site_id=$site_id 
							LIMIT 
								1";
	$ret_common = $db->query($sql_common);
	if ($db->num_rows($ret_common))
	{
		$row_common = $db->fetch_assoc($ret_common);
		foreach ($row_common as $k=>$v)
		{
			if ($k!=='listing_id' and $k!=='sites_site_id')
				fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	
	// get the details from general_settings_sites_listorders
	$sql_common = "SELECT * 
								FROM 
									general_settings_sites_listorders 
								WHERE 
									sites_site_id=$site_id 
								LIMIT 
									1";
			$ret_common = $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_assoc($ret_common);
				foreach ($row_common as $k=>$v)
				{
					if ($k!=='listing_id' and $k!=='sites_site_id')
						fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
				}	
			}
	fwrite($fp,'?>');		
	fclose($fp);
}

/*  Function to create the price displaly settings when ever a price display settings is changed from console area */
function create_PriceDisplaySettings_CacheFile($image_path,$site_id)
{

	global $db;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
	$file_name = $file_path.'/price_display_settings.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_pricedisplay
	$sql_price = "SELECT * 
							FROM 
								general_settings_site_pricedisplay 
							WHERE 
								sites_site_id = $site_id 
							LIMIT 
								1";
	$ret_price = $db->query($sql_price);
	if ($db->num_rows($ret_price))
	{
		$row_price = $db->fetch_assoc($ret_price);
		foreach($row_price as $k=>$v)
		{	
			if ($k!=='price_id' and $k!=='sites_site_id')
				fwrite($fp,'$PriceSettings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	fwrite($fp,'?>');		
	fclose($fp);	
}

/*  Function to create the price displaly settings when ever a price display settings is changed from console area for "ALL Sections" */
function create_Captions_CacheFile_All($image_path,$site_id)
{
	global $db;
	
	// Get the name of section 
	$sql_section = "SELECT section_id,section_code 
							FROM 
								general_settings_section ";
	$ret_section = $db->query($sql_section);
	if($db->num_rows($ret_section))
	{
		while($row_section 	= $db->fetch_array($ret_section))
		{
			$section_code	=  strtolower($row_section['section_code']);	
			$section_id			= $row_section['section_id'];
			if(!file_exists($image_path .'/settings_cache'))
				mkdir($image_path .'/settings_cache');
			$file_path = $image_path .'/settings_cache/settings_captions';
			if(!file_exists($file_path))
				mkdir($file_path);
			
			// get the details from general_settings_site_pricedisplay
			$sql_cap = "SELECT general_key,general_text 
									FROM 
										general_settings_site_captions  
									WHERE 
										sites_site_id = $site_id 
										AND general_settings_section_section_id = $section_id 
									";
			$ret_cap = $db->query($sql_cap);
			if ($db->num_rows($ret_cap))
			{
				$file_name = $file_path.'/'.$section_code.'.php';
				// Open the file in write mod 
				$fp = fopen($file_name,'w');
				fwrite($fp,'<?php'."\n");
				while($row_cap = $db->fetch_array($ret_cap))
				{	
					fwrite($fp,'$Cache_captions_arr["'.$row_cap['general_key'].'"] = "'. addslashes(stripslashes($row_cap['general_text'])).'";'."\n");
				}	
				fwrite($fp,'?>');		
				fclose($fp);	
			}
		}
	}		
}

/*  Function to create the site menu and mod menu cache */
function create_SitemenModmenu_CacheFile($image_path,$site_id)
{

	global $db;
	$consolecache_path = $image_path.'/cache';
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
		
	// Case of writing Site menu	
	$file_name = $file_path.'/site_menu.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_pricedisplay
	$sql_menu = "SELECT a.feature_modulename  
							FROM 
								features a,site_menu b 
							WHERE 
								b.sites_site_id=$site_id  
								AND a.feature_id = b.features_feature_id 
								AND a.feature_hide = 0";
	$ret_menu = $db->query($sql_menu);
	if($db->num_rows($ret_menu))
	{
		while ($row_menu = $db->fetch_array($ret_menu))
		{
			if(trim($row_menu['feature_modulename'])!='')
				fwrite($fp,'$inlineSiteComponents_Arr[] = "'. addslashes(stripslashes($row_menu['feature_modulename'])).'";'."\n");
		}
	}
	fwrite($fp,'?>');		
	fclose($fp);	
	
	// Case of writing Mod menu	
	$file_name = $file_path.'/mod_menu.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_pricedisplay
	$sql_menu = "SELECT a.feature_modulename  
							FROM 
								features a,mod_menu b 
							WHERE 
								b.sites_site_id=$site_id  
								AND a.feature_id = b.features_feature_id 
								AND a.feature_hide = 0";
	$ret_menu = $db->query($sql_menu);
	if($db->num_rows($ret_menu))
	{
		while ($row_menu = $db->fetch_array($ret_menu))
		{
			if(trim($row_menu['feature_modulename'])!='')
				fwrite($fp,'$consoleSiteComponents_Arr[] = "'. addslashes(stripslashes($row_menu['feature_modulename'])).'";'."\n");
		}
	}
	fwrite($fp,'?>');		
	fclose($fp);	
	$console_menu = $consolecache_path.'/console_menu.txt';
	if (file_exists($console_menu))
		unlink($console_menu);
}

/*  Function to create the currency cache */
function create_Currency_CacheFile($image_path,$site_id)
{

	global $db;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
		
	// Case of currencies
	$file_name = $file_path.'/currency.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_currency
	$sql_curr = "SELECT currency_id, curr_name, curr_sign, curr_sign_char, curr_code, curr_rate, curr_margin, curr_default, curr_numeric_code 
							FROM 
								general_settings_site_currency  
							WHERE 
								sites_site_id = $site_id 
							ORDER BY 
								curr_default DESC";
	$ret_curr = $db->query($sql_curr);
	if($db->num_rows($ret_curr))
	{
		while ($row_curr = $db->fetch_array($ret_curr))
		{
			if($row_curr['curr_default']==1)
			{
				fwrite($fp,'$default_curr[\'currency_id\'] = "'. addslashes(stripslashes($row_curr['currency_id'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_name\'] = "'. addslashes(stripslashes($row_curr['curr_name'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_sign\'] = "'. addslashes(stripslashes($row_curr['curr_sign'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_sign_char\'] = "'. addslashes(stripslashes($row_curr['curr_sign_char'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_code\'] = "'. addslashes(stripslashes($row_curr['curr_code'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_rate\'] = "'. addslashes(stripslashes($row_curr['curr_rate'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_margin\'] = "'. addslashes(stripslashes($row_curr['curr_margin'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_default\'] = "'. addslashes(stripslashes($row_curr['curr_default'])).'";'."\n");
				fwrite($fp,'$default_curr[\'curr_numeric_code\'] = "'. addslashes(stripslashes($row_curr['curr_numeric_code'])).'";'."\n");
			}
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'currency_id\'] = "'. addslashes(stripslashes($row_curr['currency_id'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_name\'] = "'. addslashes(stripslashes($row_curr['curr_name'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_sign\'] = "'. addslashes(stripslashes($row_curr['curr_sign'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_sign_char\'] = "'. addslashes(stripslashes($row_curr['curr_sign_char'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_code\'] = "'. addslashes(stripslashes($row_curr['curr_code'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_rate\'] = "'. addslashes(stripslashes($row_curr['curr_rate'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_margin\'] = "'. addslashes(stripslashes($row_curr['curr_margin'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_default\'] = "'. addslashes(stripslashes($row_curr['curr_default'])).'";'."\n");
				fwrite($fp,'$sel_curr['.$row_curr['currency_id'].'][\'curr_numeric_code\'] = "'. addslashes(stripslashes($row_curr['curr_numeric_code'])).'";'."\n");
		}
	}
	fwrite($fp,'?>');		
	fclose($fp);	
}

/*  Function to create the price displaly settings when ever a price display settings is changed from console area */
function create_Tax_Delivery_Paytype_Paymethod_CacheFile($image_path,$site_id)
{

	global $db;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
	$file_name = $file_path.'/common_settings.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_pricedisplay
	$tax_vals = 0;
	$tax_name	= array();
	$sql_tax = "SELECT tax_name,tax_val
					FROM
						general_settings_site_tax
					WHERE
						sites_site_id = $site_id
						AND tax_active = 1";
	$ret_tax = $db->query($sql_tax);
	if ($db->num_rows($ret_tax))
	{
		while ($row_tax = $db->fetch_array($ret_tax))
		{
			$tax_vals += $row_tax['tax_val'];
			$tax_name[] = stripslashes($row_tax['tax_name']);
		}
	}

	fwrite($fp,'$ret_tax["tax_val"] 		= "'. $tax_vals.'";'."\n");
	for ($i=0;$i<count($tax_name);$i++)
	{
		fwrite($fp,'$ret_tax["tax_name"][] 	= "'. $tax_name[$i].'";'."\n");
	}	
	
	$sql_del = "SELECT a.deliverymethod_id,a.deliverymethod_text,a.deliverymethod_location_required,deliverymethod_name 
						FROM
							delivery_methods a,general_settings_site_delivery b
						WHERE
							b.sites_site_id = $site_id
							AND a.deliverymethod_id=b.delivery_methods_delivery_id
						LIMIT
							1";
	$ret_del = $db->query($sql_del);
	if ($db->num_rows($ret_del))
	{
		$row_del = $db->fetch_array($ret_del);
		fwrite($fp,'$ret_delivery["deliverymethod_id"] 					= "'. $row_del['deliverymethod_id'].'";'."\n");
		fwrite($fp,'$ret_delivery["deliverymethod_name"] 				= "'. $row_del['deliverymethod_name'].'";'."\n");
		fwrite($fp,'$ret_delivery["deliverymethod_text"] 				= "'. $row_del['deliverymethod_text'].'";'."\n");
		fwrite($fp,'$ret_delivery["deliverymethod_location_required"] 	= "'. $row_del['deliverymethod_location_required'].'";'."\n");
	}
	
	
	$tot_cnt =  0;
	$tot_paymethod_cnt  = $paymethod_without_google_cnt = 0;
	
	// Get all the payment types set for the site
	$sql_paytypes = "SELECT a.paytype_id, a.paytype_name, a.paytype_code, a.paytype_order, a.paytype_showinvoucher, a.paytype_logintouse, a.paytype_showinpayoncredit,
							b.paytype_caption 
								FROM 
									payment_types a,payment_types_forsites b 
								WHERE 
									b.sites_site_id = $site_id 
									AND a.paytype_id = b.	paytype_id 
									AND b.paytype_forsites_active=1 
									AND b.paytype_forsites_userdisabled=0";
	$ret_paytypes = $db->query($sql_paytypes);
	if ($db->num_rows($ret_paytypes))
	{
		$tot_cnt = $without_google_cnt = 0;
		while ($row_paytypes = $db->fetch_array($ret_paytypes))
		{
			$tot_cnt++;
			// Payment type Array based on paytype id
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_id"]	= "'. $row_paytypes['paytype_id'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_name"]	 = "'. $row_paytypes['paytype_caption'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_code"]	= "'. $row_paytypes['paytype_code'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_order"]	 = "'. $row_paytypes['paytype_order'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_showinvoucher"] = "'. $row_paytypes['paytype_showinvoucher'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_logintouse"] = "'. $row_paytypes['paytype_logintouse'].'";'."\n");
			fwrite($fp,'$ret_paytypeId["'.$row_paytypes['paytype_id'].'"] ["paytype_showinpayoncredit"] = "'. $row_paytypes['paytype_showinpayoncredit'].'";'."\n");
			
			//  Payment type Array based on paytype code
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_id"]	 = "'. $row_paytypes['paytype_id'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_name"] = "'. $row_paytypes['paytype_caption'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_code"]	 = "'. $row_paytypes['paytype_code'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_order"] = "'. $row_paytypes['paytype_order'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_showinvoucher"] = "'. $row_paytypes['paytype_showinvoucher'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_logintouse"] = "'. $row_paytypes['paytype_logintouse'].'";'."\n");
			fwrite($fp,'$ret_paytypeCode["'.$row_paytypes['paytype_code'].'"] ["paytype_showinpayoncredit"] = "'. $row_paytypes['paytype_showinpayoncredit'].'";'."\n");
			
		}
	}
		fwrite($fp,'$total_paytype_cnts	 = "'. $tot_cnt.'";'."\n");							
	// Get all the payment methods set for the current site
	$sql_paymethod = "SELECT a.paymethod_id, a.paymethod_name, a.paymethod_key, a.paymethod_takecarddetails, a.paymethod_ssl_imagelink, a.paymethod_showinvoucher,
											a.paymethod_secured_req, a.paymethod_showinpayoncredit,b.payment_method_google_recommended,b.payment_method_preview_req,
											b.payment_method_sites_caption   
								FROM 
									payment_methods a, payment_methods_forsites b 
								WHERE 
									b.sites_site_id = $site_id 
									AND a.paymethod_id = b.	payment_methods_paymethod_id 
									AND b.payment_method_sites_active = 1 
									AND payment_hide = 0 ";
	$ret_paymethod = $db->query($sql_paymethod);
	if ($db->num_rows($ret_paymethod))
	{
		while ($row_paymethod = $db->fetch_array($ret_paymethod))
		{
			$tot_paymethod_cnt++;
			if ($row_paymethod['paymethod_key'] != 'GOOGLE_CHECKOUT')
				$paymethod_without_google_cnt++;
			// Payment method Array based on paymethod_id id
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_id"] = "'. $row_paymethod['paymethod_id'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_name"] = "'. $row_paymethod['payment_method_sites_caption'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_key"] = "'. $row_paymethod['paymethod_key'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_takecarddetails"] = "'. $row_paymethod['paymethod_takecarddetails'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_ssl_imagelink"] = "'. $row_paymethod['paymethod_ssl_imagelink'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_showinvoucher"] = "'. $row_paymethod['paymethod_showinvoucher'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_secured_req"] = "'. $row_paymethod['paymethod_secured_req'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["paymethod_showinpayoncredit"] = "'. $row_paymethod['paymethod_showinpayoncredit'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["payment_method_preview_req"] = "'. $row_paymethod['payment_method_preview_req'].'";'."\n");
			fwrite($fp,'$ret_paymethodId["'.$row_paymethod['paymethod_id'].'"] ["payment_method_google_recommended"] = "'. $row_paymethod['payment_method_google_recommended'].'";'."\n");
			
			// Payment method Array based on paymethod_key id
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_id"] = "'. $row_paymethod['paymethod_id'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_name"] = "'. $row_paymethod['payment_method_sites_caption'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_key"] = "'. $row_paymethod['paymethod_key'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_takecarddetails"]	 = "'. $row_paymethod['paymethod_takecarddetails'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_ssl_imagelink"] = "'. $row_paymethod['paymethod_ssl_imagelink'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_showinvoucher"]	 = "'. $row_paymethod['paymethod_showinvoucher'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_secured_req"] = "'. $row_paymethod['paymethod_secured_req'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["paymethod_showinpayoncredit"] = "'. $row_paymethod['paymethod_showinpayoncredit'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["payment_method_preview_req"] = "'. $row_paymethod['payment_method_preview_req'].'";'."\n");
			fwrite($fp,'$ret_paymethodKey["'.$row_paymethod['paymethod_key'].'"] ["payment_method_google_recommended"] = "'. $row_paymethod['payment_method_google_recommended'].'";'."\n");
		}
	}
	fwrite($fp,'$total_paymethods_cnt = "'. $tot_paymethod_cnt.'";'."\n");			
	fwrite($fp,'$total_paymethods_without_google_cnt	= "'. $paymethod_without_google_cnt.'";'."\n");								
	
								
	fwrite($fp,'?>');		
	fclose($fp);	
}
?>
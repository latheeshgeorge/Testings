<?
	function url_inline($l)
	{
		echo $l;
	}
// url_static_page - Ouputs the URL of a static page of specified ID.
	function url_static_page($pageId,$pageName,$ret=-1)
	{
		global $db, $ecom_siteid, $ecom_hostname,$ecom_advancedseo;
		
		if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}
		
		$pgname 						= strip_url($pageName);
		if($ecom_advancedseo=='Y')
		{
			//$staticPageUrlHash[$page_id] 	= "http://".$ecom_hostname."/pg$page_id-".$pgname.".html";
			$staticPageUrlHash 	= $http.$ecom_hostname."/".$pgname."-pg$pageId.html";
		}
		else
		{
			$staticPageUrlHash	= $http.$ecom_hostname."/pg$pageId/".$pgname.".html";
		}	
		if($ret==-1)
			url_inline($staticPageUrlHash);
		else
		{
			$ret_arr[0] = $pageName;
			$ret_arr[1] = $staticPageUrlHash;
			return $ret_arr;
		}	
	}
	//To build shelf URL
	function url_shelf_all($shelfId,$sName,$ret=-1)
	{
		global $ecom_siteid, $ecom_hostname;
		
		if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}
		
		$sName 					= strip_url($sName);// Stripping unwanted characters from the combo name
		$shelfUrl				= $http.$ecom_hostname."/".$sName."-ps".$shelfId.".html";	
			
		if($ret==2) // Case of returning an array of required values instead of printing the required link
		{
			$ret_arr[0] = $sName;
			$ret_arr[1] = $shelfUrl;
			return $ret_arr;
		}
		elseif ($ret==-1)// default case. Print the required url
		{
			url_inline($shelfUrl);
		}
		else // if return the required combo url instead of printing
			return $shelfUrl;	
	}
	// url_category - returns the URL for a category.
	function url_category($catId,$cName,$ret=-1) {
		global $categoryUrlHash, $db, $ecom_siteid, $ecom_hostname,$ecom_advancedseo;
		
		if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}
		
		$cName 	= strip_url($cName);// Stripping unwanted characters from the category name
		if($ecom_advancedseo=='Y')
		{
			$categoryUrlHash[$catId] = $http.$ecom_hostname."/".$cName."-c$catId.html";
		}
		else
		{
			$categoryUrlHash[$catId] = $http.$ecom_hostname."/c$catId/".$cName.".html";
		}	
		
		if($ret==2) // Case of returning an array of required values instead of printing the required link
		{
			$ret_arr[0] = $cName;
			$ret_arr[1] = $categoryUrlHash[$catId];
			return $ret_arr;
		}
		elseif ($ret==-1)// default case. Print the required url
		{
			url_inline($categoryUrlHash[$catId]);
		}
		else // if return the required category url instead of printing
			return $categoryUrlHash[$catId];	
	}
	// url_shop - returns the URL for a shop.
	function url_shops($shopId,$sName,$ret=-1) {
		global $ecom_siteid, $ecom_hostname,$ecom_advancedseo;
		
		if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}
		
		$sName 								= strip_url($sName);// Stripping unwanted characters from the category name
		//if ($mainshop_id==0)
		if($ecom_advancedseo=='Y')
		{
			$shopUrlHash 	= $http.$ecom_hostname."/".$sName."-sh$shopId.html";	
		}
		else
		{
			$shopUrlHash 	= $http.$ecom_hostname."/sh$shopId/".$sName.".html";
		}	
		//else
		//	$shopUrlHash[$group_id][$shopId] 	= "http://".$ecom_hostname."/shp$shopId$group_id-$mainshop_id/".$sName.".html";	
		if($ret==2) // Case of returning an array of required values instead of printing the required link
		{
			$ret_arr[0] = $sName;
			$ret_arr[1] = $shopUrlHash;
			return $ret_arr;
		}
		elseif ($ret==-1)// default case. Print the required url
		{
			url_inline($shopUrlHash);
		}
		else // if return the required category url instead of printing
			return $shopUrlHash;	
	}
	// url_combo - returns the URL for a combo deal.
	function url_combo($comboId,$cName,$ret=-1)
	{
		global $ecom_siteid, $ecom_hostname;
		
		if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}
		
		$cName 			= strip_url($cName);// Stripping unwanted characters from the combo name
		$comboUrlHash = $http.$ecom_hostname."/".$cName."-bundle$comboId.html";	
			
		if($ret==2) // Case of returning an array of required values instead of printing the required link
		{
			$ret_arr[0] = $cName;
			$ret_arr[1] = $comboUrlHash;
			return $ret_arr;
		}
		elseif ($ret==-1)// default case. Print the required url
		{
			url_inline($comboUrlHash);
		}
		else // if return the required combo url instead of printing
			return $comboUrlHash;	
	}
	?>

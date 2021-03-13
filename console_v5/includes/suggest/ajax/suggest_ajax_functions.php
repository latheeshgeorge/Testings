<?php

    // ###############################################################################################################
	//  Function which holds the display logic of states to be shown when called using ajax;				
	// ###############################################################################################################
	function show_display_feature_list($service_id,$feature_id=0)
	{
		global $db,$ecom_siteid,$ecom_levelid ;
		
		if($service_id)
		{
		$services_arr		= array();
		$exist_feature_site = array(0);
		$level_arr		 	= array(0);
		
		$sql_cons = "SELECT features_feature_id FROM console_levels_details WHERE console_levels_level_id=$ecom_levelid";
		$ret_cons = $db->query($sql_cons);
		if ($db->num_rows($ret_cons))
		{
			while ($row_cons = $db->fetch_array($ret_cons))
			{
				$level_arr[] = $row_cons['features_feature_id'];
			}
		}
		$level_str = implode(",",$level_arr);
		
			$sql_feature = "SELECT b.feature_id,b.feature_name FROM mod_menu a,features b WHERE 
			sites_site_id=$ecom_siteid AND b.feature_hide = 0 AND a.features_feature_id=b.feature_id 
			 AND b.feature_displaytouser = 1 and a.features_feature_id IN ($level_str) and b.services_service_id='".$service_id."' 
			 ORDER BY feature_ordering";					 
			 //"SELECT feature_id,feature_name FROM features WHERE services_service_id=".$service_id.""; 
								 
								
		    $ret_feature = $db->query($sql_feature);	
	?>
			
				&nbsp;&nbsp;<select class="input" name="feature" >
				<option value="0">-select-</option>
				<?
				if ($db->num_rows($ret_feature))
				{
					while($row_feature=$db->fetch_array($ret_feature))
					{
					?>
					<option value="<?=$row_feature['feature_id']?>" <? if($row_feature['feature_id']==$feature) echo "selected";?>><?=$row_feature['feature_name']?></option>
					<?
					}
				} 
				?>
				</select>
				
<?
		}
	}
?>
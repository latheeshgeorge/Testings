<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the combo to be shown when called using ajax;
	// ###############################################################################################################
	function show_news_details_list($news_id)
	{
		global $db,$ecom_siteid ;
		$sql_news = "SELECT 
							news_text
    		 		FROM 
			   				console_news 
					WHERE 
							news_id=".$news_id."";
	    $ret_news = $db->query($sql_news);
		$row_news =$db->fetch_array($ret_news)    ; 
		 ?>
		 <table width="100%" cellpadding="0" cellspacing="1" border="0">
			  <tr>
			  <td align="left" valign="middle" class="<?=$class_val?>"><?=$row_news['news_text']?>
			  </td>
			  </tr>
			  </table>
			  		 <?
		
	}

    
?>	

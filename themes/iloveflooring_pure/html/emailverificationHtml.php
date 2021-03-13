<?php
/*############################################################################
	# Script Name 	: callbackHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: LG
	# Created on	: 16-Oct-2013
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class emailverification_Html
	{
		function Show_form($cust_id,$alert='')
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
			$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		   $HTML_treemenu .=
		   '<div class="tree_menu_conA">
		   <div class="tree_menu_topA"></div>
		   <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		 echo $HTML_treemenu;
		  ?>
		  <?php if($alert){ ?>
				<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">
				<?php 
				  if($Captions_arr['CUST_REG'][$alert]){
						echo "Error !! ". stripslash_normal($Captions_arr['CUST_REG'][$alert]);
				  }else{
						echo  $alert;
				  }
				 
				?>			</div>
				<div class="cart_msg_bottomA"></div>
				</div>
		<?php 
		}
		else
		{
		?>
		   <div class="inner_contnt">
           <div class="inner_contnt_top"></div>
		   <div class="inner_contnt_middle">
			
			<?php
			if($cust_id)
			{
				$sql_cust = "SELECT 
									customer_email_7503 
							 FROM 
									customers
							 WHERE 
							 customer_id = ".$cust_id." AND sites_site_id=$ecom_siteid";
				$ret_cust = $db->query($sql_cust);
				$row_cust = $db->fetch_array($ret_cust);			 
		    }
			?>
			<form action="<?php echo url_link('emailverify.html')?>" method="post" >
			<input type="hidden" name="action" value="verify">
			<input type="hidden" name="cust_id" value="<?php echo $_REQUEST['cust_id']?>">
			
			<table cellspacing="0" cellpadding="0" border="0" class="statictable">
			<tr>
			  <td valign="top" colspan="2">
			  <?php
			  		 echo stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION_SHOWFORM']);

			  ?>
			   </td>			  
			</tr>
			<tr>
			<td colspan="2">
			<table border="0" width="100%" cellpadding="2" cellspacing="2">
			
			<tr>
			<td align="left">Email</td>
			<td> :&nbsp;<?php echo $row_cust['customer_email_7503'] ?></td>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			  <td valign="top" align="center" >
			       <input type="submit" class="inner_btn_red_x" value="Verify Email">
			   </td>			  
			</tr>
		  </tbody></table>
		  </form>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>
		  <?php
		   }
        }
		// Defining function to show the Call Back
		function Show_sent()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
			$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		   $HTML_treemenu .=
		   '<div class="tree_menu_conA">
		   <div class="tree_menu_topA"></div>
		   <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		 echo $HTML_treemenu;
		 ?>
		 <div class="inner_contnt">
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<table cellspacing="0" cellpadding="0" border="0" class="statictable">
			<tr>
			  <td valign="top">
				   <div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">
			  <?php
			  		 echo stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION_SENTMESS']);

			  ?>
			  </div>
				<div class="cart_msg_bottomA"></div>
				</div>
			   </td>			  
			</tr>
			
		  </tbody></table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>
		 <?php				
		}
		// Defining function to show the Call Back
		function Show_success()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
			$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		   $HTML_treemenu .=
		   '<div class="tree_menu_conA">
		   <div class="tree_menu_topA"></div>
		   <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		 echo $HTML_treemenu;
		 ?>
		 <?php if($alert){ ?>
				<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">
				<?php 
				  if($Captions_arr['CUST_REG'][$alert]){
						echo "Error !! ". stripslash_normal($Captions_arr['CUST_REG'][$alert]);
				  }else{
						echo  $alert;
				  }
				 
				?>			</div>
				<div class="cart_msg_bottomA"></div>
				</div>
		<?php 
		}
		
		?>
		 <div class="inner_contnt">
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<table cellspacing="0" cellpadding="0" border="0" class="statictable">
			<?php
			if(!$alert){
				?>
			<tr>
			  <td valign="top">
				  <div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">
			  <?php
			  		 echo stripslash_normal($Captions_arr['CUST_REG']['EMAIL_VERIFICATION_SUCCESS']);

			  ?>
			  </div>
				<div class="cart_msg_bottomA"></div>
				</div>
			   </td>			  
			</tr>
			<?php
		}
			?>
			<tr>
			  <td valign="top" align="center" >
			       <a href="<?php echo url_link('custlogin.html')?>"><input type="button" class="inner_btn_red_x" value="Contine To Login"></a>
			   </td>			  
			</tr>
		  </tbody></table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div>
		 <?php
		 				
		}
	};	
?>

<?php
/*############################################################################
	# Script Name 	: mydownloadsHtml.php
	# Description 		: Page which holds the display logic for listing my downloads
	# Coded by 		: Sny
	# Created on		: 28-Aug-2008
	# Modified by		: Sny
	# Modified On		: 29-Aug-2008
	##########################################################################*/
	class mydownloads_Html
	{
		// Defining function to show the My favorites
		function Show_Mydownloads($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}

			
			$customer_id 									= get_session_var("ecom_login_customer");
			$Captions_arr['MY_DOWNLOADS'] 	= getCaptions('MY_DOWNLOADS'); // to get values for the captions from the general settings site captions
			$sql_tot_download			 				= "SELECT count(ord_down_id) 
																		FROM 
																				order_product_downloadable_products  
																		WHERE
																				sites_site_id = $ecom_siteid 
																				AND customers_customer_id = $customer_id";
			$ret_tot_download 					= $db->query($sql_tot_download);
			list($tot_cntdownload) 			= $db->fetch_array($ret_tot_download); 
			$downloadperpage					=	10;
			$pg_variabledownload				= 'download_pg';
			$start_vardownload 				= prepare_paging($_REQUEST[$pg_variabledownload],$downloadperpage,$tot_cntdownload);
			$Limitdownload						= " LIMIT ".$start_vardownload['startrec'].", ".$downloadperpage;
				$sql_download						= "SELECT a.ord_down_id,b.proddown_title,a.proddown_limited,a.proddown_limit,a.proddown_days_active,a.order_details_orderdet_id,
																					a.product_downloadable_products_proddown_id,a.ord_down_id,c.order_id,c.order_paystatus, 
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_start,'%d-%m-%Y') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_start,'%d-%m-%Y') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_startdate,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid' 
																								THEN date_format(a.proddown_days_active_start,'%H:%i:%S') 
																							WHEN 'free' 
																								THEN date_format(a.proddown_days_active_start,'%H:%i:%S') 
																							ELSE 
																								'--'
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_starttime,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_end,'%d-%m-%Y') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_end,'%d-%m-%Y') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_enddate,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_end,'%H:%i:%S') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_end,'%H:%i:%S') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_endtime,
																					case (a.proddown_disabled)
																					WHEN 1
																						THEN 'Y' 
																					WHEN 0 
																						THEN 'N' 
																					END as disabled 
																		FROM
																			order_product_downloadable_products a, product_downloadable_products b ,orders c
																		WHERE
																			a.sites_site_id = $ecom_siteid  
																			AND a.customers_customer_id = $customer_id 
																			AND a.product_downloadable_products_proddown_id = b.proddown_id 
																			AND a.orders_order_id = c.order_id 
																		ORDER BY
																			c.order_date 
																		$Limitdownload";																													
				$ret_download = $db->query($sql_download);
				
				// Decide the error message to be displayed (if any)
				switch($_REQUEST['ern'])
				{
					case 1:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_LOGIN']);
					break;
					case 2:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_INV_CUST_ERR']);
					break;
					case 3:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_PAY_NOT_ERR']);
					break;
					case 4:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_CANCEL_ERR']);
					break;
					case 5:
						$alert 	= stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_INV_INPUT_ERR']);
					break;
					case 6:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DISABLED_ERR']);
					break;
					case 7:
						$alert = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NO_AUTH']);
					break;
					default:
						$alert = '';
				};
				  $HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HEADER']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
			if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							
											$HTML_alert .=  $alert;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
?>
<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['MY_DOWNLOADS']['DOWN_HEADER']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HEADER'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">
		<table width="100%" border="0" cellpadding="3" cellspacing="0"  class="reg_table" >
	
		<tr>
		<td colspan="3">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<?php
				if($alert!='')
				{
			?>
					<tr>
					<td  colspan="8" align="center" valign="middle" class="userorderheader"><?php echo $alert?></td>
					</tr>
			<?php
				}
			?>
			<tr>
			<td  colspan="8" align="center" valign="middle" class="pagingcontainertd"><?php
			if($db->num_rows($ret_download))
			{
				$path = url_link('mydownloads.html',1);
				$query_string='';
				paging_footer($path,$query_string,$tot_cntdownload,$start_vardownload['pg'],$start_vardownload['pages'],'',$pg_variabledownload,stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DOWNLOAD']),$pageclass_arr); 
			}	
		?>			</td>
			</tr>
				<tr>
					<td align="left" width="3%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SLNO'])?></td>
					<td align="left" width="10%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_ORDID'])?></td>
					<td align="left" width="24%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_TITLE'])?></td>
					<td align="center" width="14%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_LIMIT'])?></td>
					<td align="left" width="14%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_START'])?></td>
					<td align="left" width="23%" class="ordertableheader">
					<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_END'])?></td>
					<td align="left" width="5%" class="ordertableheader">&nbsp;</td>
				    <td align="left" width="7%" class="ordertableheader">&nbsp;</td>
				</tr>
				<?php
				if($db->num_rows($ret_download)==0)
			{ ?>
			<tr>
					<td colspan="8" align="center" class="userorderheader">&nbsp;<?=stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NOT_FOUND'])?></td>
			  </tr>
			<?PHP } else {
				$i=1;
				while ($row_query = $db->fetch_array($ret_download))
				{
					// Find the id of product linked with current downloadable product
					$sql_prod = "SELECT products_product_id 
											FROM 
												order_details 
											WHERE 
												orderdet_id = ".$row_query['order_details_orderdet_id']." 
												AND orders_order_id = ".$row_query['order_id']."  
											LIMIT 
												1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
					}
					$force_hide_download_link	= false;
					// Check whether any download history exists for current downlodable item
					$sql_history = "SELECT DATE_FORMAT(track_date ,'%d-%m-%Y %h:%i:%s %r') as download_date 
												FROM 
													order_product_downloadable_products_customer_track 
												WHERE 
													order_product_downloadable_products_ord_down_id = ".$row_query['ord_down_id']." 
												ORDER BY 
													track_date DESC";
					$ret_history = $db->query($sql_history);
					$check_limited 		= $row_query['proddown_limited'];
					$check_daysactive	= $row_query['proddown_days_active'];
					if ($row_query['proddown_limited']==1) // case if download limit is set
					{
						if ($row_query['proddown_limit']<=$db->num_rows($ret_history)) // case if download limit reached
						{
							$force_hide_download_link = true;
						}	
					}
					if ($row_query['proddown_days_active']==1) // case if download period is set
					{
							if($row_query['active_startdate']!='--' and $row_query['active_enddate']!='--' )
							{
									$sp_date_arr = explode('-',$row_query['active_startdate']);
									$sp_time_arr	= explode('-',$row_query['active_starttime']);
									$st_mktime	= mktime($sp_time_arr[0],$sp_time_arr[1],$sp_time_arr[2],$sp_date_arr[1],$sp_date_arr[0],$sp_date_arr[2]);
									
									$sp_date_arr = explode('-',$row_query['active_enddate']);
									$sp_time_arr	= explode('-',$row_query['active_endtime']);
									$en_mktime	= mktime($sp_time_arr[0],$sp_time_arr[1],$sp_time_arr[2],$sp_date_arr[1],$sp_date_arr[0],$sp_date_arr[2]);

									$now_mktime  = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
									if ($now_mktime>$en_mktime or $now_mktime<$st_mktime)
										$force_hide_download_link = true;
							}			
							elseif($row_query['active_startdate']=='--' or $row_query['active_enddate']=='--' )
							{
								$force_hide_download_link = true;
							}
					}
					if ($row_query['disabled']=='Y')
						$force_hide_download_link = true;
				?>
					<tr class="edithreflink_tronmouse" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DOWNLOAD'])?>" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
						<td align="left" valign="middle" class="favcontent"><?php echo $i++;?></td>
						<td width="10%" align="left" valign="middle" class="favcontent">
						<a href="index.php?req=orders&reqtype=order_det&order_id=<?php echo $row_query['order_id']?>" class="favoriteprodlink" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_VIEW_ORD'])?>"><?php echo $row_query['order_id']?></a></td>
						<td align="left" valign="middle" class="favcontent"><?php echo  stripslash_normal($row_query['proddown_title'])?></a></td>
						<td align="center" valign="middle" class="favcontent">
						<?php 
							if($row_query['proddown_limited']==1)
							{
								echo $row_query['proddown_limit'];
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}
						?>						</td>
						<td align="center" valign="middle" class="favcontent">
						<?php
							if ($row_query['proddown_days_active']==1)
							{
								if($row_query['active_startdate']!='--')
								{
										echo $row_query['active_startdate'].' '.$row_query['active_starttime'];
								}
								else
								{
									echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SET_PAY_SUCC']);
								}	
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}	
						?>						</td>
						<td width="23%" align="center" valign="middle" class="favcontent">
					  <?php
							if ($row_query['proddown_days_active']==1)
							{
								if($row_query['active_enddate']!='--')
								{	
									echo $row_query['active_enddate'].' '.$row_query['active_endtime'];
								}
								else
								{
									echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SET_PAY_SUCC']);
								}	
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}							
						?>						</td>
						<td width="5%" align="center" valign="middle" class="favcontent">
						<?php
							if(($row_query['order_paystatus']=='Paid' or $row_query['order_paystatus']=='free') and $force_hide_download_link==false)
							{
								$dld =$row_query['product_downloadable_products_proddown_id'].'~'.$row_query['order_id'].'~'.$row_query['ord_down_id'];
								$dld =  urlencode(base64_encode($dld));
						?>
								<a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/download_product.php?dld=<?php echo $dld?>" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_CLICK_DOWN']);?>"><img src="<?php url_site_image('download.gif')?>" alt="Click to Download" border="0" /></a>
						<?php
							}
							else
							{
								
								if($row_query['order_paystatus']=='Paid' or $row_query['order_paystatus']=='free')
								{
									if ($row_query['disabled']=='Y')
										$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DISABLE_ADM']);
									else
										$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_EXP']);
								}		
								else
								{
									$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_PAY_NOT']);
								}	
						?>
								<img src="<?php url_site_image('download_disabled.gif')?>" alt="<?php echo $hint?>" title="<?php echo $hint?>" border="0" />
						<?php	
							}
						?>						</td>
					    <td width="7%" align="center" valign="middle" class="favcontent">
						<?php 
						if ($db->num_rows($ret_history))
						{
						?>
							<a href="javascript:handle_downloadhistory('<?php echo $row_query['ord_down_id']?>')" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>">
							<img src="<?php url_site_image('download_viewhistory_enabled.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>" border="0" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>" /></a>
						<?php
						}
						else
						{
						?>
							<img src="<?php url_site_image('download_viewhistory_disabled.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NO_HIST']);?>" border="0" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NO_HIST']);?>"/>
						<?php
						}						
						?>						</td>
					</tr>
				<?php
					// Building the customer download history
					if ($db->num_rows($ret_history))
					{
				?>
						<tr id="downloadhistory_tr_<?php echo $row_query['ord_down_id']?>" style="display:none">
							<td colspan="4" align="left">&nbsp;</td>
							<td colspan="4" align="center">
								<table width="100%" cellpadding="3" cellspacing="0" border="0">
								<tr>
									<td align="center" width="5%" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HASH'])?></td>
									<td align="left" width="95%" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DOWN_ON'])?></td>
								</tr>
								<?php
								$cnt = 1;
								while ($row_history = $db->fetch_array($ret_history))
								{
								?>
									<tr>
										<td align="center" width="7%" class="favcontent"><?php echo $cnt++?>.</td>
										<td align="left" width="93%" class="favcontent"><?php echo $row_history['download_date']?></td>
									</tr>
								<?php
								}
								?>
								<tr>
									<td align="right" colspan="2" class="favcontent"><strong><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_TOT_DOWN'])?> <?php echo ($cnt-1)?></strong></td>
								</tr>
								</table>								</td>
						</tr>
				<?php
					}
					
				}
			}	
				?>
		  </table>
		</td>
		</tr>
	</table>
	</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr;
		
		if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
		
		?>
				<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
				<tr>
					<td width="7%" align="left" valign="middle" class="message_header" > 
					<?php
					echo $mesgHeader;
					?>
					</td>			
				</tr>
				<tr>
					<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
				</tr>
				</table>
		<?php	
		}
	};	
?>
	<?php 
?>
			

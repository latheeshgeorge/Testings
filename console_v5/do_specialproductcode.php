<?php
/*#################################################################
	# Script Name 		: do_specialproductcocde.php
	# Description 		: Feature to download and upload csv files to update special product code in bulk as requested by garraways
	# Coded by 			: Sony
	# Created on		: 22-Apr-2013
	# Modified by 		: Sony
	# Modified on		: 22-Apr-2013
	#################################################################*/
	set_time_limit(0);
	if ($_REQUEST['cur_mod']=='')
	{
		echo '<script type="text/javascript">alert("Invalid Parameter");</script>';
		exit;
	}
	include_once ("functions/functions.php");
	include ('session.php');
	require_once ("sites.php");
	require_once ("config.php");

	$headers 	= array();
	$data 		= array();
	$exists 	= false;
	if (!$_SESSION['console_id']) // checking to see if the user is logged in and is the user of current site
	{
		echo '<script type="text/javascript">alert("User not logged in. Please login..");</script>';
		exit;
	}
	else
	{
		// Check whether current user exists in current site
		$sql_site = "SELECT user_id 
						FROM 
							sites_users_7584 
						WHERE 
							sites_site_id IN(0,$ecom_siteid ) 
							AND user_id = '".$_SESSION['console_id']."' 
						LIMIT 
							1";
		$ret_site = $db->query($sql_site);
		if ($db->num_rows($ret_site)==0)
		{
			echo '<script type="text/javascript">alert("Sorry!! Invalid User");</script>';
			exit;
		}
	}
		// Array to be used for advanced offline feature
		$var_prodcombstockpriceimage_arr= array	(
													'product_name'								=> 'Product Name (Don\'t Modify)',
													'comb_name'									=> 'Variable Combination (Don\'t Modify)',
													'product_specialcode'						=> 'Special Product Code',
													'product_id'								=> 'Product Id (Don\'t Modify)',
													'comb_id'									=> 'Combination Id (Don\'t Modify)'
												);
	
	if($_REQUEST['cur_mod']=='do_download') // case of download
	{
		$filename = $ecom_hostname.'Product_Specialcode_'.date('d-m-Y');		
		
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=$filename.csv");

		
		$head_arr = $var_prodcombstockpriceimage_arr;	
		array_walk($head_arr, "add_quotes");
		print implode(",", $head_arr) . "\r\n";

		$row_count = 0;
					
		$sel_cats	= $_REQUEST['sel_category_id'];
		
		// Get the list of ids of product under selected categories
		$prodids_arr = array(-1);
		$sql_prodids = "SELECT product_id FROM products WHERE sites_site_id = $ecom_siteid" ;
		$ret_prodids = $db->query($sql_prodids);
		if($db->num_rows($ret_prodids))
		{
			$row_count = 1;	
			while ($row_prodids = $db->fetch_array($ret_prodids))
			{
				$prodids_arr[] 				= $row_prodids['product_id'];
				$cur_prodid 				= $row_prodids['product_id'];
				$data 						= array();
				$sql_var_val_exists_count 	= "SELECT COUNT(*) FROM product_variables WHERE var_value_exists=1 AND products_product_id=$cur_prodid";
				$ret_var_val_exists_count 	= $db->query($sql_var_val_exists_count);
				$row_var_val_exists_count 	= $db->fetch_array($ret_var_val_exists_count);
				$var_val_exists_count     	= $row_var_val_exists_count[0];
				
				$additional_condition 		= '';
				// get the main product details from products
				$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
									product_variablecomboprice_allowed, product_variablecombocommon_image_allowed, product_bulkdiscount_allowed,
									product_discount,product_discount_enteredasval,product_special_product_code  
								FROM 
									products
								WHERE 
									sites_site_id=$ecom_siteid 
									AND product_id = $cur_prodid 
								LIMIT 
									1";	
				$ret_stock = $db->query($sql_stock);				
				if($db->num_rows($ret_stock))
				{
					$row_stock = $db->fetch_array($ret_stock);
					
					//---------------------------- Printing First Row for Product -- starts -------------------------------------
					$data['product_name'] 		= stripslashes($row_stock['product_name']);
					$data['comb_name'] 			= '';
					$data['comb_id'] 			= '';	
					$data['product_id'] 		= 'P-'.$cur_prodid;
					// check that  all check boxes are unticked
					if($var_val_exists_count == 0) 
					{
						
						//check exists any combinations.. (only barcode exists condition).. if all checkbox unticked, but any of comb already exists in db	--starts
						$comb_exists_indb = 0;
						$sql_comb = "SELECT comb_id 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = '".$cur_prodid."'  
									ORDER BY 
										comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								$sql_combdet = "SELECT product_variables_var_id
													FROM 
														product_variable_combination_stock_details 
													WHERE 
														comb_id=".$row_comb['comb_id']." 
														AND products_product_id='".$cur_prodid."'";
								$ret_combdet = $db->query($sql_combdet);
								if($db->num_rows($ret_combdet) == $var_val_exists_count)
								{
									$comb_exists_indb ++;
								}
							}
						}
							
						if($comb_exists_indb !=0)
						{
							$data['product_specialcode'] 	= 'N/A';
						}
						else
						{
							$data['product_specialcode'] 	= $row_stock['product_special_product_code'];
						}
							
						//check exists any combinations.. (only barcode exists condition).. if all checkbox unticked, but any of comb already exists in db	--ends				
					
					}
					else // if any of the check box are ticked
					{
						$data['product_specialcode'] 	= 'N/A';
					}
					
					
				
					$print_data 	= array();
					$print_data		= array	(
												'product_name'			=> $data['product_name'],
												'comb_name'				=> $data['comb_name'],
												'product_specialcode'	=> $data['product_specialcode'],
												'product_id'			=> $data['product_id'],
												'comb_id'				=> $data['comb_id']
												);
					
					
					array_walk($print_data, "add_quotes");
					print implode(",", $print_data) ."\r\n";
					
					$row_count++;
					//---------------------------- Printing First Row for Product -- ends -------------------------------------
					$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned,comb_special_product_code  
								FROM 
									product_variable_combination_stock 
								WHERE 
									products_product_id = '".$cur_prodid."'  
								ORDER BY 
									comb_id";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb))
					{
						$prev_id = 0;
						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$data					= array();
							$comb_name_arr 			= array();
							$comb_id_arr 			= array();
							// Get the combination details
							$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
												FROM 
													product_variable_combination_stock_details 
												WHERE 
													comb_id=".$row_comb['comb_id']." 
													AND products_product_id='".$cur_prodid."'";
							$ret_combdet = $db->query($sql_combdet);
							//echo "<br><br>".$db->num_rows($ret_combdet)." =  ".$var_val_exists_count."<br><br>";
							if($db->num_rows($ret_combdet) == $var_val_exists_count)
							{
								$pname 		=  '    "';
								$data['product_name'] 		= $pname;
								if($db->num_rows($ret_combdet))
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										// Get the respective variable name
										$sql_varname = "SELECT var_name,var_id 
															FROM 
																product_variables 
															WHERE 
																var_id = '".$row_combdet['product_variables_var_id']."'  
																AND var_hide = 0  
															LIMIT 
																1";
										$ret_varname = $db->query($sql_varname);
										if($db->num_rows($ret_varname))
										{
											$row_varname = $db->fetch_array($ret_varname);											
										 
										// Get the variable value details
										$sql_varvalname = "SELECT var_value,var_value_id 
															FROM 
																product_variable_data 
															WHERE 
																product_variables_var_id = '".$row_varname['var_id']."' 
																AND var_value_id = '".$row_combdet['product_variable_data_var_value_id']."'  
															LIMIT 
																1";
										$ret_varvalname = $db->query($sql_varvalname);
										if($db->num_rows($ret_varvalname))
										{
											$row_varvalname = $db->fetch_array($ret_varvalname);											
										 
										$comb_name_arr[] = stripslashes($row_varname['var_name']).': '.stripslashes($row_varvalname['var_value']);
										$comb_id_arr[] = $row_varname['var_id'].'=>'.$row_varvalname['var_value_id'];
										}
										}
									}
								}
									
								$data['comb_name']				= implode(',',$comb_name_arr);
								$data['product_specialcode'] 	= $row_comb['comb_special_product_code'];
								$data['product_id'] 			= 'P-'.$cur_prodid;
								$data['comb_id'] 				= 'C-'.$row_comb['comb_id'];
								
								
								
								$print_data = array();
								$print_data		= array	(
															'product_name'			=> $data['product_name'],
															'comb_name'				=> $data['comb_name'],
															'product_specialcode'	=> $data['product_specialcode'],
															'product_id'			=> $data['product_id'],
															'comb_id'				=> $data['comb_id']
														);
								array_walk($print_data, "add_quotes");
								print implode(",", $print_data) ."\r\n";
								$row_count++;
							}
						}
					}
				}
		}
		}
	}
	elseif($_REQUEST['cur_mod']=='specialcode_upload') // case of upload
	{
		
		$table 		= '';
		$css		= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
		$table 		.=	$css;
		$go_back 	='<br /><br /><center><a class="smalllink" href="home.php?request=products&fpurpose=manage_specialprodcode" onclick="show_processing()"><strong>Click here to go back</strong></a></center>';
		$table 		.=	$go_back;	
		$table 		.='<br/><br/><center><span class="redtext"><strong>Sorry.. Update operation Cancelled due to following errors</strong></span></center><br/>';
		$table 		.= "<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
		$table 		.="<tr>";
		$table 		.="<td width='5%' class='listingtableheader'>Row #</td>";
		$table 		.="<td width='15%' class='listingtableheader'>Product Name</td>";
		$table 		.="<td width='15%' class='listingtableheader'>Variable Combination</td>";
		$table 		.="<td width='5%' class='listingtableheader'>Special Code</td>";
		$table 		.="<td width='20%' class='listingtableheader'>Error Message</td>";
		$table 		.="</tr>";

		
		$head_arr 		= $var_prodcombstockpriceimage_arr;	
		$err_no 		= 0;
		$error_outer  	= '';
		$sucess 		= '';
		
		// checking upload file type		
		if(!$_FILES['upload_file']['name'])
		{
			$err_no = 1;
		}
		if (strtolower($_FILES['upload_file']['type'])!='text/csv' and strtolower($_FILES['upload_file']['type'])!='text/plain' and strtolower($_FILES['upload_file']['type'])!='application/vnd.ms-excel' and strtolower($_FILES['upload_file']['type'])!='application/octet-stream' and strtolower($_FILES['upload_file']['type'])!='text/comma-separated-values')
		{
			$err_no = 2;
		}
	
		if($err_no!=0)
		{
			$error_outer = '<span class="redtext">-- Please upload a CSV file --</span>';
			$err_cnt = 1;
		}
		else
		{
			$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
			$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
			// Get the first row
			$data 	= fgetcsv($fp,$fs1, ",");
			$line	= $done_cnt = $err_cnt =  0;
			if(count($data))
			{
				if(count($data)!=count($head_arr))
				{
					$error_outer = '<span class="redtext">-- Sorry!!. The header row in your file does not match with the headers in the exported file --</span>';
					$err_cnt = 1;
				}
				else
				{
					$i = 0;
					foreach ($head_arr as $k=>$v)
					{
						if($data[$i]!=$v)
						{
							$error_outer = '<span class="redtext">-- Sorry!!. The header row in your file does not match with the headers in the exported file --</span>';
							$err_cnt = 1;
							break;
						}	
						$i++;
					}
				}	
			}
			if($error_outer=='')
			{
				$error  		= '';
				$rowcnt			= 2;
				$error_count 	= 0;
				$line 			= 0;
				fclose($fp);
				$fp				= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 			= filesize($_FILES["upload_file"]["tmp_name"]);
				/*-----------------------  First loop for checking the errors in the csv file, if error occured any of the row , that will be stored and finally it will display in table format  --------------------------- starts--*/
				while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
				{
					
						$num 		= count($data);
						$cur_msg	= '';
						$error 		= '';
						if($line!=0)
						{
							$product_name	= trim($data[0]);
							$combination	= trim($data[1]);
							$specialcode	= trim($data[2]);
							$product_id		= trim($data[3]);
							$comb_id		= trim($data[4]);
							
							
							
							
						
							
							$comb_id_format 	= $comb_id;
							$product_id_format 	= $product_id;
													
							$product_arr		= explode('-',$product_id);
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								
								if(!is_numeric($product_arr[1]))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Invalid Product Id --('.$product_id.')';
								}
								else
								{
								$product_id = addslashes(trim($product_arr[1]));
								
								
								$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
															product_variablecomboprice_allowed, product_variablecombocommon_image_allowed,product_bulkdiscount_allowed   
													FROM 
														products
													WHERE 
														sites_site_id=$ecom_siteid 
														AND product_id = $product_id 
													LIMIT 
														1";	
									$ret_stock = $db->query($sql_stock);
									$var_stock_variables = 0;
									if($db->num_rows($ret_stock))
									{
										$row_stock = $db->fetch_array($ret_stock);
									}
									else
									{
										if ($error!='')
												$error .= '<br/>';
											$error .= '-- Product not found in website';
									}
									
									/*validate csv data row wise - starts*/
									if($error=='')
									{
										//-----------------------Validate Product Row - Starts-------------------
										if($comb_id == '')
										{
											
										}
										//-----------------------Validate Product Row - Ends-------------------
																
										//-----------------------Validate Combination Row - Starts-------------------
										if($comb_id != '')
										{
											$org_combid 	= $comb_id;
											$comb_arr		= explode('-',$comb_id);
											if ($comb_arr[0] !='C' or count($comb_arr) !=2)
											{
												if ($error!='')
													$error .= '<br/>';
												$error .= '-- Invalid Combination Id Format --('.$org_combid.')';
											}
											else
											{
												if(!is_numeric($comb_arr[1]))
												{
													if ($error!='')
														$error .= '<br/>';
													$error .= '-- Invalid Combination Id --('.$org_combid.')';
												}
												else
												{
													$comb_id = $comb_arr[1];
													// Check whether current combination id is valid
													$sql_comb_check = "SELECT comb_id 
																			FROM 
																				product_variable_combination_stock  
																			WHERE 
																				comb_id= ".$comb_id." 
																				AND products_product_id =".$product_id." 
																			LIMIT 
																				1";
													$ret_comb_check = $db->query($sql_comb_check);
													if($db->num_rows($ret_comb_check)==0)
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Combination not found in site --('.$org_combid.')';
													}
												}
											}
											
										
											if($error=='')
											{
											
											}
										}
										//-----------------------Validate Combination Row - Ends-------------------
								}
										
									/*validate csv data row wise - ends*/
								}
								
							}
							
							$cls = ($rowcnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							if($error)
							{
								$table 	.="<tr>";
								$table 	.="<td class='".$cls."'>".$rowcnt."</td>";
								$table 	.="<td class='".$cls."'>".$product_name."</td>";
								$table 	.="<td class='".$cls."'>".$combination."</td>";
								$table 	.="<td class='".$cls."'>".$specialcode."</td>";
								$table 	.="<td class='".$cls."' style='color:#FF0015;'>".$error."</td>";
								$table	.="</tr>";
							}
							
							if($error != '')
							{
								$error_count++;
							}
							
							$rowcnt++;
						
						}
						
					$line ++;		
					}
					/*-----------------------  First loop for checking the errors in the csv file, if error occured in any of the row , that will be stored and finally it will display in table format  --------------------------- ends--*/
					
					
					fclose ($fp);
					$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
			        $fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
					
					if($error_count == 0)
					{
						$line = 0;
						$prev_product_id = '';	
						$comb_cnt = 0;
						
						/*-----------------------  Second loop for executing the product data updation, row by row ---------------------- starts--*/
						while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
						{
							//echo 'hai';
							$num 		= count($data);
							$cur_msg	= '';
							$error 		= '';
							if($line!=0)
							{		
								$product_name	= trim($data[0]);
								$combination	= trim($data[1]);
								$specialcode	= trim($data[2]);
								$product_id		= trim($data[3]);
								$comb_id		= trim($data[4]);
								
														
								$product_arr	= explode('-',$product_id);
														
								$product_id 	= addslashes(trim($product_arr[1]));
								
								$sql_stock 		= "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
															product_variablecomboprice_allowed, product_variablecombocommon_image_allowed, product_bulkdiscount_allowed 
													FROM 
														products
													WHERE 
														sites_site_id=$ecom_siteid 
														AND product_id = $product_id 
													LIMIT 
														1";	
								$ret_stock = $db->query($sql_stock);
								$var_stock_variables = 0;
								$update_main_stock = 1;
					
					            //-------------------------------- Update Product Row Details ------------- Starts -----------
								if($comb_id == '')
								{
									$add_spcondition = '';
									if($specialcode != 'N/A')
									{
										$add_spcondition = " product_special_product_code = '".addslashes($specialcode)."'" ;
									}
									
									
									//Update the product_variable_combination_stock table with the details
									if($add_spcondition != '')
									{
										$update_sql = "UPDATE 
															products  
														SET 
															$add_spcondition  
														WHERE 
															 product_id = ".$product_id."
														LIMIT 
															1";
										$db->query($update_sql);
									}
								} //-------------------------------- Update Product Row Details ------------- Ends -----------
								
								//-------------------------------- Update Combination Row Details ------------- Starts -----------
								else
								{
								  $add_spcondition = '';
									
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										
										$add_spcondition = " comb_special_product_code = '".addslashes($specialcode)."'" ;
										
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
											$update_sql = "UPDATE 
																product_variable_combination_stock  
															SET 
																$add_spcondition  
															WHERE 
																comb_id = ".$comb_id."  
																AND products_product_id = ".$product_id." 
															LIMIT 
																1";
											$db->query($update_sql);
										}
									
									$comb_cnt ++;	
							}
							//-------------------------------- Update Combination Row Details ------------- Ends -----------
							
							if($prev_product_id == '')
							{
								$prev_product_id = $product_id;
							}
							if($prev_product_id != $product_id)
							{
								$prev_product_id = $product_id;
							}
							}	
						$line ++;
						}
						/*-----------------------  Second loop for executing the product data updation, row by row ---------------------- ends--*/
						$sucess .= '<br/><br/><div class="redtext" width="100%" align="center"><strong>Product Special Code Update Operation Completed Successfully</strong></div>';
						
					}	
			}
		}
		$table .="</table>";
		if($sucess != '')
		{
			$css		= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
			$sucess 	=	$css.$sucess;
			$sucess 	.=	$go_back;
			echo $sucess;
		}
		elseif($error_outer!='')
		{
			$css			= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
			$error_outer 	= '<br/><br/><div class="redtext" width="100%" align="center"><strong>'.$error_outer.'</strong></div>';
			$error_outer 	=	$css.$error_outer;
			$error_outer 	.=	$go_back;
			echo $error_outer;
		}
		else
		{
				$table .= $go_back;
				echo $table;
		}
	}
?>

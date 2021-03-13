<?php
	/*#################################################################
	# Script Name 	: settings_delivery_location_and_weight.php
	# Description 	: Delivery type location and weight
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 17-Nov-2009
	# Modified by	: LSH
	# Modified On	: 17-Jan-2012
	#################################################################*/
	//Define constants for this page
	$table_name		= 'delivery_site_option_details';
	$page_type		= 'Delivery Charges';
    $help_msg = get_help_messages('EDIT_DELIVERY_LOCATION_WEIGHT_MESS1');
	$delivery_id	= $_REQUEST['deliveryid'];
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
	    $location_id        = $_REQUEST['locationid'];

	// Check whether any delivery methods groups exists for this site
	$group_exists	= false;
	$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
	$ret_check 		= $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		$group_exists = true;
	}
	if($_REQUEST['locationid'])
	{
			$sql_general = "SELECT enable_location_datetime FROM general_settings_sites_common WHERE  sites_site_id = $ecom_siteid LIMIT 1";
		$ret_general = $db->query($sql_general);
		$row_general = $db->fetch_array($ret_general); 
		if($row_general['enable_location_datetime']==1)
		{
		  $show_datetime = true;
		}
		$sqlloc	= "SELECT location_name,location_id,location_free_delivery,location_tax_applicable,location_datetime_applicable  FROM delivery_site_location where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." AND location_id=".$_REQUEST['locationid'];
		$resloc = $db->query($sqlloc);
		$rowloc = $db->fetch_array($resloc);
		
		// Check whether any delivery methods groups exists for this site
		
		$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
		$ret_check 		= $db->query($sql_check);
		if(!$group_exists)
		{
			$sql="SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." AND delivery_site_location_location_id=".$_REQUEST['locationid']." ORDER BY delopt_option";
			$res= $db->query($sql);
		}	
	}
	if($show_datetime==true)
	{
	  $colspan = 3;
	}
	else
	{
	 $colspan = 2;
	}
	if (!$_REQUEST['locationid'])
		$_REQUEST['locationid'] = -1;
	$sqlname="SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
	$resname= $db->query($sqlname);
	$rowname = $db->fetch_array($resname);
	
	$gensql = "SELECT delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment,
					  delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment     	  
					   FROM general_settings_sites_common 
							WHERE sites_site_id='".$ecom_siteid."'";
	$genres = $db->query($gensql);
	$gennum = $db->num_rows($genres);
	$genrow = $db->fetch_array($genres);
	if($gennum==0) {
		$errmsg =  " Please Set Delivery Settings. <br/> Please <a href='home.php?request=general_settings&fpurpose=settings_default'> Click here </a> to go to General Settings  ";
	} else {
		$wgmin = $genrow['delivery_settings_weight_min_limit'];
		$wgmax = $genrow['delivery_settings_weight_max_limit'];
		$wginc = $genrow['delivery_settings_weight_increment'];
		
		$othmin = $genrow['delivery_settings_common_min'];
		$othmax = $genrow['delivery_settings_common_max'];
		$othinc = $genrow['delivery_settings_common_increment'];				
	} 
			    $main_html 		= Build_main_delivery_dropdown_html();  
?>
<script type="text/javascript">
function valform(require)
{
 if(document.frmlistDelivery.location.value=='')
 {
                alert('Enter the location');
				document.frmlistDelivery.location.focus();
				return false;
 }
 if(document.getElementById("location_free_delivery").checked==true)
 {
	 var myElem = document.getElementById('location_free_delivery_subtotal');
	 if (myElem != null)
	 {
		 if(isNaN(document.frmlistDelivery.location_free_delivery_subtotal.value))
		 {
			alert('Sub total value should be Numeric');
			document.frmlistDelivery.location_free_delivery_subtotal.focus();
			return false;
		 }
	 }
 }
 for(var i=0; i<document.frmlistDelivery.elements.length;i++)
 {  
  if(document.frmlistDelivery.elements[i].name.substr(0,5)=='price')
  { 
     	  if (isNaN(document.frmlistDelivery.elements[i].value))
			{
				alert('Price should be a Numeric value');
				document.frmlistDelivery.elements[i].focus();
				return false;
			}
		if (document.frmlistDelivery.elements[i].value<0)
			{
				alert('Price should be a Positive value');
				document.frmlistDelivery.elements[i].focus();
				return false;
			}
  }
  
 }
 if(require=='more')
 {
 document.forms.frmlistDelivery.more_req.value=1;
 }

  show_processing();
  document.frmlistDelivery.fpurpose.value='deliveryData';
  document.forms.frmlistDelivery.submit();
}
function handle_change_free_delivery()
{
	if(document.getElementById('location_free_delivery').checked)
		document.getElementById('free_msg').style.display = 'none';
	else
		document.getElementById('free_msg').style.display = 'none';
}
function handle_tabs(id,mod)
	{
    tab_arr 								= new Array('main_tab_td','datetime_tab_td');  
    var loc_id  = <?php echo $_REQUEST['locationid']?>;
        var del_id  = <?php echo $_REQUEST['deliveryid']?>;

	var atleastone 							= 0;
	var card_id								= '<?php echo $card_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $sort_by?>';
	var sortorder							= '<?php echo $sort_order?>';
	var recs								= '<?php echo $records_per_page?>';
	var start								= '<?php echo $start?>';
	var pg									= '<?php echo $pg?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $advert_showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&card_id='+card_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;
	for(i=0;i<tab_arr.length;i++)
	{
		if(tab_arr[i]!=id)
		{
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			obj.className = 'toptab';
		}
	}
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	
	switch(mod)
	{
		case 'deliverymain_info':
			document.frmlistDelivery.fpurpose.value ='list_delivery_maininfo_weight';
			document.frmlistDelivery.submit();
		break;
		case 'date_time': // Case of Categories in the group
			document.frmlistDelivery.fpurpose.value ='show_date_time_weight';
			document.frmlistDelivery.curtab.value ='datetime_tab_td';			
			document.frmlistDelivery.submit();
		break;
			
	}
	}
</script>
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<?php
	if($show_datetime == true)
	{
?>
    <!-- loads jquery and jquery ui -->
    <script type="text/javascript" src="js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
    <!-- script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script -->
    
    <!-- loads mdp -->
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    
    <!-- mdp demo code -->
    <script type="text/javascript">
    <!--
    var latestMDPver = $.ui.multiDatesPicker.version;
    var lastMDPupdate = '2012-03-28';
    
    $(function() {
    // Version //
    //$('title').append(' v' + latestMDPver);
    $('.mdp-version').text('v' + latestMDPver);
    $('#mdp-title').attr('title', 'last update: ' + lastMDPupdate);
    
    // Documentation //
    $('i:contains(type)').attr('title', '[Optional] accepted values are: "allowed" [default]; "disabled".');
    $('i:contains(format)').attr('title', '[Optional] accepted values are: "string" [default]; "object".');
    $('#how-to h4').each(function () {
    var a = $(this).closest('li').attr('id');
    $(this).wrap('<'+'a href="#'+a+'"></'+'a>');
    });
    $('#demos .demo').each(function () {
    var id = $(this).find('.box').attr('id') + '-demo';
    $(this).attr('id', id)
    .find('h3').wrapInner('<'+'a href="#'+id+'"></'+'a>');
    });
    
    // Run Demos
    $('.demo .code').each(function() {
    eval($(this).attr('title','NEW: edit this code and test it!').text());
    this.contentEditable = true;
    }).focus(function() {
    if(!$(this).next().hasClass('test'))
    $(this)
    .after('<button class="test">test</button>')
    .next('.test').click(function() {
    $(this).closest('.demo').find('.box').removeClass('hasDatepicker').empty();
    eval($(this).prev().text());
    $(this).remove();
    });
    });
    });
    // -->
    </script>
    
    <!-- loads some utilities (not needed for your developments) -->
    <link rel="stylesheet" type="text/css" href="css/mdp.css">
    <link rel="stylesheet" type="text/css" href="css/prettify.css">
    <script type="text/javascript" src="js/prettify.js"></script>
    <script type="text/javascript" src="js/lang-css.js"></script>
    <script type="text/javascript">
    $(function() {
    prettyPrint();
    });
    </script>
<?php
	}
?>
<?php	
	$sqllocfree = "SELECT allow_free_delivery_location  FROM general_settings_sites_common WHERE sites_site_id =". $ecom_siteid;
	$reslocfree = $db->query($sqllocfree);
	$rowlocfree = $db->fetch_array($reslocfree);
	
	if($rowlocfree['allow_free_delivery_location'] > 0)
	{
		$show_subtotal	=	"showsubtotal();";
?>
<script language="javascript">
function showsubtotal()
{
	var $nc = jQuery.noConflict();
	if($nc('#location_free_delivery').attr('checked'))
	{
		$nc("#show_subtotal").show();
	}
	else
	{
		$nc("#show_subtotal").hide();
	}
}
</script>
<?php
	}
	else
	{
		$show_subtotal	=	"";
	}
?>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="deliveryData">
<input type="hidden" name="request" value="delivery_settings" />
<input type="hidden" name="deliveryid" value="<?php echo $delivery_id;?>">
 <input type="hidden" name="type1" value="Location_And_Amount">
 <input type="hidden" name="locationid" value="<?php echo $_REQUEST['locationid'];?>">
      <input type="hidden" name="curtab" id="curtab" value="">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings"> Delivery Charges</a><span>'<? echo $rowname['deliverymethod_name'];?>'</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
         <? if($_REQUEST['alert']){
		 $alert ="Details Saved Successfully";
		 ?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <? }
		  if($location_id>0)
	     {
		 
		  ?>
<!--		   <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
          </tr>-->
		<tr>
        	<td colspan="2" align="left" valign="middle"  >
        		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
			<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','deliverymain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<?php 
						if($show_datetime ==true)
						{
						?>
						<td  align="left" onClick="handle_tabs('datetime_tab_td','date_time')" class="<?php if($curtab=='datetime_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="datetime_tab_td"><span> Date & Time</span></td>
						<?php
						}
						?>
						<td width="90%" align="left">&nbsp;</td>  
				</tr>
				  </table>
			</td>
		</tr>
		 <tr>
        	<td colspan="2" align="left" valign="middle" class="tdcolorgraynormal" >
           
				    <table width="100%" border="0" cellspacing="0" cellpadding="0">

				<tr>
				  <td colspan="<?php echo $colspan;?>" align="left" class="" >
				  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
			
				show_delivery_maininfo_amount($location_id,$delivery_id,$alert,$sql_check);
			}
			elseif ($curtab=='datetime_tab_td')
			{
				show_date_time($location_id,$delivery_id,$rowloc['location_datetime_applicable'],$alert);
			}
		
			?>		
		  </div>		 
		  </td>
		  </tr> 
		</table>
		</td>
        </tr>
		  <?php 
		  }
		    if($location_id<=0)
	     {
		  ?>
		 <!-- <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
          </tr>-->
		<tr>
		<td colspan="4" align="left" valign="middle" class="sorttd" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0">          
          <?php // } 
		if($group_exists==false) // case if delivery groups does not exists
		{
			
	?>
	<tr>
	<td colspan="4" align="left" valign="middle" class="sorttd">
		<div class="editarea_div">
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
	<tr>
            <td align="left" style="width:35%;"><b>Location  :</b>
                <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50" /></td><td style="width:18%;" align='left'><div><input type="checkbox" onclick="javascript:handle_change_free_delivery(); <?php echo $show_subtotal;?>" name="location_free_delivery" id="location_free_delivery" value="1" <?php echo ($rowloc['location_free_delivery']==1)?'checked="checked"':''?>/>Allow Free Delivery? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_FREE_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div><div><input type="checkbox" name="location_tax_applicable" id="location_tax_applicable" value="1" <?php echo ($rowloc['location_tax_applicable']==1)?'checked="checked"':''?>/>Apply Tax? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_APPLY_TAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
               <td align="left" style="width:50%;"><?php if($rowlocfree['allow_free_delivery_location'] > 0) { ?>
	  		  <div style="float:left; display:none;" id="show_subtotal"><b>The minimum subtotal to allow free delivery :</b>
	  		    <input name="location_free_delivery_subtotal" size="10" type="text" value="<?php echo $rowloc['location_free_delivery_subtotal']; ?>" />
  		      </div>
	  		  <?php } ?></td>
          </tr>
          <tr id="free_msg"style="display:<?php echo ($rowloc['location_free_delivery']==1)?'none':'none'?>" >
           <td align='center' colspan="3"><div><span class='redtext'>Products for which free delivery is ticked will be excluded from delivery charge calculation  as "Allow free delivery" is ticked for this location.</span></div></td>
          </tr>
	<tr>
	
          <tr>
            <td align="right" width="394"><b>Option</b></td>
            <td colspan="2" align="center"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
          </tr>
          <?
    $k=0;
  while($row=$db->fetch_array($res))
  {
   $option=$row['delopt_option'];
  //echo "test".  $option;
   // list($big,$small) = split('[.]',$option);
   $big = $option;
	 //echo $big;
	  
   $k++;
   if($k==1){
   $msg='More than zero but less than ';
   }else{
    $msg='More than previous but less than';}
		
   ?>
          <tr>
            <td align="right" width="394"><? echo $msg; ?>
                <select name="del_optionbig[]" class="input">
                  <option value=''></option>
                  <? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
                  <option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
                  
                  <? }*/
				  Build_main_delivery_dropdown($big,$main_html);?>
                </select>
              </td>
            <td colspan="2" align="left"><input name="price[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>" />            </td>
          </tr>
          <?
  }
  
   for($j=0; $j<5; $j++){ 
   if($j==0 && $k==0){
   $msg='More than zero but less than ';
   }else{
    $msg='More than previous but less than';}
    ?>
          <tr>
            <td align="right" width="394"><? echo $msg;?>
                <select name="del_optionbig[]" class="input">
                  <option value=''></option>
                  <? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
                  <option value="<? echo $i; ?>"><? echo $i; ?></option>
                  
                  <? }*/
				  Build_main_delivery_dropdown('',$main_html);?>
                </select>
              </td>
            <td colspan="2" align="center"><input name="price[]" class="input" type="text" size="5" />            </td>
          </tr>
          <? } ?>
          </table>
          </div>
          </td>
          </tr>
          <td colspan="4" align="left" valign="middle" >
		<div class="editarea_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr id="largebuttonlink">
            <td align="right" width="45%">&nbsp;
                <input name="button" type="button" class="red"  onclick="valform()" value="Submit Rates" /></td>
            <td align="right"><input name="button" type="button" class="red"  onclick="valform('more')" value="Submit and enter more rates" /></td>
          </tr>
          
          </table>
          </div>
          </td>
          </tr>
          <?php
	}
	else
	{
		?>
		<tr>
		<td colspan="4" align="left" valign="middle" class="sorttd">
		<div class="editarea_div">
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
		<tr>
            <td align="left" width="35%"><b>Location  :</b>
                <input name="location" type="text" value="<? echo $rowloc['location_name'] ?>" size="50" /></td><td width="18%" align='left'><div><input type="checkbox" onclick="handle_change_free_delivery()" name="location_free_delivery" id="location_free_delivery" value="1" <?php echo ($rowloc['location_free_delivery']==1)?'checked="checked"':''?>/>Allow Free Delivery? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_FREE_DELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div><div><input type="checkbox" name="location_tax_applicable" id="location_tax_applicable" value="1" <?php echo ($rowloc['location_tax_applicable']==1)?'checked="checked"':''?>/>Apply Tax? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SETTINGS_DELIVERY_APPLY_TAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
                <td align="left" style="width:50%;"><?php if($rowlocfree['allow_free_delivery_location'] > 0) { ?>
	  		  <div style="float:left; "><b>The minimum subtotal to allow free delivery :</b>
	  		    <input name="location_free_delivery_subtotal" size="10" type="text" value="<?php echo $rowloc['location_free_delivery_subtotal']; ?>" />
  		      </div>
	  		  <?php } ?></td>
          </tr>
          <tr id="free_msg"style="display:<?php echo ($rowloc['location_free_delivery']==1)?'none':'none'?>" >
           <td align='center' colspan="3"><div><span class='redtext'>Products for which free delivery is ticked will be excluded from delivery charge calculation  as "Allow free delivery" is ticked for this location.</span></div></td>
          </tr>
		<?php
		while ($row_check = $db->fetch_array($ret_check))
		{
			$curgroup_active = 0;
			$sql_first	= "SELECT delivery_group_active_in_location FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
									AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " 
									AND delivery_site_location_location_id = ".$_REQUEST['locationid']." 
									AND sites_site_id =". $ecom_siteid." ORDER BY  delopt_option LIMIT 1";
			$ret_first  = $db->query($sql_first);
			if($db->num_rows($ret_first))
			{
				$row_first = $db->fetch_array($ret_first);
				$curgroup_active = $row_first['delivery_group_active_in_location']; 
			}
?>
          <tr>
            <td colspan="3" align="left" valign="middle" class="seperationtd" ><?php echo stripslashes($row_check['delivery_group_name']); if ($ecom_site_delivery_location_country_map==1){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' value='1' name='group_active_<?php echo $row_check['delivery_group_id']?>' <?php echo ($curgroup_active==1)?'checked':''?>>Tick to Activate this group under this location<?php }?></td>
          </tr>
          <tr>
	       <td colspan="3" align="left" valign="middle" >
			<table width="637" border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td align="right" width="700"><b>Option</b></td>
            <td align="center" width="300"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
          </tr>
          <?
    $k=0;
	$sql 		= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." 
							AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " 
							AND delivery_site_location_location_id = ".$_REQUEST['locationid']." 
							AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
	$res 		= $db->query($sql);
  	while($row=$db->fetch_array($res))
  	{
	   $option=$row['delopt_option'];
	  //echo "test".  $option;
		//list($big,$small) = split('[.]',$option);
		 //echo $big;
		 $big=$option;
		  
	   $k++;
	   if($k==1){
	   $msg='More than zero but less than ';
	   }else{
		$msg='More than previous but less than';}
		
   ?>
          <tr>
            <td align="right" width="700"><? echo $msg; ?>
                <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
                  <option value=''></option>
                  <? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
                  <option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
                  
                  <? }*/
				  Build_main_delivery_dropdown($big,$main_html);?>
                </select>
               </td>
            <td align="center" width="300"><input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>" />            </td>
          </tr>
          <?
  	}
  
	   for($j=0; $j<5; $j++){ 
	   if($j==0 && $k==0){
	   $msg='More than zero but less than ';
	   }else{
		$msg='More than previous but less than';}
		?>
          <tr>
            <td align="right" width="700"><? echo $msg;?>
                <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
                  <option value=''></option>
                  <? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
                  <option value="<? echo $i; ?>"><? echo $i; ?></option>
                  
                  <? }*/
				  Build_main_delivery_dropdown('',$main_html);?>
                </select>
               </td>
            <td align="center" width="300"><input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" />            </td>
          </tr>
          <? } ?>
          </table>
	</td>
	</tr>
          <?php
		}
	?>
	
	 </table></div></td>
		</tr>
		<td colspan="4" align="left" valign="middle" >
		<div class="editarea_div">
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
          <tr id="largebuttonlink">
            <td align="right" width="45%">&nbsp;
                <input name="button" type="button" class="red"  onclick="valform()" value="Submit Rates" /></td>
            <td align="right"><input name="button" type="button" class="red"  onclick="valform('more')" value="Submit and enter more rates" /></td>
          </tr>
          
           </table></div></td>
		</tr>
          <?php	
	}
}
	?>
      </table>
	    <input type="hidden" name="more_req" value="" />
</form>
<script type="text/javascript">
hide_processing();
</script>

<?php
	/*#################################################################
	# Script Name 	: edit_product_variable.php
	# Description 	: Page for editing Product variables
	# Coded by 		: Sny
	# Created on	: 28-Jun-2007
	# Modified by	: Sny
	# Modified On	: 23-Jul-2007
	#################################################################*/
//Define constants for this page
$page_type = 'Products';
$help_msg = 'This section helps in editing Product Videos';
$show_popupmsg = 0;
// ============================================================================================
// Get the name of current product
// ============================================================================================
$sql_prod = "SELECT product_name 
				FROM 
					products 
				WHERE 
					product_id=".$_REQUEST['checkbox'][0]." 
					AND sites_site_id = $ecom_siteid 
				LIMIT 
					1";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
	
}
else
	exit;
// ============================================================================================
// Get the details of variable being editing
// ============================================================================================
$sql_vid = "SELECT * FROM product_videos WHERE video_id=$edit_id";
$ret_vid = $db->query($sql_vid);
if ($db->num_rows($ret_vid))
{
    $row_vid = $db->fetch_array($ret_vid);
    
}

?>	
<script charset="UTF-8" src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/mColorPicker.js"></script>
<script language="javascript" type="text/javascript">
	var pname 			= '<?=$_REQUEST['productname']?>';
	var manid 			= '<?=$_REQUEST['manufactureid']?>';
	var catid 			= '<?=$_REQUEST['categoryid']?>';
	var vendorid 		= '<?=$_REQUEST['vendorid']?>';
	var rprice_from 	= '<?=$_REQUEST['rprice_from']?>';
	var rprice_to 		= '<?=$_REQUEST['rprice_to']?>';
	var cpricefrom 		= '<?=$_REQUEST['cprice_from']?>';
	var cpriceto 		= '<?=$_REQUEST['cprice_to']?>';
	var discount 		= '<?=$_REQUEST['discount']?>';
	var discountas 		= '<?=$_REQUEST['discountas']?>';
	var bulkdiscount 	= '<?=$_REQUEST['bulkdiscount']?>';
	var stockatleast	= '<?=$_REQUEST['stockatleast']?>';
	var preorder 		= '<?=$_REQUEST['preorder']?>';
	var prodhidden 		= '<?=$_REQUEST['prodhidden']?>';
	var sortby 			= '<?php echo $sort_by?>';
	var sortorder 		= '<?php echo $sort_order?>';
	var recs 			= '<?php echo $records_per_page?>';
	var start			= '<?php echo $start?>';
	var pg 				= '<?php echo $pg?>';
	var maintainstock	= '<?php echo $gen_arr['product_maintainstock']?>';
	/* preloading the image to be shown on loading*/
	pic1= new Image(); 
	pic1.src="images/loading.gif";
function valforms(frm)
{
	var atleastone = false;
	var cur_neg =0;
	var cur_negord =0;
	var cur_num =0;
	fieldRequired = Array('video_title');
	fieldDescription = Array('Video Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	
		fieldNumeric = Array('video_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{		
		return true;	
	}	
	else
	{
		return false;
	}
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			document.getElementById('prodvid_div').innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}

function in_array( what, where )
{
    var a=false;
    for(var i=0;i<where.length;i++)
    {
        if(what == where[i])
        {
            a=true;
            break;
        }
    }
    return a;
}
</script>
<style>
.floatmsg_divcls{
	background-color:#FEEAA4;
	color:#E60000;
	position:absolute;
	top:51%;
	left:33%;
	width:500px;
	height:90px;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal;
	border:2px solid #000000;
	text-align:left;
}
</style>

<form name='frmEditProductVideos' action='home.php?request=products' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a><a href="home.php?request=products&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&amp;productname=<?php echo $_REQUEST['productname']?>&amp;manufactureid=<?php echo $_REQUEST['manufactureid']?>&amp;categoryid=<?php echo $_REQUEST['categoryid']?>&amp;vendorid=<?php echo $_REQUEST['vendorid']?>&amp;rprice_from=<?php echo $_REQUEST['rprice_from']?>&amp;rprice_to=<?php echo $_REQUEST['rprice_to']?>&amp;cprice_from=<?php echo $_REQUEST['cprice_from']?>&amp;cprice_to=<?php echo $_REQUEST['cprice_to']?>&amp;discount=<?php echo $_REQUEST['discount']?>&amp;discountas=<?php echo $_REQUEST['discountas']?>&amp;bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&amp;stockatleast=<?php echo $_REQUEST['stockatleast']?>&amp;preorder=<?php echo $_REQUEST['preorder']?>&amp;prodhidden=<?php echo $_REQUEST['prodhidden']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>&curtab=<?=$_REQUEST['curtab']?>">Edit Product</a> <span> Edit Product Variable for &quot;<?php echo $showprodname?>&quot; </span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <tr>
          <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="19%" align="left">Video Title<span class="redtext"> *</span></td>
               <td width="41%" align="left"><input name="video_title" type="text" id="video_title" value="<?php echo stripslashes($row_vid['video_title'])?>" size="30" maxlength="100" /></td>
               <td width="11%" align="left">Hide</td>
               <td width="29%" align="left"><input type="radio" name="video_hide" value="1" <?php echo ($row_vid['video_hide']==1)?'checked="checked"':''?> />Yes
               <input name="video_hide" type="radio" value="0" <?php echo ($row_vid['video_hide']==0)?'checked="checked"':''?> />No
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VID_EDIT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
           
			 </tr>
             <tr>
               <td align="left" valign="top">Embed Script</td>
               <td align="left"><textarea name="video_script" cols="60" rows="5"><?php echo $row_vid['video_script']?></textarea></td>
               <td align="left">Order</td>
               <td align="left"><input name="video_order" type="text" size="5" value="<?php echo $row_vid['video_order']?>"/></td>
             </tr>
			  
             <tr>
               <td colspan="4" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td colspan="4" align="left">&nbsp;</td>
             </tr>
              
         
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td width="19%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">

		  	<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		  	<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		  	<input type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>" />
		  	<input type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>" />
			<input type="hidden" name="rprice_from" id="rprice_from" value="<?=$_REQUEST['rprice_from']?>" />
			<input type="hidden" name="rprice_to" id="rprice_to" value="<?=$_REQUEST['rprice_to']?>" />
			<input type="hidden" name="cprice_from" id="cprice_from" value="<?=$_REQUEST['cprice_from']?>" />
			<input type="hidden" name="cprice_to" id="cprice_to" value="<?=$_REQUEST['cprice_to']?>" />
			<input type="hidden" name="discount" id="discount" value="<?=$_REQUEST['discount']?>" />
			<input type="hidden" name="discountas" id="discountas" value="<?=$_REQUEST['discountas']?>" />
			<input type="hidden" name="bulkdiscount" id="bulkdiscount" value="<?=$_REQUEST['bulkdiscount']?>" />
			<input type="hidden" name="stockatleast" id="stockatleast" value="<?=$_REQUEST['stockatleast']?>" />
			<input type="hidden" name="preorder" id="preorder" value="<?=$_REQUEST['preorder']?>" />
			<input type="hidden" name="prodhidden" id="prodhidden" value="<?=$_REQUEST['prodhidden']?>" />
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
			<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
			<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_editprodvid" />
			<input type="hidden" name="saveandaddmore" id="saveandaddmore" value="0" />
			<input type="hidden" name="src_page" id="src_page" value="prodvarimg" />
			<input type="hidden" name="srcvar_id" id="srcvar_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="remvarvalueimg" id="remvarvalueimg" value="" />
			<input type="hidden" name="src_id" id="src_id" value="" />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />

			</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
	  </div>
	  	</td>
		</tr>
		<?php 
			// $edit_id = $_REQUEST['checkbox'][0];
		     //$grid_proceed = grid_enablecheck($edit_id);
            // if($grid_proceed==false)
             
			?>
		<tr>
           <td align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="100%" align="right" valign="middle">
			   <div style="display:inline; width:20px">
			<input name="prodvid_Submit" type="submit" class="red" value="Save" /> 
			</div>
			<input name="prodvidmore_Submit" type="button" class="red" value="Save & Add more value" onclick="document.frmEditProductVideos.saveandaddmore.value=1;document.frmEditProductVideos.submit()" />
			   </td>
			 </tr>
		  </table>
		  </div>
		  </td>
		</tr>
		<?php
	
		?>
		</table>
</form>	  


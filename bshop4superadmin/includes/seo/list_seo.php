<?php
	/*
	#################################################################
	# Script Name 	: list_seo.php
	# Description 	: Page for managing Seo 
	# Coded by 		: LSH
	# Created on	: 09-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'sites';
$page_type = 'Seo';
$help_msg = 'This section Helps to manage SEO';
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//Starting record.
$pages = ceil($numcount / $records_per_page);//Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
$sql_live = "SELECT site_id,site_domain,console_levels_level_id,site_title,is_managed_seo,in_web_clinic FROM sites where in_web_clinic=1 AND site_status='Live'";
	$ret_live = mysql_query($sql_live);
		
/////////////////////////////////////////////////////////////////////////////////////

?>
<script type="text/javascript">
	function handle_searchsubmit()
	{
		if(document.main.cbo_sites.value=='')
		{
			//alert("Please select a Site");
			//return false;
		}
		formhandler('sel_site');
	}
	function formhandler(purpose)
	{
		var purp
		purp=purpose
		document.main.fpurpose.value=purp
		document.main.submit()	
	}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b>Manage SEO<font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
      </tr>
	  <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td colspan="<?=$colspan?>" align="center" class="error_msg"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	    <tr class="maininnertabletd1">
		<td></td>
	   </tr>
	    <tr class="maininnertabletd1">
		<td></td>
		</tr>
	     <tr>
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>" align="center">
		<form name="main" action="home.php?request=seo" method="post">
         <input type="hidden" name="fpurpose" value="">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
				<tr>
				  <td colspan="2"><b>&nbsp;&nbsp;Please select the Site: </b>
				     <select name="cbo_sites" onchange="handle_searchsubmit();">
				    	<option value="">Select a Site</option>
						
						   <?	
						   if(mysql_num_rows($ret_live))
							{
							?>
							<optgroup label="Sites on Web Clinic">
							<?
							while ($row_live = mysql_fetch_array($ret_live))
							{
								$ext_site[] = $row_live['site_id'];
							
?>   						 <option value="<?php echo $row_live['site_id']?>" <?php echo ($row_live['site_id']==$_REQUEST['cbo_sites'])?'selected':''?>><?php echo stripslashes($row_live['site_domain'])?></option>
  <?				
							}
							}
					?>
						</optgroup>
						<?
						if(!is_array($ext_site))
						{
						$ext_site[] = 0;
						}
						$ext_str = implode(",",$ext_site);
						
						$sql_sel = "SELECT site_id,site_domain FROM sites a WHERE 
					site_id NOT IN ($ext_str) AND site_status='Live' ORDER BY site_domain";
					$ret_sel = mysql_query($sql_sel);
					if(mysql_num_rows($ret_sel))
					{
?>						
						<optgroup label="Managed SEO Sites">
<?php
						while($row_sel = mysql_fetch_array($ret_sel))
						{
?>
							<option value="<?php echo $row_sel['site_id']?>" <?php echo ($row_sel['site_id']==$_REQUEST['cbo_sites'])?'selected':''?>><?php echo $row_sel['site_domain'];?></option>
<?php							
						}
?>							
						</optgroup>
<?php
					}
?>						
                     <?php
?>						
  					</select>
				  (List only the sites on webclinic or Managed SEO) </td>
				</tr>
				<?php
			if($cbo_sites)
			{
?>			  
				<tr>
				  <td width="34%" height="25" align="center">&nbsp;</td>
						<td width="66%"center""><b> Select the individual sections below</b></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=staticpage&cbo_sites='+document.main.cbo_sites.value">Manage Static Pages</a></td>
		  </tr>
				<tr>
					<td width="34%">&nbsp;</td>
					<td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=AssignKeyword&cbo_sites='+document.main.cbo_sites.value">Manage Site Keywords</a></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=SavedKeywords&cbo_sites='+document.main.cbo_sites.value">Manage Saved Keywords</a></td>
			  </tr>
			  <tr>
				  <td>&nbsp;</td>
				  <td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=Entirekeywords&cbo_sites='+document.main.cbo_sites.value">Entire Keywords </a></td>
			  </tr>
				<tr>
					<td width="34%">&nbsp;</td>
					<td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=AssignTitle&cbo_sites='+document.main.cbo_sites.value">Site Titles</a></td>
				</tr>
				
			   
			  <tr>
				  <td>&nbsp;</td>
				  <td><a class="seo"  align="center" href="#" onclick="document.location='home.php?request=seo&fpurpose=SiteMetadescription&cbo_sites='+document.main.cbo_sites.value">Site meta Description </a></td>
			  </tr>
			<?
			}
			?>
				</table>
		  </form>
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
		?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center">
        &nbsp;&nbsp;&nbsp;</td>
      </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=themes";
  ?>
   </table>

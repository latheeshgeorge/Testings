<?php
/*#################################################################
# Script Name 	: list_colors.php
# Description 	: Page for listing colors
# Coded by 	: Sny
# Created on	: 11-Jan-2010
# Modified by	: 
# Modified On	: 
#################################################################*/
//Define constants for this page
$table_name     ='general_settings_site_colors';
$page_type      ='Product Variable Colors';
$help_msg = get_help_messages('LIST_PROD_VAR_COLOR_CODE');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistcolors,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistcolors,\'checkbox[]\')"/>','Slno.','Color Name','Hex code','Color','Image');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'color_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('color_name' => 'Color Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( color_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=colorcodes&records_per_page=$records_per_page&start=$start&pg=$pg";

?>
<script language="javascript">
function checkSelected()
{
    len=document.frmlistcolors.length;
    var cnt=0;		
    for (var j = 1; j <= len; j++) {
            el = document.frmlistcolors.elements[j]
            if (el!=null && el.name== "checkbox[]" )
                if(el.checked) {
                            cnt++;
                            user_id=el.value;
                }		
    }
    if(cnt==0) {
            alert('Please select atleast one Heading ');
            return false;
    }
    show_processing();
    return true;
}
function edit_selected()
{
    
    len=document.frmlistcolors.length;
    var cnt=0;		
    for (var j = 1; j <= len; j++) {
            el = document.frmlistcolors.elements[j]
            if (el!=null && el.name== "checkbox[]" )
                if(el.checked) {
                            cnt++;
                            user_id=el.value;
                }		
    }
    if(cnt==0) {
            alert('Please select atleast one color ');
    }
    else if(cnt>1 ){
            alert('Please select only one color to edit');
    }
    else
    {
            show_processing();
            document.frmlistcolors.fpurpose.value='edit';
            document.frmlistcolors.submit();
    }
    
    
}
function ajax_return_contents() 
{
    var ret_val='';
    if(req.readyState==4)
    {
            if(req.status==200)
            {
                    ret_val = req.responseText;
                    document.getElementById('maincontent').innerHTML=ret_val;
                    hide_processing();
            }
            else
            {
                        show_request_alert(req.status);
                    //alert("Problem in requesting XML :"+req.statusText);
            }
    }
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
    var atleastone 	= 0;
    var del_ids 	= '';
    var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
    /* check whether any checkbox is ticked */
    for(i=0;i<document.frmlistcolors.elements.length;i++)
    {
            if (document.frmlistcolors.elements[i].type =='checkbox' && document.frmlistcolors.elements[i].name=='checkbox[]')
            {

                    if (document.frmlistcolors.elements[i].checked==true)
                    {
                            atleastone = 1;
                            if (del_ids!='')
                                    del_ids += '~';
                                del_ids += document.frmlistcolors.elements[i].value;
                    }	
            }
    }
    if (atleastone==0)
    {
            alert('Please select color to delete');
    }
    else
    {
            if(confirm('Are you sure you want to delete selected color(s)?'))
            {
                    show_processing();
                    Handlewith_Ajax('services/colors.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
            }	
    }	
}
</script>
<form name="frmlistcolors" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="colorcodes" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id=" sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id=" sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id=" records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id=" search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Product Variable Colors</span> </div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
	 <td class="sorttd" colspan="3" align="right">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	</tr>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
      <div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="8%" align="left" valign="middle">Color Name</td>
          <td width="19%" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
         
          <td width="12%" align="left">Records Per Page </td>
          <td width="9%"  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
         
          <td width="8%" align="left">Sort By</td>
          <td width="24%"  align="left" nowrap="nowrap"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp;<?=$sort_by_txt?>  </td>
          <td width="20%"  align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_SIZE_CHART_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>	
	  </div>
	    </td>
    </tr>
	
    <tr>
      <td width="150" class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=colorcodes&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td class="listeditd" width="202" align="center">
	 </td>
	   <td width="383" align="right" class="listeditd">
	  </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
        <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_heading = "SELECT color_id,color_name, color_hexcode,images_image_id  
                            FROM 
                                $table_name 
                                $where_conditions 
                            ORDER BY 
                                $sort_by $sort_order 
                            LIMIT 
                                $start,$records_per_page ";
	   
	   $res = $db->query($sql_heading);
	   $srno = 1; 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['color_id']?>" type="checkbox" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="40%"><a href="home.php?request=colorcodes&fpurpose=edit&checkbox[0]=<?php echo $row['color_id']?>&<?=$query_string?>" title="<? echo $row['color_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['color_name']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo stripslashes($row['color_hexcode'])?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><div style='width:10px;height:10px;background-color:<?php echo $row['color_hexcode']?>;border:1px solid #000000'></div></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?php
		  if ($row['images_image_id']!=0)
		  {
			$sql_img = "SELECT a.image_id,a.image_gallerythumbpath,a.images_directory_directory_id 
							FROM 
								images a 
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.image_id=".$row['images_image_id']." 
							LIMIT 
								1";	
			$ret_img = $db->query($sql_img);
			if($db->num_rows($ret_img))
			{
				$row_img = $db->fetch_array($ret_img);
				$disp_delimg = true;
		  ?>
				<a href="home.php?request=colorcodes&fpurpose=edit&checkbox[0]=<?php echo $row['color_id']?>&<?=$query_string?>" title="<? echo $row['color_name']?>" style="cursor:pointer" onmouseover ="ddrivetip('<center><br><img src=http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?> title=Preview border=0/><br><br></center>')"; onmouseout="hideddrivetip()"><img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="16px" height="16px" border="0"/></a>
		  <?php
			}
		  }
		  else
		  {
		  ?>
				<a href="home.php?request=colorcodes&fpurpose=edit&checkbox[0]=<?php echo $row['color_id']?>&<?=$query_string?>" title="<? echo $row['color_name']?>"><img src="images/var_noimg.gif" title="No Image Assigned" width="16px" height="16px" border="0"/></a>
		  <?php	
		  }
		  ?>
		  </td>
        </tr>
        <?
	  }
	  }
	  else
	  {
	  ?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="5" > No colors exists .</td>
        </tr>
        <?
		}
		?>
      </table>
	  </div>
	  </td>
    </tr>
	
  <tr>
        <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=colorcodes&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
<?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td class="listeditd" width="202"  align="center">
	</td>
	   <td class="listeditd" align="right">&nbsp;   	   </td>
    </tr>
	 <tr>
	   <td class="listing_bottom_paging" colspan="3" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
    </table>
</form>

<?php
/*#################################################################
# Script Name 	: add_news.php
# Description 	: Page for Adding messages.
# Coded by 		: LSH
# Created on	: 03-jul-2008
# Modified by	: 
# Modified On	: 
#################################################################
*/
$page_type = 'Console News';
$help_msg = 'This section helps in adding the Console News.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('news_title','news_text');
	fieldDescription = Array('News Title','News Description');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
function activeperiod(check,bid){
 if(document.frmAddnews.news_activeperiod.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmAddSurvey.is_active.checked = false;
		}
		
		
}
</script>
<form name='frmAddnews' action='home.php?request=console_news' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=console_news&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List News </a> <font size="1">>></font> <strong>Add <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2" valign="top" >
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">News Title </td>
				  <td align="center">:</td>
				  <td align="left"><input name="news_title" type="text" size="40"  value="<?=$_REQUEST['news_title']?>"/></td>
			  </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">News Description </td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><textarea name="news_text" type="text" id="news_text" rows="4" cols="50"><?=$_REQUEST['news_text']?></textarea>
                  <span class="redtext">*</span></td>
			    </tr>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">News Priority </td>
				  <td width="5%" align="center">:</td>
				  <td  align="left"><select name="news_priority" id="new_priority">
      				<option value="1" >1</option>
	       		    <option value="2" >2</option>
     			    <option value="3" >3</option>
					<option value="4" >4</option>
	       		    <option value="5" >5</option>
    				</select>
                  <span class="redtext">*</span></td>
			    </tr>
				<? $sql_sites = "SELECT site_id,site_domain FROM sites";
					$res_sites = $db->query($sql_sites);
					
						$site_exists = true;
						$array_values = array(0=>'All sites');
						while($row_sites = $db->fetch_array($res_sites)) {
							$sid = $row_sites['site_id'];
							$array_values[$sid] = $row_sites['site_domain'];
					}	
					?>
					<td width="40%" align="right" class="fontblacknormal">Site </td>
									  <td width="5%" align="center">:</td>

					      <td align="left" width="55%"><?=generateselectbox('sites_id',$array_values,$_REQUEST['sites_id'])?></td>

				<tr>
				  <td width="40%" align="right" class="fontblacknormal">News Hide </td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left">
				    <input name="news_hide" type="radio"  value="1" <? if($_REQUEST['news_hide']==1) echo "checked";?>  />
				    Yes
                    <input name="news_hide" type="radio"  value="0" <? if($_REQUEST['news_hide']==0) echo "checked";?>  />
                    No			      
<span class="redtext">*</span></td>
			    </tr>
				<? $id='tr_survay';
		   if($row['news_activeperiod']==1)
		   			 {
					  $display='';
					  $exp_news_displaystartdate=explode("-",$row['news_fromdate']);
					  $val_news_displaystartdate=$exp_news_displaystartdate[2]."-".$exp_news_displaystartdate[1]."-".$exp_news_displaystartdate[0];
					  $exp_news_displayenddate=explode("-",$row['news_todate']);
					  $val_news_displayenddate=$exp_news_displayenddate[2]."-".$exp_news_displayenddate[1]."-".$exp_news_displayenddate[0];
					}
					else
					{ 
					 //echo "none";
					  $display='none';
					}
		   ?>
				<tr>
				  <td width="40%" align="right" class="fontblacknormal">News Active Period </td>
				  <td width="5%" align="center">:</td>
				  <td width="55%" align="left"><span class="redtext">
				    <input type="checkbox" name="news_activeperiod" onclick="activeperiod(this.checked,'<? echo $id?>')" value="1" <? if($row['news_activeperiod']==1) echo "checked"?>/>
			      *</span></td>
			    </tr>
				<tr id="<? echo $id;?>" style="display:<?= $display; ?>">
			   <td  colspan="3" >
			   <table width="100%" cellpadding="0" cellspacing="0"> 
			   <tr >
				<td align="right" valign="top" width="60%" class="fontblacknormal" >
					Start Date			
				<input class="input" type="text" name="news_displaystartdate" size="12" value="<? echo $_REQUEST['news_displaystartdate']; ?>"  maxlength="12"  readonly="readonly"/>		 
				<a href="javascript:show_calendar('frmAddnews.news_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
			   <td>&nbsp;</td></tr>
			   <tr >
				<td align="right" valign="top"  width="60%" class="fontblacknormal" >
					End Date			
				<input class="input" type="text" name="news_displayenddate" size="12" value="<? echo $_REQUEST['news_displayenddate']; ?>"  maxlength="12"  readonly="readonly"/>		  
			  <a href="javascript:show_calendar('frmAddnews.news_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
			   <td>&nbsp;</td></tr>
			   </table>		   </td>
			   </tr>
				<tr align="center">
				<td width="30%">&nbsp;</td>
				<td align="left">
				<input type="hidden" name="news_id" id="news_id" value="<?=$_REQUEST['news_id']?>" />
				<input type="hidden" name="news_title_search" id="news_title_search" value="<?=$_REQUEST['news_title_search']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="add_news" />
				<input type="Submit" name="Submit" id="Submit" value="Save" class="input-button">				</td>
				<td align="left">&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>
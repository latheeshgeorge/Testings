<script language="javascript">
//Not used now
function SearchDisplay(searchoptionvalue,toggle1,toggle2) {
	
	document.getElementById('request').value=searchoptionvalue;
	document.getElementById(toggle1).style.display='';
	document.getElementById(toggle2).style.display='none';
}
</script>	
<form name="frmSearch" method="post" action="home.php" class="frm_cls">
<table width="288" border="0" align="center" cellpadding="0" cellspacing="0" class="middlerightcolumn">
<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
  </tr>
<tr>
	<td width="77"><span class="fontblacknormal">Search for: </span></td>
	<td width="211"><span class="fontblacknormal"><input type="radio" id="searchsite" name="searchsite" value="site" onclick="SearchDisplay('sites','sitesearch','clientsearch');" checked="checked"/>Sites &nbsp; &nbsp;<input type="radio" id="searchsite" name="searchsite" value="client" onclick="SearchDisplay('clients','clientsearch','sitesearch');"/>Clients
	</span></td>
</tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="middlerightcolumn" id="sitesearch" style="display:''">
<tr>
  <td width="23%">&nbsp;</td>
	<td width="13%"><span class="fontblacknormal">Site Title: </span></td>
	<td width="64%"><input type="text" name="title" id="title" value="" size="30">	</td>
</tr>
<tr>
  <td>&nbsp;</td>
	<td><span class="fontblacknormal">Domain Name: </span></td>
	<td><input type="text" name="domain" id="domain" value="" size="30">	</td>
</tr>
<tr>
  <td>&nbsp;</td>
	<td><span class="fontblacknormal">Client: </span></td>
	<td><?php
	$sql_client = "SELECT client_id, client_company,client_fname,client_lname FROM clients ORDER BY client_company";
	$res = $db->query($sql_client);
	$array_values[0] = '-- All --';
	while($row = $db->fetch_array($res)) {
		$array_values[$row['client_id']] = stripslashes($row['client_fname'])." ".stripslashes($row['client_lname'])." (".$row['client_company'].")";
	}
	echo generateselectbox('client',$array_values,0); ?>	</td>
</tr>
<tr>
  <td>&nbsp;</td>
	<td><span class="fontblacknormal">Site Status: </span></td>
	<td><?php 
	$array_values = array("0" => "-- Any --", "Under Construction" => "Under Construction", "Live" => "Live", "Suspended" => "Suspended", "Cancelled" => "Cancelled");
	echo generateselectbox('status',$array_values,'0'); ?></td>
</tr>
<tr>
	<td colspan="3" align="center">
	<input type="submit" name="search_submit" value="Search" />	</td>
</tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="middlerightcolumn" id="clientsearch" style="display:none">
<tr>
  <td width="23%">&nbsp;</td>
	<td width="20%"><span class="fontblacknormal">Client Name like : </span></td>
	<td width="57%"><input type="text" name="client_name" id="client_name" value="" size="30">	</td>
</tr>
<tr>
  <td>&nbsp;</td>
	<td><span class="fontblacknormal">Company Name like : </span></td>
	<td><input type="text" name="company" id="company" value="" size="30">	</td>
</tr>
<tr>
	<td colspan="3" align="center"><input type="submit" name="search_submit" value="Search" /></td>
</tr>
</table>
<input type="hidden" id="request" name="request" value="sites" />
</form>

<?php
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	print_r($_REQUEST);
?>
<script>
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			alert('Reached here');
		}
		else
		{
			alert("Problem in requesting XML "+req.statusText);
		}
	}
}
function call_ajax()
{
	getDataXML('<?php echo $_SERVER['PHP_SELF']?>','?test=1&test1=2');
}
</script>
<a href="#" onclick="call_ajax()">Test</a>

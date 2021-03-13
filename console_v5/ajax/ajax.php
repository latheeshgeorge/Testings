<script type="text/javascript">
function Handlewith_Ajax(url,vals)
{
	if(window.XMLHttpRequest)
	{ // branch for native XMLHttpRequest object
		req =  new XMLHttpRequest();
	
		req.open("POST", url, true);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.send(vals);
		
		req.onreadystatechange = <?php echo $ajax_return_function?>;
	} 
	else // Done to handle the case of submitting twice using ajax in IE
	{
		if(window.ActiveXObject)
		{ // branch for IE/Windows ActiveX version
			req = new ActiveXObject("Microsoft.XMLHTTP");
			if (req)
			{
				req.open("POST", url, true);
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				req.send(vals);
				req.onreadystatechange = <?php echo $ajax_return_function?>;
			}
		}
	}	
}
</script>
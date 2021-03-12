<?php
	$src_dir 	= '/var/www/vhosts/bshop4.co.uk/httpdocs/testings/images/www.asll.co.uk/site_images';
	$dest_path	= '/var/www/vhosts/bshop4.co.uk/httpdocs/images/asll.bshop4.co.uk/site_images';
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">File Name</td>
	<td align="left">Status</td>
	</tr>
	<?php
	if( $dh = opendir($src_dir))
    {
		$cnt = 0;
        while (false !== ($file = readdir($dh)))
        {
            // Skip '.' and '..'
            if( $file == '.' || $file == '..')
                continue;
            $srcfile	= $src_dir.'/'.$file;
			$destfile 	= $dest_path.'/'.$file;
			// Check whether file already exists in destination path
			if(file_exists($destfile))
				$stat = 'Exists';
			else
			{
				copy($srcfile,$destfile);
				$stat = '<font style="color:#00FF00">Copied</font>';
			}
		?>
		<tr>
			<td align="left"><?php echo $cnt++?></td>
			<td align="left"><?php echo $file?></td>
			<td align="left"><?php echo $stat?></td>
		</tr>
		<?php	
        }
        closedir($dh);
    }	
?>
<tr>
<td colspan="3">-- Operation Completed --</td>
</tr>
</table>
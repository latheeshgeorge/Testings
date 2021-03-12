<?php
	for ($i=0;$i<2;$i++)
		mail('sony.joy@calpinetech.com','subject-'.$i,'message-'.$i);
		
	echo "Done";
?>
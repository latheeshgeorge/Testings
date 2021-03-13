<?php
function bookmarks($url, $title, $tweeter='',$ret=-1)
{
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
$HTML_image ='';
if(!$tweeter) {
	$tweeter = 'tweetmeme';
}
if($_REQUEST['product_id'])
{
	$pass_type ='image_bigpath';
	$img_arr = get_imagelist('prod',$_REQUEST['product_id'],$pass_type,0,0,1);
	if(count($img_arr))
	{
		$HTML_image = url_root_image($img_arr[0][$pass_type],1);
	}
}
ob_start();
?>
<!--iframe src="http://api.tweetmeme.com/button.js?url=<?php echo utf8_encode($url); ?>&amp;source=<?=$tweeter?>&amp;style=normal&amp;service=bit.ly" scrolling="no" width="50" frameborder="0" height="61"></iframe-->
<a target="_blank" href="<?php echo $ecom_selfhttp?>twitter.com/home?status=<?php echo urlencode($title." ".$url);?>" title="Spread the word on Twitter!"><img src="<? url_site_image('social_twitter_24.png');?>" alt="Tweet This" border="0" style="width:16px;height:16px;"></a>&nbsp;
<a target="_blank" href="<?php echo $ecom_selfhttp?>del.icio.us/post?url=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Bookmark with Del.icio.us"><img src="<? url_site_image('delicious.gif');?>" alt="Bookmark with Del.icio.us" border="0" style="width:16px;height:16px;"></a>&nbsp;<a target="_blank" href="<?php echo $ecom_selfhttp?>digg.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Digg This!"><img src="<? url_site_image('digg.gif');?>" alt="Digg This!" border="0" style="width:18px;height:16px;"></a>&nbsp;<a target="_blank" href="<?php echo $ecom_selfhttp?>reddit.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Post to Reddit"><img src="<? url_site_image('reddit.gif');?>" alt="Post to Reddit" border="0" style="width:17px;height:16px"></a>&nbsp;<a target="_blank" href="<?php echo $ecom_selfhttp?>www.facebook.com/sharer.php?u=<?php echo $url; ?>" title="Share on Facebook"><img src="<? url_site_image('facebook.gif');?>" alt="Share on Facebook" border="0" style="width:16px;height:16px;"></a>&nbsp;<a target="_blank" href="<?php echo $ecom_selfhttp?>www.stumbleupon.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Post to StumbleUpon"><img src="<? url_site_image('stumbleupon.gif');?>" alt="Post to StumbleUpon" border="0" style="width:16px;height:16px"></a><?php /*&nbsp;<a target="_blank" href="http://www.kaboodle.com/za/additem?get=1&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Post to Kaboodle"><img src="<? url_site_image('kaboodle.gif');?>" alt="Post to Kaboodle" border="0" style="width:16px;height:16px;"></a> &nbsp;<a target="_blank" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<?php echo $title;?>&amp;u=<?php echo $url; ?>" title="Bookmark with Yahoo"><img src="<? url_site_image('yahoo.gif');?>" alt="Bookmark with Yahoo" border="0" style="width:16px;height:16px"></a> */?>&nbsp;<a target="_blank" href="<?php echo $ecom_selfhttp?>www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php echo $url; ?>&amp;title=<?php echo $title;?>" title="Bookmark with Google"><img src="<? url_site_image('google.gif');?>" alt="Bookmark with Google" border="0" style="width:16px;height:16px"></a>
<?php
if($HTML_image!='')
{
?>
<a href="<?php echo $ecom_selfhttp?>pinterest.com/pin/create/button/?url=<?php echo urlencode($url);?>&media=<?php echo $HTML_image?>&description=<?php echo $title?>" class="pin-it-button" count-layout="horizontal" target="_blank"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" style="width:43px;height:21px"/></a>
<?php
}
	$content = ob_get_contents();
	ob_end_clean();
if ($ret==-1)
	echo $content;
else
	return $content;
}
if($HTML_image!='')
{
?>
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<?php
}
?>

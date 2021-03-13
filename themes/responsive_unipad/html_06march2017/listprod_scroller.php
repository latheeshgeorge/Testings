
<script type="text/javascript">
var myInterval;
var first = 0;
var current = 0;
var curobj;
var curimgarrs;
$( "<?php echo $listscroll_obj?>" ).mouseenter(function() {
	curobj = this;
	$(curobj).css('border','solid 1px #FCC448');
	 imgtick_unipad();
	});	

/*$(".product-pic img").on("tap",function(){
	curobj = this;
	 imgtick_unipad();
})*/
	
$( "<?php echo $listscroll_obj?>" ).mouseleave(function() {
	$(curobj).attr('src',curimgarrs[0]);
	$(curobj).css('border','0px');
	current = 0;
	clearInterval(myInterval);
});		

function imgtick_unipad()
{
	if($(curobj).attr('data-immore')!='')
	{
		curimgarrs = $(curobj).attr('data-immore').split(',');
		clearInterval(myInterval);
		if(curimgarrs.length)
		{
			myInterval = setInterval(function () {
				
				current=current+1;
				if(current>=curimgarrs.length)
				{
					current = 0;
				}
				$(curobj).animate({opacity:.6}).attr('src',curimgarrs[current]).animate({opacity:1});
				
			},<?php echo $listscroll_delay?>); 
		}
	}
}


function handletap(url,id)
{
	curobj = $('img[data-imageid="'+id+'"]');
	/*if($(curobj).attr('data-tapped')==0 && ($(window).width()<1150))
	{
		$(".listscrollimg").attr('data-tapped',0);
		$(".listscrollimg").css('border','0');
		clearInterval(myInterval);
		$(curobj).attr('data-tapped',1);
		$(curobj).css('border','solid 1px #FFE623');
		imgtick_unipad();
	}
	else
	{*/
		window.location = url;
		return true;
	/*}*/
	
}

</script>

$('#nav').live({
	mouseover: function(){
		// Stick the #nav to the top of the window
		var nav = $('#nav');
		var navHomeY = nav.offset().top;
		var isFixed = false;
		var $w = $(window);
		$w.scroll(
				  function() {
						var scrollTop = $w.scrollTop();
						var shouldBeFixed = scrollTop > navHomeY;
						if (shouldBeFixed && !isFixed)
						{
							nav.css({
								position: 'fixed',
								top: 0,
								left: nav.offset().left,
								width: nav.width(),
								background:'#FC0'
							});
							isFixed = true;
						}
						else if (!shouldBeFixed && isFixed)
						{
							nav.css({
								position: 'static',
								background:'#FBFBFB'
							});
							isFixed = false;
						}
					}
				);
		}
});
/*$( document ).delegate('#nav','click', function(){
	// Stick the #nav to the top of the window
	var nav = $('#nav');
	var navHomeY = nav.offset().top;
	var isFixed = false;
	var $w = $(window);
	$w.scroll(
			  function() {
					var scrollTop = $w.scrollTop();
					var shouldBeFixed = scrollTop > navHomeY;
					if (shouldBeFixed && !isFixed)
					{
						nav.css({
							position: 'fixed',
							top: 0,
							left: nav.offset().left,
							width: nav.width()
						});
						isFixed = true;
					}
					else if (!shouldBeFixed && isFixed)
					{
						nav.css({
							position: 'static'
						});
						isFixed = false;
					}
				}
			);
	});*/
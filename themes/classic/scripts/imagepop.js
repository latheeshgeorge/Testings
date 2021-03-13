			function LoadTmpHolder()	{
			
				var flashUrl = 'http://video.asos.com/CatwalkShots/353426-4-080708025057.flv';
			    firstTime360 = firstTime360 && (flashUrl.indexOf('.swf') > -1) && (flashUrl.indexOf('blank') < 0)
			    if (!firstTime360)
				{
			        HideZoomImage();
			    }else{
				    firstTime360 = false;
			    }
			    
				/*ar	i
				for (i=0; i < arrMainImage.length; i++) 
				{
							tmpHolder[0]	=	arrMainImage[i];
				}*/
				var newi = 0;
				tmpHolder[0]	=	arrMainImage[0];	
				/*tmpHolderBig[0]	=	arrMainImage[0];	*/
				if (arrThumbImage.length>0)
				{
					for (i=0;i<arrThumbImage.length;i++)
					{
						if	(arrThumbImage[0] != null)
						{
							newi = i+1;	
							tmpHolder[newi] 		= arrThumbImage[i];
							/*tmpHolderBig[newi] 	= arrThumbImage[i];*/
						};
					}
				}
				/*if	(arrThumbImage[0] != null)	{
					tmpHolder[1] = arrThumbImage[0];
					};
				if	(arrThumbImage[1] != null)	{
					tmpHolder[2] = arrThumbImage[1];
					};
				if	(arrThumbImage[2] != null)	{
					tmpHolder[3] = arrThumbImage[2];
					};
				*/	
			}

			function ReplaceImageFromThumb(imgThumbIndex) {
				/*var	tmp11 = tmpHolderBig[imgThumbIndex];
				var tmp22 = tmpHolderBig[0];*/
				var	tmp1 = tmpHolder[imgThumbIndex];
				var tmp2 = tmpHolder[0];
				
				tmpHolder[0] = tmp1;
				tmpHolder[imgThumbIndex] = tmp2;	
				/*tmpHolderBig[0] = tmp11;
				tmpHolderBig[imgThumbIndex] = tmp22;	*/
				
				HideZoomImage();
				PopulateImages();
			}
			
			function PopulateImages(){
				var newi = 0;
				document.getElementById('imgMainImageZoom').src = '';
				if (document.getElementById('imgMainImage') != null)	{
					if	(tmpHolder[0] != null)	{
						document.getElementById('imgMainImage').src = tmpHolder[0][1];
						};
					for (i=1;i<tmpHolder.length;i++)
					{
						if	(tmpHolder[i] != null)
						{
							newi = i+1;
							obj = eval("document.getElementById('imgThumb"+newi+"')");	
							obj.src = tmpHolder[i][0];
							obj = eval("document.getElementById('imgbig"+newi+"')");	
							if (obj)
								obj.src = tmpHolderBig[i][3];
						}
					}
					
					/*if	(tmpHolder[1] != null)	{
						document.getElementById('imgThumb2').src = tmpHolder[1][0];
						};
					if	(tmpHolder[2] != null)	{
						document.getElementById('imgThumb3').src = tmpHolder[2][0];
						};
					if	(tmpHolder[3] != null)	{
						document.getElementById('imgThumb4').src = tmpHolder[3][0];
						};
					*/	
				};
			}			
			
			function ShowZoomImage()	{
				if	(tmpHolder[0] != null)	{
					oZoomImage = new Image();
					oZoomImage.src = tmpHolder[0][2];					
										
					if (document.getElementById) { // DOM 3: IE5+, NS6, Firefox#
					
						if (document.getElementById('img_blank'))
							document.getElementById('img_blank').style.display = 'none';
						if (document.getElementById('blank_zoom'))
							document.getElementById('blank_zoom').style.display = '';	
						if (document.getElementById('blank_zoom'))
							document.getElementById('blank_zoom').style.display = 'none';	
							
						document.getElementById("imgMainImage").style.display = "none";
						document.getElementById("dvMainImageZoom").style.display = "block";
						
						document.getElementById("hypZoomPlus").style.display = "none";
						document.getElementById("hypZoomMinus").style.display = "block";
						
						if (document.getElementById("hypVideoImage") != null) {document.getElementById("hypVideoImage").style.display = "none"};
						if (document.getElementById("hypDragImage") != null) {document.getElementById("hypDragImage").style.display = "block"};
						
					} else if (document.layers) { // Netscape 4
					
						document.imgMainImage.style.display = "none";
						document.dvMainImageZoom.style.display = "block";
						
						document.hypZoomPlus.style.display = "none";
						document.hypZoomMinus.style.display = "block";
						
						if (document.hypVideoImage != null) {document.hypVideoImage.style.display = "none"};
						if (document.hypDragImage != null) {document.hypDragImage.style.display = "block"};
						
					} else if (document.all) { // IE 4
					
						document.all.imgMainImage.style.display = "none";
						document.all.dvMainImageZoom.style.display = "block";
						
						document.all.hypZoomPlus.style.display = "none";
						document.all.hypZoomMinus.style.display = "block";
						
						if (document.all.hypVideoImage != null ){document.all.hypVideoImage.style.display = "none"};
						if (document.all.hypDragImage != null ){document.all.hypDragImage.style.display = "block"};						
					}
				}
				
				if (!oZoomImage.complete) {
				    document.getElementById('dvMainImageZoom').innerHTML = document.getElementById('content_product_loading' ).innerHTML;
					tZoomLoaded = setInterval(checkZoomLoaded,'500');
				} else {
					checkZoomLoaded();
				}
			}
				
			function checkZoomLoaded(){
				if (oZoomImage.complete){
					document.getElementById('dvMainImageZoom').innerHTML = '<img id="imgMainImageZoom" src="' + oZoomImage.src + '"/>';
					clearTimeout(tZoomLoaded);		
					enableDrag('dvMainImageZoom', 'imgMainImageZoom', '', '');			
				}
			}
			
			function HideZoomImage()	{
				if (document.getElementById) { // DOM 3: IE5+, NS6, Firefox#
					if (document.getElementById('img_blank'))
							document.getElementById('img_blank').style.display = '';
					if (document.getElementById("imgMainImage") != null) {document.getElementById("imgMainImage").style.display = "block";};
					document.getElementById("dvMainImageZoom").style.display = "none";
					if (document.getElementById("hypZoomPlus") != null) {document.getElementById("hypZoomPlus").style.display = "block";};
					if (document.getElementById("hypZoomMinus") != null) {document.getElementById("hypZoomMinus").style.display = "none";};
					if (document.getElementById("hypVideoImage") != null) {document.getElementById("hypVideoImage").style.display = "block";}; 
					if (document.getElementById("hypDragImage") != null) {document.getElementById("hypDragImage").style.display = "none";};
				} else if (document.layers) { // Netscape 4
					if (document.imgMainImage != null) {document.imgMainImage.style.display = "block";};
					document.dvMainImageZoom.style.display = "none";
					if (document.hypZoomPlus != null) {document.hypZoomPlus.style.display = "block";};
					if (document.hypZoomMinus != null) {document.hypZoomMinus.style.display = "none";};
					if (document.hypVideoImage != null) {document.hypVideoImage.style.display = "block";};
					if (document.hypDragImage != null) {document.hypDragImage.style.display = "none";};
				} else if (document.all) { // IE 4
					if (document.all.imgMainImage != null) {document.all.imgMainImage.style.display = "block";};
					document.all.dvMainImageZoom.style.display = "none";
					if (document.all.hypZoomPlus != null) {document.all.hypZoomPlus.style.display = "block";};
					if (document.all.hypZoomMinus != null) {document.all.hypZoomMinus.style.display = "none";};
					if (document.all.hypVideoImage != null) {document.all.hypVideoImage.style.display = "block";}; 
					if (document.all.hypDragImage != null) {document.all.hypDragImage.style.display = "none";};
				}

				        HideVideo();
			}

									
			function ShowVideo() {
					    
                HideZoomImage();
				if (document.getElementById('blank_zoom'))
				{
					document.getElementById('blank_zoom').style.display = '';	
				}
            	if (document.getElementById) { // DOM 3: IE5+, NS6, Firefox#
					if (document.getElementById("pnlMainImage") != null) {document.getElementById("pnlMainImage").style.display = "none";};					
					if (document.getElementById("hypVideoImage") != null) {document.getElementById("hypVideoImage").style.display = "none"};
					/*if (document.getElementById("pnlFlashObject") != null) {document.getElementById("pnlFlashObject").style.display = "block"};*/
					if (document.getElementById("hypPhotoImage") != null) {document.getElementById("hypPhotoImage").style.display = "block"};
					if (document.getElementById("divFlash") != null) {document.getElementById("divFlash").style.display = "block"};
				} else if (document.layers) { // Netscape 4
					if (document.pnlMainImage != null) {document.pnlMainImage.style.display = "none";};
					if (document.hypVideoImage != null) {document.hypVideoImage.style.display = "none"};
				/*	if (document.pnlFlashObject != null) {document.pnlFlashObject.style.display = "block"};*/
					if (document.hypPhotoImage != null) {document.hypPhotoImage.style.display = "block"};
					if (document.divFlash != null) {document.divFlash.style.display = "block"};
				} else if (document.all) { // IE 4
					if (document.all.pnlMainImage != null) {document.all.pnlMainImage.style.display = "none";};
					if (document.all.hypVideoImage != null) {document.all.hypVideoImage.style.display = "none"};
					/*if (document.all.pnlFlashObject != null) {document.all.pnlFlashObject.style.display = "block"};*/
					if (document.all.hypPhotoImage != null) {document.all.hypPhotoImage.style.display = "block"};
					if (document.all.divFlash != null) {document.all.divFlash.style.display = "block"};
				}
			}

			function HideVideo() {
				if (document.getElementById) { // DOM 3: IE5+, NS6, Firefox
					if (document.getElementById('blank_zoom'))
					{
						document.getElementById('blank_zoom').style.display = 'none';	
					}
					if (document.getElementById("pnlMainImage") != null) {document.getElementById("pnlMainImage").style.display = "block";};
					if (document.getElementById("hypVideoImage") != null) {document.getElementById("hypVideoImage").style.display = "block";};
					/*if (document.getElementById("pnlFlashObject") != null) {document.getElementById("pnlFlashObject").style.display = "none";};*/
					if (document.getElementById("hypPhotoImage") != null) {document.getElementById("hypPhotoImage").style.display = "none";};
					if (document.getElementById("divFlash") != null) {document.getElementById("divFlash").style.display = "none";};
				} else if (document.layers) { // Netscape 4
					if (document.pnlMainImage != null) {ddocument.pnlMainImage.style.display = "block";};
					if (document.hypVideoImage != null) {ddocument.hypVideoImage.style.display = "block";};
					/*if (document.pnlFlashObject != null) {ddocument.pnlFlashObject.style.display = "none";};*/
					if (document.hypPhotoImage != null) {ddocument.hypPhotoImage.style.display = "none";};
					if (document.divFlash != null) {document.divFlash.style.display = "none";};
				} else if (document.all) { // IE 4
					if (document.all.pnlMainImage != null) {document.all.pnlMainImage.style.display = "block";};
					if (document.all.hypVideoImage != null) {document.all.hypVideoImage.style.display = "block";};
				/*	if (document.all.pnlFlashObject != null) {document.all.pnlFlashObject.style.display = "none";};*/
					if (document.all.hypPhotoImage != null) {document.all.hypPhotoImage.style.display = "none";};
					if (document.all.divFlash != null) {document.all.divFlash.style.display = "none";};
				}
				// loadFlash('/Video/blank.swf');
			}
			
			function loadFlash(flashUrl) {
				var flashMovie, so;
				
				if (!flashUrl) {
					flashUrl = 'http://video.asos.com/CatwalkShots/353426-4-080708025057.flv';
				}
				if (document.getElementById("pnlFlashObject") != null)
				{
				    if (flashUrl.indexOf('.flv') > -1) {
					    flashUrl = 'images/w.swf';					
					    swfobject.embedSWF(flashUrl, "pnlFlashObject","100%", "100%", "8.0.0", "http://www.asos.com/images/htmlpages/flash_install_data/expressinstall_w.swf");					
				    } else {
				        if (hasRequestedVersion) {				    
				            swfobject.embedSWF(flashUrl, "pnlFlashObject","100%", "100%", "8.0.0", "http://www.asos.com/images/htmlpages/flash_install_data/expressinstall_w.swf",{},{},{});
				        }
				    }
				}
			}
			
			
//New Window PopUp
function SmallWin(page) {
	NewLeft = (screen.width - 630) / 2;
	NewTop = (screen.height - 470) / 2;
	window.open(page,"","status=no,scrollbars=yes,resizable=no,width=630,height=570,top="+ NewTop +",left=" + NewLeft);
}

function AdjustablePopup(page,resizable,scrollbars,width,height) {
	NewLeft = (screen.width - width) / 2;
	NewTop = (screen.height - height) / 2;
	window.open(page,"","status=no,scrollbars=" + scrollbars + ",resizable=" + resizable + ",width=" + width + ",height=" + height + ",top="+ NewTop +",left=" + NewLeft);
}

var mywindow;
function spawnwindow(url, name, attributes)
{
	mywindow=window.open(url,name,attributes);
	if (window.focus) {mywindow.focus()}
}
//End

var testemail
function checkemail(emailtotest){
var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
if (filter.test(emailtotest))
	testemail=true;
else{
	testemail=false;
}
return (testemail);
}

//Drag Image Start
var dragObject  = null;
var mouseOffset = null;
var dragContainer = null;

function getCanvasWidth() { 
   return document.body.offsetWidth || window.innerWidth; 
} 

function getCanvasHeight() { 
   return document.body.offsetHeight || window.innerHeight; 
} 

function makeContainer(item){
	dragContainer = item;
	dragContainer.style.position = 'relative';
	dragContainer.style.overflow = 'hidden';
}

function getMouseOffset(target, ev){
	ev = ev || window.event;

	var docPos    = getPosition(target);
	var mousePos  = mouseCoords(ev);

	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function getPosition(obj){
	var left = 0;
	var	top = 0;
	
	if (obj.offsetParent) {

		left += obj.offsetLeft ;
		top += obj.offsetTop;		
		
		while (obj = obj.offsetParent) {
			if (parseInt(obj.style.left)) {
				left -= parseInt(obj.style.left);
				top -= parseInt(obj.style.top);
			}
		}
	}
	return {x:left, y:top};
}

function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	return {
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	};
}

function mouseMove(ev){
	ev           = ev || window.event;
	var mousePos = mouseCoords(ev);
	var targWidth, targHeight, targPos;
	var browseWidth, browseHeight;

	browseWidth		=	getCanvasWidth();
	browseHeight	=	getCanvasHeight();

	if(dragObject){	
		if (dragContainer) {
			targWidth  = parseInt(dragContainer.offsetWidth);
			targHeight = parseInt(dragContainer.offsetHeight);
			if (((mousePos.y - mouseOffset.y) < 0) && ((mousePos.y - mouseOffset.y + dragObject.height) > (targHeight)))	{dragObject.style.top	= (mousePos.y - mouseOffset.y) + 'px';}
			if (((mousePos.x - mouseOffset.x) < 0) && ((mousePos.x - mouseOffset.x + dragObject.width) > (targWidth)))	{dragObject.style.left	= (mousePos.x - mouseOffset.x) + 'px';}
		} else {
			dragObject = null;
		}
		return false;
	}
}

function mouseUp(){
		dragObject = null;
}

function makeDraggable(item){
	if(!item) return;
	try {item.style.cursor = 'pointer';} catch (e) {} //cursor property breaks IE5.5
	item.onmousedown = function(ev){		
		dragObject  = this;
		dragObject.style.position = 'absolute';
		mouseOffset = getMouseOffset(this, ev);
		return false;
	}
}

function enableDrag(spContainer, imgDrag, imgWidth, imgHeight) {

	var dragItem = null;
	var dragCont = null;
	var contW, contH = 0;
	var top, left;
	
	document.onmousemove = mouseMove;
	document.onmouseup   = mouseUp;
	
	dragCont = document.getElementById(spContainer);
	makeContainer(dragCont);

	dragItem = document.getElementById(imgDrag);
	makeDraggable(dragItem);
	dragItem.style.position = 'absolute';
	
	dragItem.style.height = imgHeight + 'px'; dragItem.style.width = imgWidth + 'px';
	
	dragItem.style.top = 0; dragItem.style.left = 0;
	dragItem.top = 0; dragItem.left = 0;
		
	top = -((parseInt(dragItem.style.height)/2) - (parseInt(dragCont.style.height)/2));
	left = -((parseInt(dragItem.style.width)/2) - (parseInt(dragCont.style.width)/2));
	
	dragItem.style.top = top +'px';
	dragItem.style.left = left+'px';
	
	dragItem.top = dragItem.style.top;
	dragItem.left = dragItem.style.left;
	
	dragItem.alt = 'Click and hold to drag image';	
}
//Drag Image End
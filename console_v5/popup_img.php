<HTML>
<HEAD>
 <TITLE>Full Image</TITLE>
 <script language='javascript'>
   var arrTemp=self.location.href.split("?");
   var picUrl = (arrTemp.length>0)?arrTemp[1]:"";
   var NS = (navigator.appName=="Netscape")?true:false;

     function FitPic() {
       iWidth = (NS)?window.innerWidth:document.body.clientWidth;
       iHeight = (NS)?window.innerHeight:document.body.clientHeight;
       iWidth = document.images[0].width - iWidth + 16;
       iHeight = document.images[0].height - iHeight + 15;
       window.resizeBy(iWidth, iHeight);
       self.focus();
     };
 </script>
</HEAD>
<BODY bgcolor="#000000" onload='FitPic();' topmargin="0"  
marginheight="0" leftmargin="0" marginwidth="0">
 <script language='javascript'>
 document.write( "<img src='" + picUrl + "' border=0>" );
 </script>
</BODY>
</HTML>
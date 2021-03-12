var data,nhp,ntz,rf,sr;
document.cookie='_support_check = 1';
nhp='http';
rf=document.referrer;
ntz=new Date();
if((location.href.substr(0,6)=='https:') || (location.href.substr(0,6)=='HTTPS:'))
  nhp='https'; 
data = '&cl='+document.cookie.length+
       '&rf='+escape(rf)+ 
       '&sl='+escape(navigator.systemLanguage)+
       '&pf='+escape(navigator.platform)+ 
       '&pg='+escape(location.pathname);
if(navigator.appVersion.substring(0,1)>'3') 
{
  data = data + '&cd='+screen.colorDepth+
                '&rs='+escape(screen.width+' x '+screen.height)+
                '&tz='+ntz.getTimezoneOffset()+
                '&je='+navigator.javaEnabled()
};

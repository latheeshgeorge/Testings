<?php
/*$sql_theme_name = "SELECT themename FROM themes WHERE theme_id=$ecom_themeid";
$res_theme_name = $db->query($sql_theme_name);
$row_theme_name = $db->fetch_array($res_theme_name);*/

if(check_IndividualSslActive())
{
	$http = "https://";
}
else
{
	$http = "http://";
}

if(file_exists(ORG_DOCROOT."/images/$ecom_hostname/css/editor.css")) {

	$css_filename = $http."$ecom_hostname/images/$ecom_hostname/css/editor.css";
} else {
	$css_filename = $http."$ecom_hostname/console_v5/css/editor.css";
}
?>
<script type="text/javascript" src="<?php echo $http;?><?=$ecom_hostname?>/console_v5/js/tinymcenew/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	
	// General options
	mode : "exact",
	elements : "<?php echo $editor_elements; ?>",
	theme : "advanced",
	skin : "o2k7",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	//plugins : "pagebreak,style,layer,table,advhr,advimage,advlink,inlinepopups,media,contextmenu,paste,directionality,fullscreen,nonbreaking,imagemanager,filemanager",
	gecko_spellcheck : true,

	// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,insertimage",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		
	convert_urls : false,
	relative_urls : false,
	
	document_base_url: "<?php echo $http.$ecom_hostname?>/",
	theme_advanced_resizing : true,
		
	
	//cleanup : false,
	content_css : "<?=$css_filename?>",
	
	extended_valid_elements : "*[*]",//"iframe[src|width|height|name|align|frameborder|scrolling|style|allowTransparency],form[action|method|name|onclick|onsubmit],input[name|type|value|id|class|style|src|onclick|onblur|readonly],select[id|style|name],option[value],textarea[cols|rows|disabled|name|readonly|class],span[id|color|class|style],g:plusone[size],g:plus[href|size]",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js"
	
});
</script>

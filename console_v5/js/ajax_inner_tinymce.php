<script language="javascript" type="text/javascript">
tinyMCE.init({
	// General options
	mode : "exact",
	elements : "<?php echo $editor_elements; ?>",
	theme : "advanced",
	skin : "o2k7",
	plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	//plugins : "pagebreak,style,layer,table,advhr,advimage,advlink,inlinepopups,media,contextmenu,paste,directionality,fullscreen,nonbreaking,imagemanager,filemanager",

	// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,insertimage",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
	convert_urls : false,
	//cleanup : false,
	content_css : "<?=$css_filename?>",
	
	extended_valid_elements : "iframe[src|width|height|name|align|frameborder],form[action|method|name|onclick|onsubmit],input[name|type|value|id|class|style],select[id|style|name],option[value],textarea[cols|rows|disabled|name|readonly|class]",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js"
	
});
</script>
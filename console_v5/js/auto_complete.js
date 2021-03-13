// JavaScript Document
function auto_search(fieldVal,tableVal)
{
	var fieldID	=	'#'+fieldVal;
	//alert(fieldID);
	var $acnc = jQuery.noConflict();
	$acnc(fieldID).autocomplete("../includes/autocomplete_key_search.php?fieldName="+fieldVal+"&tableName="+tableVal, {
		width: 220,
		matchContains: true,
		selectFirst: false
	});
}
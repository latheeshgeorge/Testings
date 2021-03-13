<?php
/*#################################################################
# Script Name 	: manage_componentpositions.php
# Description 	: Page for managing component positions
# Coded by 		: Sny
# Created on	: 25-Jul-2007
# Modified by	: Sny
# Modified On	: 26-Jul-2007
#################################################################*/

/*Define constants for this page*/
$page_type      = 'Component Titles';
$help_msg       = get_help_messages('MAN_COMPO_POS_MESS1');
$table_headers  = array('Slno.','Component Name','Shown in site as','Position','Ordering');
$colspan        = count($table_headers);
if($ecom_mobilethemeid > 0)
{
// Get the component settings
$sql_comp_pos = "SELECT features_feature_modulename, themes_mapping_fields,direct_entry   
                    FROM 
                        themes_layouts_feature_special_position_components";
$ret_comp_pos = $db->query($sql_comp_pos);
if($db->num_rows($ret_comp_pos))
{
    while($row_comp_pos = $db->fetch_array($ret_comp_pos))
    {
        $special_comp[$row_comp_pos['features_feature_modulename']] = array('map_field'=>$row_comp_pos['themes_mapping_fields'],'direct_entry'=>$row_comp_pos['direct_entry']);
    }
}
if(!$_REQUEST['layoutcode'])
{
	if($_REQUEST['cbo_sellayout'])
		$layoutcode = $_REQUEST['cbo_sellayout'];
	else
	{
		// Get the code of first layout for the theme
		$sql_lay = "SELECT layout_code FROM themes_layouts WHERE themes_theme_id = $ecom_mobilethemeid  ORDER BY layout_order LIMIT 1";
		$ret_lay = $db->query($sql_lay);
		if ($db->num_rows($ret_lay))
		{
			$row_lay = $db->fetch_array($ret_lay);
			$layoutcode = stripslashes($row_lay['layout_code']);
		}
	}	
}
else
	$layoutcode = $_REQUEST['layoutcode'];
$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE themes_theme_id=$ecom_mobilethemeid AND layout_code='$layoutcode'";
$ret_layouts = $db->query($sql_layouts);

function show_module_name_old($curmodulename,$dispfeat_arr,$featureid,$featurename)
{
    $link_req = 0;
    if(array_key_exists($curmodulename,$dispfeat_arr))
    {
        $link_req   = 1;
        $url        = $dispfeat_arr[$curmodulename]['url'];
        if($dispfeat_arr[$curmodulename]['mode']=='edit')
        {
            $url = str_replace('[rep_id]',$featureid,$url);
        }
        return '<a href="javascript:void(0)" onDblClick="window.location=\''.$url.'\'" class="complinks">'.$featurename.'</a>';
    } 
    else
        return $featurename;
}
 function show_module_name($curmodulename,$dispfeat_arr,$featureid,$featurename)
{
    $link_req = 0;
    if(array_key_exists($curmodulename,$dispfeat_arr))
    {
        $link_req   = 1;
        $url        = $dispfeat_arr[$curmodulename]['url'];
        if($dispfeat_arr[$curmodulename]['mode']=='edit')
        {
            $url = str_replace('[rep_id]',$featureid,$url);
        }
        return 'onDblclick="window.location=\''.$url.'\'";';
    } 
    else
        return '';
}
// Get the name of the theme for the site
$sql_theme = "SELECT themename,themes_support_allowed_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
$ret_theme = $db->query($sql_theme);
if ($db->num_rows($ret_theme))
{
	$row_theme = $db->fetch_array($ret_theme);
	$ecom_themename = $row_theme['themename'];
        $ecom_themes_support_allowed_positions = $row_theme['themes_support_allowed_positions'];
}
if ($db->num_rows($ret_layouts))
{
	$row_layouts	= $db->fetch_array($ret_layouts);
	$pos_arr		= explode(",",$row_layouts['layout_positions']);
	for($i=-1;$i<count($pos_arr);$i++)
	{
		if($i==-1)
			$drag_arr[] = "document.getElementById('sitemenu')";
		else
			$drag_arr[] = "document.getElementById('".$pos_arr[$i]."')";
	}
	$drag_str = implode(",",$drag_arr);
        
        // Get the set positions of all components in current layout
        $sql_layout_pos = "SELECT features_feature_id,allow_positions,features_feature_modulename  
                            FROM 
                                themes_layouts_feature_allowed_positions 
                            WHERE 
                                themes_layouts_layout_id=".$row_layouts['layout_id'];
        $ret_layout_pos = $db->query($sql_layout_pos);
        if($db->num_rows($ret_layout_pos))
        {
            while ($row_layout_pos = $db->fetch_array($ret_layout_pos))
            {
                $default_layout_pos[$row_layout_pos['features_feature_id']] = array('positions'=>$row_layout_pos['allow_positions'],'module'=>$row_layout_pos['features_feature_modulename']);
            }
        }
}
function show_legends()
{
    global $ecom_themes_support_allowed_positions;
    if($ecom_themes_support_allowed_positions==1)
    {
?>
        <table width='75%' cellpadding='0' cellspacing='0' border='0' align='right'>
        <tr>
        <td><div class='DragContainer_normal_demo' style='border:1px solid #000000; width:20px;'>&nbsp;A</div></td>
        <td class='pos_header' align='left'> <= Newly Assigned</td>
        <td><div class='DragContainer_green_demo' style='border:1px solid #000000; width:20px;'>&nbsp;A</div></td>
        <td class='pos_header'><= Acceptable at position</td>
        <td><div class='DragContainer_orange_demo' style='border:1px solid #000000; width:20px;'>&nbsp;A</div></td>
        <td class='pos_header'><= Acceptable at position but "Show in all" option is not ticked</td>
        <td><div class='DragContainer_red_demo' style='border:1px solid #000000; width:20px;'>&nbsp;A</div></td>
        <td class='pos_header'><= Not acceptable at position</td>
        </tr>
        </table>
<?php
    }
    else
        echo '&nbsp;';
}
// Building the query by combining the site_menu and features tables
$sql = "SELECT a.menu_id,b.feature_name,b.feature_ordering,b.feature_modulename,b.feature_title FROM  
		site_menu a, features b WHERE a.sites_site_id=".$ecom_siteid." AND b.feature_displaytouser=1 AND b.feature_allowedit=1 AND 
		a.features_feature_id=b.feature_id ORDER BY b.feature_ordering";
$ret = $db->query($sql);

// Building array to be used to determine the features for which the links to be displayed
$dispfeat_arr['mod_staticgroup']        = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=stat_group&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );
$dispfeat_arr['mod_combo']              = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=combo&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );                                                
$dispfeat_arr['mod_featured']           = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=featured'
                                                );                                 
$dispfeat_arr['mod_shelf']              = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=shelfs&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );
$dispfeat_arr['mod_adverts']            = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=adverts&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );
$dispfeat_arr['mod_survey']             = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=survey&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );                                                
$dispfeat_arr['mod_bestsellers']        = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=bestseller'
                                                );
$dispfeat_arr['mod_productcatgroup']    = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=[rep_id]'
                                                ); 
$dispfeat_arr['mod_shopbybrandgroup']    = array  (
                                                'mode' => 'edit',
                                                'url'  => 'home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=[rep_id]'
                                                );
$dispfeat_arr['mod_site_reviews']        = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=site_reviews'
                                                );
$dispfeat_arr['mod_giftvoucher']        = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=gift_voucher'
                                                ); 
$dispfeat_arr['mod_newsletter']        = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=newsletter'
                                                );                                                
$dispfeat_arr['mod_callback']            = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=callback'
                                                );
$dispfeat_arr['mod_preorder']            = array  (
                                                'mode' => 'direct',
                                                'url'  => 'home.php?request=preorder'
                                                );                                                
function remove_component($parentId,$myId,$Msg)
{
    echo "<a href=\"javascript:remove_me('".$parentId."','".$myId."','".$Msg."')\"><img src='images/delete_comp.gif'  alt='Click to remove from this position' border='0' title='Click to remove from this position'/></a>";
}
function name_indicator($sp,$showname)
{
    if($sp!='')
        return '<span class="spancls">'.$showname.'</span>';
    else
        return $showname;
}
function get_display_classes($component_id,$feature_id,$cur_position)
{
    global $special_comp,$db,$ecom_siteid,$default_layout_pos,$ecom_themes_support_allowed_positions;
    $module_name = $default_layout_pos[$feature_id]['module'];
    if($ecom_themes_support_allowed_positions==1)
    {
        if(array_key_exists($module_name,$special_comp)) // case of special modules defined in table themes_layouts_feature_special_position_components
        {
            if($default_layout_pos[$feature_id]!='')
            {
                // exploding to get the values of positions
                $temp_arr = explode('~',$default_layout_pos[$feature_id]['positions']);
                foreach ($temp_arr as $k=>$v)
                {
                    $pos_arr = explode('=>',$v);
                    $tmp = explode(',',$pos_arr[1]);
                    $pos_array[$pos_arr[0]] = $tmp;
                }
                $check_type = '';
                // building query to pick up the current style and also the showinall status
                switch($default_layout_pos[$feature_id]['module'])
                {
                    case 'mod_shelf':
                        // Get the value of shelf_currentstyle from product_shelf table
                        $sql_qry = "SELECT shelf_currentstyle as checkval,shelf_showinall as showall  
                                        FROM 
                                            product_shelf 
                                        WHERE 
                                            shelf_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'both';
                    break;
                    case 'mod_productcatgroup':
                        $sql_qry = "SELECT catgroup_listtype as checkval,catgroup_showinall as showall 
                                        FROM 
                                            product_categorygroup  
                                        WHERE 
                                            catgroup_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'both';
                    break;
                    case 'mod_shopbybrandgroup':
                        $sql_qry = "SELECT shopbrandgroup_listtype as checkval,shopbrandgroup_showinall as showall  
                                        FROM 
                                            product_shopbybrand_group   
                                        WHERE 
                                            shopbrandgroup_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'both';
                    break;
                };
                if($sql_qry!='') // case if entered in any of the above switch cases
                {
                    $ret_qry = $db->query($sql_qry);
                    if($db->num_rows($ret_qry))
                    {
                        $row_qry = $db->fetch_array($ret_qry);
                        if($check_type=='both') // case if both position and also the show in all option is to be checked
                        {
                            if (is_array($pos_array[$row_qry['checkval']]))
                            {
                                if(in_array($cur_position,$pos_array[$row_qry['checkval']]))
                                {
                                    if($row_qry['showall']==1)
                                        $class_typ = 'green';
                                    else
                                        $class_typ = 'orange';
                                }    
                                else
                                    $class_typ = 'red';
                            }
                            else
                                    $class_typ = 'red';
                        }
                        elseif($check_type=='showonly')  // case if only show in all option only is to be checked
                        {
                            if($row_qry['showall']==1)
                                $class_typ = 'green';
                            else
                                $class_typ = 'orange';
                        }
                    }
                    else // case if entry not found in database
                        $class_typ ='red';
                }
                else // case if not entered in any of the above switch cases 
                    $class_typ ='red';
            }
            else
                $class_typ = 'red';
        }
        else // case if module name is not there in table themes_layouts_feature_special_position_components
        {
            if($default_layout_pos[$feature_id]!='')
            {
               // exploding to get the value of positions
                $cur_check_arr = explode(',',$default_layout_pos[$feature_id]['positions']);
                // Building query to check for show in all cases
                $check_type = '';
                switch($default_layout_pos[$feature_id]['module'])
                {
                    case 'mod_staticgroup':
                        $sql_qry = "SELECT group_showinall as showall  
                                        FROM 
                                            static_pagegroup    
                                        WHERE 
                                            group_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'showonly';
                    break;
                    case 'mod_combo':
                        $sql_qry = "SELECT combo_showinall as showall  
                                        FROM 
                                            combo     
                                        WHERE 
                                            combo_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'showonly';
                    break;
                    case 'mod_adverts':
                        $sql_qry = "SELECT advert_showinall as showall  
                                        FROM 
                                            adverts      
                                        WHERE 
                                            advert_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'showonly';
                    break;
                    case 'mod_survey':
                        $sql_qry = "SELECT survey_showinall as showall  
                                        FROM 
                                            survey      
                                        WHERE 
                                            survey_id = $component_id 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $check_type = 'showonly';
                    break;
                };
                if($sql_qry!='')   // case if coming after entering any of the above switch cases 
                {
                    $ret_qry = $db->query($sql_qry);
                    if($db->num_rows($ret_qry))
                    {
                       $row_qry = $db->fetch_array($ret_qry);
                         // Check whether the current feature can be placed in current position in current layout
                        if(in_array($cur_position,$cur_check_arr))  //case if current component is allowed in current position
                        {  
                            if($check_type=='showonly')
                            {
                                if($row_qry['showall']==1)
                                    $class_typ = 'green';
                                else
                                    $class_typ = 'orange';
                            }
                        }
                        else
                            $class_typ = 'red';
                    }
                }
                else  // case if not entered in any of the above switch cases
                {
                    if(in_array($cur_position,$cur_check_arr))
                        $class_typ ='green';
                    else
                        $class_typ ='red';
                }
            }
            else
                $class_typ = 'red';
        }
        $ret_Arr['main_class']      = 'DragContainer_'.$class_typ;
        $ret_Arr['hover_class']     = 'DragContainer_'.$class_typ.'_hover';
    }
    else  // case if special type display is not activated for current theme
    {
        $ret_Arr['main_class']      = 'DragContainer_normal';
        $ret_Arr['hover_class']     = 'DragContainer_normal'; 
    }
    return $ret_Arr;
}
?>
<script language="javascript" type="text/javascript">
var mouseOffset = null;
var iMouseDown  = false;
var lMouseState = false;
var dragObject  = null;

// Demo 0 variables
var DragDrops   = [];
var curTarget   = null;
var lastTarget  = null;
var dragHelper  = null;
var tempDiv     = null;
var rootParent  = null;
var rootSibling = null;
/*var nImg        = new Image();

nImg.src        = 'images/drag_drop_poof.gif';*/

// Demo1 variables
var D1Target    = null;

Number.prototype.NaN0=function(){return isNaN(this)?0:this;}

function CreateDragContainer(){
	/*
	Create a new "Container Instance" so that items from one "Set" can not
	be dragged into items from another "Set"
	*/
	var cDrag        = DragDrops.length;
	DragDrops[cDrag] = [];

	/*
	Each item passed to this function should be a "container".  Store each
	of these items in our current container
	*/
	for(var i=0; i<arguments.length; i++){
		var cObj = arguments[i];
		DragDrops[cDrag].push(cObj);
		cObj.setAttribute('DropObj', cDrag);

		/*
		Every top level item in these containers should be draggable.  Do this
		by setting the DragObj attribute on each item and then later checking
		this attribute in the mouseMove function
		*/
		for(var j=0; j<cObj.childNodes.length; j++){

			// Firefox puts in lots of #text nodes...skip these
			if(cObj.childNodes[j].nodeName=='#text') continue;

			cObj.childNodes[j].setAttribute('DragObj', cDrag);
		}
	}
}

function getPosition(e){
	var left = 0;
	var top  = 0;
	while (e.offsetParent){
		left += e.offsetLeft + (e.currentStyle?(parseInt(e.currentStyle.borderLeftWidth)).NaN0():0);
		top  += e.offsetTop  + (e.currentStyle?(parseInt(e.currentStyle.borderTopWidth)).NaN0():0);
		e     = e.offsetParent;
	}
	left += e.offsetLeft + (e.currentStyle?(parseInt(e.currentStyle.borderLeftWidth)).NaN0():0);
	top  += e.offsetTop  + (e.currentStyle?(parseInt(e.currentStyle.borderTopWidth)).NaN0():0);
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

function writeHistory(object, message){
	if(!object || !object.parentNode || !object.parentNode.getAttribute) return;
	var historyDiv = object.parentNode.getAttribute('history');
	if(historyDiv){
		historyDiv = document.getElementById(historyDiv);
		historyDiv.appendChild(document.createTextNode(object.id+': '+message));
		historyDiv.appendChild(document.createElement('BR'));

		historyDiv.scrollTop += 50;
	}
}

function getMouseOffset(target, ev){
	ev = ev || window.event;

	var docPos    = getPosition(target);
	var mousePos  = mouseCoords(ev);
	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function mouseMove(ev){
	ev         = ev || window.event;

	/*
	We are setting target to whatever item the mouse is currently on

	Firefox uses event.target here, MSIE uses event.srcElement
	*/
	var target   = ev.target || ev.srcElement;
	var mousePos = mouseCoords(ev);
	var ifvalid = true;

	if(ifvalid)
	{
		// mouseOut event - fires if the item the mouse is on has changed
		if(lastTarget && (target!==lastTarget)){
			/*writeHistory(lastTarget, 'Mouse Out Fired');*/

			// reset the classname for the target element
			var origClass = lastTarget.getAttribute('origClass');
			if(origClass) lastTarget.className = origClass;
		}

		/*
		dragObj is the grouping our item is in (set from the createDragContainer function).
		if the item is not in a grouping we ignore it since it can't be dragged with this
		script.
		*/
		var dragObj = target.getAttribute('DragObj');

		 // if the mouse was moved over an element that is draggable
		if(dragObj!=null){

			// mouseOver event - Change the item's class if necessary
			if(target!=lastTarget){
				writeHistory(target, 'Mouse Over Fired');

				var oClass = target.getAttribute('overClass');
				if(oClass){
					target.setAttribute('origClass', target.className);
					target.className = oClass;
				}
			}

			// if the user is just starting to drag the element
			if(iMouseDown && !lMouseState){
				writeHistory(target, 'Start Dragging');

				// mouseDown target
				curTarget     = target;

				// Record the mouse x and y offset for the element
				rootParent    = curTarget.parentNode;
				rootSibling   = curTarget.nextSibling;

				mouseOffset   = getMouseOffset(target, ev);

				// We remove anything that is in our dragHelper DIV so we can put a new item in it.
				for(var i=0; i<dragHelper.childNodes.length; i++) dragHelper.removeChild(dragHelper.childNodes[i]);

				// Make a copy of the current item and put it in our drag helper.
				dragHelper.appendChild(curTarget.cloneNode(true));
				dragHelper.style.display = 'block';

				// set the class on our helper DIV if necessary
				var dragClass = curTarget.getAttribute('dragClass');
				if(dragClass){
					dragHelper.firstChild.className = dragClass;
				}

				// disable dragging from our helper DIV (it's already being dragged)
				dragHelper.firstChild.removeAttribute('DragObj');

				/*
				Record the current position of all drag/drop targets related
				to the element.  We do this here so that we do not have to do
				it on the general mouse move event which fires when the mouse
				moves even 1 pixel.  If we don't do this here the script
				would run much slower.
				*/
				var dragConts = DragDrops[dragObj];

				/*
				first record the width/height of our drag item.  Then hide it since
				it is going to (potentially) be moved out of its parent.
				*/
				curTarget.setAttribute('startWidth',  parseInt(curTarget.offsetWidth));
				curTarget.setAttribute('startHeight', parseInt(curTarget.offsetHeight));
				curTarget.style.display  = 'none';

				// loop through each possible drop container
				for(var i=0; i<dragConts.length; i++){
					with(dragConts[i]){
						var pos = getPosition(dragConts[i]);

						/*
						save the width, height and position of each container.

						Even though we are saving the width and height of each
						container back to the container this is much faster because
						we are saving the number and do not have to run through
						any calculations again.  Also, offsetHeight and offsetWidth
						are both fairly slow.  You would never normally notice any
						performance hit from these two functions but our code is
						going to be running hundreds of times each second so every
						little bit helps!

						Note that the biggest performance gain here, by far, comes
						from not having to run through the getPosition function
						hundreds of times.
						*/
						setAttribute('startWidth',  parseInt(offsetWidth));
						setAttribute('startHeight', parseInt(offsetHeight));
						setAttribute('startLeft',   pos.x);
						setAttribute('startTop',    pos.y);
					}

					// loop through each child element of each container
					for(var j=0; j<dragConts[i].childNodes.length; j++){
						with(dragConts[i].childNodes[j]){
							if((nodeName=='#text') || (dragConts[i].childNodes[j]==curTarget)) continue;

							var pos = getPosition(dragConts[i].childNodes[j]);

							// save the width, height and position of each element
							setAttribute('startWidth',  parseInt(offsetWidth));
							setAttribute('startHeight', parseInt(offsetHeight));
							setAttribute('startLeft',   pos.x);
							setAttribute('startTop',    pos.y);
						}
					}
				}
			}
		}

		// If we get in here we are dragging something
		if(curTarget){
			// move our helper div to wherever the mouse is (adjusted by mouseOffset)
			dragHelper.style.top  = mousePos.y - mouseOffset.y;
			dragHelper.style.left = mousePos.x - mouseOffset.x;

			var dragConts  = DragDrops[curTarget.getAttribute('DragObj')];
			var activeCont = null;

			var xPos = mousePos.x - mouseOffset.x + (parseInt(curTarget.getAttribute('startWidth')) /2);
			var yPos = mousePos.y - mouseOffset.y + (parseInt(curTarget.getAttribute('startHeight'))/2);

			// check each drop container to see if our target object is "inside" the container
			for(var i=0; i<dragConts.length; i++){
				with(dragConts[i]){
					if((parseInt(getAttribute('startLeft'))                                           < xPos) &&
						(parseInt(getAttribute('startTop'))                                            < yPos) &&
						((parseInt(getAttribute('startLeft')) + parseInt(getAttribute('startWidth')))  > xPos) &&
						((parseInt(getAttribute('startTop'))  + parseInt(getAttribute('startHeight'))) > yPos)){

							/*
							our target is inside of our container so save the container into
							the activeCont variable and then exit the loop since we no longer
							need to check the rest of the containers
							*/
							activeCont = dragConts[i];

							// exit the for loop
							break;
					}
				}
			}

			// Our target object is in one of our containers.  Check to see where our div belongs
			if(activeCont){
				if(activeCont!=curTarget.parentNode){
					writeHistory(curTarget, 'Moved into '+activeCont.id);
				}

				// beforeNode will hold the first node AFTER where our div belongs
				var beforeNode = null;

				// loop through each child node (skipping text nodes).
				for(var i=activeCont.childNodes.length-1; i>=0; i--){
					with(activeCont.childNodes[i]){
						if(nodeName=='#text') continue;

						// if the current item is "After" the item being dragged
						if(curTarget != activeCont.childNodes[i]                                                  &&
							((parseInt(getAttribute('startLeft')) + parseInt(getAttribute('startWidth')))  > xPos) &&
							((parseInt(getAttribute('startTop'))  + parseInt(getAttribute('startHeight'))) > yPos)){
								beforeNode = activeCont.childNodes[i];
						}
					}
				}

				// the item being dragged belongs before another item
				if(beforeNode){
					if(beforeNode!=curTarget.nextSibling){
						writeHistory(curTarget, 'Inserted Before '+beforeNode.id);

						activeCont.insertBefore(curTarget, beforeNode);
					}

				// the item being dragged belongs at the end of the current container
				} else {
					if((curTarget.nextSibling) || (curTarget.parentNode!=activeCont)){
						writeHistory(curTarget, 'Inserted at end of '+activeCont.id);

						activeCont.appendChild(curTarget);
					}
				}

				// the timeout is here because the container doesn't "immediately" resize
				setTimeout(function(){
				var contPos = getPosition(activeCont);
				activeCont.setAttribute('startWidth',  parseInt(activeCont.offsetWidth));
				activeCont.setAttribute('startHeight', parseInt(activeCont.offsetHeight));
				activeCont.setAttribute('startLeft',   contPos.x);
				activeCont.setAttribute('startTop',    contPos.y);}, 5);

				// make our drag item visible
				if(curTarget.style.display!=''){
					writeHistory(curTarget, 'Made Visible');
					curTarget.style.display    = '';
					curTarget.style.visibility = 'hidden';
				}
			} else {

				// our drag item is not in a container, so hide it.
				if(curTarget.style.display!='none'){
					writeHistory(curTarget, 'Hidden');
					curTarget.style.display  = 'none';
				}
			}
		}

		// track the current mouse state so we can compare against it next time
		lMouseState = iMouseDown;

		// mouseMove target
		lastTarget  = target;
	}
	

	if(dragObject){
		dragObject.style.position = 'absolute';
		dragObject.style.top      = mousePos.y - mouseOffset.y;
		dragObject.style.left     = mousePos.x - mouseOffset.x;
	}

	// track the current mouse state so we can compare against it next time
	lMouseState = iMouseDown;

	// this prevents items on the page from being highlighted while dragging
	if(curTarget || dragObject) return false;
}

function mouseUp(ev){

	var ifvalid = true
	if(ifvalid){
		if(curTarget){
			writeHistory(curTarget, 'Mouse Up Fired');

			dragHelper.style.display = 'none';
			if(curTarget.style.display == 'none'){
				if(rootSibling){
					rootParent.insertBefore(curTarget, rootSibling);
				} else {
					rootParent.appendChild(curTarget);
				}
			}
			curTarget.style.display    = '';
			curTarget.style.visibility = 'visible';
		}
		curTarget  = null;
	}
	
	dragObject = null;

	iMouseDown = false;
}

function mouseDown(ev){
	ev         = ev || window.event;
	var target = ev.target || ev.srcElement;
	var ifvalid = true;
	iMouseDown = true;
	if(ifvalid){
		if(lastTarget){
			writeHistory(lastTarget, 'Mouse Down Fired');
		}
	}
	if(target.onmousedown || target.getAttribute('DragObj')){
		return false;
	}
}

function makeDraggable(item){
	if(!item) return;
	item.onmousedown = function(ev){
		dragObject  = this;
		mouseOffset = getMouseOffset(this, ev);
		return false;
	}
}
function makeClickable(item){
	if(!item) return;
	item.onmousedown = function(ev){
		document.getElementById('ClickImage').value = this.name;
	}
}

function addDropTarget(item, target){
     item.setAttribute('droptarget', target);
}

document.onmousemove = mouseMove;
document.onmousedown = mouseDown;
document.onmouseup   = mouseUp;

window.onload = function(){
	var ifvalid = true;
	CreateDragContainer(<?php echo $drag_str?>);
	if(ifvalid)
	{
		// Create our helper object that will show the item while dragging
		dragHelper = document.createElement('DIV');
		dragHelper.style.cssText = 'position:absolute;display:block;';
		document.body.appendChild(dragHelper);
	}
}
function remove_me(parentid,myid,showname)
{
    mainobj = eval("document.getElementById('"+parentid+"')");
    if (mainobj)
    {
        remobj = eval("document.getElementById('"+myid+"')");
        if(remobj)
        {
            var add_msg = '\n\n-------------------------------------------------------------------------------------------------\nYour changes will be Saved only when clicked on "Save Positions" button.\n-------------------------------------------------------------------------------------------------\n';
            if(confirm('Are you sure you want to remove \"'+showname+'\" from \"'+parentid+'\" position?'+add_msg))
                mainobj.removeChild(remobj);
        }
    }
}
function valforms(frm)
{
	if(confirm('Are you sure you want to save changes?')) {
		show_processing();
		return true;
	} else {
		return false;
	}
}	
function validate_position()
{
	var retstr = '';
	<?php
		for($i=0;$i<count($pos_arr);$i++)
		{
			echo "
				retstr = retstr + '~' + generate_sting('".$pos_arr[$i]."');";
		}
	?>
	document.frmComponentPosition_move.comp_str.value = retstr;
	document.frmComponentPosition_move.submit();
}
function generate_sting(idval)
{
	var temp_str = '';
	var holdval='';
	if (document.getElementById(idval))
	{
		parentDiv = document.getElementById(idval);
		var temp = parentDiv.childNodes[0];
		do{
			/*temp.style.display ='none';*/
			if(temp.id) 
			{
				holdval = temp.id;
				temp_str += ',' + holdval;
			}	
			temp = temp.nextSibling;
		}while(temp != parentDiv.lastChild);
		if(temp.id) 
		{
			holdval = temp.id;
			temp_str += ',' + holdval;
		}	
	}	
	return temp_str;
}	
function edit_titles(pos)
{
	document.frmComponentPosition_move.movetoedit.value=pos;
	document.frmComponentPosition_move.passlayoutcode.value='<?php echo $layoutcode?>';
	validate_position();
}
</script>
<STYLE>
/*.dragtableclass{
	border:1px solid #2A3F55;
}
.dragtableclassinner{
	border:1px solid #FF0000;
}
.topcolumn{
	background-color:#E7EFFA;
	padding-right:8px;
}
.leftcolumn{
	background-color:#E7EFFA;
}
.middlecolumn{
	background-color:#E7EFFA;
}
.rightcolumn{
	background-color:#E7EFFA;
}
.footercolumn{
	background-color:#E7EFFA;
	padding-right:8px;
}
.footercolumntext{
	font-family:Arial, Helvetica, sans-serif;
	font-size:9px;
	font-weight:normal;
	background-color:#E7E7E9;
	padding-right:8px;
}
.DragContainer {
	BORDER-RIGHT: #000000 1px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 1px solid; 
	PADDING-LEFT: 5px; 
	FLOAT: left; 
	PADDING-BOTTOM: 0px; 
	MARGIN: 3px; 
	BORDER-LEFT: #000000 1px solid; 
	PADDING-TOP: 5px; 
	padding-bottom:5px;
	BORDER-BOTTOM: #000000 1px solid
}
.DragContainer div{
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 1px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 1px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #CEDDF4
}
.DragContainertop {
	BORDER-RIGHT: #000000 1px solid; 
	PADDING-RIGHT: 5px;

	BORDER-TOP: #000000 1px solid; 
	PADDING-LEFT: 5px; 
	FLOAT: right; 
	PADDING-BOTTOM: 0px; 
	MARGIN: 1px; 
	BORDER-LEFT: #000000 1px solid; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 1px solid;
	padding-bottom:5px;
	padding-right:5px;
	WIDTH: 98%; 
}
.DragContainertop div {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #CEDDF4;
	float:left;
	white-space:nowrap;
	WIDTH: 165px; 
	margin-left:3px;
}

.OverDragContainer {
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; FLOAT: left; 
	PADDING-BOTTOM: 0px; MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	WIDTH: 167px; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid;
	BACKGROUND-COLOR: #eee
}
.OverDragContainer div{
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; FLOAT: left; 
	PADDING-BOTTOM: 0px; MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid
}
.DragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #eee
}
.OverDragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #FFFF99;
	z-index:3;
	
}
.DragDragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #ffff99;
	FILTER: alpha(opacity=50);
	z-index:3;
}
.miniDragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: verdana, tahoma, arial; 
	BACKGROUND-COLOR: #eee
}
LEGEND {
	FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #666699; FONT-FAMILY: verdana, tahoma, arial
}
FIELDSET {
	PADDING-RIGHT: 3px; PADDING-LEFT: 3px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px
}
.History {
	FONT-SIZE: 10px; OVERFLOW: auto; WIDTH: 100%; FONT-FAMILY: verdana, tahoma, arial; HEIGHT: 82px
}
.miniDragBox {
	FLOAT: left; MARGIN: 0px 5px 5px 0px; WIDTH: 20px; HEIGHT: 20px
}
FORM.tb {display:inline;}
  .twidth{width:100%}
  .include{ font-size: 75%; font-family: verdana, arial, helvetica;}
  .includebig{font-family: verdana, arial, helvetica;}
  .includebig A:link { color: blue; }
  .includebig A:visited { color: purple; }
  .include  A:link { color: blue; }
  .include A:visited { color: purple; }
  .submitter { font-size: 75%; font-family: verdana, arial, helvetica; }
  .codehighlight {background:#eee}
  .WRy1{background:#fc0}
  .WRy2{background:#fff3ac}
pre.code {color: #660099; margin-left:5%}
address {text-align: right}
.WRBannerCenter {margin-left:-5%; margin-right:-5%; margin-top:8px; margin-bottom:8px; text-align:center}
*/
.themetdcontent{
padding:0;
font-size:11px;
color:#ffffff;
}
.themetdcontent_divcls{
	padding:11px 1% 11px 1%;
	font-size:12px;
	width:98%;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #669c9b;	
	text-decoration: none; /* no underline */
	color: #000;
	background:#9BC3C2; 
	margin:5px 0;
	
}

.themetdcontent_divcls_l{
	float:left;
		padding:11px 1% 11px 1%;
	font-size:12px;
	width:98%;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #fff;	
	text-decoration: none; /* no underline */
	color: #000;
	background:#fff; 
	margin:5px 0;
	
}
.themetdcontent_divcls_r{
	float:left;
		padding:11px 1% 11px 1%;
	font-size:12px;
	width:98%;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #fff;	
	text-decoration: none; /* no underline */
	color: #000;
	background:#fff ;
	margin:5px 0;
	
}
.themetdcontent_divcls_leg{
		float:left;
		padding:11px 1% 11px 1%;
	font-size:12px;
	width:98%;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #c2c2c2;	
	text-decoration: none; /* no underline */
	color: #000;
	background:#E8E8E8; 
	margin:5px 0;
	
}
.themetdcontent_divcls_top{
		float:left;
		padding:11px 1% 11px 1%;
	font-size:12px;
	width:98%;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #9A9999;	
	text-decoration: none; /* no underline */
	color: #000;
	background:#FFF; 
	margin:5px 0;
	
}
td.themeleft{
width:20%;
padding-right:10px;

}

td.themeright{
width:80%;
}

div.themeleftheader{
float:left;
width:80%;
height:40px;
padding:12px 0 0 53px;
background: url(images/headerimg.gif) 3% 3% no-repeat;
font-weight:bold;
color: #E10808;
}

div.themelefcomp{
float:left;
width:80%;
color:#004a7c;
padding:2px 15px 2px 15px;
  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #9A9999;	
background:#a8cee7 url(images/drag-arrow-a.png) 93% 50% no-repeat;
font-weight:bold;
margin:2px 0 2px 8px;
}
.DragContainer { 
	MARGIN: 3px; 
padding:10px 0;
}
.DragContainer {
	float:left;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	background-color:#E2E2E2;
	border:1px solid #9A9999;	
}
.DragContainer div{
	cursor:move;
	float:left;
font-weight:normal;
	
	
} 
.DragContainer img{
	padding-right:4px;
	margin-bottom:-3px;
}
.DragContainer_s{
	float:left;
	padding-left:5px;
	background-color:#fff;	
}
.DragContainer_red{ 
background:#919191 url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#FFFFFF;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #919191;
	width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_red_demo{ 
background:#919191;cursor:move;
color:#FFFFFF;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #919191;
	padding:5px;
	margin:3px 0;
	width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
  
.DragContainer_red_hover{ 
background:#b5b5b5 url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#FFFFFF;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #b5b5b5;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_green{ 
background:#679f1f url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#FFFFFF;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #679f1f;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_green_demo{ 
background:#679f1f ;
color:#FFFFFF;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #679f1f;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_green_hover{ 
background:#72b61a url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#FFFFFF;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #72b61a;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_orange{ 
background:#f1c80c url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#fff;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #f1c80c;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_orange_demo{ 
background:#f1c80c;
color:#fff;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #f1c80c;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_orange_hover{ 
background:#fad62f url(images/drag-arrow-a.png) 93% 50% no-repeat;
color:#fff;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #fad62f;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}

.DragContainer_normal{ 
 background:#66a6b4 url(images/drag-arrow-a.png) 93% 50% no-repeat;
 color:#fff;cursor:move;
   -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #66a6b4;
width:165px;
	padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_normal_demo{ 
 background:#66a6b4;
 color:#fff;cursor:move;
  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #66a6b4;
width:165px;
			padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainer_normal_hover{ 
 background:#8ac0cc url(images/drag-arrow-a.png) 93% 50% no-repeat;
 color:#fff;cursor:move;
  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	border:1px solid #8ac0cc;
width:165px;
		padding:5px 15px 5px 15px;
	margin:2px 0 2px 8px; 
}
.DragContainertop {	
	width:100%; 
	height:100px;
	float:left;background-color:#E2E2E2;
	  -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
	-ms-border-radius: 5px;
    border-radius: 5px;
	
	border:1px solid #9A9999;
}
.DragContainertop div{
	float:left; 
}
.dragtableclassinner{
background-color:#fff;
border:none;
}



.topcolumn{
	background-color:#ffffff;
	padding:8px;
}

.leftcolumn {		
	background-color:#ffffff;
	padding:8px;
}
.middlecolumn {	
	background-color:#ffffff;
	padding:8px;
}
.rightcolumn {	
	background-color:#ffffff;
	padding:8px;
}

.OverDragContainer {
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; FLOAT: left; 
	PADDING-BOTTOM: 0px; MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	WIDTH: 167px; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid;
	BACKGROUND-COLOR: #eee
}
.OverDragContainer div{
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; 
	FLOAT: left; 
	PADDING-BOTTOM: 0px; 
	MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid
}
.OverDragBox {
	width: 165px; 
	cursor:move;
	float:left;
	color:#ffffff;
	padding:2px 15px 2px 15px;
	border:1px solid #C4DEEF;
	background:#185D8C;
	font-weight:bold;
	margin:2px 0 2px 8px; 
	
}

.OverDragContainer {
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; FLOAT: left; 
	PADDING-BOTTOM: 0px; MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	WIDTH: 167px; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid;
	BACKGROUND-COLOR: #eee
}
.OverDragContainer div{
	BORDER-RIGHT: #000000 2px solid; 
	PADDING-RIGHT: 5px; 
	BORDER-TOP: #000000 2px solid; 
	PADDING-LEFT: 5px; 
	FLOAT: left; 
	PADDING-BOTTOM: 0px; 
	MARGIN: 3px; 
	BORDER-LEFT: #000000 2px solid; 
	PADDING-TOP: 5px; 
	BORDER-BOTTOM: #000000 2px solid
}
.DragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: arial, verdana, tahoma; 
	font-weight:bold;
	font-size:11px;
	color:#FFFFFF;
	BACKGROUND-COLOR:#eee
} 
a.complinks:link
{
    color:#03508B;
    border-top:1px dotted #FFFFFF;
    border-bottom:1px dotted #FFFFFF;
    text-decoration:none;
}
a.complinks:visited
{
    color:#03508B;
    border-top:1px dotted #FFFFFF;
    border-bottom:1px dotted #FFFFFF;
    text-decoration:none;
}
a.complinks:hover
{
    color:#03508B;
    border-top:1px dotted #FF0000;
    border-bottom:1px dotted #FF0000;
    text-decoration:none;
}
 .spancls {
    padding-right:16px;background:url(images/edit-c.png) 100% 0% no-repeat;
}
.DragDragBox {
	BORDER-RIGHT: #000 1px solid; 
	PADDING-RIGHT: 2px; 
	BORDER-TOP: #000 1px solid; 
	PADDING-LEFT: 2px; 
	FONT-SIZE: 10px; 
	MARGIN-BOTTOM: 5px; 
	PADDING-BOTTOM: 2px; 
	BORDER-LEFT: #000 1px solid; 
	WIDTH: 165px; 
	CURSOR: pointer; 
	PADDING-TOP: 2px; 
	BORDER-BOTTOM: #000 1px solid; 
	FONT-FAMILY: arial, verdana, tahoma;
	color:#FFFFFF; 
	BACKGROUND-COLOR: #185D8C;
	FILTER: alpha(opacity=50);
	z-index:3;
}

.pos_header{
	FONT-FAMILY: arial, verdana, tahoma; 
	font-weight:bold;
	padding:5px;
	font-size:11px;
	color:#e10000;
	}
.footercolumn{
	background-color:#FFFFFF;
	padding:8px;
}
.footercolumntext{
	font-family:Arial, Helvetica, sans-serif;
	font-size:9px;
	font-weight:normal;
	background-color:#E2EDF5;
	padding-right:8px;
	color:#000000
}

</STYLE>
<form name='frmComponentPosition_move' id='frmComponentPosition_move' action='home.php?request=mob_comp_pos' method="post" onsubmit="return valforms(this);" >
<input type="hidden" name="fpurpose" value="save_componentsection" />
<input type="hidden" name="comp_str" id ='comp_str' value="" />
<input type="hidden" name="movetoedit" value="" />
<input type="hidden" name="passlayoutcode" value="" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
<table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
		<td>
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="treemenutd" align="left"><div class="treemenutd_div"><span>Assign and manage the components</span></div></td>
		  </tr>
		</table></td>
	</tr>
     <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
	  <?php
	  	if($alert)
		{
	  ?>
		  <tr>
			<td class="errormsg" align="center"><?php echo $alert?></td>
		  </tr>
	  <?php
	  }
	  ?>
	<tr>
      <td class="tdcolorgraynormal" align="left">
	  <div class="themetdcontent_divcls_top">
		Select Layout
	<?php
		
		$sql_lay = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE themes_theme_id=$ecom_mobilethemeid ORDER BY layout_order";
		$ret_lay = $db->query($sql_lay);
		if($db->num_rows($ret_lay))
		{
		?>
			<select name="cbo_sellayout" id="cbo_sellayout" onchange="window.location='home.php?request=mob_comp_pos&layoutcode='+this.value">
			<?php
				while($row_lay = $db->fetch_array($ret_lay))
				{
			?>	
					<option value="<?php echo $row_lay['layout_code']?>" <?php if ($layoutcode==$row_lay['layout_code']) echo 'selected';?>><?php echo $row_lay['layout_name']?></option>
			<?php
				}
			?>	
			</select>
		<?php	
		}
	?>	<a href="#" style="cursor:pointer;" onmouseover="return overlib('<?=get_help_messages('MAN_COMPO_POS_LAYOUT')?>',100,VAUTO);" onmouseout="return nd();"><img src="images/helpicon.png" border="0" alt="" /></a>
	</div>
	</td>
	</tr>
	
	<?php /*?><tr>
          <td align="right" valign="middle">
          <?php //show_legends();?>
        </td>
          </tr><?php */?>
	  <tr>
	    <td valign="top" class="themetdcontent">
              <div class="themetdcontent_divcls">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
		  	<tr>
			<td width="19%" valign="top" align="left" class="themeleft" >
            <div class="themetdcontent_divcls_l">
			<div class="themeleftheader">Available Components</div>
			<?php
				//Get the components from site_menu
				$sql_sitemenu = "SELECT a.menu_id,b.feature_name,b.feature_ordering,b.feature_id,b.feature_modulename FROM site_menu a,features b WHERE 
								a.sites_site_id=$ecom_siteid AND b.feature_showinmobilecomponentposition=1 AND a.features_feature_id=b.feature_id 
								ORDER BY b.feature_ordering";
				$ret_sitemenu = $db->query($sql_sitemenu);
				?>
		  <div dropobj="0" class="DragContainer" id="sitemenu" overclass="OverDragContainer">
					<?php
						while($row_sitemenu = $db->fetch_array($ret_sitemenu))
						{
							$uniq = uniqid('');
                                                        $showname_new = show_module_name($row_sitemenu['feature_modulename'],$dispfeat_arr,$row_sitemenu['feature_id'],stripslashes($row_sitemenu['feature_name']));
					?>
							<div origclass="DragContainer_normal" dragobj="0" class="DragContainer_normal" id="<?php echo $row_sitemenu['feature_id'].'_0_'.$uniq?>" overclass="DragContainer_normal<?php /*OverDragBox*/?>" dragclass="DragDragBox" title="<?php echo stripslashes($row_sitemenu['feature_name'])?>" <?php echo $showname_new?>>
							<?php 
                                                        echo name_indicator($showname_new,stripslashes($row_sitemenu['feature_name']));
                                                        ?>
                                                        </div>
					<?php
							$displayed_arr[] = $row_sitemenu['feature_id'];
							$dispstr = implode(",",$displayed_arr);
						}
						if ($dispstr=='') $dispstr = 0;
						//Find other features from display_settings table which are not yet shown in the left hand section
						$sql_disp = "SELECT distinct display_component_id,features_feature_id FROM display_settings WHERE 
									sites_site_id=$ecom_siteid AND features_feature_id NOT IN($dispstr) AND display_component_id<>0";
						$ret_sitemenu = $db->query($sql_disp);
						if($db->num_rows($ret_sitemenu))
						{
							while($row_sitemenu = $db->fetch_array($ret_sitemenu))
							{
                                                            $showname = getComponenttitle($row_sitemenu['features_feature_id'],$row_sitemenu['display_component_id']);
                                                            $uniq = uniqid('');
                                                            // Find the original name of the feature
                                                            $sql_feat = "SELECT feature_id,feature_name,feature_modulename FROM features WHERE feature_id=".$row_sitemenu['features_feature_id'];
                                                            $ret_feat = $db->query($sql_feat);
                                                            if ($db->num_rows($ret_feat))
                                                            {
                                                                $row_feat       = $db->fetch_array($ret_feat);
                                                                $showfeatname   = stripslashes($row_feat['feature_name']);
                                                                $showname_new   = show_module_name($row_feat['feature_modulename'],$dispfeat_arr,$row_sitemenu['display_component_id'],$showname);
                                                            }
                                                ?>
                                                            <div origclass="DragContainer_normal" dragobj="0" class="DragContainer_normal" id="<?php echo $row_sitemenu['features_feature_id'].'_'.$row_sitemenu['display_component_id'].'_'.$uniq?>" overclass="DragContainer_normal<?php /*OverDragBox*/?>" dragclass="DragDragBox" title="<?php echo $showfeatname;?>" <?php echo $showname_new?>>
                                                            <?php 
                                                                echo name_indicator($showname_new,$showname);
                                                            ?>
                                                            </div>
						<?php	
							}
							
						}
					?>	
				</div>	</div>
				</td>
			<td width="81%" valign="top" align="left" class="themeright">
              <div class="themetdcontent_divcls_r">
					<?php 
						$fname = $layoutcode.".php";
						include ("../themes/".strtolower($ecom_themename)."/html/console_templatelayout/".$fname);
					?>			</td>
		  </tr>
			</table>		
            
            
            </div> </div>
            
            </td>
    </tr>
   <?php /*?> <tr>
          <td align="right" valign="middle">
          <?php //show_legends();?>
        </td>
          </tr><?php */?>
          <tr>
          <td align="right" valign="middle">&nbsp;
          
        </td>
          </tr>
	  <tr>
	    <td align="center" class="maininnertabletd2"><input type="button" name="Submit_position" value="Save Positions" class="red" onclick="validate_position()" /></td>
    </tr>
  </table>
</form>
<?php 
}
else
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td>&nbsp;</td>
  </tr>
   <tr> 
	  <td class="errormsg" align="center">Sorry!! You Are Not Authorised To View This Page.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?php
}

 ?>

﻿/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * This file has been compacted for best loading performance.
 * 
 * Version: 2.0 RC3
 * Created: 2005-03-02 14:14:12
 */
var FCKDebug=new Object();if (FCKConfig.Debug){FCKDebug.Output=function(message,color){if (!FCKConfig.Debug) return;if (message!=null&&isNaN(message)) message=message.replace(/</g,"&lt;");if (!this.DebugWindow||this.DebugWindow.closed) this.DebugWindow=window.open('fckdebug.html','FCKeditorDebug','menubar=no,scrollbars=no,resizable=yes,location=no,toolbar=no,width=600,height=500',true);if (this.DebugWindow.Output) this.DebugWindow.Output(message,color);};}else FCKDebug.Output=function() {};
var FCKTools=new Object();FCKTools.GetLinkedFieldValue=function(){return FCK.LinkedField.value;};FCKTools.SetLinkedFieldValue=function(value){if (FCKConfig.FormatOutput) FCK.LinkedField.value=FCKCodeFormatter.Format(value);else FCK.LinkedField.value=value;};FCKTools.AttachToLinkedFieldFormSubmit=function(functionPointer){var oForm=FCK.LinkedField.form;if (!oForm) return;if (FCKBrowserInfo.IsIE) oForm.attachEvent("onsubmit",functionPointer);else oForm.addEventListener('submit',functionPointer,true);if (!oForm.updateFCKEditor) oForm.updateFCKEditor=new Array();oForm.updateFCKEditor[oForm.updateFCKEditor.length]=functionPointer;if (!oForm.originalSubmit&&(typeof(oForm.submit)=='function'||(!oForm.submit.tagName&&!oForm.submit.length))){oForm.originalSubmit=oForm.submit;oForm.submit=function(){if (this.updateFCKEditor){for (var i=0;i<this.updateFCKEditor.length;i++) this.updateFCKEditor[i]();};this.originalSubmit();};};};FCKTools.AddSelectOption=function(targetDocument,selectElement,optionText,optionValue){var oOption=targetDocument.createElement("OPTION");oOption.text=optionText;oOption.value=optionValue;selectElement.options.add(oOption);return oOption;};FCKTools.RemoveAllSelectOptions=function(selectElement){for (var i=selectElement.options.length-1;i>=0;i--){selectElement.options.remove(i);};};FCKTools.SelectNoCase=function(selectElement,value,defaultValue){var sNoCaseValue=value.toString().toLowerCase();for (var i=0;i<selectElement.options.length;i++){if (sNoCaseValue==selectElement.options[i].value.toLowerCase()){selectElement.selectedIndex=i;return;};};if (defaultValue!=null) FCKTools.SelectNoCase(selectElement,defaultValue);};FCKTools.HTMLEncode=function(text){text=text.replace(/&/g,"&amp;");text=text.replace(/"/g,"&quot;");text=text.replace(/</g,"&lt;");text=text.replace(/>/g,"&gt;");text=text.replace(/'/g,"&#39;");return text;};FCKTools.GetResultingArray=function(arraySource,separator){switch (typeof(arraySource)){case "string":return arraySource.split(separator);case "function":return separator();default:if (isArray(arraySource)) return arraySource;else return new Array();};};FCKTools.GetElementPosition=function(el){var c={ X:0,Y:0 };while (el){c.X+=el.offsetLeft;c.Y+=el.offsetTop;el=el.offsetParent;};return c;};FCKTools.GetElementAscensor=function(element,ascensorTagName){var e=element.parentNode;while (e){if (e.nodeName==ascensorTagName) return e;e=e.parentNode;};};FCKTools.Pause=function(miliseconds){var oStart=new Date();while (true){var oNow=new Date();if (miliseconds<oNow-oStart) return;};};
FCKTools.AppendStyleSheet=function(documentElement,cssFileUrl){return documentElement.createStyleSheet(cssFileUrl);};FCKTools.ClearElementAttributes=function(element){element.clearAttributes();};FCKTools.GetAllChildrenIds=function(parentElement){var aIds=new Array();for (var i=0;i<parentElement.all.length;i++){var sId=parentElement.all[i].id;if (sId&&sId.length>0) aIds[aIds.length]=sId;};return aIds;};FCKTools.RemoveOuterTags=function(e){e.insertAdjacentHTML('beforeBegin',e.innerHTML);e.parentNode.removeChild(e);};FCKTools.CreateXmlObject=function(object){var aObjs;switch (object){case 'XmlHttp':aObjs=['MSXML2.XmlHttp','Microsoft.XmlHttp'];break;case 'DOMDocument':aObjs=['MSXML2.DOMDocument','Microsoft.XmlDom'];break;};for (var i=0;i<2;i++){try { return new ActiveXObject(aObjs[i]);}catch (e) {};};}
var FCKRegexLib=new Object();FCKRegexLib.AposEntity=/&apos;/gi;FCKRegexLib.ObjectElements=/^(?:IMG|TABLE|TR|TD|INPUT|SELECT|TEXTAREA|HR|OBJECT)$/i;FCKRegexLib.BlockElements=/^(?:P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI)$/i;FCKRegexLib.EmptyElements=/^(?:BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT)$/i;FCKRegexLib.NamedCommands=/^(?:Cut|Copy|Paste|Print|SelectAll|RemoveFormat|Unlink|Undo|Redo|Bold|Italic|Underline|StrikeThrough|Subscript|Superscript|JustifyLeft|JustifyCenter|JustifyRight|JustifyFull|Outdent|Indent|InsertOrderedList|InsertUnorderedList|InsertHorizontalRule)$/i;FCKRegexLib.BodyContents=/([\s\S]*\<body[^\>]*\>)([\s\S]*)(\<\/body\>[\s\S]*)/i;FCKRegexLib.ToReplace=/___fcktoreplace:([\w]+)/ig;FCKRegexLib.MetaHttpEquiv=/http-equiv\s*=\s*["']?([^"' ]+)/i;FCKRegexLib.HasBaseTag=/<base /i;FCKRegexLib.HeadCloser=/<\/head\s*>/i;FCKRegexLib.TableBorderClass=/\s*FCK__ShowTableBorders\s*/;
FCKLanguageManager.GetActiveLanguage=function(){if (FCKConfig.AutoDetectLanguage){var sUserLang;if (navigator.userLanguage) sUserLang=navigator.userLanguage.toLowerCase();else if (navigator.language) sUserLang=navigator.language.toLowerCase();else{return FCKConfig.DefaultLanguage;};FCKDebug.Output('Navigator Language = '+sUserLang);if (sUserLang.length>=5){sUserLang=sUserLang.substr(0,5);if (this.AvailableLanguages[sUserLang]) return sUserLang;};if (sUserLang.length>=2){sUserLang=sUserLang.substr(0,2);if (this.AvailableLanguages[sUserLang]) return sUserLang;};};return this.DefaultLanguage;};FCKLanguageManager.TranslateElements=function(targetDocument,tag,propertyToSet){var aInputs=targetDocument.getElementsByTagName(tag);for (var i=0;i<aInputs.length;i++){var sKey=aInputs[i].getAttribute('fckLang');if (sKey){var s=FCKLang[sKey];if (s) eval('aInputs[i].'+propertyToSet+' = s');};};};FCKLanguageManager.TranslatePage=function(targetDocument){this.TranslateElements(targetDocument,'INPUT','value');this.TranslateElements(targetDocument,'SPAN','innerHTML');this.TranslateElements(targetDocument,'LABEL','innerHTML');this.TranslateElements(targetDocument,'OPTION','innerHTML');};if (FCKLanguageManager.AvailableLanguages[FCKConfig.DefaultLanguage]) FCKLanguageManager.DefaultLanguage=FCKConfig.DefaultLanguage;else FCKLanguageManager.DefaultLanguage='en';FCKLanguageManager.ActiveLanguage=new Object();FCKLanguageManager.ActiveLanguage.Code=FCKLanguageManager.GetActiveLanguage();FCKLanguageManager.ActiveLanguage.Name=FCKLanguageManager.AvailableLanguages[FCKLanguageManager.ActiveLanguage.Code];FCK.Language=FCKLanguageManager;LoadLanguageFile();
var FCKEvents=function(eventsOwner){this.Owner=eventsOwner;this.RegisteredEvents=new Object();};FCKEvents.prototype.AttachEvent=function(eventName,functionPointer){if (!this.RegisteredEvents[eventName]) this.RegisteredEvents[eventName]=new Array();this.RegisteredEvents[eventName][this.RegisteredEvents[eventName].length]=functionPointer;};FCKEvents.prototype.FireEvent=function(eventName,params){var bReturnValue=true;var oCalls=this.RegisteredEvents[eventName];if (oCalls){for (var i=0;i<oCalls.length;i++) bReturnValue=(oCalls[i](params)&&bReturnValue);};return bReturnValue;}
FCKXHtmlEntities=new Object();FCKXHtmlEntities.Entities={' ':'nbsp','¡':'iexcl','¢':'cent','£':'pound','¤':'curren','¥':'yen','¦':'brvbar','§':'sect','¨':'uml','©':'copy','ª':'ordf','«':'laquo','¬':'not','­':'shy','®':'reg','¯':'macr','°':'deg','±':'plusmn','²':'sup2','³':'sup3','´':'acute','µ':'micro','¶':'para','·':'middot','¸':'cedil','¹':'sup1','º':'ordm','»':'raquo','¼':'frac14','½':'frac12','¾':'frac34','¿':'iquest','À':'Agrave','Á':'Aacute','Â':'Acirc','Ã':'Atilde','Ä':'Auml','Å':'Aring','Æ':'AElig','Ç':'Ccedil','È':'Egrave','É':'Eacute','Ê':'Ecirc','Ë':'Euml','Ì':'Igrave','Í':'Iacute','Î':'Icirc','Ï':'Iuml','Ð':'ETH','Ñ':'Ntilde','Ò':'Ograve','Ó':'Oacute','Ô':'Ocirc','Õ':'Otilde','Ö':'Ouml','×':'times','Ø':'Oslash','Ù':'Ugrave','Ú':'Uacute','Û':'Ucirc','Ü':'Uuml','Ý':'Yacute','Þ':'THORN','ß':'szlig','à':'agrave','á':'aacute','â':'acirc','ã':'atilde','ä':'auml','å':'aring','æ':'aelig','ç':'ccedil','è':'egrave','é':'eacute','ê':'ecirc','ë':'euml','ì':'igrave','í':'iacute','î':'icirc','ï':'iuml','ð':'eth','ñ':'ntilde','ò':'ograve','ó':'oacute','ô':'ocirc','õ':'otilde','ö':'ouml','÷':'divide','ø':'oslash','ù':'ugrave','ú':'uacute','û':'ucirc','ü':'uuml','ý':'yacute','þ':'thorn','ÿ':'yuml','ƒ':'fnof','Α':'Alpha','Β':'Beta','Γ':'Gamma','Δ':'Delta','Ε':'Epsilon','Ζ':'Zeta','Η':'Eta','Θ':'Theta','Ι':'Iota','Κ':'Kappa','Λ':'Lambda','Μ':'Mu','Ν':'Nu','Ξ':'Xi','Ο':'Omicron','Π':'Pi','Ρ':'Rho','Σ':'Sigma','Τ':'Tau','Υ':'Upsilon','Φ':'Phi','Χ':'Chi','Ψ':'Psi','Ω':'Omega','α':'alpha','β':'beta','γ':'gamma','δ':'delta','ε':'epsilon','ζ':'zeta','η':'eta','θ':'theta','ι':'iota','κ':'kappa','λ':'lambda','μ':'mu','ν':'nu','ξ':'xi','ο':'omicron','π':'pi','ρ':'rho','ς':'sigmaf','σ':'sigma','τ':'tau','υ':'upsilon','φ':'phi','χ':'chi','ψ':'psi','ω':'omega','ϑ':'thetasym','ϒ':'upsih','ϖ':'piv','•':'bull','…':'hellip','′':'prime','″':'Prime','‾':'oline','⁄':'frasl','℘':'weierp','ℑ':'image','ℜ':'real','™':'trade','ℵ':'alefsym','←':'larr','↑':'uarr','→':'rarr','↓':'darr','↔':'harr','↵':'crarr','⇐':'lArr','⇑':'uArr','⇒':'rArr','⇓':'dArr','⇔':'hArr','∀':'forall','∂':'part','∃':'exist','∅':'empty','∇':'nabla','∈':'isin','∉':'notin','∋':'ni','∏':'prod','∑':'sum','−':'minus','∗':'lowast','√':'radic','∝':'prop','∞':'infin','∠':'ang','∧':'and','∨':'or','∩':'cap','∪':'cup','∫':'int','∴':'there4','∼':'sim','≅':'cong','≈':'asymp','≠':'ne','≡':'equiv','≤':'le','≥':'ge','⊂':'sub','⊃':'sup','⊄':'nsub','⊆':'sube','⊇':'supe','⊕':'oplus','⊗':'otimes','⊥':'perp','⋅':'sdot','⌈':'lceil','⌉':'rceil','⌊':'lfloor','⌋':'rfloor','〈':'lang','〉':'rang','◊':'loz','♠':'spades','♣':'clubs','♥':'hearts','♦':'diams','"':'quot','Œ':'OElig','œ':'oelig','Š':'Scaron','š':'scaron','Ÿ':'Yuml','ˆ':'circ','˜':'tilde',' ':'ensp',' ':'emsp',' ':'thinsp','‌':'zwnj','‍':'zwj','‎':'lrm','‏':'rlm','–':'ndash','—':'mdash','‘':'lsquo','’':'rsquo','‚':'sbquo','“':'ldquo','”':'rdquo','„':'bdquo','†':'dagger','‡':'Dagger','‰':'permil','‹':'lsaquo','›':'rsaquo','€':'euro'};FCKXHtmlEntities.Chars='';for (var e in FCKXHtmlEntities.Entities) FCKXHtmlEntities.Chars+=e;FCKXHtmlEntities.EntitiesRegex=new RegExp('','');FCKXHtmlEntities.EntitiesRegex.compile('['+FCKXHtmlEntities.Chars+']|[^'+FCKXHtmlEntities.Chars+']+','g');FCKXHtmlEntities.GeckoEntitiesMarkerRegex=/#\?-\:/g;
var FCKXHtml=new Object();FCKXHtml.CurrentJobNum=0;FCKXHtml.GetXHTML=function(node,includeNode,format){FCKXHtml.SpecialBlocks=new Array();this.XML=FCKTools.CreateXmlObject('DOMDocument');this.MainNode=this.XML.appendChild(this.XML.createElement('xhtml'));FCKXHtml.CurrentJobNum++;if (includeNode) this._AppendNode(this.MainNode,node);else this._AppendChildNodes(this.MainNode,node,false);var sXHTML=this._GetMainXmlString();sXHTML=sXHTML.substr(7,sXHTML.length-15).trim();if (FCKConfig.ForceSimpleAmpersand) sXHTML=sXHTML.replace(/___FCKAmp___/g,'&');if (format) sXHTML=FCKCodeFormatter.Format(sXHTML);for (var i=0;i<FCKXHtml.SpecialBlocks.length;i++){var oRegex=new RegExp('___FCKsi___'+i);sXHTML=sXHTML.replace(oRegex,FCKXHtml.SpecialBlocks[i]);};this.XML=null;return sXHTML};FCKXHtml._AppendAttribute=function(xmlNode,attributeName,attributeValue){try{var oXmlAtt=this.XML.createAttribute(attributeName);oXmlAtt.value=attributeValue?attributeValue:'';xmlNode.attributes.setNamedItem(oXmlAtt);}catch (e){};};FCKXHtml._AppendChildNodes=function(xmlNode,htmlNode,isBlockElement){if (htmlNode.hasChildNodes()){var oChildren=htmlNode.childNodes;for (var i=0;i<oChildren.length;i++) this._AppendNode(xmlNode,oChildren[i]);}else{if (isBlockElement&&FCKConfig.FillEmptyBlocks){this._AppendEntity(xmlNode,'nbsp');return;};if (!FCKRegexLib.EmptyElements.test(htmlNode.nodeName)) xmlNode.appendChild(this.XML.createTextNode(''));};};FCKXHtml._AppendNode=function(xmlNode,htmlNode){switch (htmlNode.nodeType){case 1:if (FCKBrowserInfo.IsGecko&&htmlNode.hasAttribute('_moz_editor_bogus_node')) return;var sNodeName=htmlNode.nodeName.toLowerCase();if (FCKBrowserInfo.IsGecko&&sNodeName=='br'&&htmlNode.hasAttribute('type')&&htmlNode.getAttribute('type',2)=='_moz') return;if (htmlNode._fckxhtmljob==FCKXHtml.CurrentJobNum) return;else htmlNode._fckxhtmljob=FCKXHtml.CurrentJobNum;if (sNodeName.length==0||sNodeName.substr(0,1)=='/') break;var oNode=this.XML.createElement(sNodeName);FCKXHtml._AppendAttributes(xmlNode,htmlNode,oNode,sNodeName);var oTagProcessor=FCKXHtml.TagProcessors[sNodeName];if (oTagProcessor){oNode=oTagProcessor(oNode,htmlNode);if (!oNode) break;}else this._AppendChildNodes(oNode,htmlNode,FCKRegexLib.BlockElements.test(sNodeName));xmlNode.appendChild(oNode);break;case 3:var asPieces=htmlNode.nodeValue.replaceNewLineChars(' ').match(FCKXHtmlEntities.EntitiesRegex);if (asPieces){for (var i=0;i<asPieces.length;i++){if (asPieces[i].length==1){var sEntity=FCKXHtmlEntities.Entities[asPieces[i]];if (sEntity!=null){this._AppendEntity(xmlNode,sEntity);continue;};};xmlNode.appendChild(this.XML.createTextNode(asPieces[i]));};};break;case 8:xmlNode.appendChild(this.XML.createComment(htmlNode.nodeValue));break;default:xmlNode.appendChild(this.XML.createComment("Element not supported - Type: "+htmlNode.nodeType+" Name: "+htmlNode.nodeName));break;};};FCKXHtml._AppendSpecialItem=function(item){return '___FCKsi___'+FCKXHtml.SpecialBlocks.addItem(item);};FCKXHtml.TagProcessors=new Object();FCKXHtml.TagProcessors['img']=function(node){if (!node.attributes.getNamedItem('alt')) FCKXHtml._AppendAttribute(node,'alt','');return node;};FCKXHtml.TagProcessors['script']=function(node,htmlNode){if (!node.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(node,'type','text/javascript');node.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(htmlNode.text)));return node;};FCKXHtml.TagProcessors['style']=function(node,htmlNode){if (htmlNode.getAttribute('_fcktemp')) return null;if (!node.attributes.getNamedItem('type')) FCKXHtml._AppendAttribute(node,'type','text/css');node.appendChild(FCKXHtml.XML.createTextNode(FCKXHtml._AppendSpecialItem(htmlNode.innerHTML)));return node;};FCKXHtml.TagProcessors['title']=function(node,htmlNode){node.appendChild(FCKXHtml.XML.createTextNode(FCK.EditorDocument.title));return node;};FCKXHtml.TagProcessors['base']=function(node,htmlNode){if (htmlNode.getAttribute('_fcktemp')) return null;return node;};FCKXHtml.TagProcessors['link']=function(node,htmlNode){if (htmlNode.getAttribute('_fcktemp')) return null;return node;};FCKXHtml.TagProcessors['table']=function(node,htmlNode){var oClassAtt=node.attributes.getNamedItem('class');if (oClassAtt&&FCKRegexLib.TableBorderClass.test(oClassAtt.nodeValue)){var sClass=oClassAtt.nodeValue.replace(FCKRegexLib.TableBorderClass,'');if (sClass.length==0) node.attributes.removeNamedItem('class');else FCKXHtml._AppendAttribute(node,'class',sClass);};FCKXHtml._AppendChildNodes(node,htmlNode,false);return node;}
FCKXHtml._GetMainXmlString=function(){return this.MainNode.xml;};FCKXHtml._AppendEntity=function(xmlNode,entity){xmlNode.appendChild(this.XML.createEntityReference(entity));};FCKXHtml._AppendAttributes=function(xmlNode,htmlNode,node,nodeName){var aAttributes=htmlNode.attributes;for (var n=0;n<aAttributes.length;n++){var oAttribute=aAttributes[n];if (oAttribute.specified){var sAttName=oAttribute.nodeName.toLowerCase();if (sAttName=='_fckxhtmljob') continue;else if (sAttName=='style') var sAttValue=htmlNode.style.cssText;else if (sAttName=='class'||sAttName.indexOf('on')==0) var sAttValue=oAttribute.nodeValue;else if (nodeName=='body'&&sAttName=='contenteditable') continue;else if (oAttribute.nodeValue===true) sAttValue=sAttName;else var sAttValue=htmlNode.getAttribute(sAttName,2);if (FCKConfig.ForceSimpleAmpersand&&sAttValue.replace) sAttValue=sAttValue.replace(/&/g,'___FCKAmp___');this._AppendAttribute(node,sAttName,sAttValue);};};};FCKXHtml.TagProcessors['meta']=function(node,htmlNode){var oHttpEquiv=node.attributes.getNamedItem('http-equiv');if (oHttpEquiv==null||oHttpEquiv.value.length==0){var sHttpEquiv=htmlNode.outerHTML.match(FCKRegexLib.MetaHttpEquiv);if (sHttpEquiv){sHttpEquiv=sHttpEquiv[1];FCKXHtml._AppendAttribute(node,'http-equiv',sHttpEquiv);};};return node;};FCKXHtml.TagProcessors['font']=function(node,htmlNode){if (node.attributes.length==0) node=FCKXHtml.XML.createDocumentFragment();FCKXHtml._AppendChildNodes(node,htmlNode);return node;};FCKXHtml.TagProcessors['input']=function(node,htmlNode){if (htmlNode.name) FCKXHtml._AppendAttribute(node,'name',htmlNode.name);if (htmlNode.value&&!node.attributes.getNamedItem('value')) FCKXHtml._AppendAttribute(node,'value',htmlNode.value);return node;};FCKXHtml.TagProcessors['option']=function(node,htmlNode){if (htmlNode.selected&&!node.attributes.getNamedItem('selected')) FCKXHtml._AppendAttribute(node,'selected','selected');FCKXHtml._AppendChildNodes(node,htmlNode);return node;};FCKXHtml.TagProcessors['abbr']=function(node,htmlNode){var oNextNode=htmlNode.nextSibling;while (true){if (oNextNode&&oNextNode.nodeName!='/ABBR'){FCKXHtml._AppendNode(node,oNextNode);oNextNode=oNextNode.nextSibling;}else break;};return node;};FCKXHtml.TagProcessors['area']=function(node,htmlNode){if (!node.attributes.getNamedItem('coords')){var sCoords=htmlNode.getAttribute('coords',2);if (sCoords&&sCoords!='0,0,0') FCKXHtml._AppendAttribute(node,'coords',sCoords);};if (!node.attributes.getNamedItem('shape')){var sCoords=htmlNode.getAttribute('shape',2);if (sCoords&&sCoords.length>0) FCKXHtml._AppendAttribute(node,'shape',sCoords);};return node;};FCKXHtml.TagProcessors['label']=function(node,htmlNode){if (htmlNode.htmlFor.length>0) FCKXHtml._AppendAttribute(node,'for',htmlNode.htmlFor);FCKXHtml._AppendChildNodes(node,htmlNode);return node;};FCKXHtml.TagProcessors['form']=function(node,htmlNode){if (htmlNode.acceptCharset.length>0&&htmlNode.acceptCharset!='UNKNOWN') FCKXHtml._AppendAttribute(node,'accept-charset',htmlNode.acceptCharset);FCKXHtml._AppendChildNodes(node,htmlNode);return node;}
var FCKCodeFormatter=new Object();FCKCodeFormatter.Regex=new Object();FCKCodeFormatter.Regex.BlocksOpener=/\<(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI|TITLE|META|LINK|BASE|SCRIPT|LINK|TD|AREA|OPTION)[^\>]*\>/gi;FCKCodeFormatter.Regex.BlocksCloser=/\<\/(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|OL|UL|LI|TITLE|META|LINK|BASE|SCRIPT|LINK|TD|AREA|OPTION)[^\>]*\>/gi;FCKCodeFormatter.Regex.NewLineTags=/\<(BR|HR)[^\>]\>/gi;FCKCodeFormatter.Regex.MainTags=/\<\/?(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR|FONT)[^\>]*\>/gi;FCKCodeFormatter.Regex.LineSplitter=/\s*\n+\s*/g;FCKCodeFormatter.Regex.IncreaseIndent=/^\<(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR|UL|OL)[ \/\>]/i;FCKCodeFormatter.Regex.DecreaseIndent=/^\<\/(HTML|HEAD|BODY|FORM|TABLE|TBODY|THEAD|TR|UL|OL)[ \>]/i;FCKCodeFormatter.Regex.FormatIndentatorRemove=new RegExp(FCKConfig.FormatIndentator);FCKCodeFormatter.Format=function(html){var sFormatted=html.replace(this.Regex.BlocksOpener,'\n$&');;sFormatted=sFormatted.replace(this.Regex.BlocksCloser,'$&\n');sFormatted=sFormatted.replace(this.Regex.NewLineTags,'$&\n');sFormatted=sFormatted.replace(this.Regex.MainTags,'\n$&\n');var sIndentation='';var asLines=sFormatted.split(this.Regex.LineSplitter);sFormatted='';for (var i=0;i<asLines.length;i++){var sLine=asLines[i];if (sLine.length==0) continue;if (this.Regex.DecreaseIndent.test(sLine)) sIndentation=sIndentation.replace(this.Regex.FormatIndentatorRemove,'');sFormatted+=sIndentation+sLine+'\n';if (this.Regex.IncreaseIndent.test(sLine)) sIndentation+=FCKConfig.FormatIndentator;};return sFormatted.trim();}
FCK.Events=new FCKEvents(FCK);FCK.Toolbar=null;FCK.TempBaseTag=FCKConfig.BaseHref.length>0?'<base href="'+FCKConfig.BaseHref+'" _fcktemp="true"></base>':'';FCK.StartEditor=function(){this.EditorWindow=window.frames['eEditorArea'];this.EditorDocument=this.EditorWindow.document;if (FCKBrowserInfo.IsGecko) this.MakeEditable();this.SetHTML(FCKTools.GetLinkedFieldValue());FCKTools.AttachToLinkedFieldFormSubmit(this.UpdateLinkedField);this.SetStatus(FCK_STATUS_ACTIVE);};FCK.SetStatus=function(newStatus){this.Status=newStatus;if (newStatus==FCK_STATUS_ACTIVE){window.onfocus=window.document.body.onfocus=FCK.Focus;if (FCKConfig.StartupFocus) FCK.Focus();if (FCKBrowserInfo.IsIE) FCKScriptLoader.AddScript('js/fckeditorcode_ie_2.js');else FCKScriptLoader.AddScript('js/fckeditorcode_gecko_2.js');};this.Events.FireEvent('OnStatusChange',newStatus);if (this.OnStatusChange) this.OnStatusChange(newStatus);};FCK.GetHTML=function(format){var sHTML;if (FCK.EditMode==FCK_EDITMODE_WYSIWYG){if (FCKBrowserInfo.IsIE) sHTML=this.EditorDocument.body.innerHTML.replace(FCKRegexLib.ToReplace,'$1');else sHTML=this.EditorDocument.body.innerHTML;}else sHTML=document.getElementById('eSourceField').value;if (format) return FCKCodeFormatter.Format(sHTML);else return sHTML;};FCK.GetXHTML=function(format){var bSource=(FCK.EditMode==FCK_EDITMODE_SOURCE);if (bSource) this.SwitchEditMode();if (FCKConfig.FullPage) var sXHTML=FCKXHtml.GetXHTML(this.EditorDocument.getElementsByTagName('html')[0],true,format);else var sXHTML=FCKXHtml.GetXHTML(this.EditorDocument.body,false,format);if (bSource) this.SwitchEditMode();if (FCKBrowserInfo.IsIE) sXHTML=sXHTML.replace(FCKRegexLib.ToReplace,'$1');if (FCK.DocTypeDeclaration&&FCK.DocTypeDeclaration.length>0) sXHTML=FCK.DocTypeDeclaration+'\n'+sXHTML;if (FCK.XmlDeclaration&&FCK.XmlDeclaration.length>0) sXHTML=FCK.XmlDeclaration+'\n'+sXHTML;return sXHTML;};FCK.UpdateLinkedField=function(){if (FCKConfig.EnableXHTML) FCKTools.SetLinkedFieldValue(FCK.GetXHTML(FCKConfig.FormatOutput));else FCKTools.SetLinkedFieldValue(FCK.GetHTML(FCKConfig.FormatOutput));};FCK.ShowContextMenu=function(x,y){if (this.Status!=FCK_STATUS_COMPLETE) return;FCKContextMenu.Show(x,y);this.Events.FireEvent("OnContextMenu");};FCK.RegisteredDoubleClickHandlers=new Object();FCK.OnDoubleClick=function(element){var oHandler=FCK.RegisteredDoubleClickHandlers[element.tagName];if (oHandler){oHandler(element);};};FCK.RegisterDoubleClickHandler=function(handlerFunction,tag){FCK.RegisteredDoubleClickHandlers[tag.toUpperCase()]=handlerFunction;};
FCK.Description="FCKeditor for Internet Explorer 5.5+";FCK._BehaviorsStyle='<style type="text/css" _fcktemp="true"> \ TABLE	{ behavior: url('+FCKConfig.FullBasePath+'css/behaviors/showtableborders.htc) ; } \ A		{ behavior: url('+FCKConfig.FullBasePath+'css/behaviors/anchors.htc) ; } \ INPUT	{ behavior: url('+FCKConfig.FullBasePath+'css/behaviors/hiddenfield.htc) ; } \ </style>';FCK.InitializeBehaviors=function(dontReturn){this.EditorDocument.onmousedown=this.EditorDocument.onmouseup=function(){FCK.Focus();FCK.EditorWindow.event.cancelBubble=true;FCK.EditorWindow.event.returnValue=false;};this.EditorDocument.body.onpaste=function(){if (FCK.Status==FCK_STATUS_COMPLETE) return FCK.Events.FireEvent("OnPaste");else return false;};this.EditorDocument.oncontextmenu=function(){var e=this.parentWindow.event;FCK.ShowContextMenu(e.screenX,e.screenY);return false;};if (FCKConfig.UseBROnCarriageReturn||FCKConfig.TabSpaces>0){if (FCKConfig.TabSpaces>0){window.FCKTabHTML='';for (i=0;i<FCKConfig.TabSpaces;i++) window.FCKTabHTML+="&nbsp;";};this.EditorDocument.onkeydown=function(){var e=FCK.EditorWindow.event;if (e.keyCode==13&&FCKConfig.UseBROnCarriageReturn){if ((e.ctrlKey||e.altKey||e.shiftKey)) return true;else{if (FCK.EditorDocument.queryCommandState('InsertOrderedList')||FCK.EditorDocument.queryCommandState('InsertUnorderedList')) return true;FCK.InsertHtml("<br>&nbsp;");var oRange=FCK.EditorDocument.selection.createRange();oRange.moveStart('character',-1);oRange.select();FCK.EditorDocument.selection.clear();return false;};}else if (e.keyCode==9&&FCKConfig.TabSpaces>0&&!(e.ctrlKey||e.altKey||e.shiftKey)){FCK.InsertHtml(window.FCKTabHTML);return false;};return true;};};this.EditorDocument.ondblclick=function(){FCK.OnDoubleClick(FCK.EditorWindow.event.srcElement);FCK.EditorWindow.event.cancelBubble=true;};this.EditorDocument.onselectionchange=function(){FCK.Events.FireEvent("OnSelectionChange");};};FCK.Focus=function(){try{if (FCK.EditMode==FCK_EDITMODE_WYSIWYG) FCK.EditorDocument.body.focus();else document.getElementById('eSourceField').focus();}catch(e) {};};FCK.SetHTML=function(html,forceWYSIWYG){if (forceWYSIWYG||FCK.EditMode==FCK_EDITMODE_WYSIWYG){this.EditorDocument.open();if (FCKConfig.FullPage){var sExtraHtml=FCK._BehaviorsStyle+'<link href="'+FCKConfig.FullBasePath+'css/fck_internal.css'+'" rel="stylesheet" type="text/css" _fcktemp="true" />';if (FCK.TempBaseTag.length>0&&!FCKRegexLib.HasBaseTag.test(html)) sExtraHtml+=FCK.TempBaseTag;html=html.replace(FCKRegexLib.HeadCloser,sExtraHtml+'</head>');this.EditorDocument.write(html);}else{var sHtml='<html dir="'+FCKConfig.ContentLangDirection+'">'+'<head><title></title>'+'<link href="'+FCKConfig.EditorAreaCSS+'" rel="stylesheet" type="text/css" />'+'<link href="'+FCKConfig.FullBasePath+'css/fck_internal.css'+'" rel="stylesheet" type="text/css" _fcktemp="true" />';sHtml+=FCK._BehaviorsStyle;sHtml+=FCK.TempBaseTag;sHtml+='</head><body>'+html+'</body></html>';this.EditorDocument.write(sHtml);};this.EditorDocument.close();this.InitializeBehaviors();this.EditorDocument.body.contentEditable=true;this.Events.FireEvent('OnAfterSetHTML');}else document.getElementById('eSourceField').value=html;};FCK.InsertHtml=function(html){FCK.Focus();var oSel=FCK.EditorDocument.selection;if (oSel.type.toLowerCase()!="none") oSel.clear();oSel.createRange().pasteHTML(html);};

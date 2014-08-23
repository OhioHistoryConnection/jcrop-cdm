/**
 * Javascript to create crop button and open window for JCrop tool
 * Phil Sager <psager@ohiohistory.org>
 */

// path to where this file and all other jcrop files will be found
var JCROPPATH = '/ui/custom/default/collection/default/resources/custompages/jcrop';

function openCropWindow() {
	var cdm_collection = $("#cdm_collection").val();
	var cdm_show = "";
	$('#cdm_show').length ? cdm_show = $("#cdm_show").val() : cdm_show = $("#cdm_id").val();
	var page_url = JCROPPATH + "/crop_image.php?collection=" + cdm_collection + "&show=" + cdm_show;
	window.open(page_url, "clipWindow", "location=1,status=1,toolbar=1,menubar=1,scrollbars=1,width=1100,height=1000");
}

$(document).ready(function() {
	$("div#imageviewer_toolbar > ul").css("width", "415px");
	$('<li class="imageviewer_sep">&nbsp;</li>').appendTo($('#imageviewer_toolbar ul'));
	$('<li><input id="toolbar_clip" type="image" src="' + JCROPPATH + '/toolbar_clip_out.gif" onmouseover="this.src = \'' + JCROPPATH + '/toolbar_clip_over.gif\'" onmouseout="this.src = \''+ JCROPPATH + '/toolbar_clip_out.gif\'" onmousedown="openCropWindow()" onclick="return false" onfocus="this.onmouseover()" onblur="this.onmouseout()" title="Clip" border="0"  /></li>').appendTo($("div#imageviewer_toolbar > ul")); 
});


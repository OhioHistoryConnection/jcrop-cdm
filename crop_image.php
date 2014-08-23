<?php
/**
 * Code to integrate Jcrop with CONTENTdm
 * Phil Sager <psager@ohiohistory.org>
 */

// URL for your institution's CONTENTdm instance
$CONTENTDM_HOME = "http://cdm16007.contentdm.oclc.org";
// URL for your institution's CONTENTdm web services
$WEBSERVICES = "https://server16007.contentdm.oclc.org";
// path to the directory where this file and all other jcrop files will be found
$JCROP_PATH = "/ui/custom/default/collection/default/resources/custompages/jcrop";
// path to your CONTENTdm system's JQuery file (i.e. something like
// /ui/cdm/default/collection/default/js/jquery_1.7.2/jquery-1.7.2.js, or a link
// to a CDN source for JQuery like Google.
$CDM_JQUERY_PATH = "http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// collect image data from crop for GD processing
	$cdm_collection = preg_replace('/^([a-zA-Z0-9]{1,20}).*$/','$1',$_POST['collection']);
	$cdm_show = preg_replace('/^([\d]{1,10}).*$/','$1',$_POST['show']);
	$cdm_scale = preg_replace('/^([\d\.]{1,10}).*$/','$1',$_POST['scale']);
	$img_width = preg_replace('/^([\d\.]{1,10}).*$/','$1',$_POST['img_width']);
	$img_height = preg_replace('/^([\d\.]{1,10}).*$/','$1',$_POST['img_height']);
	$full_width = preg_replace('/^([\d]{1,10}).*$/','$1',$_POST['full_width']);
	$full_height = preg_replace('/^([\d]{1,10}).*$/','$1',$_POST['full_height']);
	$targ_w = $_POST['w'];
	$targ_h = $_POST['h'];
	$pos_x = $_POST['x'];
	$pos_y = $_POST['y'];
	$jpeg_quality = 60;
	
	// dimensions and positions on the smaller cropped image need to be scaled proportionally to the full resolution image
	$targ_w = ($targ_w * $full_width)/$img_width;
	$targ_h = ($targ_h * $full_height)/$img_height;
	$pos_x = ($pos_x * $full_width)/$img_width;
	$pos_y = ($pos_y * $full_height)/$img_height;
	
	echo('<html><head>');
	echo('<script src="'.$CDM_JQUERY_PATH.'"></script>');
	echo('</head><body>');
	echo('<div id="baseimage" style="text-align:center"><div id="loader"><img src="'.$JCROP_PATH.'/loader_white.gif" alt="loader"></div></div>');
	echo('<img onload="$(\'#baseimage\').hide()" src="'.$CONTENTDM_HOME.'/utils/ajaxhelper/?CISOROOT='.$cdm_collection.'&CISOPTR='.$cdm_show.'&action=2&DMSCALE=100&DMWIDTH='.$targ_w.'&DMHEIGHT='.$targ_h.'&DMX='.$pos_x.'&DMY='.$pos_y.'">"');
	echo('</body></html>');
	
	exit;
	
} else {
	
	// get basic item data
	$cdm_collection = preg_replace('/^([a-zA-Z0-9]{1,20}).*$/','$1',$_GET['collection']);
	$cdm_show = preg_replace('/^([\d]{1,10}).*$/','$1',$_GET['show']);
	//http://localhost/ui/custom/default/collection/default/resources/custompages/jcrop/crop_image.php?collection=p267401coll32&show=8560	
	// curl to get dimensions and scale to present image in crop window
	$curl_url = $WEBSERVICES."/dmwebservices/index.php?q=dmGetImageInfo/".$cdm_collection."/".$cdm_show."/xml";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $curl_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_TIMEOUT, 240); // php.ini max_execution_time = 1800 seconds
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$cdm_data_xml = curl_exec($ch);
	$xml = simplexml_load_string($cdm_data_xml);
	$cdm_data_json = json_encode($xml);
	
	$image_info = json_decode($cdm_data_json, true);
	$img_size = 1000;
	$pct = "20";
	$imgwidth = $image_info['width'];
	$imgheight = $image_info['height'];
	if($imgwidth > $img_size) {
		$pct = (($img_size * 100)/$imgwidth);
		$img_height = round((($imgheight*$pct)/100));
		//$img_width = round((($imgwidth*$pct)/100));
		$img_width = $img_size;
	} else {
		$img_width = $imgwidth;
		$img_height = $imgheight;
	}
	
}

// If not a POST request, display page below:

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.12/js/jquery.Jcrop.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.12/css/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo($JCROP_PATH) ?>/jcrop_page.css" type="text/css" />

		<script language="Javascript">

			$(function(){

				$('#cropbox').Jcrop({
					//aspectRatio: 1,
					onSelect: updateCoords
				});

			});

			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				$('#baseimage').hide();
				return false;
			};
			
		</script>

	</head>

	<body>

	<div id="outer">
	<div class="jcExample">
	<div class="article">

		<h1>Click and drag to select an area, then click the crop button</h1>
		<div id="baseimage" style="text-align:center"><div id="loader"><img src="<?php echo($JCROP_PATH) ?>/loader_white.gif" alt="loader"></div></div>
  
		<!-- This is the form that our event handler fills -->
		<form action="crop_image.php" method="post" onsubmit="$('#baseimage').show();return checkCoords()">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" id="img_height" name="img_height" value="<?php echo($img_height) ?>"/>
			<input type="hidden" id="img_width" name="img_width" value="<?php echo($img_width) ?>" />
			<input type="hidden" id="full_height" name="full_height" value="<?php echo($imgheight) ?>"/>
			<input type="hidden" id="full_width" name="full_width" value="<?php echo($imgwidth) ?>" />
			<input type="hidden" id="cdm_collection" name="collection" value="<?php echo($cdm_collection) ?>" />
			<input type="hidden" id="cdm_id" name="scale" value="<?php echo($pct) ?>" />
			<input type="hidden" id="cdm_show" name="show" value="<?php echo($cdm_show) ?>" />
			<input type="submit" value="Crop" style="font-size:120%;font-weight:bold;color: #900;" />
		</form>

		<!-- This is the image we're attaching Jcrop to -->
		<img onload="$('#baseimage').hide()" src="<?php echo($CONTENTDM_HOME) ?>/utils/getprintimage/collection/<?php echo($cdm_collection) ?>/id/<?php echo($cdm_show) ?>/scale/<?php echo($pct) ?>/width/<?php echo($img_width) ?>/height/<?php echo($img_height) ?>" id="cropbox" />
		
	</div>
	</div>
	</div>
	</body>

</html>

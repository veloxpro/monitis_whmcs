<?php

function hook_AdminAreaHeadOutput($vars) {
	$head = '';
	$head .= '<script type="text/javascript" src="../modules/addons/monitis_addon/static/js/jquery.validate.min.js"></script>';
	$head .= '<script type="text/javascript" src="../modules/addons/monitis_addon/static/js/highcharts.js"></script>';
	$head .= '<script type="text/javascript" src="../modules/addons/monitis_addon/static/js/monitis.js"></script>';
	$head .= '<link href="../modules/addons/monitis_addon/static/css/monitis.css" rel="stylesheet" type="text/css" />';
	
	//$head .= '<script type="text/javascript" src="../modules/addons/monitis_addon/static/js/jquery.validate.min.js"></script>';
	//$head .= '<link href="../modules/addons/monitis_addon/static/css/chosen.css" rel="stylesheet" type="text/css" />';
	return $head;
}

add_hook("AdminAreaHeadOutput", 1, "hook_AdminAreaHeadOutput");
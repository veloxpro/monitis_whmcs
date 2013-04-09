<?php
// FOR DEBUG
//error_reporting(-1);
error_reporting(E_ALL & ~E_NOTICE);

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

require_once 'MonitisApp.php';

function monitis_addon_config() {
	$configarray = array(
		"name" => "Monitis Addon",
		"description" => "www.monitis.com monitoring services",
		"version" => "1.0",
		"author" => "Monitis",
		"logo" => '../modules/addons/monitis_addon/static/img/logo-big.png',
		"language" => "english",
			"fields" => array(
				"confdescription" => array (
						"FriendlyName" => "",
						"Description" => "<b>Please grant access to your user from below checkboxes and save changes.<br/>
							After that go to Addons->Monitis Addon to finish setup.</b>",
				)
    ));
	return $configarray;
}

/*
 * Handle addon activation
 */
function monitis_addon_activate() {
	$query = "CREATE TABLE  `mod_monitis_conf` (
				`conf` VARCHAR( 255 ) NOT NULL ,
				`value` TEXT NOT NULL ,
				PRIMARY KEY (  `conf` )
				) ENGINE = MYISAM ;";
	$result = mysql_query($query);
	
	$query = "CREATE TABLE  `mod_monitis_servers` (
				`server_id` INT NOT NULL ,
				`test_ids` TEXT NOT NULL ,
				PRIMARY KEY (  `server_id` )
				) ENGINE = MYISAM ;";
	$result = mysql_query($query);
	
	MonitisConf::setupDB();
	
	return array('status'=>'success','description'=>'Monitis addon activation successful');
}

function monitis_addon_deactivate() {
	$query = "DROP TABLE `mod_monitis_conf`, `mod_monitis_servers`";
	$result = mysql_query($query);
	
	return array('status'=>'success','description'=>'Monitis addon deactivation successful');
}

function monitis_addon_output($vars) {
	MonitisRouter::route();
}

function monitis_addon_sidebar() {
	//$modulelink = $vars['modulelink'];
	$sidebar = <<<EOF
	<span class="header">
    <img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" />Monitis Links</span>
	<ul class="menu">
		<li><a href="http://portal.monitis.com/">Monitis Dashboard</a></li>
	</ul>
EOF;
	return $sidebar;
}
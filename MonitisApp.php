<?php
define('MONITIS_APP_PATH', dirname(realpath(__FILE__)));
define('MONITIS_APP_URL', '?module=monitis_addon');

function _dump($var) {
	echo "<div style='border: 2px solid #ccc; padding: 3px; margin: 2px; text-align: left;'><pre>";
	var_dump($var);
	echo "</pre></div>";
}
function monitisGet($varName, $default = '') {
	return isset($_GET[$varName]) ? $_GET[$varName] : $default;
}
function monitisGetInt($varName, $default = 0) {
	return isset($_GET[$varName]) ? (int)$_GET[$varName] : $default;
}
function monitisPost($varName, $default = '') {
	return isset($_POST[$varName]) ? $_POST[$varName] : $default;
}
function monitisPostInt($varName, $default = 0) {
	return isset($_POST[$varName]) ? (int)$_POST[$varName] : $default;
}
$monitisErrors = array();
function monitisPrintErrors() {
	foreach ($monitisErrors as $error) {
		echo $error;
	}
}
function monitisAddError($msg) {
	$monitisErrors[] = $msg;
}

/*function monitisLoadModule($name, $vars = array(), $render = true) {
	extract($vars);
	if ($render)
		include MONITIS_APP_PATH . '/modules/' . $name . '/' . $name . '.php';
	else {
		ob_start();
		include MONITIS_APP_PATH . '/modules/' . $name . '/' . $name . '.php';
		$content = ob_get_clean();
		return $content;
	}
}*/

// Includes lib
require_once 'lib/MonitisConf.php';
require_once 'lib/MonitisApi.php';
require_once 'lib/MonitisRouter.php';
require_once 'lib/Highcharts/Highchart.php';
require_once 'lib/MonitisChart.php';
require_once 'lib/MonitisChartExternal.php';
require_once 'lib/MonitisChartPing.php';
require_once 'lib/MonitisChartHttp.php';
require_once 'lib/MonitisChartHttps.php';
require_once 'lib/MonitisWidgetContainer.php';
require_once 'lib/MonitisApiHelper.php';

MonitisConf::load();

class MonitisApp {
	private static $errors = array();
	private static $warnings = array();
	private static $messages = array();
	
	static function addError($msg) {
		self::$errors[] = $msg;
	}
	
	static function addMessage($msg) {
		self::$messages[] = $msg;
	}
	
	static function addWarning($msg) {
		self::$warnings[] = $msg;
	}
	
	static function printErrors() {
		foreach (self::$errors as $error) {
			echo '<div style="
					margin:10px 5px;
					padding: 5px;
					background-color: #FBEEEB;
					border: 1px dashed #cc0000;
					font-weight: bold;
					color: #cc0000;
					font-size:14px;
					text-align: center;
					-moz-border-radius: 5px;
					-webkit-border-radius: 5px;
					-o-border-radius: 5px;
					border-radius: 5px;">'
				. $error . '<div class="notification_x"></div></div>';
		}
	}
	static function printMessages() {
		foreach (self::$messages as $msg) {
			echo '<div style="
					margin:10px 5px;
					padding: 5px;
					background-color: #dff0d8;
					border: 1px solid #b9cfa7;
					font-weight: bold;
					color: #468847;
					font-size:14px;
					text-align: center;
					-moz-border-radius: 5px;
					-webkit-border-radius: 5px;
					-o-border-radius: 5px;
					border-radius: 5px;">'
					. $msg . '<span class="notification_x">×</div></span>';
		}
	}
	static function printWarnings() {
		foreach (self::$warnings as $warning) {
			echo '<div style="
					margin:10px 5px;
					padding: 5px;
					background-color: #FCF8E3;
					border: 1px solid #efc987;
					font-weight: bold;
					color: #C09853;
					font-size:14px;
					text-align: center;
					-moz-border-radius: 5px;
					-webkit-border-radius: 5px;
					-o-border-radius: 5px;
					border-radius: 5px;">'
					. $warning . '<div class="notification_x"></div></div>';
		}
	}
	
	static function printNotifications() {
		self::printErrors();
		self::printWarnings();
		self::printMessages();
	}
	
	static function redirect($url) {
		header('location:' . $url);
		exit;
	}
	
	static function getModule($moduleName) {
		$className = 'MonitisModule' . $moduleName;
		require_once MONITIS_APP_PATH . '/modules/' . $moduleName . '/' . $moduleName . '.php';
		return new $className;
	}
}
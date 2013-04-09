<?php
class MonitisConf {
	private static $configs = array('apiKey', 'secretKey', 'newServerMonitors');
	
	static $apiKey = '';
	static $secretKey = '';
	static $newServerMonitors = 'ping,http';
	
	static function update($conf, $value) {
		if (!in_array($conf, self::$configs))
			return;
		self::$$conf = $value;
		$update = array('value' => self::$$conf);
		$where = array('conf' => $conf);
		update_query('mod_monitis_conf',$update,$where);
	}
	
	static function load() {
		$confNames = array_map(function($v){ return '"' . $v . '"'; }, self::$configs);
		$confNames = implode(',', $confNames);
		$res = mysql_query('SELECT conf, value FROM mod_monitis_conf WHERE conf IN (' . $confNames . ')');
		while ($row = mysql_fetch_assoc($res)) {
			self::${$row['conf']} = $row['value'];
		}
	}
	
	/*static function checkRequired() {
		if (empty(self::$apiKey) || empty(self::$secretKey))
			return false;
		return true;
	}*/
	
	static function setupDB() {
		foreach (self::$configs as $config) {
			$values = array('conf' => $config, 'value' => self::$$config);
			insert_query('mod_monitis_conf', $values);
		}
	}
}
<?php
class MonitisApiHelper {
	static function getExternalLocationsGroupedByCountry() {
		$locations = MonitisApi::getExternalLocations();
		$americasIDs = array(1, 3, 5, 9, 10, 14, 15, 17, 26, 27);
		$europeIDs = array(2, 4, 7, 11, 12, 18, 19, 22, 23, 24, 25, 28, 29);
		$asiaIDs = array(8, 13, 16, 21);
		
		$loc = array('Americas' => array(), 'Europe' => array(), 'Asia' => array(), 'Other' => array());
		foreach ($locations as $l) {
			if (in_array($l['id'], $americasIDs))
				$loc['Americas'][$l['id']] = $l;
			elseif (in_array($l['id'], $europeIDs))
			$loc['Europe'][$l['id']] = $l;
			elseif (in_array($l['id'], $asiaIDs))
			$loc['Asia'][$l['id']] = $l;
			else
				$loc['Other'][$l['id']] = $l;
		}
		return $loc;
	}
	
	static function addDefaultPing($server) {
		$name = $server['name'] . '_ping';
		$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
		$interval = 1;
		$timeout = 1000;
		$locationIDs = array(1, 9, 10);
		$tag = $server['name'] . '_whmcs';
		if (empty($url))
			return false;
		
		$resp = MonitisApi::createExternalPing($name, $url, $interval, $timeout, $locationIDs, $tag);
		//_dump($resp);
		if (@$resp['status'] == 'ok' || @$resp['error'] == 'monitorUrlExists') {
			$newID = $resp['data']['testId'];
			
			$res = mysql_query(sprintf('SELECT server_id, test_ids FROM mod_monitis_servers WHERE server_id=%d', $server['id']));
			$dbServ = mysql_fetch_array($res);
			if ($dbServ === false) {
				$values = array("server_id" => $server['id'], "test_ids" => $newID);
				insert_query('mod_monitis_servers', $values);
			} else {
				$dbServTestids = explode(',', $dbServ['test_ids']);
				$dbServTestids[] = $newID;
				$dbServTestids = array_unique($dbServTestids);
	
				$update = array("test_ids" => implode(',', $dbServTestids));
				$where = array("server_id" => $server['id']);
				update_query('mod_monitis_servers', $update, $where);
			}
			return true;
		}
		
		return false;
	}
	static function addDefaultHttp($server) {
		$name = $server['name'] . '_http';
		$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
		$interval = 1;
		$timeout = 10;
		$locationIDs = array(1, 9, 10);
		$tag = $server['name'] . '_whmcs';
		if (empty($url))
			return false;
		
		$resp = MonitisApi::createExternalHttp($name, $url, $interval, $timeout, $locationIDs, $tag);
		//_dump($resp);
		if (@$resp['status'] == 'ok' || @$resp['error'] == 'monitorUrlExists') {
			$newID = $resp['data']['testId'];
	
			$res = mysql_query(sprintf('SELECT server_id, test_ids FROM mod_monitis_servers WHERE server_id=%d', $server['id']));
			$dbServ = mysql_fetch_array($res);
			if ($dbServ === false) {
				$values = array("server_id" => $server['id'], "test_ids" => $newID);
				insert_query('mod_monitis_servers', $values);
			} else {
				$dbServTestids = explode(',', $dbServ['test_ids']);
				$dbServTestids[] = $newID;
				$dbServTestids = array_unique($dbServTestids);
					
				$update = array("test_ids" => implode(',', $dbServTestids));
				$where = array("server_id" => $server['id']);
				update_query('mod_monitis_servers', $update, $where);
			}
			return true;
		}
		
		return false;
	}
	static function addDefaultHttps($server) {
		$name = $server['name'] . '_https';
		$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
		$interval = 1;
		$timeout = 10;
		$locationIDs = array(1, 9, 10);
		$tag = $server['name'] . '_whmcs';
		if (empty($url))
			return false;
		
		$resp = MonitisApi::createExternalHttps($name, $url, $interval, $timeout, $locationIDs, $tag);
		if (@$resp['status'] == 'ok' || @$resp['error'] == 'monitorUrlExists') {
			$newID = $resp['data']['testId'];
				
			$res = mysql_query(sprintf('SELECT server_id, test_ids FROM mod_monitis_servers WHERE server_id=%d', $server['id']));
			//_dump($resp);
			$dbServ = mysql_fetch_array($res);
			if ($dbServ === false) {
				$values = array("server_id" => $server['id'], "test_ids" => $newID);
				insert_query('mod_monitis_servers', $values);
			} else {
				$dbServTestids = explode(',', $dbServ['test_ids']);
				$dbServTestids[] = $newID;
				$dbServTestids = array_unique($dbServTestids);
					
				$update = array("test_ids" => implode(',', $dbServTestids));
				$where = array("server_id" => $server['id']);
				update_query('mod_monitis_servers', $update, $where);
			}
			return true;
		}
		
		return false;
	}
	
	static function addAllDefault($server) {
		$monitorTypes = explode(',', MonitisConf::$newServerMonitors);
		foreach ($monitorTypes as $type) {
			switch ($type) {
				case 'ping':
					self::addDefaultPing($server);
					break;
				case 'http':
					self::addDefaultHttp($server);
					break;
				case 'https':
					self::addDefaultHttps($server);
					break;
			}
		}
	}
}
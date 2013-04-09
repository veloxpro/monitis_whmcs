<?php
class MonitisApi {
	static $endpoint = 'https://api.monitis.com/api';
	//static $endpoint = 'http://prelive.monitis.com/api';
	
	static function requestGet($action, $params) {
		// TODO: error handling when JSON is not returned
		$params['version'] = '2';
		$params['action'] = $action;
		$params['apikey'] = MonitisConf::$apiKey;
		$query = http_build_query($params);
		$ch = curl_init(self::$endpoint . '?' . $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$json = json_decode(curl_exec($ch), true);
		return $json;
	}
	
	static function requestPost($action, $params) {
		// TODO: error handling when JSON is not returned
		$params['version'] = '2';
		$params['action'] = $action;
		$params['apikey'] = MonitisConf::$apiKey;
		$params['timestamp'] = date("Y-m-d H:i:s");
		$params = self::hmacSign($params);
		$query = http_build_query($params);
		$ch = curl_init(self::$endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		$result = curl_exec($ch);
		$json = json_decode($result, true);
		return $json;
	}
	
	static function hmacSign($params) {
		ksort($params);
		$joined = '';
		foreach ($params as $k => $v)
			$joined .= $k . $v;
		$checksum =  base64_encode(hash_hmac('sha1', $joined, MonitisConf::$secretKey, TRUE));
		$params['checksum'] = $checksum;
		return $params;
	}
	
	static function checkKeysValid($apiKey, $secretKey) {
		$params['version'] = '2';
		$params['action'] = 'secretkey';
		$params['apikey'] = $apiKey;
		$query = http_build_query($params);
		$ch = curl_init(self::$endpoint . '?' . $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$json = json_decode(curl_exec($ch), true);
		if (isset($json['secretkey']) && $json['secretkey'] == $secretKey)
			return true;
		return false;
	}
	
	/*
	 * $type "http", "https", "ftp", "ping", "ssh", "dns", "mysql", "udp", "tcp", "sip", "smtp", "imap", "pop"
	 */
	static function addExternalMonitor($type, $name, $url, $interval, $locationIds, $tag) {
		$params = array(
				'type' => $type,
				'name' => $name,
				'url' => $url,
				'interval' => $interval,
				'locationIds' => $locationIds,
				'tag' => $tag
				);
		$resp = self::requestPost('addExternalMonitor', $params);
		if ($resp['status'] == 'ok')
			return $resp['data']['testId'];
		else
			return 0;
	}
	
	static function getExternalMonitors() {
		return self::requestGet('tests', array());
	}
	
	static function getExternalMonitorInfo($monitorID) {
		return self::requestGet('testinfo', array('testId' => $monitorID));
	}
	
	static function getExternalSnapshot() {
		return self::requestGet('testsLastValues', array());
	}
	
	static function getExternalResults($monitorID, $day, $month, $year, $locationIDs = array(), $timezone = 0) {
		$params = array(
				'testId' => $monitorID,
				'day' => $day,
				'month' => $month,
				'year' => $year,
				'locationIds' => $locationIDs,
				'timezone' => $timezone
				);
		return self::requestGet('testresult', $params);
	}
	
	static function getExternalLocations() {
		return self::requestGet('locations', array());
	}
	
	static function createExternalPing($name, $url, $interval, $timeout, $locationIDs, $tag, $uptimeSLA='', $responseSLA='') {
		$params = array(
				'type' => 'ping',
				);
		empty($name) || $params['name'] = $name;
		empty($url) || $params['url'] = $url;
		empty($interval) || $params['interval'] = $interval;
		empty($timeout) || $params['timeout'] = $timeout;
		empty($locationIDs) || $params['locationIds'] = implode(',', $locationIDs);
		empty($tag) || $params['tag'] = $tag;
		
		empty($uptimeSLA) || $params['uptimeSLA'] = $uptimeSLA;
		empty($responseSLA) || $params['responseSLA'] = $responseSLA;
		//_dump($params);
		return self::requestPost('addExternalMonitor', $params);
	}
	static function createExternalHttp($name, $url, $interval, $timeout, $locationIDs, $tag,
			$uptimeSLA='', $responseSLA='', $detailedTestType='', $contentMatchFlag='', $postData='', $basicAuthUser='', $basicAuthPass='') {
		$params = array(
				'type' => 'http',
		);
		empty($name) || $params['name'] = $name;
		empty($url) || $params['url'] = $url;
		empty($interval) || $params['interval'] = $interval;
		empty($timeout) || $params['timeout'] = $timeout;
		empty($locationIDs) || $params['locationIds'] = implode(',', $locationIDs);
		empty($tag) || $params['tag'] = $tag;
	
		empty($uptimeSLA) || $params['uptimeSLA'] = $uptimeSLA;
		empty($responseSLA) || $params['responseSLA'] = $responseSLA;
		empty($detailedTestType) || $params['detailedTestType'] = $detailedTestType;
		empty($contentMatchFlag) || $params['contentMatchFlag'] = $detailedTestType;
		empty($postData) || $params['postData'] = $postData;
		empty($basicAuthUser) || $params['basicAuthUser'] = $basicAuthUser;
		empty($basicAuthPass) || $params['basicAuthPass'] = $basicAuthPass;
		//_dump($params);
		return self::requestPost('addExternalMonitor', $params);
	}
	static function createExternalHttps($name, $url, $interval, $timeout, $locationIDs, $tag,
			$uptimeSLA='', $responseSLA='', $detailedTestType='', $contentMatchFlag='', $postData='', $basicAuthUser='', $basicAuthPass='') {
		$params = array(
				'type' => 'https',
		);
		empty($name) || $params['name'] = $name;
		empty($url) || $params['url'] = $url;
		empty($interval) || $params['interval'] = $interval;
		empty($timeout) || $params['timeout'] = $timeout;
		empty($locationIDs) || $params['locationIds'] = implode(',', $locationIDs);
		empty($tag) || $params['tag'] = $tag;
	
		empty($uptimeSLA) || $params['uptimeSLA'] = $uptimeSLA;
		empty($responseSLA) || $params['responseSLA'] = $responseSLA;
		empty($detailedTestType) || $params['detailedTestType'] = $detailedTestType;
		empty($contentMatchFlag) || $params['contentMatchFlag'] = $detailedTestType;
		empty($postData) || $params['postData'] = $postData;
		empty($basicAuthUser) || $params['basicAuthUser'] = $basicAuthUser;
		empty($basicAuthPass) || $params['basicAuthPass'] = $basicAuthPass;
		//_dump($params);
		return self::requestPost('addExternalMonitor', $params);
	}
}
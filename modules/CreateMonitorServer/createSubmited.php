<?php
$type = monitisPost('type');
switch ($type) {
	case 'ping' :
		$name = monitisPost('name');
		$url = monitisPost('url');
		$interval = monitisPostInt('interval');
		$timeout = monitisPostInt('timeout');
		$locationIDs = isset($_POST['locationIDs']) ? $_POST['locationIDs'] : array();
		$tag = monitisPost('tag');
		$uptimeSLA = monitisPostInt('uptimeSLA');
		$responseSLA = monitisPostInt('responseSLA');
		$resp = MonitisApi::createExternalPing($name, $url, $interval, $timeout, $locationIDs, $tag, $uptimeSLA, $responseSLA);
		break;
	case 'http' :
		$name = monitisPost('name');
		$url = monitisPost('url');
		$detailedTestType = monitisPost('detailedTestType');
		$postData = monitisPost('postData');
		$contentMatchFlag = monitisPost('contentMatchFlag');
		$contentMatchString = monitisPost('contentMatchString');
		$interval = monitisPostInt('interval');
		$timeout = monitisPostInt('timeout');
		$locationIDs = isset($_POST['locationIDs']) ? $_POST['locationIDs'] : array();
		$tag = monitisPost('tag');
		$uptimeSLA = monitisPostInt('uptimeSLA');
		$responseSLA = monitisPostInt('responseSLA');
		$basicAuthUser = monitisPostInt('basicAuthUser');
		$basicAuthPass = monitisPostInt('basicAuthPass');
		$resp = MonitisApi::createExternalHttp($name, $url, $interval, $timeout, $locationIDs, $tag,
			$uptimeSLA, $responseSLA, $detailedTestType, $contentMatchFlag, $postData, $basicAuthUser, $basicAuthPass);
		break;
	case 'https' :
		$name = monitisPost('name');
		$url = monitisPost('url');
		$detailedTestType = monitisPost('detailedTestType');
		$postData = monitisPost('postData');
		$contentMatchFlag = monitisPost('contentMatchFlag');
		$contentMatchString = monitisPost('contentMatchString');
		$interval = monitisPostInt('interval');
		$timeout = monitisPostInt('timeout');
		$locationIDs = isset($_POST['locationIDs']) ? $_POST['locationIDs'] : array();
		$tag = monitisPost('tag');
		$uptimeSLA = monitisPostInt('uptimeSLA');
		$responseSLA = monitisPostInt('responseSLA');
		$basicAuthUser = monitisPostInt('basicAuthUser');
		$basicAuthPass = monitisPostInt('basicAuthPass');
		$resp = MonitisApi::createExternalHttps($name, $url, $interval, $timeout, $locationIDs, $tag,
				$uptimeSLA, $responseSLA, $detailedTestType, $contentMatchFlag, $postData, $basicAuthUser, $basicAuthPass);
		break;
}
_dump($resp);
if ($resp['status'] == 'ok') {
	MonitisApp::addMessage('Monitor successfully created and asociated with this server');
	$res = mysql_query(sprintf('SELECT * FROM mod_monitis_servers WHERE server_id=%d', $serverID));
	$row = mysql_fetch_assoc($res);
	if ($row === false) {
		$values = array("server_id" => $serverID, "test_ids" => $resp['data']['testId']);
		insert_query('mod_monitis_servers', $values);
	} else {
		$testIDs = explode(',', $row['test_ids']);
		if (!in_array($resp['data']['testId'], $testIDs))
			$testIDs[] = $resp['data']['testId'];
		
		$update = array("test_ids" => implode(',', $testIDs));
		$where = array("server_id" => $row['server_id']);
		update_query('mod_monitis_servers', $update, $where);
	}
} else
	MonitisApp::addError('Unable to create monitor, API request failed');	

self::render('default');
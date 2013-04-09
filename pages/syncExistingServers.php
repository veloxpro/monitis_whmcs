<?php
	$servers = array();
	$res = mysql_query('SELECT id, name, ipaddress, hostname,
			nameserver1, nameserver1ip,  nameserver2, nameserver2ip,
			nameserver3, nameserver3ip,  nameserver4, nameserver4ip,
			nameserver5, nameserver5ip
			FROM tblservers');
	while($s = mysql_fetch_array($res)) {
		$servers[$s['id']] = $s;
	}
	$serverCount = count($servers);
	
	if ($serverCount < 1)
		MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=servers');

	if (isset($_POST['sync'])) {
		if($_POST['sync']) {
			$monitorTypes = explode(',', MonitisConf::$newServerMonitors);
			
			// Adding Ping monitors
			if (in_array('ping', $monitorTypes)) {
				foreach ($servers as $server) {
					$name = $server['name'] . '_ping';
					$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
					$interval = 1;
					$timeout = 1000;
					$locationIDs = array(1, 9, 10);
					$tag = $server['name'] . '_whmcs';
					if (!empty($url)) {
						$resp = MonitisApi::createExternalPing($name, $url, $interval, $timeout, $locationIDs, $tag);
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
						}
						//_dump($resp);
					}
				}
			}
			
			// Adding Http monitors
			if (in_array('http', $monitorTypes)) {
				foreach ($servers as $server) {
					$name = $server['name'] . '_http';
					$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
					$interval = 1;
					$timeout = 10;
					$locationIDs = array(1, 9, 10);
					$tag = $server['name'] . '_whmcs';
					if (!empty($url)) {
						$resp = MonitisApi::createExternalHttp($name, $url, $interval, $timeout, $locationIDs, $tag);
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
						}
						//_dump($resp);
					}
				}
			}
			
			// Adding Https monitors
			if (in_array('https', $monitorTypes)) {
				foreach ($servers as $server) {
					$name = $server['name'] . '_https';
					$url = empty($server['hostname']) ? $server['ipaddress'] : $server['hostname'];
					$interval = 1;
					$timeout = 10;
					$locationIDs = array(1, 9, 10);
					$tag = $server['name'] . '_whmcs';
					if (!empty($url)) {
						$resp = MonitisApi::createExternalHttps($name, $url, $interval, $timeout, $locationIDs, $tag);
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
						}
						//_dump($resp);
					}
				}
			}
			
		}
		MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=servers');
	}
	
	$newCreateMonitorsText = explode(',', MonitisConf::$newServerMonitors);
	$newCreateMonitorsText = array_map(function($v){ return ucfirst($v); }, $newCreateMonitorsText);
	$newCreateMonitorsText = implode(', ', $newCreateMonitorsText);
?>
<h3>You have <b><?php echo $serverCount; ?></b> servers, do you want to create <b><?php echo $newCreateMonitorsText; ?></b> monitors for each server?</h3>
<form method="post" action="">
	<input type="hidden" name="sync" value="0" />
	<input type="submit" onclick="javascript: $(this).parent('form').find('input[name=sync]').val(1);" value="Yes, please" />
	<input type="submit" onclick="javascript: $(this).parent('form').find('input[name=sync]').val(0);" value="No, thanks" />
</form>
<?php MonitisApp::printNotifications(); ?>
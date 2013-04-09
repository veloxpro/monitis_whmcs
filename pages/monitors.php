<?php
$serverID = monitisGetInt('server_id');
if ($serverID == 0)
	MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=servers');

$associateModule = MonitisApp::getModule('AssociateMonitorServer');
$associateModuleContent = $associateModule->execute();
$createModule = MonitisApp::getModule('CreateMonitorServer');
$createModuleContent = $createModule->execute();

$res = mysql_query(sprintf('SELECT id, name, ipaddress, hostname, test_ids
						FROM tblservers
						LEFT JOIN mod_monitis_servers ON tblservers.id=mod_monitis_servers.server_id
						WHERE id=%d', $serverID));
$server = mysql_fetch_object($res);
$server->test_ids = empty($server->test_ids) ? array() : explode(',', $server->test_ids);

$extMonitors = MonitisApi::getExternalMonitors();
//_dump($extMonitors);

$monitors = array();
foreach ($extMonitors['testList'] as $m) {
	if (in_array($m['id'], $server->test_ids))
		$monitors[$m['id']] = $m;
}

//_dump($monitors);
?>

<?php MonitisApp::printNotifications(); ?>

<div align="left">
	<?php //monitisLoadModule('createMonitor', array('extMonitors' => $extMonitors)); ?>
	<?php //monitisLoadModule('associateMonitor', array('extMonitors' => $extMonitors, 'serverID' => $serverID, 'associatedIDs' => $server->test_ids)); ?>
	<?php echo $createModuleContent; ?>
	<?php echo $associateModuleContent; ?>
	<hr/>
</div>

<?php
if (count($monitors) < 1) {
	echo '<br/><br/>';
	echo '<center><h3>No monitors associated with this server</h3></center>';
}


$widgetContainer = new MonitisWidgetContainer();
foreach ($monitors as $monitor) {
	$chart = NULL;
	/*witch($monitor['type']) {
		case 'ping':
			$chart = new MonitisChartPing($monitor['id']);
			$chart->setHeight(300);
			break;
		case 'http':
			$chart = new MonitisChartHttp($monitor['id']);
			$chart->setHeight(300);
			break;
		case 'https':
			$chart = new MonitisChartHttps($monitor['id']);
			$chart->setHeight(300);
			break;
		default:;
	}*/
	$chart = new MonitisChartExternal($monitor['id']);
	$chart->setHeight(200);
	if (is_null($chart))
		continue;

	$title = $monitor['name'] . ' (' . ucfirst($monitor['type']) . ')';
	
	if (!$monitor['isSuspended']) {
		$chart->renderJS();
		ob_start();
		$chart->renderHtml();
		$content = ob_get_clean();
	} else {
		$content = '<div style="height: 110px; padding-top: 90px;"><span class="label" style="font-size: 14px;">MONITOR IS SUSPENDED</span></div>';
	}
	$widget = new MonitisWidget($title, $content);
	$widgetContainer->addWidget($widget);
}
$widgetContainer->render();
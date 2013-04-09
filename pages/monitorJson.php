<?php
$monitorID = monitisGetInt('monitor_id');
if ($monitorID == 0) {
	echo 'Please provide monitor_id';
	exit;
}

//$extMonitors = MonitisApi::getExternalMonitors();
//_dump($extMonitors);
//$a = array(array(1, 2,3), array('sssss', 'ddd', 'sdf'), array('sds', 3));
//echo json_encode($a);

$monitor = new MonitisChartExternal($monitorID);
$monitor->renderJson();
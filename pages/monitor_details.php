<?php
$monitorID = monitisGetInt('monitor_id');
if ($monitorID == 0)
	MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=servers');

//$monitorInfo = MonitisApi::getExternalMonitorInfo($monitorID);
//_dump($monitorInfo);

$d = new DateTime();
$d->setTimestamp(strtotime('-0 day', time()));
//_dump($d->format('d-m-Y'));

$monitorResult = MonitisApi::getExternalResults($monitorID, $d->format('d'), $d->format('m'), $d->format('Y'));
//_dump($monitorResult[0]['data']);
$data = array();
foreach ($monitorResult[0]['data'] as $point) {
	$data[] = array(strtotime($point[0]) * 1000, $point[1]);
}

$chart = new MonitisChartPing();

/*$chart->addSeries('name1', array(
		array(new HighchartJsExpr("Date.UTC(1970,  9,  9)"), 0),
		array(new HighchartJsExpr("Date.UTC(1970,  9, 14)"), 1.115),
		array(new HighchartJsExpr("Date.UTC(1970, 10, 28)"), 3.315),
		array(new HighchartJsExpr("Date.UTC(1970, 11, 12)"), 0.416))
);*/
$chart->addSeries('aaa', $data);

$chart->render();
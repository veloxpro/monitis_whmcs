<?php
$isNewAcc = monitisGetInt('isNewAcc');

if (monitisPost('saveConfig')) {
	$saveNewServerMonitors = isset($_POST['newServerMonitors']) ? $_POST['newServerMonitors'] : array();
	$saveNewServerMonitors = implode(',', $saveNewServerMonitors);
	
	if ($saveNewServerMonitors != MonitisConf::$newServerMonitors)
		MonitisConf::update('newServerMonitors', $saveNewServerMonitors);
	
	if ($isNewAcc)
		MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=syncExistingServers');
} else {
	if ($isNewAcc)
		MonitisApp::addMessage('Now please review plugin settings and click on "Save" button');
}

$newServerMonitors = explode(',', MonitisConf::$newServerMonitors);
?>
<?php MonitisApp::printNotifications(); ?>
<center>
	<form action="" method="post">
		<table class="form" width="100%" border=0 cellspacing=2 cellpadding=3>
			<tr>
				<td class="fieldlabel" style="width: 30%;">Automatically create following monitors when<br/>creating new servers on WHMCS</td>
				<td class="fieldarea">	
					<table class="monitisDatatable" cellspacing="1" cellpadding="3" style="text-align: left;">
						<tr class="">
							<th colspan=3 style="border-bottom: 1px dotted black;">External Monitors</th>
						</tr>
						<tr>
							<td><input type="checkbox" name="newServerMonitors[]" value="ping" <?php if(in_array('ping', $newServerMonitors)) echo 'checked=checked'; ?> /> Ping</td>
							<td><input type="checkbox" name="newServerMonitors[]" value="http" <?php if(in_array('http', $newServerMonitors)) echo 'checked=checked'; ?> /> HTTP</td>
							<td><input type="checkbox" name="newServerMonitors[]" value="https" <?php if(in_array('https', $newServerMonitors)) echo 'checked=checked'; ?> /> HTTPS</td>
						</tr>
					</table>
					<br/>
					
					<table class="monitisDatatable" cellspacing="1" cellpadding="3" style="text-align: left;">
						<tr class="">
							<th colspan=3 style="border-bottom: 1px dotted black;">Internal Monitors</th>
						</tr>
						<tr>
							<td><input type="checkbox" name="newServerMonitors[]" value="cpu" <?php if(in_array('cpu', $newServerMonitors)) echo 'checked=checked'; ?> /> CPU</td>
							<td><input type="checkbox" name="newServerMonitors[]" value="memory" <?php if(in_array('memory', $newServerMonitors)) echo 'checked=checked'; ?> /> Memory</td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="fieldlabel"></td>
				<td class="fieldarea">
					<input type="submit" value="Save" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="saveConfig" value="1" />
	</form>
</center>
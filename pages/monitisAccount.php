<?php
$isNewAcc = empty(MonitisConf::$apiKey);

if (monitisPostInt('monitisFormSubmitted')) {
	$apiKey = trim(monitisPost('apiKey'));
	$secretKey = trim(monitisPost('secretKey'));
	
	if (empty($apiKey))
		MonitisApp::addError('Please provide valid API Key');
	elseif (empty($secretKey))
		MonitisApp::addError('Please provide valid Secret Key');
	elseif (!MonitisApi::checkKeysValid($apiKey, $secretKey))
		MonitisApp::addError('Wrong API and/or Secret keys provided.');
	else {
		MonitisConf::update('apiKey', $apiKey);
		MonitisConf::update('secretKey', $secretKey);
		
		if ($isNewAcc) {
			header('location: ' . MONITIS_APP_URL . '&monitis_page=configure&isNewAcc=1');
		}
		//header('location: ' . MONITIS_APP_URL . '&monitis_page=servers');
	}
} else {
	if ($isNewAcc)
		MonitisApp::addMessage('Wellcome to Monitis plugin for WHMCS. Please start by entering your account information below.');
}
?>
<?php MonitisApp::printNotifications(); ?>
<center>
	<form action="" method="post">
		<table class="form" width="100%" border=0 cellspacing=2 cellpadding=3>
			<tr>
				<td class="fieldlabel">API Key</td>
				<td class="fieldarea">
					<input type="text" name="apiKey" size="40" value="<?php echo monitisPost('apiKey', MonitisConf::$apiKey); ?>" />
				</td>
			</tr>
			<tr>
				<td class="fieldlabel">Secret Key</td>
				<td class="fieldarea">
					<input type="text" name="secretKey" size="40" value="<?php echo monitisPost('secretKey', MonitisConf::$secretKey); ?>" />
				</td>
			</tr>
			<tr>
				<td class="fieldlabel"></td>
				<td class="fieldarea">
					<input type="submit" value="Save" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="monitisFormSubmitted" value="1" />
	</form>
</center>
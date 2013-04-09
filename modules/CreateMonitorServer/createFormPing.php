<monitis_data>
<?php
$locations = MonitisApiHelper::getExternalLocationsGroupedByCountry();
foreach ($locations as $key => $value) {
	if (empty($value))
		unset($locations[$key]);
}
//_dump($locations);
?>
<form action="" method="post">
	<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td class="fieldlabel" width="30%">Monitor type</td>
			<td class="fieldarea">
				<select name="type" onchange="javascript: m_CreateMonitorServer.loadCreateForm(this.value);">
					<optgroup label="External monitors">
						<option value="ping">Ping</option>
						<option value="http">HTTP</option>
						<option value="https">HTTPS</option>
					</optgroup>
					<optgroup label="Internal monitors">
						<option value="cpu">CPU</option>
						<option value="memory">Memory</option>
					</optgroup>
				</select>
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Name</td>
			<td class="fieldarea">
				<input type="text" name="name" size="50" placeholder="Name of the monitor" />
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Url</td>
			<td class="fieldarea">
				<input type="text" name="url" size="50" placeholder="URL for the test" />
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Check interval</td>
			<td class="fieldarea">
				<select name="interval">
					<option value="1">1</option>
					<option value="3">3</option>
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="60">60</option>
				</select> min.
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Test timeout in</td>
			<td class="fieldarea">
				<input type="text" name="timeout" size="20" value="1000" /> ms.
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Check locations</td>
			<td class="fieldarea">
				<!--input type="text" name="locationIDs" size="20" value="10000" /-->
				<div class="monitisMultiselect">
					<span class="monitisMultiselectText"><u>{count}</u> locations selected</span>
					<input type="button" class="monitisMultiselectTrigger" value="Select" />
					<div class="monitisMultiselectInputs" inputName="locationIDs[]"></div>
					<div class="monitisMultiselectDialog">
						<table style="width: 100%;" cellpadding=10>
							<tr>
								<?php foreach ($locations as $countryName => $country) { ?>
								<td style="vertical-align: top;">
									<div style="font-weight: bold; color: #71a9d2;">
										<?php echo $countryName; ?>
									</div>
									<hr/>
									<?php foreach ($country as $location) { ?>
										<div>
											<input type="checkbox" name="locationIDs[]" value="<?php echo $location['id']; ?>">
											<?php echo $location['fullName']; ?>
										</div>
									<?php } ?>
								</td>
								<?php } ?>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Tag</td>
			<td class="fieldarea">
				<input type="text" name="tag" size="50" placeholder="Tag of the monitor" />
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Uptime SLA</td>
			<td class="fieldarea">
				<input type="text" name="uptimeSLA" size="40" placeholder="Minimum allowed uptime" /> %
			</td>
		</tr>
		<tr>
			<td class="fieldlabel">Response SLA</td>
			<td class="fieldarea">
				<input type="text" name="responseSLA" size="40" placeholder="Maximum allowed response time" /> seconds
			</td>
		</tr>
		<tr>
			<td class="fieldlabel"></td>
			<td class="fieldarea">
				<input type="button" value="Create" onclick="javascript: m_CreateMonitorServer.submitForm();">
			</td>
		</tr>
	</table>
	<input type="hidden" name="module_CreateMonitorServer_action" value="createSubmited" />
</form>
</monitis_data>
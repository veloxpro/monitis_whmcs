<?php
$serverID = monitisGetInt('server_id');
if ($serverID == 0)
	MonitisApp::redirect(MONITIS_APP_URL . '&monitis_page=servers');

$res = mysql_query(sprintf('SELECT id, name, ipaddress, hostname, test_ids
						FROM tblservers
						LEFT JOIN mod_monitis_servers ON tblservers.id=mod_monitis_servers.server_id
						WHERE id=%d', $serverID));
$server = mysql_fetch_object($res);
$server->test_ids = empty($server->test_ids) ? array() : explode(',', $server->test_ids);

$extMonitors = MonitisApi::getExternalMonitors();

$monitors = array();
foreach ($extMonitors['testList'] as $m) {
	if (in_array($m['id'], $server->test_ids))
		$monitors[$m['id']] = $m;
}
?>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td width="50%" align="left">
			<b>Options:</b>
			<input type="button" class="btn" value="Add new monitor" />
		</td>
		<td width="50%" align="right">
			Filter:
			<select name="page" onchange="submit()">
				<option value="0" selected="">All</option>
			</select>
			<input type="submit" value="Go">
		</td>
	</tr>
</table>

<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3" style="text-align: left;">
	<tr>
		<th width="20"><input type="checkbox" id="checkall"></th>
		<th width="70"><a href="#">ID</a></th>
		<th><a href="#">Monitor Type</a></th>
		<th><a href="#">Name</a></th>
		<th><a href="#">Url</a></th>
		<th><a href="#">Monitor Status</a></th>
		<th><a href="#">Monitoring Result</a></th>
		<th><a href="#">Details</a></th>
	</tr>
<?php foreach ($monitors as $monitor): ?>
	<tr>
		<td><input type="checkbox" /></td>
		<td><?php echo $monitor['id'] ?></td>
		<td><?php echo ucfirst($monitor['type']) ?></td>
		<td><?php echo $monitor['name'] ?></td>
		<td><?php echo $monitor['url'] ?></td>
		<td style="text-align: center;">
			<?php if ($monitor['isSuspended']) : ?>
				<span class="label closed">Suspended</span>
			<?php else : ?>
				<span class="label active">Active</span>
			<?php endif; ?>
		</td>
		<td align="center"><?php echo 'average result' ?></td>
		<td align="center">
			<a href="<?php echo MONITIS_APP_URL ?>&monitis_page=monitor_details&monitor_id=<?php echo $monitor['id'] ?>">Monitoring details &#8594;</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>

			
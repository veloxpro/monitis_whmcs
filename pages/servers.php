<?php
$res = mysql_query('SELECT id, name, ipaddress, hostname, test_ids
						FROM tblservers
						LEFT JOIN mod_monitis_servers ON tblservers.id=mod_monitis_servers.server_id
						WHERE 1');
$servers = array();
$snapshot = MonitisApi::getExternalSnapshot();
//_dump($snapshot);

while($server = mysql_fetch_object($res)) {
	$server->test_ids = empty($server->test_ids) ?
		array() : explode(',', $server->test_ids);
	
	$server->NOKCount = 0;
	
	foreach ($snapshot as $location) {
		foreach ($location['data'] as $monitor) {
			if (in_array($monitor['id'], $server->test_ids)) {
				if ($monitor['status'] != 'OK')
					$server->NOKCount++;
			}
		}
	}
	
	//$server->monitoringStatus = rand(0, 1);
	
	$servers[$server->id] = $server;
}
?>
<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3" style="text-align: left;">
	<tr>
		<th width="20"><input type="checkbox" id="checkall"></th>
		<th width="30"><a href="#">ID</a></th>
		<th><a href="#">Server Name</a></th>
		<th><a href="#">IP address</a></th>
		<th><a href="#">Hostname</a></th>
		<th><a href="#">Current Status</a></th>
		<th><a href="#">Monitis Monitors</a></th>
	</tr>
<?php foreach ($servers as $server): ?>
	<tr>
		<td><input type="checkbox" /></td>
		<td><?php echo $server->id ?></td>
		<td><?php echo $server->name ?></td>
		<td><?php echo $server->ipaddress ?></td>
		<td><?php echo $server->hostname ?></td>
		<td style="text-align: center;">
			<?php if (empty($server->test_ids)) : ?>
				<span class="label pending">No active monitors</span>
			<?php elseif ($server->NOKCount > 0) : ?>
				<span class="label closed">Failed</span>
			<?php else : ?>
				<span class="label active">All OK</span>
			<?php endif; ?>
		</td>
		<td style="text-align: center;">
			<a href="<?php echo MONITIS_APP_URL ?>&monitis_page=monitors&server_id=<?php echo $server->id ?>">Monitors &#8594;</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>
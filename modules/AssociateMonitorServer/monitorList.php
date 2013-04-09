<monitis_data>
<?php
$type = monitisPost('type');
$serverID = monitisGet('server_id');

$extMonitors = MonitisApi::getExternalMonitors();

$res = mysql_query(sprintf('SELECT id, name, ipaddress, hostname, test_ids
						FROM tblservers
						LEFT JOIN mod_monitis_servers ON tblservers.id=mod_monitis_servers.server_id
						WHERE id=%d', $serverID));
$server = mysql_fetch_object($res);
$server->test_ids = empty($server->test_ids) ? array() : explode(',', $server->test_ids);

?>
<div>
	<form action="" method="post">
		<div>
			Type:
			<select onchange="javascript: m_AssociateMonitorServer.loadMonitorList(this.value)">
				<option value="">All</option>
				<optgroup label="External Monitors">
					<option value="ping" <?php echo $type != 'ping' ?: 'selected'; ?>>Ping</option>
					<option value="http" <?php echo $type != 'http' ?: 'selected'; ?>>Http</option>
				</optgroup>
				<optgroup label="Internal Monitors">
					<option value="cpu" <?php echo $type != 'cpu' ?: 'selected'; ?>>Cpu</option>
					<option value="memory" <?php echo $type != 'memory' ?: 'selected'; ?>>Memory</option>
				</optgroup>
			</select>
		</div>
		<hr/>
		<table id="m_AssociateMonitorServer_Table" class="monitisDatatable" width="100%" border="0" cellspacing="1" cellpadding="3" style="text-align: left;">
			<tr>
				<th><input type='checkbox' class='monitis_checkall' /></th>
				<th>ID</th>
				<th>Name</th>
				<th>Type</th>
				<th>URL</th>
				<th>Status</th>
				<th>Associated</th>
			</tr>
			<?php
			$monCount = 0;
			foreach ($extMonitors['testList'] as $m) {
				if (!empty($type) && $m['type'] != $type)
					continue;
				$monCount++;
			?>
			<tr>
				<td><input type="checkbox" class='monitis_checkall' name="m_AssociateMonitorServer_Selecteds[]" value="<?php echo $m['id'] ?>" /></td>
				<td><?php echo $m['id']; ?></td>
				<td><?php echo $m['name']; ?></td>
				<td><?php echo $m['type']; ?></td>
				<td><?php echo $m['url']; ?></td>
				<td><?php echo $m['isSuspended'] ? '<span class="label pending">Suspended</span>' : 'Active'; ?></td>
				<td><?php echo in_array($m['id'], $server->test_ids) ? '<span class="label active">Associated</span>' : ''; ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php if($monCount == 0) { ?>
		<div class="mGrit">No records</div>
		<?php } ?>
		<input type="hidden" name="module_AssociateMonitorServer_action" value="" />
		<div style="text-align: right; margin: 10px 0px;">
			With selected: 
			<input type="button" class="button" id="m_AssociateMonitorServer_Associate" value="Associate" />
			<input type="button" class="button" id="m_AssociateMonitorServer_Unassociate" value="Unassociate" />
		</div>
	</form>
</div>
</monitis_data>
<?php
if (!isset($extMonitors)) {
	echo "module createMonitor: required variables are not defined";
	return;
}
?>
<input type="button" class="button" id="m_createMonitor_trigger" value="Create new monitor" />
<div style="display: none;" id="m_createMonitor">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="right">Monitor Type&nbsp;&nbsp;</td>
			<td>
				<select name="" id="monitisCreateMonitorType" style="width: 200px;" onchange="javascript: monitisCreateMonitor.loadForm($(this).val())">
					<option value="">--- Please select monitor type ---</option>
					<optgroup label="EXTERNAL MONITORS">
						<option value="ping">Ping</option>
						<option value="http">Http</option>
					</optgroup>
					<optgroup label="INTERNAL MONITORS">
						<option value="cpu">Cpu</option>
						<option value="memory">Memory</option>
					</optgroup>
				</select>
			</td>
		</tr>
	</table>
	<br/>
	<form method="post" action="">
		<table class="form" id="monitisCreateMonitorTable" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tbody>
			<tr>
				<td class="fieldlabel">Type</td>
				<td class="fieldarea">
					<select name="" id="monitisCreateMonitorType" style="width: 200px;" onchange="javascript: monitisCreateMonitor.loadForm($(this).val())">
						<option value="">--- Please select monitor type ---</option>
						<optgroup label="EXTERNAL MONITORS">
							<option value="ping">Ping</option>
							<option value="http">Http</option>
						</optgroup>
						<optgroup label="INTERNAL MONITORS">
							<option value="cpu">Cpu</option>
							<option value="memory">Memory</option>
						</optgroup>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
</div>
<script type="text/javascript">
	var monitisCreateMonitor = {
		loadForm: function(type) {
			switch (type) {
				case 'ping':
				case 'http':
					$.get("../modules/addons/monitis_addon/modules/createMonitor/formExternal.php", function(data) {
						$("#monitisCreateMonitorTable tr:gt(0)").remove();
						alert($(data).html());
						$("#monitisCreateMonitorTable tbody").append($(data).filter("#monitisAjaxData").html());
						//console.log($(data).filter("#monitisAjaxData").html());
						//console.log("./../modules/addons/monitis_addon/modules/createMonitor/formExternal.php");
					});
					break;
				case 'cpu':
				case 'memory':
					console.log('loading form');
					break;
			}
		}
	};
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#m_createMonitor").dialog({
			title: 'Create new monitor',
			width: 800,
			autoOpen: false,
			modal: true,
		});

		$("#m_createMonitor_trigger").click(function(){
			$("#m_createMonitor").dialog( "open" );
		});
	});
</script>
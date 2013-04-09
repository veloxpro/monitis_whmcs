<input type="button" class="button" id="m_CreateMonitorServer_Trigger" value="Create new monitor for this server" />
<div id="m_CreateMonitorServer_Content"></div>
<script type="text/javascript">
var m_CreateMonitorServer = {
	serverID: <?php echo monitisGet('server_id') ?>,
	init: function() {
		var that = this;
		$("#m_CreateMonitorServer_Trigger").click(function(){
			that.loadCreateForm('ping');
			that.openDialog();
		});

		$("#m_CreateMonitorServer_Content").dialog({
			title: "Create new monitor for this server",
			width: 800,
			autoOpen: false,
			modal: true,
		});
	},
	loadCreateForm: function(type) {
		type = type.charAt(0).toUpperCase() + type.slice(1);
		var that = this;
		this.load('createForm' + type, {}, function() {
				$.getScript("../modules/addons/monitis_addon/modules/CreateMonitorServer/static/js/createForm" + type + ".js",
						function(data, textStatus, jqxhr) {
							var form = $("#m_CreateMonitorServer_Content").find("form").first();
							that.initValidator(form);
						}
				);
				
				initMonitisMultiselect('#m_CreateMonitorServer_Content');
			}
		);
	},
	load: function(actionName, params, callback) {
		$("#m_CreateMonitorServer_Content").prepend("<div class='monitisOverlay'></div><div class='monitisLoader'></div>");
		
		if (typeof params == 'undefined')
			params = {};
		params.module_CreateMonitorServer_action = actionName;
		
		var url = "<?php echo MONITIS_APP_URL; ?>&monitis_module=CreateMonitorServer&server_id="
			+ this.serverID; 
			
		$.post(url, params, function(data) {
			$('#m_CreateMonitorServer_Content').html($(data).find('monitis_data').html());

			if (callback instanceof Function)
				callback();
		});
	},
	openDialog: function() {
		$("#m_CreateMonitorServer_Content").dialog( "open" );
	},
	submitForm: function() {
		var form = $("#m_CreateMonitorServer_Content").find("form").first();
		if (form.valid())
			form.submit();
	},
	initValidator: function(jqForm) {
		// each form should overwrite this method with its own validator
	}
};

$(document).ready(function(){
	m_CreateMonitorServer.init();
});
</script>
<?php

class MonitisModuleCreateMonitorServer {
	function execute() {
		ob_start();
		$this->render();
		$content = ob_get_clean();
		return $content;
	}
	
	function render($action = '') {
		$serverID = monitisGet('server_id');
		
		if (empty($action))
			$action = monitisPost('module_CreateMonitorServer_action');
		if (empty($action))
			$action = 'default';
		
		include str_replace('/', '_', $action) . '.php';
	}
}
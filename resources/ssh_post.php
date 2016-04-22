<?php
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	
	# Start the session
	InitSession();
	
	# Include the lib
	include('lib/phpSec/Net/SSH2.php');
	
	# Init the class
	$ssh = new Net_SSH2($_SERVER['SERVER_NAME']);
	
	# Receive the form data
	if(!empty($_POST) && isset($_SESSION['Authenticated']) && $_SESSION['Authenticated'] == 1) {
		# Attempt to log in via SSH
		if(!$ssh->login($_POST['username'], $_POST['password'])) {
			exit('Authentication Failed');
		}
		
		# Update/reset/shutdown/custom command
		if($_POST['option'] == 'update') {
			$prntDir = dirname(dirname(__FILE__));
			echo $ssh->exec('cd ' . $prntDir . ' && git pull');
		}else if($_POST['option'] == 'reboot') {
			echo 'Rebooting';
			$ssh->exec('reboot');
		}else if($_POST['option'] == 'shutdown') {
			echo 'Shutting down';
			$ssh->exec('reboot');
		}else if($_POST['option'] == 'custom') {
			echo '<span style="font-size:12px;">' . nl2br($ssh->exec($_POST['command'])) . '</span>';
		}
	}else{
		exit('Unauthenticated');
	}
	
	# Custom session function
	function InitSession($timeout = 3600) {
		ini_set('session.gc_maxlifetime', $timeout);
		session_start();
	
		if(isset($_SESSION['timeout_idle']) && $_SESSION['timeout_idle'] < time()) {
			session_destroy();
			session_start();
			session_regenerate_id();
			$_SESSION = array();
		}
	
		$_SESSION['timeout_idle'] = time() + $timeout;
	}
?>
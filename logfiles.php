<?php
	# Start the session
	InitSession();

	# Simple API
	if(!empty($_GET['alerts'])) {
		# Save the date into a variable
		$hsDate = $_GET['alerts'];
		
		if(file_exists(__DIR__ . '/resources/data/alerts_' . $hsDate . '.json')) {
			$GetLog = file_get_contents(__DIR__ . '/resources/data/alerts_' . $hsDate . '.json');
		}else{
			$GetLog = array('status' => 'no_alerts');
			$GetLog = json_encode($GetLog, true);
		}
		
		print_r($GetLog);
		die;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Montr - Resource Monitoring</title>		
		<link type="text/css" rel="stylesheet" href="resources/style/css/bootstrap.min.css" media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="resources/style/css/font-awesome.min.css" media="screen"/>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="resources/style/css/montr.css" media="screen"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcollapse" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">
						<img alt="" class="menuimg" />
						<p class="logo_letter">m</p>
					</a>
				</div>
				
				<div class="collapse navbar-collapse" id="navcollapse">
					<ul class="nav navbar-nav" style="margin-left:22px;">
						<li><a href="index.php">Home</a></li>
						<li class="active"><a href="logfiles.php">Logs <span class="sr-only">(current)</span></a></li>
					</ul>
				</div>
			</div>
		</nav>
		
        <div class="container">
			<div class="row">
				<div class="col-md-12">
					<p align="center" class="title">logs</p><br />
					<div class="fill-in">

					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="resources/style/js/bootstrap.min.js"></script>
		
		<script>
			// Initially load the table
			$(document).ready(function () {
				$('.fill-in').load('resources/logs.php').stop().fadeIn();
			});
		
			// Reload the table every 5 seconds
			setInterval(function(){
				$('.fill-in').load('resources/logs.php').stop().fadeIn();
			}, 5000);
		</script>
	</body>
</html>
<?php
	function LoadConfig() {
		if(file_exists(__DIR__ . '/resources/data/config.json')) {
			$cfgCont = file_get_contents(__DIR__ . '/resources/data/config.json');

			$cfgCont = json_decode($cfgCont, true);

			return $cfgCont;
		}
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
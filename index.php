<?php
	# Start the session
	InitSession();

	# Load the config file
	$mCfg = LoadConfig();

	# Authenticating
	if(!empty($_POST['authPass']) && !empty($mCfg)) {
		if(password_verify($_POST['authPass'], $mCfg['auth']['password'])) {
			$_SESSION['Authenticated'] = 1;
		}
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
						<li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
						<li><a href="logfiles.php">Logs</a></li>
					</ul>
					<?php
						if(isset($_SESSION['Authenticated'])) {
							//echo '<p class="navbar-text navbar-right" style="margin-right:15px;">Authenticated</p>';
							echo '<button type="button" style="margin-right:15px; margin-top:10px;" class="btn btn-primary btn-sm navbar-right" data-toggle="modal" data-target="#myModal">Configuration</button>';
						}else{
							echo '<form method="post" class="navbar-form navbar-right" role="search">';
							echo '<div class="form-group"><input type="password" id="authPass" name="authPass" class="form-control" placeholder="Enter password.."></div>';
							echo '<button type="submit" style="margin-left:5px;" class="btn btn-default">Auth</button></form>';
						}
					?>					
				</div>
			</div>
		</nav>
		
        <div class="container">
			<div class="row">
				<div class="col-md-12">
					<p align="center" class="title">montr</p>
					<div class="fill-in">

					</div>
				</div>
			</div>
		</div>
		
		<br />
		
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="resources/style/js/bootstrap.min.js"></script>
		
		<script>
			// Initially load the table
			$(document).ready(function () {
				$('.fill-in').load('resources/load.php').stop().fadeIn();
			});
		
			// Reload the table every 5 seconds
			setInterval(function(){
				$('.fill-in').load('resources/load.php').stop().fadeIn();
			}, 5000);
		</script>
	</body>
</html>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Configuration Editor</h4>
			</div>
			<div class="modal-body">
				<h4>Drive Information</h4>
				
				<form class="form-inline">
				<?php
					############# DRIVE ONE
					echo '<br /><h5>Drive 1</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][0]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive1" name="drive1" value="' . $mCfg['disks'][0]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive1" name="drive1" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][0]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive1" name="drive1" value="' . $mCfg['disks'][0]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive1" name="drive1" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][0]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive1" name="drive1" value="' . $mCfg['disks'][0]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive1" name="drive1" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE TWO
					echo '<br /><h5>Drive 2</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][1]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive2" name="drive2" value="' . $mCfg['disks'][1]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive2" name="drive2" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][1]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive2" name="drive2" value="' . $mCfg['disks'][1]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive2" name="drive2" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][1]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive2" name="drive2" value="' . $mCfg['disks'][1]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive2" name="drive2" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE THREE
					echo '<br /><h5>Drive 3</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][2]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive3" name="drive3" value="' . $mCfg['disks'][2]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive3" name="drive3" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][2]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive3" name="drive3" value="' . $mCfg['disks'][2]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive3" name="drive3" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][2]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive3" name="drive3" value="' . $mCfg['disks'][2]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive3" name="drive3" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE FOUR
					echo '<br /><h5>Drive 4</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][3]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive4" name="drive4" value="' . $mCfg['disks'][3]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive4" name="drive4" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][3]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive4" name="drive4" value="' . $mCfg['disks'][3]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive4" name="drive4" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][3]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive4" name="drive4" value="' . $mCfg['disks'][3]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive4" name="drive4" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE FIVE
					echo '<br /><h5>Drive 5</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][4]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive5" name="drive5" value="' . $mCfg['disks'][4]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive5" name="drive5" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][4]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive5" name="drive5" value="' . $mCfg['disks'][4]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive5" name="drive5" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][4]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive5" name="drive5" value="' . $mCfg['disks'][4]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive5" name="drive5" placeholder="Limit">';
					}
					echo '</div><hr>';
					
					echo '<h4>Contact Information</h4>';
					
					############# CONTACT ONE
					echo '<br /><h5>Contact 1</h5>';
					echo '<div class="form-group" style="width:49%;">';
					echo '<label class="sr-only" for="drive1">Contact Email</label>';
					# Check if we have a name
					if(!empty($mCfg['contacts']['email_1'])) {
						echo '<input type="text" size="28" class="form-control" id="email1" name="email1" value="' . $mCfg['contacts']['email_1'] . '">';
					}else{
						echo '<input type="text" size="28" class="form-control" id="email1" name="email1" placeholder="Email">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:51%;">';
					echo '<label class="sr-only" for="drive2">Contact Number</label>';
					# Check if we have a location
					if(!empty($mCfg['contacts']['mobile_1'])) {
						echo '<input type="text" size="30" class="form-control" id="number1" name="number1" value="' . $mCfg['contacts']['mobile_1'] . '">';
					}else{
						echo '<input type="text" size="30" class="form-control" id="number1" name="number1" placeholder="Number">';
					}
					echo '</div>';
					
					echo '<pre>';
					print_r($mCfg);
					echo '</pre>';
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
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
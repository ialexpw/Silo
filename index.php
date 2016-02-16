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

	# Updating the configuration
	if(isset($_GET['udc']) && !empty($_POST)) {
		echo 'update??';
		
		$defCfg = array();
		
		$defCfg['auth']['password'] = 'test';
		
		$defCfg['disks'][0]['name'] = 'Name';
		
		print_r($defCfg);
		
		/*$defCfg = array(
			'auth' => array(
				'password' => ''
			),
			'disks' => array(
				0 => array(
					'name' => '',
					'location' => '',
					'limit' => ''
				),
				1 => array(
					'name' => '',
					'location' => '',
					'limit' => ''
				),
				2 => array(
					'name' => '',
					'location' => '',
					'limit' => ''
				),
				3 => array(
					'name' => '',
					'location' => '',
					'limit' => ''
				),
				4 => array(
					'name' => '',
					'location' => '',
					'limit' => ''
				),
			),
			'contacts' => array(
				'mobile_1' => '',
				'email_1' => '',
				'mobile_2' => '',
				'email_2' => '',
				'mobile_3' => '',
				'email_3' => ''
			),
			'limits' => array(
				'memory_usage' => '75', # (%)
				'memory_units' => 'mb',
				'load_alert' => '6'
			)
		);*/
	}

	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
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
				
				<form class="form-inline" action="?udc" id="cfgUp" name="cfgUp" method="post">
				<?php
					############# DRIVE ONE
					echo '<br /><h5>Drive 1</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][0]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive1_name" name="drive1_name" value="' . $mCfg['disks'][0]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive1_name" name="drive1_name" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][0]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive1_loc" name="drive1_loc" value="' . $mCfg['disks'][0]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive1_loc" name="drive1_loc" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][0]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive1_limit" name="drive1_limit" value="' . $mCfg['disks'][0]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive1_limit" name="drive1_limit" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE TWO
					echo '<br /><h5>Drive 2</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][1]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive2_name" name="drive2_name" value="' . $mCfg['disks'][1]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive2_name" name="drive2_name" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][1]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive2_loc" name="drive2_loc" value="' . $mCfg['disks'][1]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive2_loc" name="drive2_loc" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][1]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive2_limit" name="drive2_limit" value="' . $mCfg['disks'][1]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive2_limit" name="drive2_limit" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE THREE
					echo '<br /><h5>Drive 3</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][2]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive3_name" name="drive3_name" value="' . $mCfg['disks'][2]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive3_name" name="drive3_name" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][2]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive3_loc" name="drive3_loc" value="' . $mCfg['disks'][2]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive3_loc" name="drive3_loc" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][2]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive3_limit" name="drive3_limit" value="' . $mCfg['disks'][2]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive3_limit" name="drive3_limit" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE FOUR
					echo '<br /><h5>Drive 4</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][3]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive4_name" name="drive4_name" value="' . $mCfg['disks'][3]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive4_name" name="drive4_name" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][3]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive4_loc" name="drive4_loc" value="' . $mCfg['disks'][3]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive4_loc" name="drive4_loc" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][3]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive4_limit" name="drive4_limit" value="' . $mCfg['disks'][3]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive4_limit" name="drive4_limit" placeholder="Limit">';
					}
					echo '</div>';
					
					
					############# DRIVE FIVE
					echo '<br /><h5>Drive 5</h5>';
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive1">Drive Name</label>';
					# Check if we have a name
					if(!empty($mCfg['disks'][4]['name'])) {
						echo '<input type="text" size="20" class="form-control" id="drive5_name" name="drive5_name" value="' . $mCfg['disks'][4]['name'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive5_name" name="drive5_name" placeholder="Name">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:40%;">';
					echo '<label class="sr-only" for="drive2">Drive Path</label>';
					# Check if we have a location
					if(!empty($mCfg['disks'][4]['location'])) {
						echo '<input type="text" size="20" class="form-control" id="drive5_loc" name="drive5_loc" value="' . $mCfg['disks'][4]['location'] . '">';
					}else{
						echo '<input type="text" size="20" class="form-control" id="drive5_loc" name="drive5_loc" placeholder="Path">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:20%;">';
					echo '<label class="sr-only" for="drive3">Alert Limit</label>';
					# Check if we have a limit
					if(!empty($mCfg['disks'][4]['limit'])) {
						echo '<input type="text" size="9" class="form-control" id="drive5_limit" name="drive5_limit" value="' . $mCfg['disks'][4]['limit'] . '">';
					}else{
						echo '<input type="text" size="9" class="form-control" id="drive5_limit" name="drive5_limit" placeholder="Limit">';
					}
					echo '</div><hr>';
					
					echo '<h4>Contact Information</h4>';
					
					############# CONTACT ONE
					echo '<br /><h5>Contact 1</h5>';
					echo '<div class="form-group" style="width:49%;">';
					echo '<label class="sr-only" for="drive1">Contact Email</label>';
					# Check if we have an email
					if(!empty($mCfg['contacts']['email_1'])) {
						echo '<input type="text" size="28" class="form-control" id="email1" name="email1" value="' . $mCfg['contacts']['email_1'] . '">';
					}else{
						echo '<input type="text" size="28" class="form-control" id="email1" name="email1" placeholder="Email">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:51%;">';
					echo '<label class="sr-only" for="drive2">Contact Number</label>';
					# Check if we have a phone #
					if(!empty($mCfg['contacts']['mobile_1'])) {
						echo '<input type="text" size="30" class="form-control" id="number1" name="number1" value="' . $mCfg['contacts']['mobile_1'] . '">';
					}else{
						echo '<input type="text" size="30" class="form-control" id="number1" name="number1" placeholder="Number">';
					}
					echo '</div>';
					
					############# CONTACT TWO
					echo '<br /><h5>Contact 2</h5>';
					echo '<div class="form-group" style="width:49%;">';
					echo '<label class="sr-only" for="drive1">Contact Email</label>';
					# Check if we have an email
					if(!empty($mCfg['contacts']['email_2'])) {
						echo '<input type="text" size="28" class="form-control" id="email2" name="email2" value="' . $mCfg['contacts']['email_2'] . '">';
					}else{
						echo '<input type="text" size="28" class="form-control" id="email2" name="email2" placeholder="Email">';
					}
					echo '</div>';
					
					echo '<div class="form-group" style="width:51%;">';
					echo '<label class="sr-only" for="drive2">Contact Number</label>';
					# Check if we have a phone #
					if(!empty($mCfg['contacts']['mobile_2'])) {
						echo '<input type="text" size="30" class="form-control" id="number2" name="number2" value="' . $mCfg['contacts']['mobile_2'] . '">';
					}else{
						echo '<input type="text" size="30" class="form-control" id="number2" name="number2" placeholder="Number">';
					}
					echo '</div>';

					############# CONTACT THREE
					echo '<br /><h5>Contact 3</h5>';
					echo '<div class="form-group" style="width:49%;">';
					echo '<label class="sr-only" for="drive1">Contact Email</label>';
					# Check if we have an email
					if(!empty($mCfg['contacts']['email_3'])) {
						echo '<input type="text" size="28" class="form-control" id="email3" name="email3" value="' . $mCfg['contacts']['email_3'] . '">';
					}else{
						echo '<input type="text" size="28" class="form-control" id="email3" name="email3" placeholder="Email">';
					}
					echo '</div>';

					echo '<div class="form-group" style="width:51%;">';
					echo '<label class="sr-only" for="drive2">Contact Number</label>';
					# Check if we have a phone #
					if(!empty($mCfg['contacts']['mobile_3'])) {
						echo '<input type="text" size="30" class="form-control" id="number3" name="number3" value="' . $mCfg['contacts']['mobile_3'] . '">';
					}else{
						echo '<input type="text" size="30" class="form-control" id="number3" name="number3" placeholder="Number">';
					}
					echo '</div><hr>';

					echo '<h4>Update Password</h4>';

					echo '<br /><h5>Password</h5>';
					echo '<input type="password" size="64" class="form-control" id="password" name="password" placeholder="Leave blank for no change">';

					echo '<pre>';
					print_r($mCfg);
					echo '</pre>';
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
			</form>
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
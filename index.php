<?php
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

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
	if(isset($_GET['udc']) && !empty($_POST) && isset($_SESSION['Authenticated'])) {
		 # Initialise the array
		$defCfg = array();
		
		# Password change?
		if(!empty($_POST['password'])) {
			$defCfg['auth']['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
		}else{
			$defCfg['auth']['password'] = $mCfg['auth']['password'];
		}
		
		# Build the drive array
		for($i=0;$i<5;$i++) {
			if(!empty($_POST['drive' . ($i+1) . '_name']) && !empty($_POST['drive' . ($i+1) . '_loc']) && isset($_POST['drive' . ($i+1) . '_limit'])) {
				$defCfg['disks'][$i]['name'] = $_POST['drive' . ($i+1) . '_name'];
				$defCfg['disks'][$i]['location'] = $_POST['drive' . ($i+1) . '_loc'];
				$defCfg['disks'][$i]['limit'] = $_POST['drive' . ($i+1) . '_limit'];
			}
		}
		
		# Build up the contact numbers
		for($i=0;$i<3;$i++) {
			if(!empty($_POST['number' . ($i+1)])) {
				$defCfg['contacts']['mobile_' . ($i+1)] = $_POST['number' . ($i+1)];
			}
		}
		
		# Build up the contact emails
		for($i=0;$i<3;$i++) {
			if(!empty($_POST['email' . ($i+1)])) {
				$defCfg['contacts']['email_' . ($i+1)] = $_POST['email' . ($i+1)];
			}
		}
		
		# Memory usage alert percentage
		if(isset($_POST['mem_level'])) {
			$defCfg['limits']['memory_usage'] = $_POST['mem_level'];
		}
		
		# Load alert level
		if(isset($_POST['load_level'])) {
			$defCfg['limits']['load_alert'] = $_POST['load_level'];
		}
		
		# Encode the array into json
		$defCfg = json_encode($defCfg);
		
		# Write the new configuration file
		file_put_contents(__DIR__ . '/resources/data/config.json', $defCfg);
		
		# Redirect back to refresh
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Silo - Resource Panel</title>		
		<link type="text/css" rel="stylesheet" href="resources/style/css/bootstrap.min.css" media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="resources/style/css/font-awesome.min.css" media="screen"/>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="resources/style/css/montr.css" media="screen"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			.pdR {
				margin-right:8px;
			}
		</style>
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
					<a class="navbar-brand" href="index.php">
						<img alt="" class="menuimg" />
						<p class="logo_letter">s</p>
					</a>
				</div>
				
				<div class="collapse navbar-collapse" id="navcollapse">
					<ul class="nav navbar-nav" style="margin-left:22px;">
						<li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
						<li><a href="logfiles.php">Logs</a></li>
					</ul>
					<?php
						if(isset($_SESSION['Authenticated'])) {
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
					<p align="center" class="title">silo</p>
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
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script>
			$(document).ready(function () {
				$("#submit-button").click(function (e) {
					e.preventDefault();
		
					var username = $('#ssh-username').val();
					var password = $('#ssh-password').val();
					var post = $('#custom').val();
		
					var option = $('input[name="sshSelect"]:checked').val();
		
					$.ajax({ 
						type:"POST",
						url:"resources/ssh_post.php",
						data: {username: username, password: password, command: post, option: option},
						success: function(data){
							 $(".result").html(data);
						}
					});
				});
			});
		</script>
	</body>
</html>
<?php
	if(isset($_SESSION['Authenticated']) && $_SESSION['Authenticated'] == 1) {
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Configuration Editor</h4>
			</div>
			<div class="modal-body">
				<div>
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#drives" aria-controls="drives" role="tab" data-toggle="tab">Drives</a></li>
						<li role="presentation"><a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">Contacts</a></li>
						<li role="presentation"><a href="#limits" aria-controls="limits" role="tab" data-toggle="tab">Limits</a></li>
						<li role="presentation"><a href="#password" aria-controls="settings" role="tab" data-toggle="tab">Password</a></li>
						<li role="presentation"><a href="#control" aria-controls="control" role="tab" data-toggle="tab">Control</a></li>
						<li role="presentation"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">About</a></li>
					</ul>
					
					<!-- Form -->
					<form class="form-inline" action="?udc" id="cfgUp" name="cfgUp" method="post">
						<!-- Tab panes -->
						<div class="tab-content">						
							<div role="tabpanel" class="tab-pane active" id="drives">
							<h4>Drive Information</h4>
							<p>Enter the details of all the drives you would like to monitor here, make sure they are valid before submitting.
								If you would not like to set an alarm limit, set it to "0".</p>
								
							<hr>

							<?php
								############# DRIVE ONE
								echo '<h5>Drive 1</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Drive Name</label>';
								# Check if we have a name
								if(!empty($mCfg['disks'][0]['name'])) {
									echo '<input type="text" class="form-control pdR" id="drive1_name" name="drive1_name" placeholder="Name" value="' . $mCfg['disks'][0]['name'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive1_name" name="drive1_name" placeholder="Name">';
								}

								echo '<label class="sr-only" for="drive2">Drive Path</label>';
								# Check if we have a location
								if(!empty($mCfg['disks'][0]['location'])) {
									echo '<input type="text" class="form-control pdR" id="drive1_loc" name="drive1_loc" placeholder="Path" value="' . $mCfg['disks'][0]['location'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive1_loc" name="drive1_loc" placeholder="Path">';
								}

								echo '<label class="sr-only" for="drive3">Alert Limit</label>';
								# Check if we have a limit
								if(isset($mCfg['disks'][0]['limit'])) {
									echo '<input type="text" size="10" class="form-control" id="drive1_limit" name="drive1_limit" placeholder="Limit" value="' . $mCfg['disks'][0]['limit'] . '">';
								}else{
									echo '<input type="text" size="10" class="form-control" id="drive1_limit" name="drive1_limit" placeholder="Limit">';
								}
								echo '</div>';

								############# DRIVE TWO
								echo '<br /><h5>Drive 2</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Drive Name</label>';
								# Check if we have a name
								if(!empty($mCfg['disks'][1]['name'])) {
									echo '<input type="text" class="form-control pdR" id="drive2_name" name="drive2_name" placeholder="Name" value="' . $mCfg['disks'][1]['name'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive2_name" name="drive2_name" placeholder="Name">';
								}
								

								echo '<label class="sr-only" for="drive2">Drive Path</label>';
								# Check if we have a location
								if(!empty($mCfg['disks'][1]['location'])) {
									echo '<input type="text" class="form-control pdR" id="drive2_loc" name="drive2_loc" placeholder="Path" value="' . $mCfg['disks'][1]['location'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive2_loc" name="drive2_loc" placeholder="Path">';
								}

								echo '<label class="sr-only" for="drive3">Alert Limit</label>';
								# Check if we have a limit
								if(isset($mCfg['disks'][1]['limit'])) {
									echo '<input type="text" size="10" class="form-control" id="drive2_limit" name="drive2_limit" placeholder="Limit" value="' . $mCfg['disks'][1]['limit'] . '">';
								}else{
									echo '<input type="text" size="10" class="form-control" id="drive2_limit" name="drive2_limit" placeholder="Limit">';
								}
								echo '</div>';


								############# DRIVE THREE
								echo '<br /><h5>Drive 3</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Drive Name</label>';
								# Check if we have a name
								if(!empty($mCfg['disks'][2]['name'])) {
									echo '<input type="text" class="form-control pdR" id="drive3_name" name="drive3_name" placeholder="Name" value="' . $mCfg['disks'][2]['name'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive3_name" name="drive3_name" placeholder="Name">';
								}

								echo '<label class="sr-only" for="drive2">Drive Path</label>';
								# Check if we have a location
								if(!empty($mCfg['disks'][2]['location'])) {
									echo '<input type="text" class="form-control pdR" id="drive3_loc" name="drive3_loc" placeholder="Path" value="' . $mCfg['disks'][2]['location'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive3_loc" name="drive3_loc" placeholder="Path">';
								}

								echo '<label class="sr-only" for="drive3">Alert Limit</label>';
								# Check if we have a limit
								if(isset($mCfg['disks'][2]['limit'])) {
									echo '<input type="text" size="10" class="form-control" id="drive3_limit" name="drive3_limit" placeholder="Limit" value="' . $mCfg['disks'][2]['limit'] . '">';
								}else{
									echo '<input type="text" size="10" class="form-control" id="drive3_limit" name="drive3_limit" placeholder="Limit">';
								}
								echo '</div>';


								############# DRIVE FOUR
								echo '<br /><h5>Drive 4</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Drive Name</label>';
								# Check if we have a name
								if(!empty($mCfg['disks'][3]['name'])) {
									echo '<input type="text" class="form-control pdR" id="drive4_name" name="drive4_name" placeholder="Name" value="' . $mCfg['disks'][3]['name'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive4_name" name="drive4_name" placeholder="Name">';
								}

								echo '<label class="sr-only" for="drive2">Drive Path</label>';
								# Check if we have a location
								if(!empty($mCfg['disks'][3]['location'])) {
									echo '<input type="text" class="form-control pdR" id="drive4_loc" name="drive4_loc" placeholder="Path" value="' . $mCfg['disks'][3]['location'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive4_loc" name="drive4_loc" placeholder="Path">';
								}

								echo '<label class="sr-only" for="drive3">Alert Limit</label>';
								# Check if we have a limit
								if(isset($mCfg['disks'][3]['limit'])) {
									echo '<input type="text" size="10" class="form-control" id="drive4_limit" name="drive4_limit" placeholder="Limit" value="' . $mCfg['disks'][3]['limit'] . '">';
								}else{
									echo '<input type="text" size="10" class="form-control" id="drive4_limit" name="drive4_limit" placeholder="Limit">';
								}
								echo '</div>';


								############# DRIVE FIVE
								echo '<br /><h5>Drive 5</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Drive Name</label>';
								# Check if we have a name
								if(!empty($mCfg['disks'][4]['name'])) {
									echo '<input type="text" class="form-control pdR" id="drive5_name" name="drive5_name" placeholder="Name" value="' . $mCfg['disks'][4]['name'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive5_name" name="drive5_name" placeholder="Name">';
								}

								echo '<label class="sr-only" for="drive2">Drive Path</label>';
								# Check if we have a location
								if(!empty($mCfg['disks'][4]['location'])) {
									echo '<input type="text" class="form-control pdR" id="drive5_loc" name="drive5_loc" placeholder="Path" value="' . $mCfg['disks'][4]['location'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="drive5_loc" name="drive5_loc" placeholder="Path">';
								}

								echo '<label class="sr-only" for="drive3">Alert Limit</label>';
								# Check if we have a limit
								if(isset($mCfg['disks'][4]['limit'])) {
									echo '<input type="text" size="10" class="form-control" id="drive5_limit" name="drive5_limit" placeholder="Limit" value="' . $mCfg['disks'][4]['limit'] . '">';
								}else{
									echo '<input type="text" size="10" class="form-control" id="drive5_limit" name="drive5_limit" placeholder="Limit">';
								}
								echo '</div>';
							?>
						</div>

						<div role="tabpanel" class="tab-pane" id="contacts">
							<h4>Contact Information</h4>
							
							<p>Enter the information for the people who would need to be contacted when alert limits are hit. They will be alerted for drive, memory and load limits.</p>
							
							<hr>
							
							<?php
								############# CONTACT ONE
								echo '<h5>Contact 1</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Contact Email</label>';
								# Check if we have an email
								if(!empty($mCfg['contacts']['email_1'])) {
									echo '<input type="text" class="form-control pdR" id="email1" name="email1" placeholder="Email" value="' . $mCfg['contacts']['email_1'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="email1" name="email1" placeholder="Email">';
								}

								echo '<label class="sr-only" for="drive2">Contact Number</label>';
								# Check if we have a phone #
								if(!empty($mCfg['contacts']['mobile_1'])) {
									echo '<input type="text" class="form-control" id="number1" name="number1" placeholder="Number" value="' . $mCfg['contacts']['mobile_1'] . '">';
								}else{
									echo '<input type="text" class="form-control" id="number1" name="number1" placeholder="Number">';
								}
								echo '</div>';

								############# CONTACT TWO
								echo '<br /><h5>Contact 2</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Contact Email</label>';
								# Check if we have an email
								if(!empty($mCfg['contacts']['email_2'])) {
									echo '<input type="text" class="form-control pdR" id="email2" name="email2" placeholder="Email" value="' . $mCfg['contacts']['email_2'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="email2" name="email2" placeholder="Email">';
								}

								echo '<label class="sr-only" for="drive2">Contact Number</label>';
								# Check if we have a phone #
								if(!empty($mCfg['contacts']['mobile_2'])) {
									echo '<input type="text" class="form-control" id="number2" name="number2" placeholder="Number" value="' . $mCfg['contacts']['mobile_2'] . '">';
								}else{
									echo '<input type="text" class="form-control" id="number2" name="number2" placeholder="Number">';
								}
								echo '</div>';

								############# CONTACT THREE
								echo '<br /><h5>Contact 3</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Contact Email</label>';
								# Check if we have an email
								if(!empty($mCfg['contacts']['email_3'])) {
									echo '<input type="text" class="form-control pdR" id="email3" name="email3" placeholder="Email" value="' . $mCfg['contacts']['email_3'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="email3" name="email3" placeholder="Email">';
								}

								echo '<label class="sr-only" for="drive2">Contact Number</label>';
								# Check if we have a phone #
								if(!empty($mCfg['contacts']['mobile_3'])) {
									echo '<input type="text" class="form-control" id="number3" name="number3" placeholder="Number" value="' . $mCfg['contacts']['mobile_3'] . '">';
								}else{
									echo '<input type="text" class="form-control" id="number3" name="number3" placeholder="Number">';
								}
								echo '</div>';
							?>
						</div>

						<div role="tabpanel" class="tab-pane" id="limits">
							<h4>Limit Alerts</h4>
							
							<p>Set the limits for memory usage and the load level. The load level which it will alarm on is the 5 minute average.</p>
							
							<hr>
							
							<?php
								############# ALERT LIMITS
								echo '<h5>Memory and load Limits</h5>';
								echo '<div class="form-group">';
								echo '<label class="sr-only" for="drive1">Memory percentage alert level</label>';
								# Check if we have an email
								if(isset($mCfg['limits']['memory_usage'])) {
									echo '<input type="text" class="form-control pdR" id="mem_level" name="mem_level" placeholder="Memory usage percentage" value="' . $mCfg['limits']['memory_usage'] . '">';
								}else{
									echo '<input type="text" class="form-control pdR" id="mem_level" name="mem_level" placeholder="Memory usage percentage">';
								}

								echo '<label class="sr-only" for="drive2">Load alert level</label>';
								# Check if we have a phone #
								if(isset($mCfg['limits']['load_alert'])) {
									echo '<input type="text" class="form-control" id="load_level" name="load_level" placeholder="Load max level" value="' . $mCfg['limits']['load_alert'] . '">';
								}else{
									echo '<input type="text" class="form-control" id="load_level" name="load_level" placeholder="Load max level">';
								}
								echo '</div>';
							?>
						</div>

						<div role="tabpanel" class="tab-pane" id="password">
							<h4>Update Password</h4>
							
							<p>You can update your password here. This should be done on the first install to replace the default. If you would not like to change it, leave it blank.</p>
							
							<hr>
							
							<?php
								echo '<h5>Password</h5>';
								echo '<input type="password" size="64" class="form-control" id="password" name="password" placeholder="Leave blank for no change">';
							?>
						</div>
							
						<div role="tabpanel" class="tab-pane" id="control">
							<h4>Server Control</h4>
							
							<p>If you allow it, Silo can run simple commands for you on your server. Silo does not store any sensitive information about your server and only uses it to execute
							the commands that you select.</p>
							
							<hr>
							
							<div id="ssh-post" method="post">
								<div class="radio">
									<label>
										<input type="radio" name="sshSelect" id="sshSelect1" value="update" checked>
										 Update
									</label>
								</div>
								<br />
								<div class="radio">
									<label>
										<input type="radio" name="sshSelect" id="sshSelect2" value="reboot">
										 Reboot
									</label>
								</div>
								<br />
								<div class="radio">
									<label>
										<input type="radio" name="sshSelect" id="sshSelect3" value="shutdown">
										 Shutdown
									</label>
								</div>
								<br />
								<div class="radio">
									<label>
										<input type="radio" name="sshSelect" id="sshSelect4" value="custom">
										 Custom
									</label>
								</div>
								
								<br />
								
								<input type="text" class="form-control" id="custom" name="custom" placeholder="ps aux">
								
								<br /><br />
								
								<div class="form-group">
									<input type="text" class="form-control pdR" id="ssh-username" name="ssh-username" placeholder="root">
									<input type="password" class="form-control pdR" id="ssh-password" name="ssh-password" placeholder="password">
									<button id="submit-button" class="btn btn-default">Submit</button>
								</div>
							</div>
							<br />
							<div class="well well-sm result"></div>
						</div>

						<div role="tabpanel" class="tab-pane" id="about">
							<h4>About Silo</h4>
							
							<p>Welcome! Silo is a simple resource panel for servers. It can monitor drive, memory and load usage as well as execute SSH commands.</p>
							
							<p>Silo can send both email and SMS alerts to specified contacts when
							the alarm thresholds are hit. The SMS notifications are sent via an external service called <a target="_blank" href="https://nexmo.com">Nexmo</a>.</p>
							
							<p>If you have any ideas for improvement or general feedback, you can email me at <a href="mailto:montr@paq.nz">silo@paq.nz</a></p>
						</div>
					  </div>

					</div>
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
}

	# Function to load the configuration file into an array
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
<!DOCTYPE html>
<html>
	<head>
		<title>Montr - Resource Monitoring</title>
		<link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			body {
				font-family: 'Open Sans', sans-serif;
			}
			
			.progress-bar {
				background: rgba(130, 170, 204, 1);
			}
			
			.label-success {
			  background-color: #77DD77;
			}
			
			.label-info {
			  background-color: #779ECB;
			}
			
			.label-warning {
			  background-color: #CFCFC4;
			}
			.progress {
			  width: 100%;
			}
			.barwrapp {
			  position: relative;
			  max-width: 600px;
			}
		</style>
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h3 style="margin-top:65px;"> m<span style="color:#779ECB;">on</span>tr</h3>
					<div class="panel panel-default">
						<div class="panel-body fill-in">
							
						</div>
					</div>
				</div>
				
				<div class="col-md-3"></div>
			</div>
			<p align="center">powered by m<span style="color:#779ECB;">on</span>tr</p>
		</div>
		
		<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
		<script src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
		
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
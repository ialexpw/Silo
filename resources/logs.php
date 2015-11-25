<?php
	include 'cfg/config.inc.php';

	# Get the latest alerts log if available
	$cTime = time();
	if(file_exists(__DIR__ . '/data/alerts_' . date('d-m-y', $cTime) . '.json')) {
		$GetLog = file_get_contents(__DIR__ . '/data/alerts_' . date('d-m-y', $cTime) . '.json');
	}else{
		$GetLog = '';
	}

	# Check that it is not empty
	if(!empty($GetLog)) {
		$EmptyLog = 0;
		
		# Decode
		$GetLog = json_decode($GetLog, true);

		# Reverse the array (newest first)
		$GetLog = array_reverse($GetLog);
	}else{
		$EmptyLog = 1;
	}
?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<?php
			if($EmptyLog) {
				echo '<p align="center">It seems that todays log is currently empty!</p>';
				echo '<p align="center"><i class="fa fa-smile-o fa-2x"></i></p>';
			}else{
				echo '<div class="panel panel-default">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>Type</th>';
				echo '<th width="15%">Limit</th>';
				echo '<th width="25%">Date/Time</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

				foreach($GetLog as $Entry) {
					echo '<tr>';
					echo '<td>' . $Entry['type'] . '</td>';

					# Alarm limit
					if(!empty($Entry['limit'])) {
						echo '<td>' . $Entry['limit'] . '</td>';
					}else{
						echo '<td>N/A</td>';
					}

					echo '<td>' . date('d M Y \a\t H:i', $Entry['time']) . '</td>';
					echo '</tr>';
				}
				echo '</table></div>';
			}
		?>
	</div>
	<div class="col-md-1"></div>
</div>
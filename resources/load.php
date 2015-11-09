<?php
	include 'cfg/config.inc.php';

	# System load
	$SystemLoad = $Montr->getLoad();

	# System uptime
	$SystemUptime = $Montr->getUptime();

	# Memory usage
	$MemoryUsage = $Montr->getMemory();

	$Processor = $Montr->getProcessorInfo();

	# Get the latest log
	$cTime = time();
	$GetLog = file_get_contents(__DIR__ . '/data/' . date('m-d-y', $cTime) . '.json');

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
<script>
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
</script>

<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h4><span style="color:#779ECB;">o</span>verview</h4>
		<p align="center">
			<?php
				echo gethostname() . '<br />';
				echo $Processor['type'];
				echo ' (' . $Processor['cores'] . ' cores)';
			?>
		</p>
	</div>
	<div class="col-md-1"></div>
</div>

<hr>

<div class="row">
	<div class="col-md-6" style="border-right: solid 1px #ccc;">
		<p align="center">Current Load<br />
			<span class="label label-success"><?php echo $SystemLoad['one']; ?></span> 
			<span class="label label-info"><?php echo $SystemLoad['five']; ?></span> 
			<span class="label label-warning"><?php echo $SystemLoad['fifteen']; ?></span>
		</p>
	</div>
	
	<div class="col-md-6">
		<p align="center">Current Uptime<br />
			<span><?php echo $SystemUptime['days'] . ' days, ' . $SystemUptime['hours'] . ' hours and ' . $SystemUptime['minutes'] . ' minutes.'; ?>
		</p>
	</div>
</div>

<hr>

<div class="row">
	<div class="col-md-6" style="border-right: solid 1px #ccc;">
		<?php
			foreach($Cfg_disks as $Disk) {
				# Disk usage
				$DiskUsage = $Montr->getDisk($Disk['location']);
		?>
			<p align="center">Disk Usage: <?php echo $DiskUsage['used']; ?>GB / <?php echo $DiskUsage['total']; ?>GB</p>
			<div class="barwrapp">
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $DiskUsage['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $DiskUsage['percent']; ?>%;">
						<?php echo $DiskUsage['percent']; ?>%
					</div>
				</div>
				<?php
					# Add the alert limit
					if(!empty($Disk['limit']) && $Disk['limit'] > 0) {
						# Work out where it should be
						$AlertLimit_Disk = 100 - $Disk['limit'];
						echo '<div style="width: 4px; height: 20px; position: absolute; background: #FF6961; top: 0; right: ' . $AlertLimit_Disk . '%;" data-toggle="tooltip" data-placement="bottom" title="Alert Limit - ' . (100-$AlertLimit_Disk) . '%"></div>';
					}
				?>
			</div>
			<span style="font-size:8px; margin-top:-20px;" class="pull-right"><?php echo $Disk['name']; ?></span>
		<?php
			}
		?>
	</div>
	
	<div class="col-md-6">
		<p align="center">Memory Usage: <?php echo $MemoryUsage['used']; ?>MB / <?php echo $MemoryUsage['total']; ?>MB</p>
		<div class="barwrapp">	
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $MemoryUsage['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $MemoryUsage['percent']; ?>%;">
					<?php echo $MemoryUsage['percent']; ?>%
				</div>
			</div>
			<?php
				# Add the alert limit
				if(isset($Cfg_limits['memory_usage']) && !empty($Cfg_limits['memory_usage'])) {
					# Work out where it should be
					$AlertLimit_Memory = 100 - $Cfg_limits['memory_usage'];
					echo '<div style="width: 4px; height: 20px; position: absolute; background: #FF6961; top: 0; right: ' . $AlertLimit_Memory . '%;" data-toggle="tooltip" data-placement="bottom" title="Alert Limit - ' . (100-$AlertLimit_Memory) . '%"></div>';
				}
			?>
		</div>
	</div>
</div>
<?php
	if($Cfg_general['show_logs']) {
?>
<hr>

<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<h4>latest l<span style="color:#779ECB;">o</span>gs</h4>
		<?php
			if($EmptyLog) {
				echo '<p align="center">It seems that todays log is currently empty... :-)</p>';
			}else{
				echo '<div class="panel panel-default">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>Type</th>';
				echo '<th>Limit</th>';
				echo '<th>Time</th>';
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

					echo '<td>' . date('H:i', $Entry['time']) . '</td>';
					echo '</tr>';
				}
				echo '</table></div>';
			}
		?>
	</div>
	<div class="col-md-1"></div>
</div>
<?php
	}
?>
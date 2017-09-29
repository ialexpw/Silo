<?php
	include 'cfg/config.inc.php';
	
	# Load the config file
	$mCfg = $Montr->LoadConfig();

	# System load
	$SystemLoad = $Montr->getLoad();

	# System uptime
	$SystemUptime = $Montr->getUptime();

	# Memory usage
	$MemoryUsage = $Montr->getMemory($Cfg_limits['memory_units']);

	# Processor
	$Processor = $Montr->getProcessorInfo();

	# Cores
	if(!empty($Processor['phy_cores'])) {
		$ProCores = $Processor['phy_cores'];
	}else{
		$ProCores = $Processor['cores'];
	}
?>
<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>

<br />

<div class="row">
	<div class="col-md-5">
		<p align="center"><i class="fa fa-bar-chart fa-4x"></i></p>
		<h4 align="center">Current Load</h4>
		<p align="center">
			<span class="label label-info" data-toggle="tooltip" data-placement="bottom" title="1 minute average"><?php echo $SystemLoad['one']; ?></span> 
			<span class="label label-success" data-toggle="tooltip" data-placement="bottom" title="5 minute average"><?php echo $SystemLoad['five']; ?></span> 
			<span class="label label-warning" data-toggle="tooltip" data-placement="bottom" title="15 minute average"><?php echo $SystemLoad['fifteen']; ?></span>
		</p>
	</div>
	
	<div class="col-md-2"><br /></div>
	
	<div class="col-md-5">
		<p align="center"><i class="fa fa-clock-o fa-4x"></i></p>
		<h4 align="center">Current Uptime</h4>
		<p align="center">
            <span><?php echo $SystemUptime['days'] . ' days, ' . $SystemUptime['hours'] . ' hours and ' . $SystemUptime['minutes'] . ' minutes.'; ?></span>
		</p>
	</div>
</div>

<br />
<div class="panel panel-default">
	<div class="panel-body" style="font-size:18px;">
		System Hostname: <?php echo gethostname(); ?><span class="pull-right"><?php echo $Processor['type'] . ' (' . $ProCores . ' cores)'; ?></span>
	</div>
</div>
<br />

<div class="row">
	<div class="col-md-5">
		<p align="center"><i class="fa fa-hdd-o fa-4x"></i></p>
		<h4 align="center">Disk Usage</h4>
		<?php
			foreach($mCfg['disks'] as $Disk) {
				# Disk usage
				$DiskUsage = $Montr->getDisk($Disk['location']);
                
                # Invalid disk?
                if($DiskUsage['total'] == 0 && $DiskUsage['free'] == 0 && $DiskUsage['used'] == 0) {
                    echo 'Drive "' . strip_tags($Disk['name']) . '" seems to be invalid.';
                    continue;
                }
		?>
			<p><?php echo strip_tags($Disk['name']); ?></p>
			<div class="barwrapp">
				<div class="progress" style="height:12px;">
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $DiskUsage['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $DiskUsage['percent']; ?>%;">
						_
					</div>
				</div>
				<?php
					# Add the alert limit
					if(!empty($Disk['limit']) && $Disk['limit'] > 0) {
						# Work out where it should be
						$AlertLimit_Disk = 100 - $Disk['limit'];
						echo '<div style="width: 4px; height: 12px; position: absolute; background: #FF6961; top: 0; right: ' . $AlertLimit_Disk . '%;" data-toggle="tooltip" data-placement="bottom" title="Alert Limit - ' . (100-$AlertLimit_Disk) . '%"></div>';
					}
				?>
			</div>
			<span class="pull-right" style="font-size:12px; margin-top:-15px;"><?php echo $DiskUsage['used']; ?>GB / <?php echo $DiskUsage['total']; ?>GB</span>
		<?php
			}
		?>
	</div>
	
	<div class="col-md-2"><br /></div>
	
	<div class="col-md-5">
		<p align="center"><i class="fa fa-area-chart fa-4x"></i></p>
		<h4 align="center">Memory Usage</h4>
		<p>RAM</p>
		<div class="barwrapp">	
			<div class="progress" style="height:12px;">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $MemoryUsage['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $MemoryUsage['percent']; ?>%;">
					_
				</div>
			</div>
			<?php
				# Add the alert limit
				if(isset($mCfg['limits']['memory_usage']) && !empty($mCfg['limits']['memory_usage'])) {
					# Work out where it should be
					$AlertLimit_Memory = 100 - $mCfg['limits']['memory_usage'];
					echo '<div style="width: 4px; height: 12px; position: absolute; background: #FF6961; top: 0; right: ' . $AlertLimit_Memory . '%;" data-toggle="tooltip" data-placement="bottom" title="Alert Limit - ' . (100-$AlertLimit_Memory) . '%"></div>';
				}
			?>
		</div>
		<span class="pull-right" style="font-size:12px; margin-top:-15px;"><?php echo $MemoryUsage['used'] . strtoupper($Cfg_limits['memory_units']); ?> / <?php echo $MemoryUsage['total'] . strtoupper($Cfg_limits['memory_units']); ?></span>
	</div>
</div>
<?php
	echo '<pre>';
	print_r(getDisk('/'));
	echo '</pre>';

	echo '<br />';

	echo '<pre>';
	print_r(getRaidInfo());
	echo '</pre>';

	function getDisk($Prt) {
		# Total Disk
		$Disk_total = round(disk_total_space("$Prt")/1073741824, 2);
		
		# Free Space
		$Disk_free = round(disk_free_space("$Prt")/1073741824, 2);
		
		# Used Space
		$Disk_used = round($Disk_total-$Disk_free, 2);
		
		# Percentage Used
		$Disk_percent = round(($Disk_used / $Disk_total) * 100);
		
		return array(
			'total' => $Disk_total,
			'free' => $Disk_free,
			'used' => $Disk_used,
			'percent' => $Disk_percent
		);
	}

	function getRaidInfo() {
		$raidData = explode("\n", file_get_contents("/proc/mdstat"));

		print_r($raidData);
		exit();

		if(in_array("(F)", $raidData)) {
			return array('raid' => "unhealthy");
		}else{
			return array('raid' => "healthy");
		}
	}
?>
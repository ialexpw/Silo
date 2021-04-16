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

		// Set a flag
		$rdFlag = 0;

		foreach($raidData as $rd) {
			// Is the flag set?
			if($rdFlag) {
				// Check for possible errors
				if(str_contains($rd, 'U_') || str_contains($rd, '_U') || in_array("(F)", $raidData)) {
					return array('raid' => "unhealthy");
				}else{
					// Reset flag to try searching again
					$rdFlag = 0;
				}
			}

			// Check for the active/raid drive line
			if(str_contains($rd, 'active') || str_contains($rd, 'md')) {
				// Enable the flag for the next go
				$rdFlag = 1;
			}
		}

		// Could not identify raid or return healthy
		if(!$rdFlag) {
			return array('raid' => "not_available");
		}else{
			return array('raid' => "healthy");
		}
	}
?>
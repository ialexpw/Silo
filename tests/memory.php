<?php
	$mem = getSystemMemInfo();
	
	echo '<pre>';
	print_r($mem);
	echo '</pre>';

	function getSystemMemInfo() {
		$memData = explode("\n", file_get_contents("/proc/meminfo"));
		$memInfo = array();
		foreach ($memData as $line) {
			list($key, $val) = explode(":", $line);
			$memInfo[$key] = trim($val);
		}
		
		# Total memory
		$Memory_total = str_replace(' kB', '', $memInfo['MemTotal']);
		
		# Free memory
		$Memory_used_free = str_replace(' kB', '', $memInfo['MemFree']);
		
		# Buffered
		$Memory_used_buffers = str_replace(' kB', '', $memInfo['Buffers']);
		
		# Cached
		$Memory_used_cached = str_replace(' kB', '', $memInfo['Cached']);
		
		# Work out the memory used
		$Memory_used = $Memory_total - $Memory_used_free - $Memory_used_buffers - $Memory_used_cached;
		
		# Round the values to the nearest MB
		$Memory_total = round($Memory_total/1024);
		$Memory_used = round($Memory_used/1024);
		
		# Free Memory
		$Memory_free = round($Memory_total-$Memory_used, 2);
		
		# Percentage Used
		$Memory_percent = round(($Memory_used / $Memory_total) * 100);
		
		return array(
			'total' => $Memory_total,
			'free' => $Memory_free,
			'used' => $Memory_used,
			'percent' => $Memory_percent
		);
	}
?>
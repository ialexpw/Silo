<?php
	echo '<pre>';
	print_r(getUptime());
	echo '</pre>';

	function getUptime() {
		# Get uptime seconds
		$Uptime = explode(' ', file_get_contents('/proc/uptime'));
		
		# Get days uptime
		$Uptime_days = floor($Uptime[0]/60/60/24);
		
		# Get hours uptime
		$Uptime_hours = $Uptime[0]/60/60%24;
			
		# Get minutes uptime
		$Uptime_minutes = $Uptime[0]/60%60;
		
		return array(
			'days' => $Uptime_days,
			'hours' => $Uptime_hours,
			'minutes' => $Uptime_minutes
		);
	}
?>
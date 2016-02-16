<?php
	echo '<pre>';
	print_r(getLoad());
	echo '</pre>';

	function getLoad() {
		# Get 5 minute load
		$Load = explode(' ', file_get_contents('/proc/loadavg'));
		
		return array(
			'one' => $Load[0],
			'five' => $Load[1],
			'fifteen' => $Load[2]
		);
	}
?>
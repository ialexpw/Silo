<?php
	$proc = getProcessorInfo();
	
	echo '<pre>';
	print_r($proc);
	echo '</pre>';

	function getProcessorInfo() {
		$procData = explode("\n", file_get_contents("/proc/cpuinfo"));
		$procInfo = array();
		foreach ($procData as $line) {
			if(!empty($line)) {
				list($key, $val) = explode(":", $line);
				$procInfo[str_replace(' ', '_', trim($key))] = trim($val);
			}
		}
		
		# Processor type
		$Proc_type = $procInfo['model_name'];
		
		$Proc_cores = $procInfo['cpu_cores'];

		return array(
			'type' => $Proc_type,
			'cores' => $Proc_cores
		);
	}
?>
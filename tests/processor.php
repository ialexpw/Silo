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
		
		# Proc cores (older)
		$Proc_cores = $procInfo['cpu_cores'];

		# Logical cores
		$log_Cores = shell_exec("lscpu -p | egrep -v '^#' | wc -l");
		$log_Cores = str_replace(" ", "", trim($log_Cores));

		# Physical cores
		$phy_Cores = shell_exec("lscpu -p | egrep -v '^#' | sort -u -t, -k 2,4 | wc -l");
		$phy_Cores = str_replace(" ", "", trim($phy_Cores));

		return array(
			'type' => $Proc_type,
			'cores' => $Proc_cores,
			'log_cores' => $log_Cores,
			'phy_cores' => $phy_Cores
		);
	}
?>
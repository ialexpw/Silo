<?php
	#######################
	## Begin configuration
	#######################
	
	# Include libraries
	include __DIR__ . '/../lib/Nexmo/NexmoMessage.php';
	
	# Define the class
	$Montr = new Montr();

	# Install montr if it has not been
	$Montr->InstallMontr();
	
	# Send another alert when the alert clears?
	$Cfg_general = array(
		'alert_on_clear' => 0
	);
	
	# What units to use for memory usage (kb, mb, gb)
	$Cfg_limits = array(
		'memory_units' => 'mb'
	);
	
	#######################
	## End configuration
	#######################
	
	class Montr {
		function WriteLog($Type, $Limit='') {
			# Get the day/time
			$cTime = time();
			
			# Check if we have an alert log
			if(!file_exists(__DIR__ . '/../data/alerts_' . date('d-m-y', $cTime) . '.json')) {
				$mkAlertLog = fopen(__DIR__ . '/../data/alerts_' . date('d-m-y', $cTime) . '.json', 'w') or die('Please ensure the data folder is writable');
			}
			
			# Going into alarm
			if(!empty($Limit)) {
				# Should we add a %?
				if(strpos($Type, 'disk_usage') !== false) {
					$mkArray = array(
						'type' => $Type,
						'limit' => $Limit . '%',
						'time' => $cTime
					);
				}else if($Type == 'memory_usage') {
					$mkArray = array(
						'type' => $Type,
						'limit' => $Limit[$Type] . '%',
						'time' => $cTime
					);
				}else{
					$mkArray = array(
						'type' => $Type,
						'limit' => $Limit[$Type],
						'time' => $cTime
					);
				}
			}else{
				$mkArray = array(
					'type' => $Type,
					'time' => $cTime
				);
			}
			
			# Open our log file and decode the JSON
			$LogContents = file_get_contents(__DIR__ . '/../data/alerts_' . date('d-m-y', $cTime) . '.json');
			$tmpContents = json_decode($LogContents);
			
			# Is the file empty?
			if(empty($tmpContents)) {
				$tmpContents = array();
			}
			
			# Push the new array into it
			array_push($tmpContents, $mkArray);
			
			# JSON encode it again
			$jsonData = json_encode($tmpContents);
			
			# ...and then put it back
			file_put_contents(__DIR__ . '/../data/alerts_' . date('d-m-y', $cTime) . '.json', $jsonData);
		}
        
        function WriteCronTime($time) {
            # Write the time the cron last went
            $cron = array(
                'time' => $time,
            );
            
            # JSON encode it
            $jsonCron = json_encode($cron);
            
            # ...and then push it
			file_put_contents(__DIR__ . '/../data/cron.json', $jsonCron);
        }
        
        function WriteData($dataArr) {
            
        }
		
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

            # Processor cores
			$Proc_cores = $procInfo['cpu_cores'];

			//return array(
			//	'type' => $Proc_type,
			//	'cores' => $Proc_cores
			//);
            
            ///////////////////////
            
            # Logical cores
            $log_Cores = shell_exec("lscpu -p | egrep -v '^#' | wc -l");
            
            # Physical cores
            $phy_Cores = shell_exec("lscpu -p | egrep -v '^#' | sort -u -t, -k 2,4 | wc -l");
            
            return array(
                'type' => $Proc_type,
				'cores' => $Proc_cores,
                'log_cores' => $log_Cores,
                'phy_cores' => $phy_Cores
            );
            
            ///////////////////////
		}
		
		function getMemory($units = '') {
			$memData = explode("\n", file_get_contents("/proc/meminfo"));
			$memInfo = array();
			foreach ($memData as $line) {
				if(!empty($line)) {
					list($key, $val) = explode(":", $line);
					$memInfo[$key] = trim($val);
				}
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
			
			# Work out units
			if((empty($units)) || strtolower($units) != 'kb' && strtolower($units) != 'mb' && strtolower($units) != 'gb'){
				$units = 'mb';
			}
			
			# Work out units
			if(strtolower($units) == 'kb'){
				$Memory_total = round($Memory_total);
				$Memory_used = round($Memory_used);
			}else if(strtolower($units) == 'mb') {
				$Memory_total = round($Memory_total/1024);
				$Memory_used = round($Memory_used/1024);
			}else if(strtolower($units) == 'gb'){
				$Memory_total = round($Memory_total/1048576, 2);
				$Memory_used = round($Memory_used/1048576, 2);
			}
			
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
	
		function getLoad() {
			# Get server load
			$Load = explode(' ', file_get_contents('/proc/loadavg'));
	
			return array(
				'one' => $Load[0],
				'five' => $Load[1],
				'fifteen' => $Load[2]
			);
		}
	
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
		
		function sendAlerts($Type, $Contacts, $General) {
			foreach($Contacts as $Contact_type => $Contact) {
				# Send mobile alerts via Nexmo / SMS
				if(strpos($Contact_type, 'mobile') !== false) {
					if(!empty($General['nexmo']['key']) && !empty($General['nexmo']['secret'])) {
						$NexmoSMS = new NexmoMessage($General['nexmo']['key'], $General['nexmo']['secret']);
					
						# Send the SMS message
						$info = $NexmoSMS->sendText($Contact, 'Silo Alert', 'A ' . $Type . ' alert has been generated for ' . $General['server']['name'] . '!');
					}
				}
				
				# Send an email alert
				if(strpos($Contact_type, 'email') !== false) {
					$Message = 'A ' . $Type . ' alert has been generated for ' . $General['server']['name'] . '!';
					$Headers = 'From: alerts@silo.one' . "\r\n" .
						'Reply-To: alerts@silo.one' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					
					mail($Contact, 'Silo Alert', $Message, $Headers);
				}
			}
		}
		
		function makeSlug($input) {
			# Replace spaces with underscores
			$input = str_replace(' ', '_', $input);
			
			return $input;
		}
		
		function InstallMontr() {
			if(file_exists(__DIR__ . '/../data/config.json')) {
				return;
			}else{
				$mkLog = fopen(__DIR__ . '/../data/config.json', 'w') or die('Please ensure the data folder is writable');
			}
			
			# Hash the password
			$stPass = password_hash('password', PASSWORD_BCRYPT);
			
			$defCfg = array(
				'auth' => array(
					'password' => $stPass
				),
				'server' => array(
					'name' => 'Server'
				),
				'nexmo' => array(
					'key' => '',
					'secret'
				),
				'disks' => array(
					0 => array(
						'name' => 'Main Drive',
						'location' => '/',
						'limit' => '80' # (%)
					),
				),
				'contacts' => array(
					'mobile_1' => '447711223344',
					'email_1' => 'alerts@silo.one'
				),
				'limits' => array(
					'memory_usage' => '75', # (%)
					'memory_units' => 'mb',
					'load_alert' => '6'
				)
			);
			
			# JSON encode it
			$jsonData = json_encode($defCfg);
			
			# ...and then put it inside the new file
			file_put_contents(__DIR__ . '/../data/config.json', $jsonData);
		}
		
		function LoadConfig() {
			if(file_exists(__DIR__ . '/../data/config.json')) {
				$cfgCont = file_get_contents(__DIR__ . '/../data/config.json');
		
				$cfgCont = json_decode($cfgCont, true);
		
				return $cfgCont;
			}
		}
	}
?>
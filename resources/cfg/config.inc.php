<?php
	#######################
	## Begin configuration
	#######################
	
	# Debugging only
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	# Include libraries
	include __DIR__ . '/../lib/Nexmo/NexmoMessage.php';
	
	# Define the class
	$Montr = new Montr();
	
	# General configuration
	$Cfg_general = array(
		'site_name' => 'Website Title',
		'nexmo_key' => '885db2be',
		'nexmo_secret' => '773f91d9',
		'alert_on_clear' => 0,
		'show_logs' => 1
	);
	
	# Who to contact when an alert is triggered
	$Cfg_contacts = array(
		//'mobile_personal' => '447711868356',
		'mobile_work' => '447867396980',
		'email_alerts' => 'alerts@paq.nz',
		'email_personal' => 'alex@paq.nz'
	);
	
	# Disk configuration
	$Cfg_disks = array(
		0 => array(
			'name' => 'SSD Disk',
			'location' => '/',
			'limit' => '80' # (%)
		),
		1 => array(
			'name' => 'Storage One',
			'location' => '/mnt/store1',
			'limit' => '80' # (%)
		),
		2 => array(
			'name' => 'Media Store',
			'location' => '/mnt/store2',
			'limit' => '95' # (%)
		),
		3 => array(
			'name' => 'Storage Three',
			'location' => '/mnt/store3',
			'limit' => '80' # (%)
		),
		4 => array(
			'name' => 'Storage Four',
			'location' => '/mnt/store4',
			'limit' => '80' # (%)
		),
	);
	
	# The limits that must be reached to send out alerts
	$Cfg_limits = array(
		'memory_usage' => '75', # (%)
		'load_alert' => '6'
	);
	
	#######################
	## End configuration
	#######################
	
	class Montr {
		function CreateLog() {
			# Get the day
			$cTime = time();
			
			# Check if we have a log
			if(file_exists(__DIR__ . '/../data/' . date('m-d-y', $cTime) . '.json')) {
				return;
			}else{
				$mkLog = fopen(__DIR__ . '/../data/' . date('m-d-y', $cTime) . '.json', 'w') or die('Please ensure the data folder is writable');
			}
		}
		
		function WriteLog($Type, $Limit='') {
			# Get the day/time
			$cTime = time();
			
			# Going into alarm
			if(!empty($Limit)) {
				# Should we add a %?
				if($Type == 'disk_usage' or $Type == 'memory_usage') {
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
			$LogContents = file_get_contents(__DIR__ . '/../data/' . date('m-d-y', $cTime) . '.json');
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
			file_put_contents(__DIR__ . '/../data/' . date('m-d-y', $cTime) . '.json', $jsonData);
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

			$Proc_cores = $procInfo['cpu_cores'];

			return array(
				'type' => $Proc_type,
				'cores' => $Proc_cores
			);
		}
		
		function getMemory() {
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
					$NexmoSMS = new NexmoMessage($General['nexmo_key'], $General['nexmo_secret']);
					
					# Send the SMS message
					$info = $NexmoSMS->sendText($Contact, 'Montr Alert', 'A ' . $Type . ' alert has been generated for ' . $General['site_name'] . '!');
				}
				
				# Send an email alert
				if(strpos($Contact_type, 'email') !== false) {
					$Message = 'A ' . $Type . ' alert has been generated for ' . $General['site_name'] . '!';
					$Headers = 'From: noreply@paq.nz' . "\r\n" .
						'Reply-To: noreply@paq.nz' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					
					mail($Contact, 'Montr Alert', $Message, $Headers);
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
			
			# Default configuration
			$defCfg = array(
				'username' => 'admin',
				'password' => 'password'
			);
			
			# JSON encode it
			$jsonData = json_encode($defCfg);
			
			# ...and then put it inside the new file
			file_put_contents(__DIR__ . '/../data/config.json', $jsonData);
		}
	}
?>
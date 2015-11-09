<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	include 'cfg/config.inc.php';

	# Create daily log file
	$mtLog = $Montr->CreateLog();
	
	# Server load
	$MemoryUsage = $Montr->getMemory();
	
	# Server load
	$ServerLoad = $Montr->getLoad();
	
	# Disk limits are done separate due to multiple disks
	foreach($Cfg_disks as $Disk) {
		# Initialise as disk_usage
		$type = 'disk_usage';
		
		# Disk usage
		$DiskUsage = $Montr->getDisk($Disk['location']);
		
		# Over limits?
		if(!empty($Disk['limit']) && $Disk['limit'] > 0) {
			if($DiskUsage['percent'] > $Disk['limit']) {
				# Check if the alert was sent before
				if(!file_exists(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk['name']))) {
					fopen(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk['name']), 'w');
					
					# Alert was not sent before, send it
					$Montr->sendAlerts($type . ' - ' . $Montr->makeSlug($Disk['name']), $Cfg_contacts, $Cfg_general);
					
					# Write to the daily log file
					$Montr->WriteLog($type . ' - ' . $Montr->makeSlug($Disk['name']), $Cfg_limits);
				}
			}else{
				if(file_exists(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk['name']))) {
					unlink(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk['name']));
					
					# Send an alert once it's cleared?
					if($Cfg_general['alert_on_clear']) {
						# Alert was not sent before, send it
						$Montr->sendAlerts($type . '_cleared - ', $Cfg_contacts, $Cfg_general);
					}
					
					# Write to the daily log file
					$Montr->WriteLog($type . '_cleared');
				}
			}
		}
	}
	
	# Loop through all our limits
	foreach($Cfg_limits as $type => $limit) {
		# Disk usage
		if($type == 'disk_usage') {
			foreach($Cfg_location as $Disk => $Location) {
				# Disk usage
				$DiskUsage = $Montr->getDisk($Location);
				
				if($DiskUsage['percent'] > $limit) {
					# Check if the alert was sent before
					if(!file_exists(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk))) {
						fopen(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk), 'w');
						
						# Alert was not sent before, send it
						$Montr->sendAlerts($type, $Cfg_contacts, $Cfg_general);
						
						# Write to the daily log file
						$Montr->WriteLog($type, $Cfg_limits);
					}
				}else{
					if(file_exists(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk))) {
						unlink(__DIR__ . '/data/disk_usage_' . $Montr->makeSlug($Disk));
						
						# Send an alert once it's cleared?
						if($Cfg_general['alert_on_clear']) {
							# Alert was not sent before, send it
							$Montr->sendAlerts($type . '_cleared', $Cfg_contacts, $Cfg_general);
						}
						
						# Write to the daily log file
						$Montr->WriteLog($type . '_cleared');
					}
				}
			}
		}
		
		# Memory usage
		if($type == 'memory_usage') {
			if($MemoryUsage['percent'] > $limit) {
				# Check if the alert was sent before
				if(!file_exists(__DIR__ . '/data/memory_usage')) {
					fopen(__DIR__ . '/data/memory_usage', 'w');
					
					# Alert was not sent before, send it
					$Montr->sendAlerts($type, $Cfg_contacts, $Cfg_general);
					
					# Write to the daily log file
					$Montr->WriteLog($type, $Cfg_limits);
				}
			}else{
				if(file_exists(__DIR__ . '/data/memory_usage')) {
					unlink(__DIR__ . '/data/memory_usage');
					
					# Send an alert once it's cleared?
					if($Cfg_general['alert_on_clear']) {
						# Alert was not sent before, send it
						$Montr->sendAlerts($type . '_cleared', $Cfg_contacts, $Cfg_general);
					}
					
					# Write to the daily log file
					$Montr->WriteLog($type . '_cleared');
				}
			}
		}
		
		# Load alerts
		if($type == 'load_alert') {
			if($ServerLoad['one'] > $limit) {
				# Check if the alert was sent before
				if(!file_exists(__DIR__ . '/data/load_alert')) {
					fopen(__DIR__ . '/data/load_alert', 'w');
					
					# Alert was not sent before, send it
					$Montr->sendAlerts($type, $Cfg_contacts, $Cfg_general);
					
					# Write to the daily log file
					$Montr->WriteLog($type, $Cfg_limits);
				}
			}else{
				if(file_exists(__DIR__ . '/data/load_alert')) {
					unlink(__DIR__ . '/data/load_alert');
					
					# Send an alert once it's cleared?
					if($Cfg_general['alert_on_clear']) {
						# Alert was not sent before, send it
						$Montr->sendAlerts($type . '_cleared', $Cfg_contacts, $Cfg_general);
					}
					
					# Write to the daily log file
					$Montr->WriteLog($type . '_cleared');
				}
			}
		}
	}
?>
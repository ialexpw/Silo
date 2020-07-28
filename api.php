<?php
	include 'resources/cfg/config.inc.php';

	# Load the config file
	$mCfg = $Montr->LoadConfig();

	$apiConf = array();

	# Server name
	if(!empty($mCfg['server']['name'])) {
		$apiConf['server']['name'] = $mCfg['server']['name'];
	}

	# Build the drive array
	for($i=0;$i<5;$i++) {
		if(!empty($mCfg['disks'][$i]['name']) && !empty($mCfg['disks'][$i]['location']) && isset($mCfg['disks'][$i]['limit'])) {
			$apiConf['disks'][$i]['name'] = $mCfg['disks'][$i]['name'];
			$apiConf['disks'][$i]['location'] = $mCfg['disks'][$i]['location'];
			$apiConf['disks'][$i]['limit'] = $mCfg['disks'][$i]['limit'];
		}
	}

	# Memory usage alert percentage
	if(isset($mCfg['limits']['memory_usage'])) {
		$apiConf['limits']['memory_usage'] = $_POST['mem_level'];
	}
	
	# Load alert level
	if(isset($mCfg['limits']['load_alert'])) {
		$apiConf['limits']['load_alert'] = $_POST['load_level'];
	}
	
	# Encode the array into json
	$apiConf = json_encode($apiConf);

	echo '<pre>';
	print_r($mCfg);
	echo '</pre>';

	echo '<pre>';
	print_r($apiConf);
	echo '</pre>';
?>
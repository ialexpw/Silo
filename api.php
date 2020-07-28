<?php
	include 'resources/cfg/config.inc.php';

	# Load the config file
	$mCfg = $Montr->LoadConfig();

	$apiConf = array(

	);

	echo '<pre>';
	print_r($mCfg);
	echo '</pre>';
?>
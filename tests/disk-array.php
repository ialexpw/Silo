<?php
	include '../resources/cfg/config.inc.php';
	
	foreach($Cfg_disks as $Disk) {
		print_r($Disk);
		echo '<br />';
		echo $Disk['name'];
		echo '<br />';
	}
?>
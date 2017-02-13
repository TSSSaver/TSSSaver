<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	require_once 'inc/medoo.php';
	require_once 'inc/config.php';
	require_once 'inc/functions.php';
	
	$countApnonce = count($apnonce);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$signedVersionsURL);
	$result = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result, true);
	
	
	
	$database = new medoo([
			'database_type' => 'mysql',
			'database_name' => $db['name'],
			'server' => $db['server'],
			'username' => $db['user'],
			'password' => $db['password'],
			'charset' => 'utf8'
		]);
	
	$savedDevices = $database->query('SELECT * FROM '.$db['table'])->fetchAll();
	unset($database);
	
	$countSavedDevices = count($savedDevices);
	echo "Total ECIDs: ".$countSavedDevices."\n";
	for($i = 0; $i < $countSavedDevices; $i++) {
		$deviceInfo = $savedDevices[$i];
		echo "Current ECID: ".$deviceInfo['deviceECID']."\n";
		
		$firmwares = $data['devices'][$deviceInfo['deviceIdentifier']]['firmwares'];
		$countFirmwares = count($firmwares);
		for($y = 0; $y < $countFirmwares; $y++) {
			$current = $firmwares[$y];
			if($current['signed'] == true)
				$firmware[] = $current;
		}
		$countFirmware = count($firmware);
		
		for($a = 0; $a < $countFirmware; $a++) {
			$currentFirmware = $firmware[$a];
			
			$savePath = 'shsh/'.$deviceInfo['deviceECID'].'/'.$currentFirmware['version'];
			
			if (!file_exists($savePath)) {
				mkdir($savePath, 0777, true);
			}
			
			if (!file_exists($savePath.'/noapnonce')) {
				mkdir($savePath.'/noapnonce', 0777, true);
			}
			
			$cmd = "./tsschecker";
			$cmd .= " -d ".escapeshellarg($deviceInfo['deviceIdentifier']);
			$cmd .= " -e ".escapeshellarg($deviceInfo['deviceECID']);
			$cmd .= " -i ".escapeshellarg($currentFirmware['version']);
			$cmd .= " --buildid ".escapeshellarg($currentFirmware['buildid']);
			$cmd .= " -s ";
			$cmd .= "--save-path ".$savePath.'/noapnonce';
			echo "Running: ".$cmd."\n";
			shell_exec($cmd);
			
			for($b = 0; $b < $countApnonce; $b++) {
				$currentApnonce = $apnonce[$b];
				
				if (!file_exists($savePath.'/apnonce-'.$currentApnonce)) {
					mkdir($savePath.'/apnonce-'.$currentApnonce, 0777, true);
				}
				
				$cmd = "./tsschecker ";
				$cmd .= "-d ".escapeshellarg($deviceInfo['deviceIdentifier']);
				$cmd .= " -e ".escapeshellarg($deviceInfo['deviceECID']);
				$cmd .= " -i ".escapeshellarg($currentFirmware['version']);
				$cmd .= " --buildid ".escapeshellarg($currentFirmware['buildid']);
				$cmd .= " -s";
				$cmd .= " --apnonce ".$currentApnonce;
				$cmd .= " --save-path ".$savePath.'/apnonce-'.$currentApnonce.'';
				echo "Running: ".$cmd."\n";
				shell_exec($cmd);
			}
		}
	}
?>

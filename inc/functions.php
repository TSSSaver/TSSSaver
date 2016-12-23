<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	
	function saveBlobs($deviceInfo, $apnonce, $signedVersionsURL) {
		$countApnonce = count($apnonce);  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$signedVersionsURL);
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result, true);
		
		$firmwares = $data['devices'][$deviceInfo['deviceIdentifier']]['firmwares'];
		$countFirmwares = count($firmwares);
		for($i = 0; $i < $countFirmwares; $i++) {
			$current = $firmwares[$i];
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
			
			$cmd  = "./bin/tsschecker";
			$cmd .= " -d ".escapeshellarg($deviceInfo['deviceIdentifier']);
			$cmd .= " -e ".escapeshellarg($deviceInfo['deviceECID']);
			$cmd .= " -i ".escapeshellarg($currentFirmware['version']);
			$cmd .= " --buildid ".escapeshellarg($currentFirmware['buildid']);
			$cmd .= " --save-path ".$savePath.'/noapnonce';
			$cmd .= " -s";
			shell_exec($cmd);
			
			for($b = 0; $b < $countApnonce; $b++) {
				$currentApnonce = $apnonce[$b];
				if (!file_exists($savePath.'/apnonce-'.$currentApnonce)) {
					mkdir($savePath.'/apnonce-'.$currentApnonce, 0777, true);
				}
				
				$cmd  = "./bin/tsschecker";
				$cmd .= " -d ".escapeshellarg($deviceInfo['deviceIdentifier']);
				$cmd .= " -e ".escapeshellarg($deviceInfo['deviceECID']);
				$cmd .= " -i ".escapeshellarg($currentFirmware['version']);
				$cmd .= " --buildid ".escapeshellarg($currentFirmware['buildid']);
				$cmd .= " --apnonce ".$currentApnonce;
				$cmd .= " --save-path ".$savePath.'/apnonce-'.$currentApnonce;
				$cmd .= " -s ";
				
				shell_exec($cmd);
			}
		}
	}
	
	function err($error) {
		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= "<title> Error - TSS Saver</title>";
		$html .= "<meta http-equiv='refresh' content='5'>";
		$html .= "<meta name='viewport' content='width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no' />";
		$html .= "<link rel='stylesheet' href='style.css'>";
		$html .= "<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800' rel='stylesheet'>";
		$html .= "</head>";
		$html .= "<body>";
		$html .= "<div class='box'>";
		$html .= "<h1 class='note'>";
		$html .= $error;
		$html .= "</h1>";
		$html .= "<p>Refresh in 5 seconds...";
		$html .= "</div>";
		$html .= "</body>";
		$html .= "</html>";
		die($html);
	}
?>

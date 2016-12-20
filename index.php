<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	
	require_once 'inc/medoo.php';
	require_once 'inc/config.php';
	require_once 'inc/functions.php';
	
	
	
	if(isset($_POST['submit'])) {
		if($reCaptcha['enabled'] == true) {
			if (!isset($_POST['g-recaptcha-response'])) {
				err("Captcha Error!");
			}
			
			$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$reCaptcha['privateKey']."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
			$response2 = json_decode($response, true);
			
			if($response2['success'] == false){
				err("Captcha Error!");
			}
		}
		if(ctype_xdigit($_POST['ECID']) && is_numeric(hexdec($_POST['ECID']))) {
			$deviceECID = hexdec($_POST['ECID']);
		} else {
			err("Invalid ECID!");
		}
		
		// TODO: Change to let users just select the model
		if($_POST['deviceType'] == 0) {
			$deviceType = "iPhone";
		} else if($_POST['deviceType'] == 1) {
			$deviceType = "iPod";
		} else if($_POST['deviceType'] == 2) {
			$deviceType = "iPad";
		} else if($_POST['deviceType'] == 3) {
			$deviceType = "AppleTV";
		}
		
		

		$deviceInfo = array(
				'deviceIdentifier' => $deviceType.$_POST['identifier'],
				'deviceType' => $deviceType,
				'deviceID' => $_POST['identifier'],
				'deviceECID' => $deviceECID
			);
		$deviceList = json_decode(file_get_contents('json/devices.json'), true);
		if(!in_array($deviceInfo['deviceIdentifier'], $deviceList)){
			err("Device Identifier not recognized!");
		}
		
		
		
		$database = new medoo([
				'database_type' => 'mysql',
				'database_name' => $db['name'],
				'server' => $db['server'],
				'username' => $db['user'],
				'password' => $db['password'],
				'charset' => 'utf8'
			]);
		 
		$result = $database->select($database['table'], 'deviceECID', [
				'deviceECID' => $deviceECID
			]);
		
		$url = $savedSHSHURL.$deviceECID;
		
		if(count($result) > 0) {
			saveBlobs($deviceInfo);
			
			die("<center>Device identifier already added! (Force download starting.) <br><a href='".$url."'>".$url."</a></center>");
		}
		
		if (!file_exists('shsh/'.$deviceECID)) {
			mkdir('shsh/'.$deviceECID, 0777, true);
		}
		
		$database->insert($database['table'], $deviceInfo);
		
		saveBlobs($deviceInfo);
		
		exit("<center>Done saving ECID!<br>SHSH blobs will be saved in <a href='".$url."'>".$url."</a></center>");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>TSS Saver - Conan</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800" rel="stylesheet">
	</head>
	<body>
		<div class="box">
			<h1 class="title"><span style="font-weight:600;">SHSH2</span> Blobs Saver</h1>
			<p class="author">by <a href="https://www.reddit.com/user/1Conan/">/u/1Conan</a></p>
			<p class="author">Theme by <a href="https://www.reddit.com/user/MareddySaiKiran">/u/MareddySaiKiran</a></p>
			</div>
		<div class="box">
			<h1 class="note">Note : </h1>
			<p>&#8226; Please use HEXADECIMAL ECIDs only.</p>
			<p>&#8226; Reddit thread + tutorial: <a href="https://redd.it/5ivapw" target="_blank">Click Here</a></p>
			<p>&#8226; Find device identifiers here: <a href="https://www.theiphonewiki.com/wiki/Models" target="_blank">The iPhone Wiki:Models</a></p>
		</div>
		<div class="box">
			<h1 class="note">ECID : </h1>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
				<input type="text" name="ECID" placeholder="Type ECID Here..."><br>
				<br>
				<h1 class="note">Identifier : </h1>
				<select name="deviceType">
					<option value="0">iPhone</option>
					<option value="1">iPod</option>
					<option value="2">iPad</option>
					<option value="3">AppleTV</option>
				</select>
				<input type="text" name="identifier" placeholder="Ex. 8,1" style="width:50px;padding:7px;">
				<br><br>
				<?php 
					if($reCaptcha['enabled'] == true) {
						echo '<div class="g-recaptcha" data-sitekey="'.$reCaptcha['publicKey'].'"></div><br>';
					}
				?>
				<input class="button" type="submit" value="Submit" name="submit">
			</form>  
		</div>
		<div class="box">
			<h1 class="note">Lost your link? </h1>
			<input type="text" name="ECID" placeholder="Type (HEX)ECID Here..." id="inp_hex"><br><br>
			<button class="button" id="showlink" style="width:100%">Get your blobs</a>
		</div>
		<p style="text-align:center;">Copyright &copy; 1Conan, 2016</p>
		<script>
				var serverURL = <?php echo $serverURL; ?>;
				function getJSON(url) {
						var request = new XMLHttpRequest(),
							data;
						request.open('GET', url, false);
						request.onload = function () {
							if (request.status >= 200 && request.status < 400) {
								data = request.responseText;
							} else {
								return false;
							}
						};
						request.send();
						return JSON.parse(data);
					}
				document.getElementById("showlink").onclick = function() {
					var ecid = getJSON(serverURL + "conv.php?hex=" + document.getElementById("inp_hex").value);
					window.location = serverURL + "shsh/" + ecid.dec
				};
		</script>
		<?php 
			if($reCaptcha['enabled'] == true) {
				echo "<script src='https://www.google.com/recaptcha/api.js' defer></script>";
			}
		?>
	</body>
</html>

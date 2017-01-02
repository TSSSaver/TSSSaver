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
		if($_POST['ECIDType'] == 0) {
			if(ctype_xdigit($_POST['ECID']) && is_numeric(hexdec($_POST['ECID']))) {
				$deviceECID = hexdec($_POST['ECID']);
			} else {
				err("Invalid ECID! (HEX)");
			}
		} else if($_POST['ECIDType'] == 1) {
			if(is_numeric($_POST['ECID'])) {
				$deviceECID = $_POST['ECID'];
			} else {
				err("Invalid ECID! (DEC)");
			}
		}
		
		if($_POST['deviceType'] == "iPhone") {
			$deviceType = "iPhone";
		} else if($_POST['deviceType'] == "iPod") {
			$deviceType = "iPod";
		} else if($_POST['deviceType'] == "iPad") {
			$deviceType = "iPad";
		} else if($_POST['deviceType'] == "AppleTV") {
			$deviceType = "AppleTV";
		} else {
            err("Invalid Device Type!");
        }
        $deviceModelList = json_decode(file_get_contents('json/deviceModels.json'), true);
        
        $deviceIdentifier = $deviceModelList[$deviceType][$_POST['deviceModel']];

		$deviceInfo = array(
				'deviceIdentifier' => $deviceIdentifier,
				'deviceType' => $deviceType,
				'deviceID' => str_replace($deviceType, "", $deviceIdentifier),
				'deviceECID' => $deviceECID
			);
		$deviceList = json_decode(file_get_contents('json/devices.json'), true);
		if(!in_array($deviceInfo['deviceIdentifier'], $deviceList)){
			err("Device Identifier not recognized! ".$deviceIdentifier);
		}
		
		
		
		$database = new medoo([
				'database_type' => 'mysql',
				'database_name' => $db['name'],
				'server' => $db['server'],
				'username' => $db['user'],
				'password' => $db['password'],
				'charset' => 'utf8'
			]);
		$result = $database->select($db['table'], 'deviceECID', [
				'deviceECID' => $deviceECID
			]);
		
		$url = $savedSHSHURL.$deviceECID;
		
		if(count($result) > 0) {
			saveBlobs($deviceInfo, $apnonce, $signedVersionsURL);
			
			die("<center>Device identifier already added! (Force download starting.) <br><a href='".$url."'>".$url."</a></center>");
		}
		
		if (!file_exists('shsh/'.$deviceECID)) {
			mkdir('shsh/'.$deviceECID, 0777, true);
		}
		
		$database->insert($db['table'], $deviceInfo);
		
		saveBlobs($deviceInfo, $apnonce, $signedVersionsURL);
		
		exit("<center>Done saving ECID!<br>SHSH blobs will be saved in <a href='".$url."'>".$url."</a></center>");
	}
	
	if(isset($_POST['delete'])) {
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
		if($_POST['ECIDType'] == 0) {
			if(ctype_xdigit($_POST['ECID']) && is_numeric(hexdec($_POST['ECID']))) {
				$deviceECID = hexdec($_POST['ECID']);
			} else {
				err("Invalid ECID! (HEX)");
			}
		} else if($_POST['ECIDType'] == 1) {
			if(is_numeric($_POST['ECID'])) {
				$deviceECID = $_POST['ECID'];
			} else {
				err("Invalid ECID! (DEC)");
			}
		}
		$database = new medoo([
				'database_type' => 'mysql',
				'database_name' => $db['name'],
				'server' => $db['server'],
				'username' => $db['user'],
				'password' => $db['password'],
				'charset' => 'utf8'
			]);
		
		$result = $database->select($db['table'], 'deviceECID', [
				'deviceECID' => $deviceECID
			]);
		if(count($result) == 0) {
			err("<center>ECID Not found!</center>");
		}
		$database->delete($db['table'], [
				"AND" => [
					"deviceECID" => $deviceECID
				]
			]);
		err('Sucessfully deleted ECID!');
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>TSS Saver</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800" rel="stylesheet">
	</head>
	<body>
		<div class="box">
			<h1 class="title"><span style="font-weight:600;">TSS Saver</span> - SHSH2 Blobs Saver</h1>
			<p class="author">by <a href="https://www.reddit.com/user/1Conan/">/u/1Conan</a></p>
			<p class="author">Theme by <a href="https://www.reddit.com/user/MareddySaiKiran">/u/MareddySaiKiran</a></p>
			</div>
		<div class="box">
			<h1 class="note">Note : </h1>
			<p>&#8226; Reddit thread + tutorial: <a href="https://redd.it/5ivapw" target="_blank">Click Here</a></p>
			<p>&#8226; Verify blobs here: <a href="check.php" target="_blank">Click Here</a></p>
		</div>
		<div class="box">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<h1 class="note">ECID : </h1>
			    <div class="inputGroup">
                    <select name="ECIDType" style="width:15%;float:left;height:29px">
                        <option value="0">Hex</option>
                        <option value="1">Dec</option>
                    </select>
					<input type="text" name="ECID" placeholder="Type ECID Here..." style="width:85%">
				</div>
				<br>
				<br>
				<h1 class="note">Identifier : </h1>
				<select id="deviceType" name="deviceType">
					<option value="iPhone">iPhone</option>
					<option value="iPod">iPod</option>
					<option value="iPad">iPad</option>
					<option value="AppleTV">AppleTV</option>
				</select>
				<select id="deviceModel" name="deviceModel">
					<option value="0">iPhone 2G</option>
					<option value="1">iPhone 3G</option>
					<option value="2">iPhone 3G[S]</option>
					<option value="3">iPhone 4 (GSM)</option>
					<option value="4">iPhone 4 (GSM 2012)</option>
					<option value="5">iPhone 4 (CDMA)</option>
					<option value="6">iPhone 4[S]</option>
					<option value="7">iPhone 5 (GSM)</option>
					<option value="8">iPhone 5 (Global)</option>
					<option value="9">iPhone 5c (GSM)</option>
					<option value="10">iPhone 5c (Global)</option>
					<option value="11">iPhone 5s (GSM)</option>
					<option value="12">iPhone 5s (Global)</option>
					<option value="13">iPhone 6+</option>
					<option value="14">iPhone 6</option>
					<option value="15">iPhone 6s</option>
					<option value="16">iPhone 6s+</option>
					<option value="17">iPhone SE</option>
					<option value="18">iPhone 7 (Global)</option>
					<option value="19">iPhone 7 Plus (Global)</option>
					<option value="20">iPhone 7 (GSM)</option>
					<option value="21">iPhone 7 Plus (GSM)</option>
				</select>
				<br><br>
				<?php 
					if($reCaptcha['enabled'] == true) {
						echo '<div id="recaptcha1"></div><br>';
					}
				?>
				<input class="button" type="submit" value="Submit" name="submit">
			</form>  
		</div>
		<div class="box">
			<h1 class="note">Lost your link? </h1>
			<select id="inp_ECIDType" style="width:15%;float:left;height:29px">
                        <option value="0">Hex</option>
                        <option value="1">Dec</option>
            </select>
			<input type="text" placeholder="Type ECID Here..." id="inp_ecid" style="width:85%"><br><br>
			<button class="button" id="showlink" style="width:100%">Get your blobs</a>
		</div>
		<div class="box">
			<h1 class="note">Wrong model selected?</h1>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off">
				<select id="ECIDType" style="width:30%;float:left;height:29px">
							<option value="0">Hex</option>
							<option value="1">Dec</option>
				</select>
				<input type="text" placeholder="Type ECID Here..." name="ECID" style="width:70%"><br><br>
				<?php 
						if($reCaptcha['enabled'] == true) {
							echo '<div id="recaptcha2"></div><br>';
						}
				?>
				<input class="button" type="submit" name="delete" value="Delete ECID!" style="width:100%">
			</form>
		</div>
		<p style="text-align:center;">Copyright &copy; 1Conan, 2016</p>
		<p style="text-align:center;"><a href="https://github.com/1Conan/TSSSaver">TSS Savver</a> is licensed under <a href="LICENSE">MIT License</a></p>
		<script>
				var serverURL = "<?php echo $serverURL; ?>";
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
					var inp_ECIDType = document.getElementById('inp_ECIDType').value;
					var inp_ecid = document.getElementById("inp_ecid").value;
					console.log(inp_ECIDType);
					if(inp_ECIDType == 0) {
						var ecid = getJSON(serverURL + "conv.php?hex=" + inp_ecid);
						console.log(ecid);
						window.location = serverURL + "shsh/" + ecid.dec;
					} else if(inp_ECIDType == 1) {
						window.location = serverURL + "shsh/" + inp_ecid;
					}
				};
				document.getElementById('deviceType').onchange = function() {
					var deviceType = document.getElementById('deviceType');
                    var deviceModel = document.getElementById('deviceModel');
					var modelList = getJSON(serverURL + "json/" + deviceType.value + ".json");
                    while(deviceModel.hasChildNodes()){
                        deviceModel.removeChild(deviceModel.lastChild);
                    }
                    for(var i = 0; i < modelList.length; i++) {
                        console.log(i);
                        var child = document.createElement('option');
                        var text = document.createTextNode(modelList[i]);
                        child.setAttribute('value', i);
                        child.appendChild(text);
                        deviceModel.appendChild(child);
                    }
					
				}
		</script>
		<?php 
			if($reCaptcha['enabled'] == true) {
				?>
					<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" defer></script>
					<script>
						var recaptcha1;
						var recaptcha2;
						var myCallBack = function() {
							recaptcha1 = grecaptcha.render('recaptcha1', {
								'sitekey' : '<?php echo $reCaptcha['publicKey']; ?>',
								'theme' : 'light'
							});
							recaptcha2 = grecaptcha.render('recaptcha2', {
								'sitekey' : '<?php echo $reCaptcha['publicKey']; ?>',
								'theme' : 'light'
							});
						};
					</script>
				<?php
			}
		?>
	</body>
</html>

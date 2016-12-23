<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	
	require_once '../inc/config.php';
	require_once '../inc/functions.php';
	
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
		$output = exec('./bin/img4tool_linux -a -s '.escapeshellarg($_FILES['blob']['tmp_name']), $arr);
		$countOutput = count($arr);
		if(!in_array('rosi: rosi: ------------------------------',$arr)){
			$html = "<!DOCTYPE html>";
			$html .= "<html>";
			$html .= "<head>";
			$html .= "<title>TSS Saver</title>";
			$html .= "<meta name='viewport' content='width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no' />";
			$html .= "<link rel='stylesheet' href='style.css'>";
			$html .= "<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800' rel='stylesheet'>";
			$html .= "</head>";
			$html .= "<body>";
			$html .= "<div class='box'>";
			$html .= "<h1 class='note'>";
			$html .= 'rosi tag not found.<br><br>Output :';
			$html .= "</h1>";
			$html .= "<pre style='color:#FFF!important;border:none!important;padding:7px;border-radius:2px;background:#1A1A1A;'>";
			$html .= $output."<br>";
			for($i = 0; $i < $countOutput; $i++){
				$html .= $arr[$i]."<br>";
			}
			$html .= "</pre>";
			$html .= "</div>";
			$html .= "</body>";
			$html .= "</html>";
			die($html);
		} else {
			$html = "<!DOCTYPE html>";
			$html .= "<html>";
			$html .= "<head>";
			$html .= "<title>TSS Saver</title>";
			$html .= "<meta name='viewport' content='width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no' />";
			$html .= "<link rel='stylesheet' href='style.css'>";
			$html .= "<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800' rel='stylesheet'>";
			$html .= "</head>";
			$html .= "<body>";
			$html .= "<div class='box'>";
			$html .= "<h1 class='note'>";
			$html .= 'rosi tag found.<br><br>Output :';
			$html .= "</h1>";
			$html .= "<pre style='color:#FFF!important;border:none!important;padding:7px;border-radius:2px;background:#1A1A1A;'>";
			$html .= $output."<br>";
			for($i = 0; $i < $countOutput; $i++){
				$html .= $arr[$i]."<br>";
			}
			$html .= "</pre>";
			$html .= "</div>";
			$html .= "</body>";
			$html .= "</html>";
			die($html);
		}
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
			<h1 class="title"><span style="font-weight:600;">iOS 10 SHSH2</span> Blobs Checker</h1>
			<p class="author">by <a href="https://www.reddit.com/user/1Conan/">/u/1Conan</a></p>
			<p class="author">Theme by <a href="https://www.reddit.com/user/MareddySaiKiran">/u/MareddySaiKiran</a></p>
			</div>
		<div class="box">
			<h1 class="note">Note : </h1>
			<p>&#8226; Sample note.</p>	
			</form>
		</div>
		<div class="box">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
			<h1 class="note">Blob : </h1>
				<input type="file" name="blob" style="width:100%">
				<br><br>
				<?php 
					if($reCaptcha['enabled'] == true) {
						echo '<div id="recaptcha1"></div><br>';
					}
				?>
				<input class="button" type="submit" value="Submit" name="submit" style="width:100%">
			</form>  
		</div>
		<p style="text-align:center;">Copyright &copy; 1Conan, 2016</p>
		<p style="text-align:center;"><a href="https://github.com/1Conan/TSSSaver">TSS Saver</a> is licensed under MIT</p>
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
						};
					</script>
				<?php
			}
		?>
	</body>
</html>

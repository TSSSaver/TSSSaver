<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	$serverURL    = "https://tsssaver.1conan.com/"; //your server url ;https://tsssaver.1conan.com/
	$savedSHSHURL = $serverURL."shsh/";

	$tssCheckerPath
		= "./tsschecker"; //path to tsschecker executable https://github.com/tihmstar/tsschecker/releases ;./tsschecker

	$blackStyle = TRUE; //true|false ;use black style layout

	$reCaptcha[ 'enabled' ]    = FALSE; //true|false ;enabled/disable recaptcha
	$reCaptcha[ 'privateKey' ] = ""; //recaptcha private key (take from google dev api)
	$reCaptcha[ 'privateKey' ] = ""; //recaptcha public key (generate/take from google dev api)
	$reCaptcha[ 'publicKey' ]  = "";

	$db[ 'server' ]   = "localhost"; //db server
	$db[ 'name' ]     = "tsssaver"; //database name
	$db[ 'user' ]     = "user"; //database username
	$db[ 'password' ] = "password"; //database password
	$db[ 'table' ]    = "devices"; //device table

	$signedVersionsURL
		= "https://api.ipsw.me/v2.1/firmwares.json/condensed"; //url to get info about signed ios fw ;https://api.ipsw.me/v2.1/firmwares.json/condensed


	$apnonce = [
		'603be133ff0bdfa0f83f21e74191cf6770ea43bb',
		'352dfad1713834f4f94c5ff3c3e5e99477347b95',
		'42c88f5a7b75bc944c288a7215391dc9c73b6e9f',
		'0dc448240696866b0cc1b2ac3eca4ce22af11cb3',
		'9804d99e85bbafd4bb1135a1044773b4df9f1ba3',
	]; //Just add more to this array if you want.

?>

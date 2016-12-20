<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	
	function ret($arr) {
		header('Content-Type: application/json');
		exit(json_encode($arr));
	}
	if(isset($_GET['hex'])) {
		if(ctype_xdigit($_GET['hex']) && is_int(hexdec($_GET['hex']))) {
			$deviceECID = hexdec($_GET['hex']);
			ret(array(
				'dec' => (string)$deviceECID //Javascript hates big numbers. Setting it as a string is not that bad for our use case
			));
		} else {
			ret(array(
				"error" => "not-hex"
			));
		}
	} else {
		ret(array(
			"error" => "not-set"
		));
	}
?>

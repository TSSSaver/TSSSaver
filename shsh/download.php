<?php
	/*
	* TSS Saver
	* Author: 1Conan
	* License: MIT
	*/
	
	if(!is_numeric($_GET['ecid'])) {
		die('ecid error!');
	}
	if(!file_exists($_GET['ecid'])) {
		die('ecid not found');
	}
	
	$filename = $_GET['ecid']."-blobs-all.zip";
	$folder = $_GET['ecid'];
	
	// https://gist.github.com/panslaw/4327882
	class FlxZipArchive extends ZipArchive {
		public function addDir($location, $name) {
			$this->addEmptyDir($name);
			$this->addDirDo($location, $name);
		 } 
		private function addDirDo($location, $name) {
			$name .= '/';        
			$location .= '/';
			$dir = opendir ($location);
			while ($file = readdir($dir))    {
				if ($file == '.' || $file == '..') continue;
				$do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
				$this->$do($location . $file, $name . $file);
			}
		} 
	}
	
	$zip = new FlxZipArchive;
	$zip->open($filename, ZipArchive::CREATE);
	$zip->addDir($folder, basename($folder));
	$zip->close();
	
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($filename));
	readfile($filename);
	@unlink($filename);
?>

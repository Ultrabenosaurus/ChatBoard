<?php
session_start();

$dir = "msgs/";
$date = date('dmY');
$end = "msgs.txt";
$fname = $dir.$date.$end;

if(isset($_POST['msg']) && ($_POST['msg'] !== null && $_POST['msg'] !== "")){
	if(!file_exists($fname)){
		$file = fopen($fname, 'w');
		$existed = false;
	} else {
		$existed = true;
	}
	if($existed){
		$file = file($fname);
		if(count($file) > 99){
			$file[] = "\r\n".$_SESSION['user'].">> ".urldecode($_POST['msg']);
			$contents = array_reverse($file);
			$new = fopen($fname, 'w');
			for($i = 0; $i < 99; $i++){
				fwrite($new, $contents[$i]);
			}
			fclose($new);
		} else {
			$file = fopen($fname, 'a');
			$done = fwrite($file, "\r\n".$_SESSION['user'].">> ".urldecode($_POST['msg']));
			if($done){
				echo "written";
			} else {
				echo "failed to write";
			}
			fclose($file);
		}
	} else {
		fwrite($file, $_SESSION['user'].">> ".urldecode($_POST['msg']));
		fclose($file);
	}
}

if(isset($_POST['read'])){
	if(file_exists($fname)){
		$file = fopen($fname, 'r');
		echo fread($file, filesize($fname));
		fclose($file);
	} else {
		echo "no messages";
	}
}

?>
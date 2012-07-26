<?php
session_start();
$_SESSION['errors'] = "";

if($_SERVER['REMOTE_ADDR'] == '192.168.1.91'){
	die();
}
if(!isset($_GET['reg'])){
	$fname = "reg/users";
	$file = file($fname);
	$found = false;
	for($i = 0; $i < count($file); $i++){
		$user = explode("=", $file[$i]);
		if($_SERVER['REMOTE_ADDR'] == $user[0]){
			$found = true;
			$user = explode(";", $user[1]);
			$_SESSION['user'] = $user[0];
			break;
		}
	}
	if(!$found){
		header("Location: ./?reg");
	}
}
if(isset($_GET['reg'])){
	$fname = "reg/users";
	$file = file($fname);
	if($_GET['reg'] === ""){
		$found = false;
		for($i = 0; $i < count($file); $i++){
			$user = explode("=", $file[$i]);
			if($_SERVER['REMOTE_ADDR'] == $user[0]){
				$found = true;
				$user = explode(";", $user[1]);
				$_SESSION['user'] = $user[0];
				break;
			}
		}
		if($found){
			header("Location: ./?chat");
		} else {
			if(file_exists("reg/users")){
				$reg_file = fopen("reg/users", 'a');
				fwrite($reg_file, "\r\n".$_SERVER['REMOTE_ADDR']."=");
				fclose($reg_file);
			} else {
				$reg_file = fopen("reg/users", 'a+');
				fwrite($reg_file, $_SERVER['REMOTE_ADDR']."=");
				fclose($reg_file);
			}
		}
	} else {
		$found = false;
		for($i = 0; $i < count($file); $i++){
			$user = explode("=", $file[$i]);
			if($_SERVER['REMOTE_ADDR'] == $user[0]){
				$found = true;
				break;
			}
		}
		if($found){
			$reg_file = fopen("reg/users", 'a+');
			fwrite($reg_file, $_GET['reg'].";");
			fclose($reg_file);
			$_SESSION['user'] = $_GET['reg'];
			header("Location: ./?chat");
		} else {
			$reg_file = fopen("reg/users", 'a+');
			fwrite($reg_file, "\r\n".$_SERVER['REMOTE_ADDR']."=".$_GET['reg'].";");
			fclose($reg_file);
			$_SESSION['user'] = $_GET['reg'];
			header("Location: ./?chat");
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<!-- <link rel='stylesheet' type='text/css' href='' /> -->
	<title>Welcome</title>
	<style>
	body {
		font-family: Calibri, Helvetic, sans-serif;
		background-color: #9c9c9c;
	}
	.right {
		float: right;
	}
	.under {
		text-decoration: underline;
	}
	#container {
		position: relative;
		width: 900px;
		margin: 0 auto;
		background-color: #cfcfcf;
		top: -19px;
		padding: 10px;
	}
	#container h2 {
		padding-top: 10px;
	}
	#msgs {
		width: 100%;
		min-height: 50px;
		max-height: 650px;
		overflow-y: scroll;
		border: thin #9c9c9c solid;
	}
	</style>
	<?php if(isset($_GET['chat']) || isset($_GET['reg'])){echo "<script type='text/javascript' src='jquery.js'></script><script type='text/javascript' src='msgs.js'></script>";} ?>
</head>
	<body>
		<div id="container">
		<?php
		if(isset($_GET['reg'])){
			?>
			<h2>Register</h2>
			<input type="text" name="user_name" id="user_name" /><input type="button" value="register" id="reg_user" onclick="javascript: regUser()" />
			<?php
		} else if(isset($_GET['chat'])){
			?>
			<h2>Hi, <? echo $_SESSION['user'] ?></h2>
			<div id="msgs">
				
			</div>
			<div id="msg_controls">
				<textarea name="new_msg" id="new_msg" cols="50" rows="5" style="font-family:Calibri;"></textarea><input type="button" value="send" id="send_msg" onclick="javascript: sendMsg()" /><br />
				<span ="format_hint">
					[url http://www.google.com/]Google[/url] - hyperlink<br />
					[i]text[/i] - italics<br />
					[b]text[/b] - bold<br />
					[u]text[/u] - underline<br />
					[img http://www.muffinrecipes.net/li/muffins.jpg (w:100) (h:100)] - image (optional width and/or height)
				</span>
			</div>
			<?php
		} else if($_SERVER['REMOTE_ADDR'] == '192.168.0.101') {
			echo "<h2>Hello Dan, how are you today?</h2>";
			if(isset($_SESSION['errors'])){
				echo "<pre>" . print_r($_SESSION['errors'], true) . "</pre>";
			}
			if(isset($_GET['list'])){
				if($_GET['list'] == 'test'){
					for($i = 0; $i < 50; $i++){
						echo "<span>line ".$i."</span><br />";
					}
				} else {
					$date = $_GET['list'];
					$file = file_get_contents("logs/".$date."/".$date."-ip.log");
					echo "<p>Log file ".substr($date, 0, 2)."/".substr($date, 2, 2)."/".substr($date, 4).":<span class='right'><a href='./'>&lt;&lt; back</a></span></p>";
					echo "<pre>" . print_r($file, true) . "</pre>";
				}
			} else {
				$fname = "reg/users";
				$file = file($fname);
				echo "<pre>" . print_r($file, true) . "</pre>";
			}
		}
		
		?>
		</div>
	</body>
</html>
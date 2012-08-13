<?php

include '../classes/_db.php';

$db = new db();

$db->server = '127.0.0.1';
$db->user = 'root';
$db->pass= '';
$db->db = 'samplemysqldb';

$conn = $db->connect();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<link rel='stylesheet' type='text/css' href='' />
	<title>DB Class Test</title>
</head>
	<body>
		<?php
		echo "cheese";
		if(gettype($conn) === 'boolean'){
			if($conn){
				$query = $db->query($db->prepare("SELECT * FROM `users`"));
				if(!$db->errors()){
					echo "<h2>DATA</h2>\n";
					foreach ($query as $key => $value) {
						echo "<p>$key - $value</p>\n";
					}
				} else {
					echo "<h2>ERRORS</h2>\n";
					echo "<p>".$db->errors()."</p>\n";
				}
			}
		} else {
			echo "<h2>ERRORS</h2>\n";
			echo "<p>".$db->errors()."</p>\n";
			echo "<p>".$conn."</p>\n";
		}
		
		?>
	</body>
</html>
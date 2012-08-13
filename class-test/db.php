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
		$temp = $db->db_res->errno;
		echo gettype($temp);
		
		echo "<pre>" . print_r($db, true) . "</pre>";
		
		if(gettype($conn) === 'boolean'){
			if($conn){
				$query = $db->query($db->prepare("SELECT * FROM `users`"));
				if(!$db->errors()){
					echo "<h2>DATA</h2>\n<p>\n";
					foreach ($query as $key => $value) {
						foreach ($value as $k => $v) {
							echo "$k - $v<br />\n";
						}
						echo "<br />";
					}
					echo "</p>\n";
				} else {
					echo "<h2>ERRORS</h2>\n";
					echo "Connection Status: <pre>" . print_r($conn, true) . "</pre>";
					echo "Error: <pre>" . print_r($db->errors(), true) . "</pre>";
				}
			}
		} else {
			echo "<h2>ERRORS</h2>\n";
			echo "Connection Status: <pre>" . print_r($conn, true) . "</pre>";
			echo "Error: <pre>" . print_r($db->errors(), true) . "</pre>";
		}
		
		?>
	</body>
</html>
<?php

$db->conn_die();

?>
<?php 
$user = 'root';
$pass = '';
try {
	$dbh = new PDO('mysql:host=localhost;dbname=lb_pdo_netstat;', $user, $pass);
} catch (PDOException $e) {
	echo $e->getMessage();
}
?>
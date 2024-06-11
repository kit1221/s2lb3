<?php 
require 'connect.php';
$clientId = $_GET['clientId'];
try {
  $sth = $dbh->prepare('SELECT c.login , s.start, s.stop FROM client c JOIN seanse s ON c.id_client = s.fid_client WHERE c.id_client = :clientId');
  $sth->bindParam(':clientId', $clientId);
  $sth->execute();
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);
  foreach ($result as $item){
    echo '<li> Користувач з логіном ' . $item['login'] . ' має останній сеанс з ' . $item['start'] . ' по ' . $item['stop'] . '</li>';
  }
} catch (PDOException $e) {
  echo $e->getMessage(); 
}
?>
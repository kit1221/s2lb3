<?php 
require 'connect.php';
header('Content-Type: application/json');
try {
  $sth = $dbh->prepare('SELECT name, ip, balance FROM client WHERE balance <= 0');
  $sth->execute();
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]); 
}
?>
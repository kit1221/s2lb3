<?php 
require 'connect.php';
$start = $_GET['start'];
$stop = $_GET['stop'];
header('Content-Type: application/xml');
try {
  $sth = $dbh->prepare('SELECT c.login , s.start, s.stop, s.in_traffic, s.out_traffic FROM client c JOIN seanse s ON c.id_client = s.fid_client WHERE s.start >= :start AND s.stop <= :stop');
  $sth->bindParam(':start', $start);
  $sth->bindParam(':stop', $stop);
  $sth->execute();
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);
  echo '<?xml version="1.0" encoding="UTF-8"?>';
  echo '<sessions>';
  foreach ($result as $item){
    $trafficDifference = $item['out_traffic'] - $item['in_traffic'];
    echo '<session>';
    echo '<login>' . $item['login'] . '</login>';
    echo '<start>' . $item['start'] . '</start>';
    echo '<stop>' . $item['stop'] . '</stop>';
    echo '<trafficDifference>' . $trafficDifference . '</trafficDifference>';
    echo '</session>';
  }
  echo '</sessions>';
} catch (PDOException $e) {
  echo '<error>' . $e->getMessage() . '</error>'; 
}
?>
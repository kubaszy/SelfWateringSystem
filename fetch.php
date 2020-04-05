
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

$num=$_GET["ID_plant"];
// var_dump($num);
$query = "SELECT humidity_soil, LEFT(time,13) from humidity_soil where id_plant='$num' GROUP BY LEFT(time,13) ORDER BY LEFT(time,13) DESC LIMIT 24 ";

// echo $query;

 $statement = $pdo->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();

//  var_dump($result);
 foreach($result as $row)
 {
  $output[] = array(
   'humidity_soil'   => $row["humidity_soil"],
   'time'  => $row["LEFT(time,13)"]
  );
 }
 echo json_encode($output);


?>
<html>
<head>
<title>Try Session JSON</title>
</head>
<body>
<?php

	try{
	    include 'db.php' ;

		$stmt = $pdo->query("SELECT state_pump FROM watering_plan");
	    //$f_index = ($stmt->fetchColumn());

		$json_array=array();
		$i=0;


      	while($row = $stmt->fetch())
     	 {
           $json_array[]=$row['state_pump'];
      	}

      	$stmt->closeCursor();
		//	echo '<pre>';
		//	print_r($json_array);
		//	echo '</pre>';
		echo json_encode($json_array);
	}
	catch(PDOException $e) {
	        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
	}
?>
</body>
</html>
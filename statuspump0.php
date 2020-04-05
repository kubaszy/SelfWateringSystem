<?php
$idPlant = -1;
class sendToDBStatusPump0{
 	function __construct($number){
 		$idPlant = $this->get_ID_Plant($number);
  		$this->storeInDBDStatusPump0($idPlant);
 	}



	function get_ID_Plant ($numberPlant) {
	     try{
	     	include 'db.php' ;
	        if ($numberPlant==1) {
	            $stmt = $pdo->query("SELECT min(ID_plants) as minid from plants");
	            $f_index = ($stmt->fetchColumn());
	            $stmt->closeCursor();
	            return $f_index;
	        } 
	        if ($numberPlant==2) {
	            // $stmt = $pdo->query("SELECT ID_plants from plants ORDER BY ID_plants");
	            // $IDPlants = array(0,0,0);
	            // $i=0;
	            // foreach($stmt as $row)
	            // {
	            //     $IDPlants[$i] = $row['ID_plants'];
	            //     $i++;
	            // }
	            // $stmt->closeCursor();
	            return 2;
	        } 
	        if ($numberPlant==3) {
	            $stmt = $pdo->query("SELECT max(ID_plants) as maxid from plants");
	            $l_index = ($stmt->fetchColumn());
	            $stmt->closeCursor();
	            return $l_index;
	        } 
	    }

	    catch(PDOException $e) {
	        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
	    }
	}
 
 	function storeInDBDStatusPump0($number){
 		try {
 			include 'db.php' ;
      		$pdo->exec ("UPDATE watering_plan set state_pump=0 WHERE ID_plants='$number'");
      	}
      	catch(PDOException $e) {
	        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
	    }
 	}
 
 }

if($_POST['numberOfPlants'] != '')  
{
 	$sendToDBStatusPump=new sendToDBStatusPump0($_POST['numberOfPlants']);
}

?>

<?php
class sendToDB{
 	public $link='';
 	function __construct($temperature, $humidity, $soil1, $soil2, $soil3, $intensityUV1, $intensityUV2, $intensityUV3, $empty){
  		$this->connect();
  		$this->storeInDBDht22($temperature, $humidity);
  		$this->storeInDBSoilWatch10($soil1, $soil2, $soil3);
  		$this->storeInDBML851($intensityUV1, $intensityUV2, $intensityUV3);
  		$this->checkUpdatePumpStatus($soil1, $soil2, $soil3);
      $this->storeInDBWaterLevel($empty);
 	}
 
 	function connect(){
  		$this->link = mysqli_connect('localhost','30744230_baza','Inzynierka2020') or die('Cannot connect to the DB');
  		mysqli_select_db($this->link,'30744230_baza') or die('Cannot select the DB');
 	}
 
 	function storeInDBDht22($temperature, $humidity){
  		$query = "insert into dht22 set humidity='".$humidity."', temperature='".$temperature."'";
  		$result = mysqli_query($this->link,$query) or die('Errant query:  '.$query);
 	}

 	function storeInDBSoilWatch10($soil1, $soil2, $soil3){
  		$soils=array($soil1,$soil2,$soil3);
	
		for ($i=0;$i<3;$i++) {
			if ($soils[$i]!=-100) {
				$l=$i+1;
  				$query2 = "insert into humidity_soil set humidity_soil='".$soils[$i]."', id_plant='".$l."'";
  				$result2 = mysqli_query($this->link,$query2) or die('Errant query:  '.$query2);
  			}
  		}
 	}

 	function storeInDBML851($intensityUV1, $intensityUV2, $intensityUV3){
  		$intensitys=array($intensityUV1,$intensityUV2,$intensityUV3);
	
		for ($i=0;$i<3;$i++) {
			if ($intensitys[$i]!=-100) {
				$l=$i+1;
  				$query2 = "insert into intensity_uv set value='".$intensitys[$i]."', id_plant='".$l."'";
  				$result2 = mysqli_query($this->link,$query2) or die('Errant query:  '.$query2);
  			}
  		}
 	}

 	function checkUpdatePumpStatus($soil1, $soil2, $soil3) {
 		$soils=array($soil1,$soil2,$soil3);
 		$soilLimit=array(100,100,100); 

 		 for ($k=0;$k<3;$k++) {
 		 	$d=$k+1;
 		 	$sql = "SELECT humidity_soil FROM plants where ID_plants='$d'";
			 $records=mysqli_query($this->link,$sql) or die('Errant query:  '.$sql);
			 $row= mysqli_fetch_object($records);
			 $soilLimit[$k] = $row->humidity_soil;
 		 }
 		$d=0;
 		for ($i=0;$i<3;$i++) {
 			$d=$i+1;
			if ($soils[$i]!=-100) {
				if ($soils[$i]<$soilLimit[$i]) {
					$query = "UPDATE watering_plan set state_pump=1 WHERE ID_watering_plan='$d'";
  				$result2 = mysqli_query($this->link,$query) or die('Errant query:  '.$query);
				}
        
  			}
  		}
 	}

  function storeInDBWaterLevel($empty){

      $sql = "SELECT sensor1 FROM water_level where ID_water_level=1";
      $records=mysqli_query($this->link,$sql) or die('Errant query:  '.$sql);
      $row= mysqli_fetch_object($records);
      $water_level = $row->sensor1;

      $sql2 = "SELECT email FROM email where ID_email=1";
      $records2=mysqli_query($this->link,$sql2) or die('Errant query:  '.$sql2);
      $row2= mysqli_fetch_object($records2);
      $email = $row2->email;

      $query = "UPDATE water_level set sensor1='$empty' WHERE ID_water_level=1";
      $result2 = mysqli_query($this->link,$query) or die('Errant query:  '.$query);
      if ($empty==1 && $water_level==0) {
        $sub = "System podlewania";
        $msg = "W zbiorniku nie ma wody. Uzupełnij ją jak najszybciej. ";
        $rec = $email;
        mail($rec,$sub,$msg,'From: podlewanie2020@kubaszy.pl');
      }
  }

 
 }

if($_POST['temperature'] != '' and  $_POST['humidity'] != '' and  $_POST['soil1'] != '' and  $_POST['soil2'] != '' and  $_POST['soil3'] != '' and  $_POST['intensityUV1'] != '' and  $_POST['intensityUV2'] != '' and  $_POST['intensityUV3'] != '' and  $_POST['empty'] != '')  
{
 	$sendToDB=new sendToDB($_POST['temperature'],$_POST['humidity'],$_POST['soil1'],$_POST['soil2'],$_POST['soil3'],$_POST['intensityUV1'],$_POST['intensityUV2'],$_POST['intensityUV3'],$_POST['empty']);
}

?>

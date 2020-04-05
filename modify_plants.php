
<?php

	try {
		include 'db.php';

		$id=$_POST['id'];
		$name=$_POST['name'];
		$temp_max=$_POST['temp_max'];
		$temp_min=$_POST['temp_min'];
		$humidity_air=$_POST['humidity_air'];
		$light=$_POST['light'];
		$humidity_soil=$_POST['humidity_soil'];
		$description=$_POST['description'];

		$liczba = $pdo->exec("UPDATE plants SET name='$name' , humidity_air='$humidity_air' , humidity_soil='$humidity_soil' , min_temperature='$temp_min' , max_temperature='$temp_max' , insolation='$light' , description='$description' WHERE id_plants='$id'");

		if($liczba > 0)
		{
			echo 'Dodano';
		}
		else
		{
			echo 'Wystąpił błąd podczas dodawania rekordów!';
		}
	}
	catch(PDOException $e)
	{
		echo 'Wystąpił błąd biblioteki PDO: ' . $e->getMessage();
	}
?>
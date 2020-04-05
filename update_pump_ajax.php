<?php

	try
	{
		include 'db.php';
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$num=$_GET["number"];
		$liczba = $pdo->exec("UPDATE watering_plan SET state_pump=1 WHERE id_plants='$num'");

		if($liczba > 0)
		{
			echo 'Dodano: '.$liczba.' rekordow';
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
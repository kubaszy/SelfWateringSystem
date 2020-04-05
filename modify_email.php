
<?php

	try {
		include 'db.php';

		$email=$_POST['email'];

		$liczba = $pdo->exec("UPDATE email SET email='$email' WHERE id_email=1");

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
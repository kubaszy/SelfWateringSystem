<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
      
        <title>Automat podlewający rośliny</title>
    </head>
    <body>
            <?php 
                try{
                    include 'db.php' ;
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->query("SELECT COUNT(ID_plants) as total FROM plants");
                                 
                    $allOfPlants = ($stmt->fetchColumn());
                    $stmt->closeCursor();
                    }
                catch(PDOException $e) {
                    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
                }

        
                // $allOfPlants=-1;
                // $link = mysqli_connect("localhost","root","","temphumid");
                // $sql = "SELECT COUNT(ID_plants) as total FROM plants";
                // if (mysqli_query($link,$sql)){
                //     $result = mysqli_query($link,$sql);
                //     $value = mysqli_fetch_assoc($result);
                //     $allOfPlants = $value['total'];
                // }
                
                if ($allOfPlants == 1) {

                }
                else if($allOfPlants == 2) {

                }
                else if($allOfPlants == 3) {
                    include 'threeplants.php';
                }
            ?>
    </body>
</html>
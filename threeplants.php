<!DOCTYPE html>
<html lang="pl-PL">

<head>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'>
</script>

    <script src="http://echarts.baidu.com/build/dist/echarts.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.compatibility.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Automat podlewający rośliny</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/agency.min.css" rel="stylesheet">

    <!-- Temporary navbar container fix -->
    <style>
    .navbar-toggler {
        z-index: 1;
    }
    
    @media (max-width: 576px) {
        nav > .container {
            width: 100%;
        }
    }
    </style>


<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_light_intensity ($numberPlant) {
    include 'db.php';
    $valueOfSun = 235;
    $counter=0;
    try {
        $stmt = $pdo->query("SELECT LEFT(time,11) FROM intensity_uv Where id_plant='$numberPlant' ORDER BY     ID_intensityUV DESC LIMIT 1");
        $current_day = $stmt->fetchColumn();
        $stmt->closeCursor();
        $my_day = date('Y-m-d', strtotime('-1 day', strtotime($current_day)));
    }
    catch(PDOException $e) {
        echo 'Polecenie nie mogło zostać zrealizowane: ' . $e->getMessage();
    }

    try {
        $query = "SELECT value, LEFT(time,13) from intensity_uv where id_plant='$numberPlant' and LEFT(time,11)='$my_day' GROUP BY LEFT(time,13) LIMIT 24";
        
        $statement = $pdo->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
    foreach($result as $row) {
        if ($row["value"]>$valueOfSun) $counter++;
    }
 }
 catch(PDOException $e) {
        echo 'Polecenie nie mogło zostać zrealizowane: ' . $e->getMessage();
    }
    //echo $counter;
    return $counter;
}

function get_all_Plant () {
    include 'db.php';
    $stmt = $pdo->query("SELECT * from plants");

    // $stmt->closeCursor();
    return $stmt;
}


function get_ID_Plant ($numberPlant) {
include 'db.php';
     try{
        if ($numberPlant==1) {
            $stmt = $pdo->query("SELECT min(ID_plants) as minid from Plants");
            $f_index = ($stmt->fetchColumn());
            $stmt->closeCursor();
            return $f_index;
        } 
        if ($numberPlant==2) {
            $stmt = $pdo->query("SELECT ID_plants from Plants ORDER BY ID_plants");
            $IDPlants = array(0,0,0);
            $i=0;
            foreach($stmt as $row)
            {
                $IDPlants[$i] = $row['ID_plants'];
                $i++;
            }
            $stmt->closeCursor();
            return $IDPlants[1];
        } 
        if ($numberPlant==3) {
            $stmt = $pdo->query("SELECT max(ID_plants) as maxid from Plants");
            $l_index = ($stmt->fetchColumn());
            $stmt->closeCursor();
            return $l_index;
        } 
    }

    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}


function get_current_humidity_soil($numberPlant) {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT humidity_soil FROM humidity_soil WHERE ID_plant='$numberPlant' ORDER BY `ID_humidity_soil` DESC LIMIT 1");
        $humidity_soil_current = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $humidity_soil_current;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_current_temperature() {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT temperature FROM dht22 ORDER BY `ID` DESC LIMIT 1");
        $temperature_current = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $temperature_current;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_current_humidity_air() {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT humidity FROM dht22 ORDER BY `ID` DESC LIMIT 1");
        $temperature_current = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $temperature_current;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_max_temperature($numberPlant) {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT max_temperature FROM plants WHERE ID_plants='$numberPlant'");
        $max = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $max;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_min_temperature($numberPlant) {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT min_temperature FROM plants WHERE ID_plants='$numberPlant'");
        $min = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $min;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_min_light_intensity($numberPlant) {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT insolation FROM plants WHERE ID_plants='$numberPlant'");
        $ins = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $ins;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_water_level() {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT sensor1 FROM water_level WHERE ID_water_level=1");
        $wl = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $wl;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

function get_min_humidity_air($numberPlant) {
    include 'db.php';
    try{
        $stmt = $pdo->query("SELECT humidity_air FROM plants WHERE ID_plants='$numberPlant'");
        $humi = ($stmt->fetchColumn());
        $stmt->closeCursor();
        return $humi;
    }
    
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}

?>


<script>
function move(number,id_plant) {

        var bar = "myBar"+number ;
        var wat = "wat" + number;
        var water_button = "water_button"+number;

        var elem = document.getElementById(bar);  
         document.getElementById(wat).innerHTML = "<p class='text-success'>Proszę czekać. Planuję podlewanie rośliny. Za chwilę nastąpi odświeżenie strony. </p>";
        var Buttons = document.getElementById(water_button);
        Buttons.style.display = "none"; 
        var ProgBar3 = document.getElementById(bar);
        ProgBar3.style.display = "inherit"; 
    
    var width = 1;
    var id = setInterval(frame, 40);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
        }
    }
    $.ajax({
           type: "POST",
           url: "update_pump_ajax.php?number="+id_plant,
           data:{action:'call_this'},
           success:function(html) {
              //console.log (id_plant);
           }

      });
    setTimeout(function(){location.reload();}, 6000);
}
</script>


<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
        url: 'fetch.php?ID_plant=1',
        type: 'get',
        dataType: 'JSON',
        success: function(response){
            var len = response.length;
            for(var i=0; i<len; i++){
                var humidity_soil = response[i].humidity_soil;
                var time = response[i].time;

                //console.log(response);                
            }
        }
    });
});

</script>


</head>

<body id="page-top">

    <!-- Manu -->
    <nav class="navbar fixed-top navbar-toggleable-md navbar-inverse" id="mainNav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#page-top"><img src="img/logo2.png"/></a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#page-top">Start</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Ogólne</a>
                    </li>

                    <?php
                        $all_plants = get_all_Plant();
                        foreach($all_plants as $plants)
                            {
                    ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#plant<?php echo $plants['ID_plants']; ?>"><?php echo $plants['name'];?></a>
                    </li>
                    <?php
                        }
                    ?>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Naglowek -->
    <header class="masthead">
        <div class="container">
            <div class="intro-text">
                <div id="ramka">
                <div class="intro-lead-in">Self watering system !</div>
                <div class="intro-heading">Automat podlewający</div>
                </div>
                <a href="#services" class="btn btn-xl">Dowiedz się więcej</a>
            </div>
        </div>
    </header>

    <!-- O nas -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Twoje rośliny</h2>
                    <h3 class="section-subheading text-muted">Kliknij aby przejść do szczegółów.</h3>
                </div>
            </div>
            

            <div class="row text-center">

                 <?php
                        $all_plants = get_all_Plant();
                        $count =1;
                        foreach($all_plants as $plants)
                            {
                    ?>

                <div class="col-md-4" >
                    <a href="#plant<?php echo $plants['ID_plants'];?>" ><span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-leaf fa-stack-1x fa-inverse kolka"></i>
                    </span></a>
                    <h4 class="service-heading">
                        <?php 
                            echo $plants['name'];
                        ?>
                    </h4>
                    <p class="text-muted">Aktualna wartość wilgotności gleby: 
                        <?php 

                            try{
                                $current_humidity = get_current_humidity_soil($plants['ID_plants']);
                                $need_humidity = $plants['humidity_soil'];
                                

                                echo "<h5>";
                                echo $current_humidity;
                                echo "%</h5>";

                                if ($current_humidity>80) {    
                                    echo "<p class='text-muted'>Wartość wilgotności gleby jest wyższa niż 80%. Nie możemy pozwolić, na to abyś przelał roślinę. Jest to maksymalny poziom jaki dopuszczamy podczas ręcznego podlewania. Przypominamy, że Twoja wartość oczekiwana, wynosi: $need_humidity%</p>" ;

                                }
                                else {
                                    echo "<p class='text-muted'>Możesz ręcznie podlać roślinę. Kliknij podlej, a następnie poczekaj, aż pasek postępu dojdzie do zera. Przypominamy, że wartość oczekiwana wilgotności gleby wynosi: $need_humidity% </p>" ;
                                    echo '<button onclick="move('.$count.','.$plants['ID_plants'].')" type="button" class="btn btn-outline-success" id="water_button'.$count.'">Podlej</button>';
                                    
                                    
                                }
                                echo '
                                    <p id="wat'.$count.'"></p>
                                    <div id="myProgress'.$count.'">
                                    <div id="myBar'.$count.'"></div>
                                    </div> ';
                                    $count++;
                            }

                            catch(PDOException $e) {
                                echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
                            }
                        ?>

                    </p>
                </div>
                <?php
                        }
                    ?>

                    </p>
        </div>
    </div>
    </section>
    <?php
        $all_plants = get_all_Plant();
    

        foreach($all_plants as $plants)
        {

    ?>
    <!-- Rosliny -->
        <section class="bg-faded" id="plant<?php echo $plants['ID_plants']; ?>">
            <div class="container">
                <div class="row">
                <div class="col-lg-1"> </div>
                    <div class="col-lg-10 text-center">
                        <h2 class="section-heading"><?php 
                            echo $plants['name'];
                            ?></h2>
                        <p class="text-muted"><?php 
                            echo $plants['description'];
                        ?></p>
                        <br/><br/>
                        
                        <div id="main<?php echo $plants['ID_plants']; ?>" style="height:400px">
                            <?php
                                    $number_humidity_plant = $plants['ID_plants'];
                                    include 'chart.php'; 
                            ?>
                        </div>
                        <div id="information">
                            <h3 class="section-subheading text-muted"><img width="25" src="img/sun-icon.png"/> Wczorajszego dnia na roślinę słońce świeciło przez: 
                            <?php
                              get_light_intensity ($plants['ID_plants']);
                            ?>
                            h.</h3>

                             <h3 class="section-subheading text-muted"><img width="25" src="img/water-icon.png"/> Aktualna wilgotniść gleby wynosi: 
                            <?php
                              echo get_current_humidity_soil($plants['ID_plants']);
                            ?>
                            %.</h3>

                             <h3 class="section-subheading text-muted"><img width="25" src="img/tmp-icon.png"/> Aktualna wartość temperatury wynosi: 
                            <?php
                              echo get_current_temperature();
                            ?>
                            °C.</h3>

                            <h3 class="section-subheading text-muted"><img width="25" src="img/humidity_air-icon.png"/> Wilgotność powietrza wynosi: 
                            <?php
                              echo get_current_humidity_air();
                            ?>
                            %.</h3>

                             <h3 class="section-subheading text-muted"><img width="25" src="img/tmp-icon.png"/> Maksymalna temperatura dla rośliny: 
                            <?php
                              echo get_max_temperature($plants['ID_plants']);
                            ?>
                            °C.</h3>

                             <h3 class="section-subheading text-muted"><img width="25" src="img/tmp-icon.png"/> Minimalna temperatura dla rośliny: 
                            <?php
                              echo get_min_temperature($plants['ID_plants']);
                            ?>
                            °C.</h3>
                            <br/>
                            <h3 class="section-subheading text-muted"> <p>Wskazówki:</p>
                            <?php
                                $current_temp = get_current_temperature();
                                $current_humidity_air = get_current_humidity_air();
                                $min_humidity_air = get_min_humidity_air($plants['ID_plants']);
                                $max_temperature = get_max_temperature($plants['ID_plants']);
                                $min_temperature = get_min_temperature($plants['ID_plants']);
                                $light_intensity = get_light_intensity($plants['ID_plants']);
                                $plant_min_light = get_min_light_intensity($plants['ID_plants']);
                                $water_level = get_water_level();

                                if ($current_temp>$max_temperature) {
                                    echo "<br/>Temperatura jest zbyt wysoka dla tej rośliny.";
                                }
                                if ($current_temp<$min_temperature) {
                                    echo "<br/>Temperatura jest zbyt niska dla tej rośliny.";
                                }
                                if ($min_humidity_air>$current_humidity_air) {
                                    echo "<br/>Wilgotność powietrza nie jest wystarczająca dla rośliny. Proszę zwiększyć wilgotność powietrza w pokoju. ";
                                }
                                if ($light_intensity<$plant_min_light) {
                                    echo "<br/>W dniu wczorajszym roślina nie była wystarczająco nasłoneczniona. Możesz zmienić pozycję rośliny, przestawiając ją w miejsce o większym stopniu nasłonecznienia. ";
                                }
                                if ($water_level==0) {
                                    echo "<br/>W zbiorniku jest woda. ";
                                }
                                if ($water_level==1) {
                                    echo "<br/>W zbiorniku nie ma wody do podlewania. Sprawdź stan zbiornika.";
                                }

                            ?>
                            </h3>

                        </div>
                    </div>

                        </div>
                        <div class="col-lg-1"> </div>
            </div>
        </section>

    <?php
        }
    ?>


    <!-- Bootstrap core JavaScript -->
    <!-- <script src="vendor/jquery/jquery.min.js"></script> -->
    <script src="vendor/tether/tether.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/agency.min.js"></script>


</body>

</html>
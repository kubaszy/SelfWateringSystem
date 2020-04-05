<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>

    <!-- Custom styles for this template -->
        <link href="css/agency.min.css" rel="stylesheet">
        <title>Automat podlewający rośliny</title>
  

    <script>

        $(document).ready(function(){
            $("input").blur(function(){

                const form = this.closest("form");
                var formID = $(this).closest("form").attr("id");
                //=================>
                var save = true;
                var lastfocus = "";

                

                if (formID<4 && formID>0) {
                    var name = $(`#name_plant${formID}`).val(); 

                    if (name=="") {
                        name = $(`#name_plant${formID}`).attr("placeholder");
                    }
                    if(name.length > 25){
                        save = false;
                        lastfocus = "name_plant"+formID;
                    }

                    var temp_max = $(`#temp_max${formID}`).val();
                    if (temp_max=="") {
                        temp_max = $(`#temp_max${formID}`).attr("placeholder");
                    } 
                    if(temp_max < -50 || temp_max > 50 ){
                        save = false;
                        lastfocus = "temp_max"+formID;
                    }

                    var temp_min = $(`#temp_min${formID}`).val(); 
                    if (temp_min=="") {
                        temp_min = $(`#temp_min${formID}`).attr("placeholder");
                    } 
                    if(temp_min < -50 || temp_min > 50 ){
                        save = false;
                        lastfocus = "temp_min"+formID;
                    }

                    var humidity_air = $(`#humidity_air${formID}`).val(); 
                    if (humidity_air=="") {
                      humidity_air = $(`#humidity_air${formID}`).attr("placeholder");
                    } 
                    if(humidity_air < 0 || humidity_air > 100 ){
                        save = false;
                        lastfocus = "humidity_air"+formID;
                    }

                    var light = $(`#light${formID}`).val();
                    if (light=="") {
                      light = $(`#light${formID}`).attr("placeholder");
                    } 
                    if(light < 0 || light > 24 ){
                        save = false;
                        lastfocus = "light"+formID;
                    }

                    var humidity_soil = $(`#humidity_soil${formID}`).val(); 
                    if (humidity_soil=="") {
                      humidity_soil = $(`#humidity_soil${formID}`).attr("placeholder");
                    } 
                    if(humidity_soil < 0 || humidity_soil > 100 ){
                        save = false;
                        lastfocus = "humidity_soil"+formID;
                    }

                    var description = $(`#description${formID}`).val(); 
                    if (description=="") {description = $(`#description${formID}`).attr("placeholder");} 
                    console.log (save);

//==============>
                    if(save) {
                        $.ajax({
                            url: "modify_plants.php",
                            type: "POST",
                            data: {
                                id: formID,
                                name: name,
                                temp_max: temp_max,
                                temp_min: temp_min,
                                humidity_air: humidity_air,
                                light: light,
                                humidity_soil: humidity_soil,   
                                description: description              
                            },
                            cache: false,
                            success: function(dataResult){
                                console.log ("Pomyślnie dokonano zmian w roślinie !");
                            }
                        });
                    }
                    else {
                        document.getElementById(lastfocus).style.backgroundColor = 'rgba(255,0,0,0.3)' ;
                        setTimeout( function(){
                          document.getElementById(lastfocus).style.backgroundColor = 'rgba(255,0,0,0)';
                        },2000);
                    }

                }

                else if (formID==4) {
                    var email = $(`#email`).val();
                    if (email=="") {email = $(`#email`).attr("placeholder");} 

                    if(humidity_soil < 0 || humidity_soil > 100 ){
                        save = false;
                        lastfocus = "humidity_soil"+formID;
                    }

                    $.ajax({
                        url: "modify_email.php",
                        type: "POST",
                        data: {
                            email: email              
                        },
                        cache: false,
                        success: function(dataResult){
                            console.log ("Pomyślnie zmieniono adres e-mail !");
                        }
                    });
                }
            });
        });
    </script>

    </head>





    <body id="page-top">
             <nav class="navbar fixed-top navbar-toggleable-md navbar-inverse" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top"><img src="img/logo2.png"/></a>
        </div>
    </nav>
    <header class="masthead">
        <div class="container">
            <div class="intro-text">
                <div id="ramka">
                <div class="intro-lead-in">Plant management</div>
                <div class="intro-heading">System zarządzania roślinami</div>
                </div>
            </div>
        </div>
    </header>  


<?php 
    try{
        include 'db.php' ;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        }
    catch(PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
?>


<center>

<?php 
    for ($i=1;$i<4;$i++) {
?>
    <form class="form-horizontal" id="<?php echo $i ?>" class="needs-validation" novalidate>
<fieldset style="margin: 50px;">

<!-- Form Name -->
<legend>Modyfikacja rośliny <?php echo $i; ?> </legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nazwa</label>  
  <div class="col-md-4">
  <input maxlength="30" id="name_plant<?php echo $i;?>" name="name_plant<?php echo $i;?>" type="text" id="validationCustom05" placeholder="<?php 
    $stmt = $pdo->query("SELECT name FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group" >
  <label class="col-md-4 control-label" for="">Temperatura max</label>  
  <div class="col-md-4">
  <input id="temp_max<?php echo $i;?>" name="temp_max<?php echo $i;?>" type="number" min="-50" max="50" placeholder="<?php 
    $stmt = $pdo->query("SELECT max_temperature FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="">Temperatura min</label>  
  <div class="col-md-4">
  <input id="temp_min<?php echo $i;?>" name="temp_min<?php echo $i;?>" type="number" min="-50" max="50" placeholder="<?php 
    $stmt = $pdo->query("SELECT min_temperature FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="">Wilgotność powietrza</label>  
  <div class="col-md-4">
  <input id="humidity_air<?php echo $i;?>" name="humidity_air<?php echo $i;?>" type="number" min="0" max="100" placeholder="<?php 
    $stmt = $pdo->query("SELECT humidity_air FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="">Nasłonecznienie ilość / 24h </label>  
  <div class="col-md-4">
  <input id="light<?php echo $i;?>" name="light<?php echo $i;?>" type="number" min="0" max="24" placeholder="<?php 
    $stmt = $pdo->query("SELECT insolation FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="">Wilgotność gleby</label>  
  <div class="col-md-4">
  <input id="humidity_soil<?php echo $i;?>" name="humidity_soil<?php echo $i;?>" type="number" min="0" max="100" placeholder="<?php 
    $stmt = $pdo->query("SELECT humidity_soil FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="">OPIS</label>  
  <div class="col-md-4">
  <input id="description<?php echo $i;?>" name="description<?php echo $i;?>" type="text" placeholder="<?php 
    $stmt = $pdo->query("SELECT description FROM plants WHERE ID_plants=$i");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    </div>
</div>


</fieldset>
</form>

<?php 
    }
?>


<form class="form-horizontal" id="4" class="needs-validation" novalidate>
<fieldset style="margin: 50px;">

<!-- Form Name -->
<legend>Powiadomienia i ostrzeżenia</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Adres e-mail</label>  
  <div class="col-md-4">
  <input id="email" name="email" type="email" placeholder="<?php 
    $stmt = $pdo->query("SELECT email FROM email WHERE ID_email=1");
                     
        $name = ($stmt->fetchColumn());
        $stmt->closeCursor();
  echo "$name"; ?>" class="form-control input-md">
    
  </div>
</div>

</fieldset>
</form>


</center>


    </body>
</html>
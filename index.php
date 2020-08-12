<?php

//solicitar credenciales en https://www.buenosaires.gob.ar/desarrollourbano/transporte/apitransporte

include 'api/credenciales.php';

$cliente = new Credencial(); //encapsule las credenciales 8-)
$credenciales = "client_id=" . $cliente->getClient() . "&client_secret=" . $cliente->getClient_S();
//$keyGoogle= $cliente->getKey();


$url1 = "https://apitransporte.buenosaires.gob.ar/"; //url de apis fija
$ruta = "transito/v1/cortes?";                       //cambia segun lo que necesite (subtes, trenes, bondis, etc.)
//cortes pide opcional un callback -> Nombre de la función donde se almacenará el response de la consulta

$union = $url1 . $ruta . $credenciales; //unimos las rutas y las credenciales
// return $union;

$json = file_get_contents($union);
//var_dump($json);

$cortes = json_decode($json, true); //decodificar el json a array
//echo "<pre>";var_dump($cortes);echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cortes CABA</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" /> <!-- estilos del mapa -->

</head>

<body>

    <h3>Lets start to learn about api</h3>

    <?php
    //echo count($cortes);
    //var_dump($cortes);
    for ($i = 0; $i <= count($cortes); $i++) {
        echo "Tipo de corte: " . $cortes["incidents"][$i]["type"] . "<br>";
        echo "Descripcion del corte: " . $cortes["incidents"][$i]["description"] . "<br>";
        if (array_key_exists("location", $cortes["incidents"][$i])) { //compruebo que existe el array ubicacion(hay casos en que no)
            $location = $cortes["incidents"][$i]["location"]["polyline"]; //almaceno el string de las coordenas 
            //--------------esto es para control
            //echo "<br>".$location."<br>";
            //   var_dump($cortes["incidents"][$i]["location"]["polyline"]);

            $ubicacion = explode(" ", $location);  //corto el string por espacios - separa latitud y longitud (tmb en api se repite tres veces seguidas c/u)
            $latitud = $ubicacion[0];
            $longitud = $ubicacion[1];
            echo "Ubicacion del corte: <br> Latitud" . $latitud . "<br> Longitud" . $longitud . "<br>";
            //echo "<iframe src=https://www.google.com/maps/search/?api=1&query=".$latitud.",".$longitud.'width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>'; 
            //aca arriba quise saltearme el uso de la api porque google-maps me pide cobro para su uso - pero me bloqueo 


        } else {
            echo "No detalla ubicacion";
        }

        echo "<br><br>";
    }

    ?>
    <script>
        var mymap = L.map('mapid').setView([51.505, -0.09], 13);
    </script>

</body>

</html>
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


    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.2.js"></script>
    <script type="text/javascript" src="https://www.ign.gob.ar/argenmap/argenmap.jquery.min.js"></script>
</head>

<body>

    <h3>Lets start to learn about api</h3>

    <?php
    //echo count($cortes);
    $latitud=array();
    $longitud=array();


    for ($i = 0; $i <= count($cortes); $i++) {
        echo "Tipo de corte: " . $cortes["incidents"][$i]["type"] . "<br>";
        echo "Descripcion del corte: " . $cortes["incidents"][$i]["description"] . "<br>";
        if (array_key_exists("location", $cortes["incidents"][$i])) { //compruebo que existe el array ubicacion(hay casos en que no)
            $location = $cortes["incidents"][$i]["location"]["polyline"]; //almaceno el string de las coordenas 
            //--------------esto es para control
            //echo "<br>".$location."<br>";
            //   var_dump($cortes["incidents"][$i]["location"]["polyline"]);

            $ubicacion = explode(" ", $location);  //corto el string por espacios - separa latitud y longitud 
            $latitud[$i] = $ubicacion[0];
            $longitud[$i] = $ubicacion[1];
            echo "Ubicacion del corte: <br> Latitud" . $latitud[$i] . "<br> Longitud" . $longitud[$i] . "<br>";

    ?>
            <script>
                //     lat: <?php //echo $latitud ?>,
                //     lng: <?php //echo $longitud ?>,
                //     contenido: "<?php //echo $cortes["incidents"][$i]["description"] ?>"
          </script>
    <?php

        } else {
            echo "No detalla ubicacion";
        }
        echo "<br><br>";
    }
var_dump($longitud);
var_dump($latitud);
    ?>
    <div id="mapa" style="height:315px;"></div>

</body>
<script type="text/javascript">
    $(document).ready(function() {
        var marcador = [
            <?php echo $latitud[0] ?>,
            <?php echo $longitud[0] ?>,
            "CORTE BA", "CORTE BA", "CORTE BA"
        ]
        console.log(marcador)

        $("#mapa").argenmap()
        $("#mapa").centro(-34.63, -58.52).zoom(13)
        $("#mapa").agregarMarcador(marcador)


    });



</script>

</html>
<?php


function connectDB(){

    $servername = "localhost";
    $username = "root";
    $password = "lokomartin82";
    $basedades = "biblioteca";


// Create connection
    $mysqli = new mysqli($servername, $username, $password, $basedades);


// Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
//echo "Connected successfully";

    $mysqli->set_charset("utf8");

    return $mysqli;
}



function menu_desplegable($nombre, $opdcioBuida=FALSE,$textebuit){
    echo "<select name='$nombre'>";
    if ($opdcioBuida){

        echo"<OPTION VALUE=''> $textebuit </OPTION>";
    }

    connectDB();

    $sql = "SELECT *  from NACIONALITATS";
    $cursor = connectDB()->query($sql) or die('Error query' . $sql);
    while ($reg = $cursor->fetch_assoc()) {

        echo '<option value="'.$reg['NACIONALITAT'].'">'.$reg['NACIONALITAT'].'</option>';

    }
    echo "</select>";
}

function datanull($data){
    if($data != ''){
        return "'$data'";
    } else {
        return "NULL";
    }
}
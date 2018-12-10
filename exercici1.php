<html>
<head>
    <title>Autors</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body class="container">

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

$conexio=connectDB();



$orderby = 'ID_AUT';
$sentit = 'ASC';
if (isset($_POST['ORDERBY'])) {
    $orderby = $_POST['ORDERBY'];
}
if (isset($_POST['SENTIT'])) {
    $sentit = $_POST['SENTIT'];
}

if (isset($_POST['ordenar'])) {

    if ($sentit == 'ASC') {
        $sentit = 'DESC';
    } else {
        $sentit = 'ASC';
    }
}

if (isset($_POST['altaAutor'])) {
    $nomAltaAutor = connectDB()->real_escape_string($_POST['NomAltaAutor']);
    $altaPaisAutor= connectDB()->real_escape_string($_POST['paisos']);
    $sql = "INSERT INTO AUTORS (ID_AUT, NOM_AUT, FK_NACIONALITAT) VALUES ((select max(ID_AUT)+1 from AUTORS as ID),'$nomAltaAutor', '$altaPaisAutor' )";
    $cursor = connectDB()->query($sql) or die('Error query' . $sql);

}

if (isset($_POST['Guardar'])) {

    $codiEdicio = '';
    $nomEditat = connectDB()->real_escape_string($_POST['NomEditat']);
    $paisEditat= connectDB()->real_escape_string($_POST['paisos']);
    $codiEdicio = $_POST['Guardar'];
    print_r($_POST);
    $sql = "UPDATE AUTORS SET NOM_AUT = '$nomEditat', FK_NACIONALITAT = '$paisEditat' WHERE ID_AUT =$codiEdicio";
    $cursor = connectDB()->query($sql) or die('Error query' . $sql);

}

if (isset($_POST['Borrar'])) {

    $codi = $_POST['Borrar'];
    print_r($_POST);
    $sql = "DELETE FROM AUTORS WHERE ID_AUT= $codi";
    $cursor = connectDB()->query($sql) or die('Error query' . $sql);

}


$filtra = "";
$textcerca = connectDB()->real_escape_string( (isset($_POST['TEXTCERCA']) ? $_POST['TEXTCERCA'] : ''));
if ($textcerca <> '') {
    $filtra = " where ID_AUT = '$textcerca' OR NOM_AUT like '%$textcerca%' ";
}

$posicio = 0;
$files_per_pagina = 10;
if (isset($_GET['POSICIO'])) {
    $posicio = $_GET['POSICIO'];
}
$sql = "SELECT ID_AUT,NOM_AUT, FK_NACIONALITAT from AUTORS $filtra";
$sql .= " ORDER BY $orderby $sentit";
$cursor = connectDB()->query($sql) or die('Error query' . $sql);

$totalregistres = $cursor->num_rows;
$totalpagines = (ceil($totalregistres / $files_per_pagina) - 1);
$posiciodarrer = $totalpagines * $files_per_pagina;

$query = "SELECT ID_AUT,NOM_AUT, FK_NACIONALITAT from AUTORS $filtra";
$query .= " ORDER BY $orderby $sentit LIMIT $posicio,$files_per_pagina";


$codiEditar = '';
if (isset($_POST['Editar'])) {
    $codiEditar = $_POST['Editar'];
}


if ($cursor = connectDB()->query($query) or DIE($query)) {

    echo '<div class="col-md-"12>';
    echo "<H6>CONSULTA LLISTA DE AUTORS</H6>";

    echo "<form method='post' action='#'>";
    echo "<input type='text' size='35px' placeholder='ID o Nom Autor' name='TEXTCERCA' value='$textcerca'>";
    echo "  ";
    echo "<input class='btn btn-secondary btn-sm' type='submit' value='Cercar'>";
    echo " ";
    echo "<a href='exercici1.php?ORDREBY=$orderby&SENTIT=$sentit&TEXTCERCA=$textcerca&POSICIO=0' class='btn btn-secondary btn-sm' role='button'>
<<</a>&nbsp;&nbsp;";
    $prior = $posicio - $files_per_pagina;
    if ($prior < 0) {
        $prior = 0;
    }
    echo "<a href='exercici1.php?ORDREBY=$orderby&SENTIT=$sentit&TEXTCERCA=$textcerca&POSICIO=$prior' class='btn btn-secondary btn-sm' role='button'><</a>&nbsp;&nbsp;";
    $next = $posicio + $files_per_pagina;
    if ($next > $posiciodarrer) {
        $next = $posiciodarrer;
    }
    echo "<a href='exercici1.php?ORDREBY=$orderby&SENTIT=$sentit&TEXTCERCA=$textcerca&POSICIO=$next' class='btn btn-secondary btn-sm' role='button'>></a>&nbsp;&nbsp;";
    $last = $posiciodarrer;
    echo "<a href='exercici1.php?ORDREBY=$orderby&SENTIT=$sentit&TEXTCERCA=$textcerca&POSICIO=$last' class='btn btn-secondary btn-sm' role=\"button\">>></a>&nbsp;&nbsp;";
    echo '<caption>LLista de Autors</caption>';
    echo " ";
    $pagina = $posicio / $files_per_pagina;
    echo "Pagines $pagina / $totalpagines Registres $totalregistres";
    echo "</form>";

    echo "<form method='post' action='#'>";

    if (isset($_POST['afegirAutor'])) {

        echo '<table class="table table-sm table-hover table-bordered table-dark table-sm">';
        echo '<thead class="thead-light">';
        echo "<th>";
        echo "AUTOR";
        echo " ";
        echo "<form method='post' action='#'>";
        echo "<input type='text' size='35px' placeholder='Introdueix un Autor' name='NomAltaAutor'>";
        echo "</th>";
        echo "<th>";
        echo "NACIONALITAT";
        echo " ";
        echo menu_desplegable('paisos', false, 'Introdueix un País'); // POSAR SELECTOR DE NACILATITAT
        echo "</th>";
        echo "<th>";
        echo "<button class='btn btn-success btn-sm' type='submit' name='altaAutor'>
Afegir Autor</button>";
        echo "<button class='btn btn-danger btn-sm' type='submit' name='Cancelar'>
Cancelar</button>";
        echo "</th>";
    } else {
        echo "<input class='btn btn-secondary btn-sm' type='submit' name='afegirAutor' value='Introdueix un Autor'>";
    }
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";
    echo "</form>";

    echo '<div class="table-responsive-sm">';

    echo '<table class="table table-sm table-hover table-bordered table-dark table-sm">';
    echo '<thead class="thead-light">';
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='ID_AUT'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

    echo "<input class='btn btn-secondary btn-sm' type='submit' name='ordenar' value='ID'></th>";
    echo "</form>";
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='NOM_AUT'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

    echo "<input class='btn btn-secondary btn-sm' type='submit' name='ordenar' value='Nom AUTOR'>";
    echo "<th>";
    echo "</form>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='FK_NACIONALITAT'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

    echo "<input class='btn btn-secondary btn-sm' type='submit' name='ordenar' value='NACIONALITAT'>";
    echo "<th>";
    echo "</form>";
    echo "</thead>";


    while ($reg = $cursor->fetch_assoc()) {
        echo '<tbody>';
        echo '<tr>';
        echo '<td>';
        echo $reg['ID_AUT'];
        echo '</td>';

        echo "<form method='post' action='#'>";
        if ($reg['ID_AUT'] == $codiEditar) {
            echo '<td>';
            echo "<input type='text' size='35px' placeholder='Nom Autor' name='NomEditat' value='{$reg['NOM_AUT']}'>";
            echo '</td>';
            echo '<td>';
            echo menu_desplegable('paisos', false, 'Introdueix un País'); // POSAR SELECTOR DE NACILATITAT
            echo '</td>';
            echo '<td>';
            echo "<button class='btn btn-success btn-sm' type='submit' name='Guardar' value='{$reg['ID_AUT']}'>
Guardar</button>";
            echo "<button class='btn btn-danger btn-sm' type='submit' name='Cancelar'>
Cancelar</button>";
            echo '</td>';

        } else {
            echo '<td>';
            echo $reg['NOM_AUT'];
            echo '</td>';
            echo '<td>';
            echo $reg['FK_NACIONALITAT'];
            echo '</td>';
            echo '<td>';

            echo "<button class='btn btn-success btn-sm' type='submit' name='Editar' value='{$reg['ID_AUT']}'>
Edicio</button>";

            echo "<button class='btn btn-danger btn-sm' type='submit' name='Borrar' value='{$reg['ID_AUT']}'>
Borrar</button>";
            echo '</td>';

        }
        echo "<input type='hidden' name='SENTIT' value='$sentit'>";
        echo "<input type='hidden' name='ORDERBY' value='$orderby'>";
        echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

        echo "</form>";

        echo '</tr>';
        echo '</tbody>';
    }
}
echo '</table>';
echo "</div>";
echo '</div>';
echo '</div>';

echo "<pre>";
print_r($_POST);
echo "</pre>";






function menu_desplegable($nombre, $opdcioBuida=TRUE,$textebuit="Tria una Opció"){
    echo "<select name='$nombre' required>";
    if ($opdcioBuida){

        echo"<OPTION VALUE=>$textebuit</OPTION>";
    }

    connectDB();

    $sql = "SELECT *  from NACIONALITATS";
    $cursor = connectDB()->query($sql) or die('Error query' . $sql);
    while ($reg = $cursor->fetch_assoc()) {

        echo '<option value="'.$reg['NACIONALITAT'].'">'.$reg['NACIONALITAT'].'</option>';

    }
    echo "</select>";
}


?>


</body>
</html>
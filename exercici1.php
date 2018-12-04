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

$orderby = 'ID_AUT';
$sentit = 'ASC';
if (isset($_POST['ORDERBY'])) {
    $orderby = $_POST['ORDERBY'];
}
if (isset($_POST['SENTIT'])) {
    $sentit = $_POST['SENTIT'];
}

$filtra = "";
$textcerca = (isset($_POST['TEXTCERCA']) ? $_POST['TEXTCERCA'] : '');
if ($textcerca <> '') {
    $filtra = " where ID_AUT = '$textcerca' OR NOM_AUT like '%$textcerca%' ";
}

$posicio = 0;
$files_per_pagina = 10;
if (isset($_GET['POSICIO'])) {
    $posicio = $_GET['POSICIO'];
}
$sql = "SELECT ID_AUT,NOM_AUT from AUTORS $filtra";
$sql .= " ORDER BY $orderby $sentit";
$cursor = $mysqli->query($sql) or die('Error query' . $sql);

$totalregistres = $cursor->num_rows;
$totalpagines = (ceil($totalregistres / $files_per_pagina) - 1);
$posiciodarrer = $totalpagines * $files_per_pagina;

$query = "SELECT ID_AUT,NOM_AUT from AUTORS $filtra";
$query .= " ORDER BY $orderby $sentit LIMIT $posicio,$files_per_pagina";

if(isset($_POST['Borrar'])){

    $codi = $_POST['Borrar'];
    print_r($_POST);
    $sql="DELETE FROM AUTORS WHERE ID_AUT= $codi";
    $cursor = $mysqli->query($sql) or die('Error query' . $sql);

}


if ($cursor = $mysqli->query($query) or DIE($query)) {

    echo '<div class="col-md-12">';
    echo "<H6>CONSULTA LLISTA DE AUTORS</H6>";

    echo "<form method='post' action='#'>";
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
    $pagina=$posicio/$files_per_pagina;
    echo "Pagines $pagina / $totalpagines Registres $totalregistres";
    echo "  ";
    echo "<input type='text' size='35px' placeholder='ID o Nom Autor' name='TEXTCERCA' value='$textcerca'>";
    echo "  ";
    echo "<input class='btn btn-secondary btn-xs' type='submit' value='Cercar'>";
    echo "</form>";


    echo '<div class="table-responsive-sm">';

    echo '<table class="table table-sm table-hover table-bordered table-dark table-sm">';


    if ($sentit == 'ASC') {
        $sentit = 'DESC';
    } else {
        $sentit = 'ASC';
    }

    echo '<thead class="thead-light">';
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='ID_AUT'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

    echo "<input class='btn btn-secondary btn-sm' type='submit' value='ID'></th>";
    echo "</form>";
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='NOM_AUT'>";
    echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";

    echo "<input class='btn btn-secondary btn-sm' type='submit' value='Nom AUTOR'>";
    echo "<th>";
    echo "</form>";
    echo "<form method='post' action='#'>";
    echo "<input class='btn btn-secondary btn-sm' type='submit' value='Introdueix Autor'>";
    echo "</th>";
    echo "</form>";
    echo "</thead>";


    while ($reg = $cursor->fetch_assoc()) {
        echo '<tbody>';
        echo '<tr>';
        echo '<td>';
        echo $reg['ID_AUT'];
        echo '</td>';
        echo '<td>';
        echo $reg['NOM_AUT'];
        echo '</td>';
        echo "<td>";
        echo "<form method='post' action='#'>";
        echo "<input type='hidden' name='SENTIT' value='$sentit'>";
        echo "<input type='hidden' name='ORDERBY' value='$orderby'>";
        echo "<input type='hidden' name='TEXTCERCA' value='$textcerca'>";
        echo "<button class='btn btn-danger btn-sm' type='submit' name='Borrar' value='{$reg['ID_AUT']}'>
Borrar</button>";
        echo "<button class='btn btn-success btn-sm' type='submit' name='Edicio' value='{$reg['ID_AUT']}'>
Edicio</button>";
        echo "</form>";
        echo "</td>";
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
?>


</body>
</html>
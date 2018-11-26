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
<body class='container'>
<H1>CONSULTA LLISTA DE AUTORS</H1>


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

echo "<br>";
$mysqli->set_charset("utf8");

$orderby = 'ID_AUT';
$sentit = 'ASC';
if (isset($_POST['ORDERBY'])) {
    $orderby = $_POST['ORDERBY'];
}
if (isset($_POST['SENTIT'])) {
    $sentit = $_POST['SENTIT'];
}


$query = "SELECT ID_AUT,NOM_AUT from AUTORS";
$query .= " ORDER BY $orderby $sentit limit 20";

if ($cursor = $mysqli->query($query) or DIE($query)) {


    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<div class="table-responsive-sm">';
    echo '<table class="table table-sm table-hover table-bordered table-dark">';
    echo '<caption>LLista de Autors</caption>';
    if ($sentit == 'ASC') {
        $sentit = 'DESC';
    } else {
        $sentit = 'ASC';
    }
    
    echo '<thead class="thead-light">';
    echo '<tr>';
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='ID_AUT'>";
    echo "<input class='btn btn-secondary' type='submit' value='ID'></th>";
    echo "</form>";
    echo "     ";
    echo "<th>";
    echo "<form method='post' action='#'>";
    echo "<input type='hidden' name='SENTIT' value='$sentit'>";
    echo "<input type='hidden' name='ORDERBY' value='NOM_AUT'>";
    echo "<input class='btn btn-secondary' type='submit' value='Nom AUTOR'></th>";
    echo "</form>";
    echo "</th>";
    echo "</tr>";
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
        echo '</tr>';
        echo '</tbody>';
    }
}
echo '</table>';
echo '</div>';
echo '</div>';
echo '</div>';
?>


</body>
</html>
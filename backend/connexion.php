<?php
function connectToDatabase() {
    $host = 'localhost';
    $user = 'u716777407_admin1';
    $password = 'Dalulou123'; 
    $database = 'u716777407_LocationChef';

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die('Erreur de connexion : ' . mysqli_connect_error());
    }
    else{
        echo "Connexion réussie à la base de données.";
    }

    
    return $conn;

    
}

?>

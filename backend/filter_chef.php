<?php

$host = 'localhost';
$dbname = 'u716777407_LocationChef';
$username = 'u716777407_admin1';
$password = 'Dalulou123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';

$query = "
    SELECT 
        Chefs.id, 
        Utilisateurs.nom, 
        Utilisateurs.prenom, 
        Chefs.specialites, 
        Chefs.tarif_horaire, 
        Chefs.photo, 
        Chefs.note_moyenne, 
        Utilisateurs.adresse,
        COUNT(Avis.id) AS nombre_avis
    FROM Chefs
    JOIN Utilisateurs ON Chefs.utilisateur_id = Utilisateurs.id
    LEFT JOIN Avis ON Avis.chef_id = Chefs.id
    WHERE 1=1
";

$params = [];

if (!empty($specialty)) {
    $query .= " AND Chefs.specialites LIKE :specialty";
    $params[':specialty'] = '%' . $specialty . '%';
}

if (!empty($city)) {
    $query .= " AND Utilisateurs.adresse LIKE :city";
    $params[':city'] = '%' . $city . '%';
}

$query .= " GROUP BY Chefs.id, Utilisateurs.nom, Utilisateurs.prenom, Chefs.specialites, Chefs.tarif_horaire, Chefs.photo, Chefs.note_moyenne, Utilisateurs.adresse";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$chefs = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($chefs);

?>

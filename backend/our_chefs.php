<?php

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'LocationChef';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $query = "SELECT u.nom, u.prenom, c.specialites, c.photo, c.note_moyenne 
              FROM Chefs c 
              JOIN Utilisateurs u ON c.utilisateur_id = u.id
              ORDER BY c.note_moyenne DESC
              LIMIT 3";
    $stmt = $pdo->query($query);

    $chefs = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chefs[] = [
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'specialites' => $row['specialites'],
            'photo' => $row['photo'],
            'note_moyenne' => $row['note_moyenne']
        ];
    }

    echo json_encode($chefs);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion : ' . $e->getMessage()]);
}
?>
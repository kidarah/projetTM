<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "u716777407_admin1", "Dalulou123", "u716777407_LocationChef");

// Test de la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$chef_id = $_GET['id'] ?? null;

if (!$chef_id) {
    echo json_encode(["error" => "ID du chef non fourni"]);
    exit();
}

$query = "SELECT c.*, u.nom, u.prenom FROM Chefs c 
          JOIN Utilisateurs u ON c.utilisateur_id = u.id 
          WHERE c.id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "Erreur de préparation SQL"]);
    exit();
}

$stmt->bind_param("i", $chef_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Chef introuvable"]);
    exit();
}

$chef = $result->fetch_assoc();

// 🔎 Récupérer les plats du chef
$query_plats = "SELECT photo FROM Menus WHERE chef_id = ?";
$stmt_plats = $conn->prepare($query_plats);
$stmt_plats->bind_param("i", $chef_id);
$stmt_plats->execute();
$result_plats = $stmt_plats->get_result();
$plats = [];
while ($row = $result_plats->fetch_assoc()) {
    $plats[] = $row['photo'];
}
$chef['plats'] = $plats;

// 🔎 Récupérer les avis du chef
$query_avis = "SELECT note, commentaire FROM Avis WHERE chef_id = ?";
$stmt_avis = $conn->prepare($query_avis);
$stmt_avis->bind_param("i", $chef_id);
$stmt_avis->execute();
$result_avis = $stmt_avis->get_result();
$avis = [];
while ($row = $result_avis->fetch_assoc()) {
    $avis[] = $row;
}
$chef['avis'] = $avis;

// ✅ Envoyer un JSON propre
echo json_encode($chef, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>

<?php
session_start();
$conn = new mysqli("localhost", "u716777407_admin1", "Dalulou123", "u716777407_LocationChef");

if ($conn->connect_error) {
    die(json_encode(["error" => "Erreur de connexion à la base de données : " . $conn->connect_error]));
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "Utilisateur non connecté."]));
}

// ✅ Récupérer `client_id` depuis la table `Clients`
$stmt_client = $conn->prepare("SELECT id FROM Clients WHERE utilisateur_id = ?");
$stmt_client->bind_param("i", $_SESSION['user_id']);
$stmt_client->execute();
$result_client = $stmt_client->get_result();
$row_client = $result_client->fetch_assoc();
$client_id = $row_client["id"] ?? null;

if (!$client_id) {
    die(json_encode(["error" => "Erreur : Impossible de trouver l'ID du client associé."]));
}

$chef_id = $_POST['chef_id'] ?? null;
$note = $_POST['note'] ?? null;
$commentaire = $_POST['commentaire'] ?? '';

if (!$chef_id || !$note) {
    die(json_encode(["error" => "Informations manquantes."]));
}

// Vérifier si le client a une réservation confirmée avec ce chef
$stmt = $conn->prepare("SELECT id FROM Reservations WHERE client_id = ? AND chef_id = ? AND statut = 'Confirmée' ORDER BY date_reservation DESC LIMIT 1");
$stmt->bind_param("ii", $client_id, $chef_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$reservation_id = $row["id"] ?? null;

if (!$reservation_id) {
    echo json_encode(["error" => "Vous devez avoir une réservation confirmée avec ce chef pour laisser un avis."]);
    exit(); // Stopper l'exécution du script
}

// Insérer l'avis dans la base de données
$stmt = $conn->prepare("INSERT INTO Avis (client_id, chef_id, reservation_id, note, commentaire) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiis", $client_id, $chef_id, $reservation_id, $note, $commentaire);

if (!$stmt->execute()) {
    die(json_encode(["error" => "Erreur lors de l'ajout de l'avis : " . $stmt->error]));
}

// Recalculer la note moyenne du chef
$stmt = $conn->prepare("SELECT AVG(note) AS moyenne FROM Avis WHERE chef_id = ?");
$stmt->bind_param("i", $chef_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$moyenne = round($row["moyenne"], 2);

// Mettre à jour la moyenne dans la table Chefs
$stmt = $conn->prepare("UPDATE Chefs SET note_moyenne = ? WHERE id = ?");
$stmt->bind_param("di", $moyenne, $chef_id);

if (!$stmt->execute()) {
    die(json_encode(["error" => "Erreur lors de la mise à jour de la note moyenne : " . $stmt->error]));
}

echo json_encode(["success" => "Avis ajouté avec succès et note moyenne mise à jour !"]);
$conn->close();
?>

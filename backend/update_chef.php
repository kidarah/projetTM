<?php
session_start();

// Définir la réponse en JSON pour éviter les erreurs de format
header('Content-Type: application/json');

$conn = new mysqli("localhost", "u716777407_admin1", "Dalulou123", "u716777407_LocationChef");

// Vérification de la connexion
if ($conn->connect_error) {
    die(json_encode(["error" => "Erreur de connexion : " . $conn->connect_error]));
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "Utilisateur non connecté."]));
}

$utilisateur_id = $_SESSION['user_id'];
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$specialite = $_POST['specialite'] ?? '';
$bio = $_POST['bio'] ?? '';
$tarif = isset($_POST['tarif']) && is_numeric($_POST['tarif']) ? $_POST['tarif'] : null;
$photo = null;

// Vérification des valeurs reçues (à activer temporairement si nécessaire)
// error_log(print_r($_POST, true)); // Remplace var_dump($_POST) pour éviter de polluer JSON

// Gestion de l'upload d'image
if (!empty($_FILES['photo']['name'])) {
    $photo = "uploads/" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
} else {
    // Récupérer l'image existante si aucune nouvelle n'est envoyée
    $stmt_photo = $conn->prepare("SELECT photo FROM Chefs WHERE utilisateur_id=?");
    $stmt_photo->bind_param("i", $utilisateur_id);
    $stmt_photo->execute();
    $result_photo = $stmt_photo->get_result();
    
    if ($row = $result_photo->fetch_assoc()) {
        $photo = $row['photo'];
    }
}

// Mise à jour des informations du chef
$sql = "UPDATE Chefs SET specialites=?, experience=?, tarif_horaire=?, photo=? WHERE utilisateur_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsi", $specialite, $bio, $tarif, $photo, $utilisateur_id);

if (!$stmt->execute()) {
    die(json_encode(["error" => "Erreur SQL (mise à jour du chef) : " . $stmt->error]));
}

// Vérifier si la mise à jour a bien été appliquée
if ($stmt->affected_rows === 0) {
    die(json_encode(["error" => "Aucune mise à jour effectuée. Vérifiez les valeurs."]));
}

// Mise à jour du nom et prénom du chef
if (!empty($nom)) {
    $stmt_nom = $conn->prepare("UPDATE Utilisateurs SET nom=? WHERE id=?");
    $stmt_nom->bind_param("si", $nom, $utilisateur_id);
    $stmt_nom->execute();
}

if (!empty($prenom)) {
    $stmt_prenom = $conn->prepare("UPDATE Utilisateurs SET prenom=? WHERE id=?");
    $stmt_prenom->bind_param("si", $prenom, $utilisateur_id);
    $stmt_prenom->execute();
}

// Réponse JSON pour succès
echo json_encode(["success" => "Profil mis à jour avec succès!"]);
exit(); // 🚨 Assurer que rien d'autre n'est ajouté après la réponse JSON

$conn->close();
?>

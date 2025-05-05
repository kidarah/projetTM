<?php
session_start();

$conn = new mysqli("localhost", "u716777407_admin1", "Dalulou123", "u716777407_LocationChef");

// Test de la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Erreur : utilisateur non connecté.");
}

$utilisateur_id = $_SESSION['user_id'];
$nom = $_POST['nom'] ?? '';
$specialite = $_POST['specialite'] ?? '';
$bio = $_POST['bio'] ?? '';
$photo = null;

// Vérifier et enregistrer la photo
if (!empty($_FILES['photo']['name'])) {
    $photo = "uploads/" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
}

$sql = "UPDATE Chefs SET specialites=?, experience=?, photo=? WHERE utilisateur_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $specialite, $bio, $photo, $utilisateur_id);

if ($stmt->execute()) {
    echo "Profil mis à jour avec succès!";
} else {
    echo "Erreur de mise à jour : " . $stmt->error;
}

$conn->close();
?>

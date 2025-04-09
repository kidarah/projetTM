<?php
session_start();
$conn = new mysqli("localhost", "root", "", "locationChef");

// Test de la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, role, mot_de_passe FROM utilisateurs WHERE email = ?");
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['mot_de_passe'])) { // Vérification sécurisée
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../FrontView.html");
        exit();
    } else {
        header("Location: connexion.html"); // Retour à la page de connexion 
        exit();
    }
}
?>

<?php
session_start();
header('Content-Type: application/json');

// Connexion à la base de données
$conn = new mysqli("localhost", "u716777407_admin1", "Dalulou123", "u716777407_LocationChef");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion à la base de données.']);
    exit;
}

// Vérifier que les champs sont présents
if (empty($_POST['email']) || empty($_POST['password'])) {
    echo json_encode(['success' => false, 'error' => 'Veuillez remplir tous les champs.']);
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

// Récupérer l'utilisateur
$stmt = $conn->prepare("SELECT id, mot_de_passe, role FROM Utilisateurs WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Email ou mot de passe incorrect.']);
    exit;
}

$user = $result->fetch_assoc();

// Vérifier le mot de passe
if (password_verify($password, $user['mot_de_passe'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Email ou mot de passe incorrect.']);
}

$stmt->close();
$conn->close();
?>

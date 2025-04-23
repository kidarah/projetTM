<?php
header('Content-Type: application/json');

// Fonction de connexion simplifiée
function connectToDatabase() {
    $host = 'localhost';
    $user = 'u716777407_admin1';
    $password = 'TON_MOT_DE_PASSE_ICI'; // Remplace par ton mot de passe
    $database = 'u716777407_LocationChef';

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die(json_encode(['success' => false, 'errors' => ['dbError' => 'Erreur de connexion : ' . mysqli_connect_error()]]));
    }

    return $conn;
}

// Initialiser un tableau pour stocker les erreurs
$errors = [];

// Vérifications formulaire
if (!isset($_POST['userType']) || ($_POST['userType'] !== 'chef' && $_POST['userType'] !== 'client')) {
    $errors['userTypeErrors'] = 'Veuillez sélectionner un type d’utilisateur (Chef ou Client).';
}
if (empty($_POST['firstname'])) {
    $errors['firstnameErrors'] = 'Le prénom est requis.';
}
if (empty($_POST['lastname'])) {
    $errors['lastnameErrors'] = 'Le nom de famille est requis.';
}
if (empty($_POST['email'])) {
    $errors['emailErrors'] = 'L’adresse email est requise.';
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['emailErrors'] = 'Veuillez entrer une adresse email valide.';
}
if (empty($_POST['password'])) {
    $errors['passwordErrors'] = 'Le mot de passe est requis.';
} elseif (strlen($_POST['password']) < 6) {
    $errors['passwordErrors'] = 'Le mot de passe doit contenir au moins 6 caractères.';
}
if (empty($_POST['passwordC'])) {
    $errors['confirm_passwordErrors'] = 'La confirmation du mot de passe est requise.';
} elseif ($_POST['password'] !== $_POST['passwordC']) {
    $errors['confirm_passwordErrors'] = 'Les mots de passe ne correspondent pas.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Connexion à la base de données
$conn = connectToDatabase();

// Préparer les données sécurisées
$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$userType = $_POST['userType'];
$telephone = isset($_POST['telephone']) ? mysqli_real_escape_string($conn, $_POST['telephone']) : '';
$adresse = isset($_POST['adresse']) ? mysqli_real_escape_string($conn, $_POST['adresse']) : '';

// Vérifier si l'email existe déjà
$emailCheck = $conn->prepare("SELECT id FROM Utilisateurs WHERE email = ?");
$emailCheck->bind_param("s", $email);
$emailCheck->execute();
$emailCheck->store_result();

if ($emailCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'errors' => ['emailErrors' => 'Cet email est déjà utilisé.']]);
    $emailCheck->close();
    $conn->close();
    exit;
}
$emailCheck->close();

// Insérer l'utilisateur
$stmt = $conn->prepare("INSERT INTO Utilisateurs (nom, prenom, email, telephone, adresse, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $lastname, $firstname, $email, $telephone, $adresse, $passwordHash, $userType);

if ($stmt->execute()) {
    $userId = $stmt->insert_id;

    if ($userType === 'chef') {
        $defaultTarif = 50.00;
        $chefStmt = $conn->prepare("INSERT INTO Chefs (utilisateur_id, experience, specialites, tarif_horaire, photo, note_moyenne) VALUES (?, ?, ?, ?, ?, ?)");
        $experience = '';
        $specialites = '';
        $photo = '';
        $noteMoyenne = 0.0;
        $chefStmt->bind_param("issdss", $userId, $experience, $specialites, $defaultTarif, $photo, $noteMoyenne);
        $chefStmt->execute();
        $chefStmt->close();
    } elseif ($userType === 'client') {
        $clientStmt = $conn->prepare("INSERT INTO Clients (utilisateur_id) VALUES (?)");
        $clientStmt->bind_param("i", $userId);
        $clientStmt->execute();
        $clientStmt->close();
    }

    echo json_encode(['success' => true, 'message' => 'Tout est correct, utilisateur ajouté avec succès !']);
} else {
    echo json_encode(['success' => false, 'errors' => ['dbError' => 'Erreur lors de l\'ajout de l\'utilisateur.']]);
}

$stmt->close();
$conn->close();
?>

<?php
session_start();

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Récupérer les données du formulaire
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=hackaton;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Préparer la requête
    $stmt = $pdo->prepare('SELECT id, username, password FROM utilisateurs WHERE username = :username');
    $stmt->execute(['username' => $username]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        // Identifiants incorrects
        header('Location: login.php?error=1');
        exit;
    }
    
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    error_log($e->getMessage());
    header('Location: login.php?error=1');
    exit;
}
?>

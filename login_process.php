<?php
include 'config.php';
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
    // Utiliser la connexion PDO de config.php
    $stmt = $pdo->prepare('SELECT id, username, password FROM utilisateurs WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Vérifier le mot de passe avec SHA1
    if ($user && $user['password'] === sha1($password)) {
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

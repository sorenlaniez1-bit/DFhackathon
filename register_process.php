<?php
session_start();

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Récupérer les données du formulaire
$username = trim($_POST['username'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$age = intval($_POST['age'] ?? 0);
$ville = trim($_POST['ville'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Vérifier que les mots de passe correspondent
if ($password !== $password_confirm) {
    header('Location: register.php?error=password');
    exit;
}

// Connexion à la base de données
try {
    $dsn = 'mysql:host=192.168.1.62;dbname=hackaton;charset=utf8mb4';
    $pdo = new PDO($dsn, 'root_remote', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Vérifier si l'utilisateur existe déjà
    $checkStmt = $pdo->prepare('SELECT COUNT(*) as count FROM utilisateurs WHERE username = ?');
    $checkStmt->execute([$username]);
    $result = $checkStmt->fetch();
    
    if ($result['count'] > 0) {
        header('Location: register.php?error=exists');
        exit;
    }
    
    // Hasher le mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    
    // Insérer le nouvel utilisateur
    $insertStmt = $pdo->prepare(
        'INSERT INTO utilisateurs (username, password, nom, prenom, age, ville, role, date_creation) 
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $insertStmt->execute([$username, $password_hash, $nom, $prenom, $age, $ville, $role]);
    
    // Rediriger vers la page de connexion avec message de succès
    header('Location: login.php?success=1');
    exit;
    
} catch (PDOException $e) {
    error_log('Erreur SQL: ' . $e->getMessage());
    die('Erreur de base de données: ' . $e->getMessage());
}
?>


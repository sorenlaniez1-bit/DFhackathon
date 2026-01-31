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

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=192.168.1.62;dbname=hackaton;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE username = :username');
    $stmt->execute(['username' => $username]);
    
    if ($stmt->fetch()) {
        // L'utilisateur existe déjà
        header('Location: register.php?error=exists');
        exit;
    }
    
    // Hasher le mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur avec tous les champs
    $stmt = $pdo->prepare('INSERT INTO utilisateurs (username, password, nom, prenom, age, ville, role, date_creation) VALUES (:username, :password, :nom, :prenom, :age, :ville, "user", NOW())');
    $stmt->execute([
        'username' => $username,
        'password' => $password_hash,
        'nom' => $nom,
        'prenom' => $prenom,
        'age' => $age,
        'ville' => $ville
    ]);
    
    // Rediriger vers la page de connexion avec message de succès
    header('Location: login.php?success=1');
    exit;
    
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    error_log($e->getMessage());
    header('Location: register.php?error=1');
    exit;
}
?>

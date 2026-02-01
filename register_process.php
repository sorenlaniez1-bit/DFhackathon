<?php
include 'config.php';
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
$roles_array = $_POST['roles'] ?? [];
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Valider qu'au moins un rôle est sélectionné
if (empty($roles_array)) {
    header('Location: register.php?error=Veuillez sélectionner au moins un rôle');
    exit;
}

// Convertir le tableau en string pour le champ SET
$roles = implode(',', $roles_array);

// Vérifier que les mots de passe correspondent
if ($password !== $password_confirm) {
    header('Location: register.php?error=Les mots de passe ne correspondent pas');
    exit;
}

// Vérifier que l'identifiant n'existe pas déjà
$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE username = :username');
$stmt->execute(['username' => $username]);
if ($stmt->fetch()) {
    header('Location: register.php?error=Identifiant déjà utilisé');
    exit;
}

// Hasher le mot de passe avec SHA1
$password_hash = sha1($password);

// Insérer le nouvel utilisateur
$stmt = $pdo->prepare('INSERT INTO utilisateurs (username, password, nom, prenom, age, ville, roles, date_creation, date_modification) VALUES (:username, :password, :nom, :prenom, :age, :ville, :roles, NOW(), NOW())');
$stmt->execute([
    'username' => $username,
    'password' => $password_hash,
    'nom' => $nom,
    'prenom' => $prenom,
    'age' => $age,
    'ville' => $ville,
    'roles' => $roles
]);

header('Location: login.php?success=Compte créé avec succès ! Vous pouvez vous connecter');
exit;

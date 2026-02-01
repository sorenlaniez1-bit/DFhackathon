<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profil.php');
    exit;
}

// Récupérer les données
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$age = intval($_POST['age'] ?? 0);
$ville = trim($_POST['ville'] ?? '');
$roles_array = $_POST['roles'] ?? [];

// Validation
if (empty($nom) || empty($prenom) || $age <= 0 || empty($ville)) {
    header('Location: modifier_profil.php?error=Tous les champs sont requis');
    exit;
}

// Valider qu'au moins un rôle est sélectionné
if (empty($roles_array)) {
    header('Location: modifier_profil.php?error=Veuillez sélectionner au moins un rôle');
    exit;
}

// Convertir le tableau en string pour le champ SET
$roles = implode(',', $roles_array);

try {
    // Mettre à jour le profil
    $stmt = $pdo->prepare('UPDATE utilisateurs SET nom = :nom, prenom = :prenom, age = :age, ville = :ville, roles = :roles, date_modification = NOW() WHERE id = :id');
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'age' => $age,
        'ville' => $ville,
        'roles' => $roles,
        'id' => $_SESSION['user_id']
    ]);
    
    header('Location: profil.php?success=Profil mis à jour avec succès');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: modifier_profil.php?error=Erreur lors de la modification');
    exit;
}

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
    header('Location: dashboard.php');
    exit;
}

// Récupérer les données du formulaire
$titre = trim($_POST['titre'] ?? '');
$description = trim($_POST['description'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$lieu = trim($_POST['lieu'] ?? '');
$duree = trim($_POST['duree'] ?? '');
$date_limite = $_POST['date_limite'] ?? '';

// Calcul des points : 10 points si 1h ou moins, puis 10 points par heure supplémentaire
$duree_heures = floatval($duree);
$points_attribues = $duree_heures <= 1 ? 10 : ($duree_heures * 10);

// Validation
if (empty($titre) || empty($description) || empty($ville) || empty($lieu) || empty($duree) || empty($date_limite)) {
    header('Location: creer_demande.php?error=Tous les champs sont requis');
    exit;
}

try {
    // Insérer la demande
    $stmt = $pdo->prepare('INSERT INTO demandes (id_demandeur, titre, description, ville, lieu, duree, date_limite, points_attribues) VALUES (:id_demandeur, :titre, :description, :ville, :lieu, :duree, :date_limite, :points)');
    $stmt->execute([
        'id_demandeur' => $_SESSION['user_id'],
        'titre' => $titre,
        'description' => $description,
        'ville' => $ville,
        'lieu' => $lieu,
        'lieu' => $lieu,
        'duree' => $duree,
        'date_limite' => $date_limite,
        'points' => $points_attribues
    ]);
    
    header('Location: dashboard.php?success=Demande créée avec succès');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: creer_demande.php?error=Erreur lors de la création');
    exit;
}

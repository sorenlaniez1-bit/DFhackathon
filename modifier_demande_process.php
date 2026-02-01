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
    header('Location: mes_demandes.php');
    exit;
}

// Récupérer les données
$id_demande = intval($_POST['id'] ?? 0);
$titre = trim($_POST['titre'] ?? '');
$description = trim($_POST['description'] ?? '');
$lieu = trim($_POST['lieu'] ?? '');
$duree = trim($_POST['duree'] ?? '');
$date_limite = $_POST['date_limite'] ?? '';

// Validation
if ($id_demande <= 0 || empty($titre) || empty($description) || empty($lieu) || empty($duree) || empty($date_limite)) {
    header('Location: modifier_demande.php?id=' . $id_demande . '&error=Tous les champs sont requis');
    exit;
}

try {
    // Vérifier que la demande appartient à l'utilisateur et est ouverte
    $stmt = $pdo->prepare('SELECT * FROM demandes WHERE id = :id AND id_demandeur = :user_id AND statut = "ouverte"');
    $stmt->execute([
        'id' => $id_demande,
        'user_id' => $_SESSION['user_id']
    ]);
    $demande = $stmt->fetch();
    
    if (!$demande) {
        header('Location: mes_demandes.php?error=Demande introuvable ou non modifiable');
        exit;
    }
    
    // Mettre à jour la demande
    $stmt = $pdo->prepare('UPDATE demandes SET titre = :titre, description = :description, lieu = :lieu, duree = :duree, date_limite = :date_limite WHERE id = :id');
    $stmt->execute([
        'titre' => $titre,
        'description' => $description,
        'lieu' => $lieu,
        'duree' => $duree,
        'date_limite' => $date_limite,
        'id' => $id_demande
    ]);
    
    header('Location: mes_demandes.php?success=Demande modifiée avec succès');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: modifier_demande.php?id=' . $id_demande . '&error=Erreur lors de la modification');
    exit;
}

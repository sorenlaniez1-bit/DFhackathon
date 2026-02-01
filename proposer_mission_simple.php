<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_demande = intval($_GET['id'] ?? 0);

if ($id_demande <= 0) {
    header('Location: missions.php?error=Demande invalide');
    exit;
}

try {
    // Vérifier que la demande existe et est ouverte
    $stmt = $pdo->prepare('SELECT * FROM demandes WHERE id = :id AND statut = "ouverte"');
    $stmt->execute(['id' => $id_demande]);
    $demande = $stmt->fetch();
    
    if (!$demande) {
        header('Location: missions.php?error=Demande non disponible');
        exit;
    }
    
    // Vérifier que l'utilisateur ne se propose pas sur sa propre demande
    if ($demande['id_demandeur'] == $_SESSION['user_id']) {
        header('Location: missions.php?error=Vous ne pouvez pas accepter votre propre demande');
        exit;
    }
    
    // Vérifier si l'utilisateur ne s'est pas déjà proposé
    $stmt = $pdo->prepare('SELECT * FROM mises_en_relation WHERE id_demande = :id_demande AND id_benevole = :id_benevole');
    $stmt->execute([
        'id_demande' => $id_demande,
        'id_benevole' => $_SESSION['user_id']
    ]);
    
    if ($stmt->fetch()) {
        header('Location: missions.php?error=Vous vous êtes déjà proposé pour cette mission');
        exit;
    }
    
    // Créer la proposition
    $stmt = $pdo->prepare('INSERT INTO mises_en_relation (id_demande, id_benevole) VALUES (:id_demande, :id_benevole)');
    $stmt->execute([
        'id_demande' => $id_demande,
        'id_benevole' => $_SESSION['user_id']
    ]);
    
    header('Location: missions.php?success=Proposition envoyée avec succès');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: missions.php?error=Erreur serveur');
    exit;
}

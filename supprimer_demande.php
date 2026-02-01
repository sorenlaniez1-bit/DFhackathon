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
    header('Location: mes_demandes.php');
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
        header('Location: mes_demandes.php?error=Demande introuvable ou non supprimable');
        exit;
    }
    
    // Supprimer les propositions associées
    $stmt = $pdo->prepare('DELETE FROM mises_en_relation WHERE id_demande = :id');
    $stmt->execute(['id' => $id_demande]);
    
    // Supprimer la demande
    $stmt = $pdo->prepare('DELETE FROM demandes WHERE id = :id');
    $stmt->execute(['id' => $id_demande]);
    
    header('Location: mes_demandes.php?success=Demande supprimée avec succès');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: mes_demandes.php?error=Erreur lors de la suppression');
    exit;
}

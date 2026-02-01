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
    header('Location: mes_demandes.php?error=Demande invalide');
    exit;
}

try {
    // Récupérer la demande
    $stmt = $pdo->prepare('SELECT * FROM demandes WHERE id = :id');
    $stmt->execute(['id' => $id_demande]);
    $demande = $stmt->fetch();
    
    if (!$demande) {
        header('Location: mes_demandes.php?error=Demande introuvable');
        exit;
    }
    
    // Vérifier que c'est bien le demandeur qui termine
    if ($demande['id_demandeur'] != $_SESSION['user_id']) {
        header('Location: mes_demandes.php?error=Non autorisé');
        exit;
    }
    
    // Vérifier que la demande est en cours
    if ($demande['statut'] != 'prise_en_charge') {
        header('Location: mes_demandes.php?error=Mission non prise en charge');
        exit;
    }
    
    // Terminer la mission et attribuer les points
    $pdo->beginTransaction();
    
    // Mettre à jour la demande
    $stmt = $pdo->prepare('UPDATE demandes SET statut = "terminee", date_cloture = NOW() WHERE id = :id');
    $stmt->execute(['id' => $id_demande]);
    
    // Créer ou mettre à jour le solde de points du bénévole
    $stmt = $pdo->prepare('INSERT INTO points (id_utilisateur, points) VALUES (:id, :points) ON DUPLICATE KEY UPDATE points = points + :points2');
    $stmt->execute([
        'id' => $demande['id_benevole'],
        'points' => $demande['points_attribues'],
        'points2' => $demande['points_attribues']
    ]);
    
    // Enregistrer la transaction
    $stmt = $pdo->prepare('INSERT INTO transactions_points (id_utilisateur, type, montant, description, id_demande) VALUES (:id, "gain", :montant, :description, :id_demande)');
    $stmt->execute([
        'id' => $demande['id_benevole'],
        'montant' => $demande['points_attribues'],
        'description' => 'Mission terminée : ' . $demande['titre'],
        'id_demande' => $id_demande
    ]);
    
    $pdo->commit();
    
    header('Location: mes_demandes.php?success=Mission terminée, ' . $demande['points_attribues'] . ' points attribués');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    header('Location: mes_demandes.php?error=Erreur serveur');
    exit;
}

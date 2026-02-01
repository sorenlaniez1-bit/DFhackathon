<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_proposition = intval($_GET['id'] ?? 0);

if ($id_proposition <= 0) {
    header('Location: mes_demandes.php?error=Proposition invalide');
    exit;
}

try {
    // Récupérer la proposition et la demande associée
    $stmt = $pdo->prepare('
        SELECT mr.*, d.id_demandeur, d.statut, d.points_attribues, d.id as id_demande
        FROM mises_en_relation mr
        JOIN demandes d ON mr.id_demande = d.id
        WHERE mr.id = :id
    ');
    $stmt->execute(['id' => $id_proposition]);
    $proposition = $stmt->fetch();
    
    if (!$proposition) {
        header('Location: mes_demandes.php?error=Proposition introuvable');
        exit;
    }
    
    // Vérifier que c'est bien le demandeur qui accepte
    if ($proposition['id_demandeur'] != $_SESSION['user_id']) {
        header('Location: mes_demandes.php?error=Non autorisé');
        exit;
    }
    
    // Vérifier que la demande est toujours ouverte
    if ($proposition['statut'] != 'ouverte') {
        header('Location: mes_demandes.php?error=Demande déjà prise en charge');
        exit;
    }
    
    // Accepter la proposition
    $pdo->beginTransaction();
    
    // Mettre à jour la proposition
    $stmt = $pdo->prepare('UPDATE mises_en_relation SET statut = "accepte" WHERE id = :id');
    $stmt->execute(['id' => $id_proposition]);
    
    // Refuser les autres propositions
    $stmt = $pdo->prepare('UPDATE mises_en_relation SET statut = "refuse" WHERE id_demande = :id_demande AND id != :id_proposition');
    $stmt->execute([
        'id_demande' => $proposition['id_demande'],
        'id_proposition' => $id_proposition
    ]);
    
    // Mettre à jour la demande
    $stmt = $pdo->prepare('UPDATE demandes SET statut = "prise_en_charge", id_benevole = :id_benevole, date_prise_en_charge = NOW() WHERE id = :id');
    $stmt->execute([
        'id_benevole' => $proposition['id_benevole'],
        'id' => $proposition['id_demande']
    ]);
    
    $pdo->commit();
    
    header('Location: mes_demandes.php?success=Bénévole accepté');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    header('Location: mes_demandes.php?error=Erreur serveur');
    exit;
}

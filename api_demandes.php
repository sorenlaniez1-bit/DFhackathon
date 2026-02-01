<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'demandes' => []]);
    exit;
}

try {
    // Récupérer toutes les demandes ouvertes (sauf celles de l'utilisateur)
    $stmt = $pdo->prepare('
        SELECT d.*, u.username, u.nom, u.prenom, u.ville,
        (SELECT COUNT(*) FROM mises_en_relation WHERE id_demande = d.id AND statut = "propose") as nb_propositions
        FROM demandes d
        JOIN utilisateurs u ON d.id_demandeur = u.id
        WHERE d.statut = "ouverte" AND d.id_demandeur != :user_id
        ORDER BY d.date_creation DESC
    ');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $demandes = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'demandes' => $demandes]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'demandes' => []]);
}

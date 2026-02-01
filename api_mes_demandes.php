<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'demandes' => []]);
    exit;
}

try {
    // Récupérer les demandes de l'utilisateur avec les propositions
    $stmt = $pdo->prepare('
        SELECT d.*, 
        (SELECT COUNT(*) FROM mises_en_relation WHERE id_demande = d.id AND statut = "propose") as nb_propositions
        FROM demandes d
        WHERE d.id_demandeur = :user_id
        ORDER BY d.date_creation DESC
    ');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $demandes = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'demandes' => $demandes]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'demandes' => []]);
}

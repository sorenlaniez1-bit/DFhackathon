<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'propositions' => []]);
    exit;
}

$id_demande = intval($_GET['id_demande'] ?? 0);

if ($id_demande <= 0) {
    echo json_encode(['success' => false, 'propositions' => []]);
    exit;
}

try {
    // Vérifier que la demande appartient à l'utilisateur
    $stmt = $pdo->prepare('SELECT id_demandeur FROM demandes WHERE id = :id');
    $stmt->execute(['id' => $id_demande]);
    $demande = $stmt->fetch();
    
    if (!$demande || $demande['id_demandeur'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'propositions' => []]);
        exit;
    }
    
    // Récupérer les propositions
    $stmt = $pdo->prepare('
        SELECT mr.*, u.username, u.nom, u.prenom, u.ville
        FROM mises_en_relation mr
        JOIN utilisateurs u ON mr.id_benevole = u.id
        WHERE mr.id_demande = :id_demande AND mr.statut = "propose"
        ORDER BY mr.date_proposition ASC
    ');
    $stmt->execute(['id_demande' => $id_demande]);
    $propositions = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'propositions' => $propositions]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'propositions' => []]);
}

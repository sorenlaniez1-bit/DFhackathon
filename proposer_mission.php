<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

// Récupérer l'id de la demande
$id_demande = intval($_POST['id_demande'] ?? 0);

if ($id_demande <= 0) {
    echo json_encode(['success' => false, 'message' => 'Demande invalide']);
    exit;
}

try {
    // Vérifier que la demande existe et est ouverte
    $stmt = $pdo->prepare('SELECT * FROM demandes WHERE id = :id AND statut = "ouverte"');
    $stmt->execute(['id' => $id_demande]);
    $demande = $stmt->fetch();
    
    if (!$demande) {
        echo json_encode(['success' => false, 'message' => 'Demande non disponible']);
        exit;
    }
    
    // Vérifier que l'utilisateur ne se propose pas sur sa propre demande
    if ($demande['id_demandeur'] == $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas accepter votre propre demande']);
        exit;
    }
    
    // Vérifier si l'utilisateur ne s'est pas déjà proposé
    $stmt = $pdo->prepare('SELECT * FROM mises_en_relation WHERE id_demande = :id_demande AND id_benevole = :id_benevole');
    $stmt->execute([
        'id_demande' => $id_demande,
        'id_benevole' => $_SESSION['user_id']
    ]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Vous vous êtes déjà proposé']);
        exit;
    }
    
    // Créer la proposition
    $stmt = $pdo->prepare('INSERT INTO mises_en_relation (id_demande, id_benevole) VALUES (:id_demande, :id_benevole)');
    $stmt->execute([
        'id_demande' => $id_demande,
        'id_benevole' => $_SESSION['user_id']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Proposition envoyée avec succès']);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}

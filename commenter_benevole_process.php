<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

$id_demande = isset($_POST['id_demande']) ? intval($_POST['id_demande']) : 0;
$commentaire = isset($_POST['commentaire']) ? trim($_POST['commentaire']) : '';

if ($id_demande <= 0 || empty($commentaire)) {
    header('Location: dashboard.php');
    exit();
}

// Vérifier que la demande existe, appartient au demandeur, est terminée et a un bénévole
$stmt = $pdo->prepare("SELECT id_demandeur, id_benevole FROM demandes 
                       WHERE id = :id AND id_demandeur = :user_id AND statut = 'terminee' AND id_benevole IS NOT NULL");
$stmt->execute([
    'id' => $id_demande,
    'user_id' => $_SESSION['user_id']
]);
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    header('Location: dashboard.php');
    exit();
}

// Ajouter ou modifier le commentaire
$stmt = $pdo->prepare("UPDATE demandes SET commentaire_benevole = :commentaire WHERE id = :id");
$stmt->execute([
    'commentaire' => $commentaire,
    'id' => $id_demande
]);

header('Location: dashboard.php?commentaire_success=1');
exit();

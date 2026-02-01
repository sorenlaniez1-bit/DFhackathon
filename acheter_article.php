<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_article = intval($_GET['id'] ?? 0);

if ($id_article <= 0) {
    header('Location: boutique.php?error=Article invalide');
    exit;
}

try {
    // Récupérer l'article
    $stmt = $pdo->prepare('SELECT * FROM boutique WHERE id = :id AND actif = 1');
    $stmt->execute(['id' => $id_article]);
    $article = $stmt->fetch();
    
    if (!$article) {
        header('Location: boutique.php?error=Article introuvable');
        exit;
    }
    
    // Vérifier le stock
    if ($article['stock'] !== null && $article['stock'] <= 0) {
        header('Location: boutique.php?error=Article en rupture de stock');
        exit;
    }
    
    // Récupérer le solde de l'utilisateur
    $stmt = $pdo->prepare('SELECT points FROM points WHERE id_utilisateur = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $solde = $stmt->fetch();
    $points_user = $solde ? $solde['points'] : 0;
    
    // Vérifier que l'utilisateur a assez de points
    if ($points_user < $article['prix_points']) {
        header('Location: boutique.php?error=Solde insuffisant');
        exit;
    }
    
    // Acheter l'article
    $pdo->beginTransaction();
    
    // Déduire les points
    $stmt = $pdo->prepare('UPDATE points SET points = points - :prix WHERE id_utilisateur = :id');
    $stmt->execute([
        'prix' => $article['prix_points'],
        'id' => $_SESSION['user_id']
    ]);
    
    // Enregistrer la transaction
    $stmt = $pdo->prepare('INSERT INTO transactions_points (id_utilisateur, type, montant, description) VALUES (:id, "depense", :montant, :description)');
    $stmt->execute([
        'id' => $_SESSION['user_id'],
        'montant' => $article['prix_points'],
        'description' => 'Achat : ' . $article['nom']
    ]);
    
    // Enregistrer l'achat
    $stmt = $pdo->prepare('INSERT INTO achats (id_utilisateur, id_article) VALUES (:id_user, :id_article)');
    $stmt->execute([
        'id_user' => $_SESSION['user_id'],
        'id_article' => $id_article
    ]);
    
    // Décrémenter le stock si défini
    if ($article['stock'] !== null) {
        $stmt = $pdo->prepare('UPDATE boutique SET stock = stock - 1 WHERE id = :id');
        $stmt->execute(['id' => $id_article]);
    }
    
    $pdo->commit();
    
    header('Location: boutique.php?success=Article acheté avec succès !');
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    header('Location: boutique.php?error=Erreur lors de l\'achat');
    exit;
}

<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer le solde de points de l'utilisateur
try {
    $stmt = $pdo->prepare('SELECT points FROM points WHERE id_utilisateur = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $solde = $stmt->fetch();
    $points_user = $solde ? $solde['points'] : 0;
    
    // Récupérer les articles de la boutique
    $stmt = $pdo->query('SELECT * FROM boutique WHERE actif = 1 ORDER BY prix_points ASC');
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $articles = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Boutique</h1>
        
        <div class="demande-card" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h2 style="margin: 0; color: white;">Votre solde</h2>
            <div style="font-size: 3em; font-weight: bold; margin: 10px 0;"><?php echo $points_user; ?> points</div>
        </div>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (empty($articles)) : ?>
            <div class="no-demandes">
                <p>Aucun article disponible pour le moment.</p>
            </div>
        <?php else : ?>
            <h2>Articles disponibles</h2>
            <?php foreach ($articles as $article) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($article['nom']); ?></h2>
                        <span class="statut-badge" style="background: #ff9800;"><?php echo $article['prix_points']; ?> points</span>
                    </div>
                    
                    <?php if ($article['description']) : ?>
                        <div class="demande-info">
                            <?php echo nl2br(htmlspecialchars($article['description'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($article['stock'] !== null) : ?>
                        <div class="demande-info">
                            <strong>Stock :</strong> <?php echo $article['stock'] > 0 ? $article['stock'] . ' disponible(s)' : 'Rupture de stock'; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="actions-buttons">
                        <?php if ($article['stock'] === null || $article['stock'] > 0) : ?>
                            <?php if ($points_user >= $article['prix_points']) : ?>
                                <button class="btn-voir-propositions" onclick="if(confirm('Acheter cet article pour <?php echo $article['prix_points']; ?> points ?')) window.location.href='acheter_article.php?id=<?php echo $article['id']; ?>'">
                                    Acheter
                                </button>
                            <?php else : ?>
                                <button class="btn-modifier" disabled style="opacity: 0.6; cursor: not-allowed;">
                                    Solde insuffisant
                                </button>
                            <?php endif; ?>
                        <?php else : ?>
                            <button class="btn-supprimer" disabled style="opacity: 0.6; cursor: not-allowed;">
                                Rupture de stock
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <h2>Historique des missions réalisés</h2>
        <?php
        try {
            $stmt = $pdo->prepare('SELECT * FROM transactions_points WHERE id_utilisateur = :id ORDER BY date DESC LIMIT 10');
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $transactions = $stmt->fetchAll();
        } catch (PDOException $e) {
            $transactions = [];
        }
        ?>
        
        <?php if (empty($transactions)) : ?>
            <div class="no-demandes">
                <p>Aucune transaction.</p>
            </div>
        <?php else : ?>
            <?php foreach ($transactions as $trans) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <span><?php echo htmlspecialchars($trans['description']); ?></span>
                        <span class="statut-badge <?php echo $trans['type'] === 'gain' ? 'statut-ouverte' : 'statut-depassee'; ?>">
                            <?php echo $trans['type'] === 'gain' ? '+' : '-'; ?><?php echo $trans['montant']; ?> points
                        </span>
                    </div>
                    <div class="demande-info" style="color: #999; font-size: 0.9em;">
                        <?php echo date('d/m/Y à H:i', strtotime($trans['date'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

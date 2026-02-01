<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les infos de l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Vérifier que l'utilisateur a le rôle demandeur
if (strpos($user['roles'], 'demandeur') === false) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une demande</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Créer une nouvelle demande</h1>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="creer_demande_process.php">
            <div class="form-group">
                <label for="titre">Titre de la demande *</label>
                <input type="text" id="titre" name="titre" required maxlength="255">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required rows="5" placeholder="Décrivez en détail ce dont vous avez besoin..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="ville">Ville *</label>
                <input type="text" id="ville" name="ville" required maxlength="100" placeholder="Ex: Paris, Lyon, Marseille...">
            </div>
            
            <div class="form-group">
                <label for="lieu">Adresse complète *</label>
                <input type="text" id="lieu" name="lieu" required maxlength="255" placeholder="Ex: 15 rue de la République">
                <p style="font-size: 14px; color: #4b5563; margin-top: 5px;">L'adresse ne sera visible que par le bénévole accepté</p>
            </div>
            
            <div class="form-group">
                <label for="duree">Durée estimée *</label>
                <select id="duree" name="duree" required>
                    <option value="">-- Sélectionnez la durée --</option>
                    <option value="0.5">0.5 heure</option>
                    <option value="1">1 heure</option>
                    <option value="1.5">1.5 heure</option>
                    <option value="2">2 heures</option>
                    <option value="2.5">2.5 heures</option>
                    <option value="3">3 heures</option>
                    <option value="3.5">3.5 heures</option>
                    <option value="4">4 heures</option>
                    <option value="5">5 heures</option>
                    <option value="6">6 heures</option>
                    <option value="7">7 heures</option>
                    <option value="8">8 heures</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date_limite">Date limite *</label>
                <input type="datetime-local" id="date_limite" name="date_limite" required>
            </div>
                      
            <button type="submit">Créer la demande</button>
        </form>
        
        <a href="dashboard.php" class="register-link">
            <button type="button" class="register-button">Retour au tableau de bord</button>
        </a>
    </div>
</body>
</html>

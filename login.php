<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Bouton de scroll vers le haut en haut -->
    <div class="scroll-button-container-top">
        <button class="scroll-up-btn" onclick="scrollUp()" aria-label="Remonter" style="display: none;">
            ▲ Haut
        </button>
    </div>

    <div class="login-container">
        <div class="header-section">
            <h1>Se Connecter</h1>
            <p class="subtitle">Entrez vos identifiants pour accéder à votre compte</p>
        </div>
        
        <?php
        // Afficher un message d'erreur si présent
        if (isset($_GET['error'])) {
            echo '<div class="error-message" role="alert">Identifiants incorrects</div>';
        }
        
        // Afficher un message de succès si présent
        if (isset($_GET['success'])) {
            echo '<div class="success-message" role="alert">Inscription réussie ! Vous pouvez vous connecter.</div>';
        }
        ?>

        <form method="POST" action="login_process.php">
            <div class="form-group">
                <label for="username">Identifiant</label>
                <p class="helper-text">Entrez votre nom d'utilisateur</p>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="username"
                    aria-required="true"
                    placeholder="Votre identifiant">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <p class="helper-text">Entrez votre mot de passe</p>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    aria-required="true"
                    placeholder="••••••">
            </div>

            <button type="submit" class="btn-primary">Se CONNECTER</button>
        </form>

        <div class="login-link-section">
            <p class="login-text">Pas encore de compte ?</p>
            <a href="register.php">
                <button type="button" class="btn-secondary">Créer un compte</button>
            </a>
        </div>
    </div>

    <!-- Bouton de scroll vers le bas en bas -->
    <div class="scroll-button-container-bottom">
        <button class="scroll-down-btn" onclick="scrollDown()" aria-label="Descendre">
            ▼ Appuyez ici pour défiler
        </button>
    </div>

    <script>
        function scrollDown() {
            window.scrollBy({
                top: 200,
                behavior: 'smooth'
            });
        }

        function scrollUp() {
            window.scrollBy({
                top: -200,
                behavior: 'smooth'
            });
        }

        // Afficher/cacher les boutons selon la position du scroll
        window.addEventListener('scroll', function() {
            const downBtn = document.querySelector('.scroll-down-btn');
            const upBtn = document.querySelector('.scroll-up-btn');
            
            // Bouton vers le bas : caché si on est tout en bas
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                downBtn.style.display = 'none';
            } else {
                downBtn.style.display = 'block';
            }
            
            // Bouton vers le haut : visible si on a scrollé
            if (window.scrollY > 50) {
                upBtn.style.display = 'block';
            } else {
                upBtn.style.display = 'none';
            }
        });
    </script>
</body>
</html>

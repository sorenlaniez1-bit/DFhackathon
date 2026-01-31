<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="header-section">
            <h1>Créer un Compte</h1>
            <p class="subtitle">Remplissez le formulaire ci-dessous pour vous inscrire</p>
        </div>
        
        <?php
        // Afficher un message d'erreur si présent
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'password') {
                echo '<div class="error-message" role="alert">Les mots de passe ne correspondent pas</div>';
            } elseif ($_GET['error'] == 'exists') {
                echo '<div class="error-message" role="alert">Cet identifiant existe déjà</div>';
            } else {
                echo '<div class="error-message" role="alert">Une erreur est survenue</div>';
            }
        }
        
        // Afficher un message de succès si présent
        if (isset($_GET['success'])) {
            echo '<div class="success-message" role="alert">Inscription réussie ! Vous pouvez vous connecter.</div>';
        }
        ?>

        <form method="POST" action="register_process.php">
            <div class="form-group">
                <label for="username">Identifiant (nom d'utilisateur)</label>
                <p class="helper-text">Choisissez un identifiant pour vous connecter</p>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="username"
                    aria-required="true"
                    placeholder="Ex: Jean_Dupont">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        required 
                        autocomplete="family-name"
                        aria-required="true"
                        placeholder="Votre nom">
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        required 
                        autocomplete="given-name"
                        aria-required="true"
                        placeholder="Votre prénom">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="age">Âge</label>
                    <input 
                        type="number" 
                        id="age" 
                        name="age" 
                        required 
                        min="1"
                        max="150"
                        aria-required="true"
                        placeholder="Ex: 75">
                </div>

                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input 
                        type="text" 
                        id="ville" 
                        name="ville" 
                        required 
                        autocomplete="address-level2"
                        aria-required="true"
                        placeholder="Votre ville">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <p class="helper-text">Entrez un mot de passe sécurisé (minimum 6 caractères)</p>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    aria-required="true"
                    placeholder="••••••">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <p class="helper-text">Retapez le même mot de passe</p>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    required 
                    autocomplete="new-password"
                    aria-required="true"
                    placeholder="••••••">
            </div>

            <button type="submit" class="btn-primary">S'INSCRIRE</button>
        </form>

        <div class="login-link-section">
            <p class="login-text">Vous avez déjà un compte ?</p>
            <a href="login.php">
                <button type="button" class="btn-secondary">Aller à la connexion</button>
            </a>
        </div>
    </div>

    <!-- Bouton de scroll pour les personnes âgées -->
    <div class="scroll-button-container">
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

        // Afficher/cacher le bouton selon la position du scroll
        window.addEventListener('scroll', function() {
            const btn = document.querySelector('.scroll-down-btn');
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'block';
            }
        });
    </script>
</body>
</html>

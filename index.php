<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fais ta BA ! - Plateforme d'entraide solidaire</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            padding: 20px 50px;
            background: #ffffff;
            border-bottom: 3px solid #000000;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-left {
            order: 1;
        }
        
        .nav-center {
            order: 2;
        }
        
        .nav-right {
            order: 3;
        }
        
        .logo {
            height: 60px;
        }
        
        .logo img {
            height: 100%;
            width: auto;
        }
        
        .nav-link {
            color: #000000;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            padding: 8px 16px;
            border: 3px solid transparent;
        }
        
        .nav-link:hover {
            border-color: #000000;
            background: #000000;
            color: #ffffff;
        }
        
        .main-content {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .hero-title {
            text-align: center;
            font-size: 52px;
            color: #000000;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            text-align: center;
            font-size: 24px;
            color: #000000;
            margin-bottom: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 600;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }
        
        .info-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            border: 4px solid #000000;
            display: flex;
            flex-direction: column;
        }
        
        .info-card h2 {
            font-size: 32px;
            color: #000000;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .info-card p {
            font-size: 20px;
            color: #000000;
            line-height: 1.8;
            margin-bottom: 30px;
            flex-grow: 1;
            font-weight: 600;
        }
        
        .info-card button {
            font-size: 20px;
            padding: 16px;
            font-weight: 700;
        }
        
        /* RESPONSIVE */
        @media screen and (max-width: 1024px) {
            .cards-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .info-card {
                max-width: 600px;
                margin: 0 auto;
            }
        }
        
        @media screen and (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                gap: 20px;
            }
            
            .logo {
                height: 45px;
            }
            
            .nav-link {
                font-size: 16px;
                padding: 6px 12px;
            }
            
            .main-content {
                margin: 30px auto;
                padding: 0 15px;
            }
            
            .hero-title {
                font-size: 36px;
            }
            
            .hero-subtitle {
                font-size: 18px;
                margin-bottom: 40px;
            }
            
            .info-card {
                padding: 25px;
            }
            
            .info-card h2 {
                font-size: 26px;
            }
            
            .info-card p {
                font-size: 17px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .navbar {
                padding: 10px 15px;
                gap: 15px;
            }
            
            .logo {
                height: 35px;
            }
            
            .nav-link {
                font-size: 14px;
                padding: 5px 10px;
            }
            
            .hero-title {
                font-size: 28px;
            }
            
            .hero-subtitle {
                font-size: 16px;
            }
            
            .info-card {
                padding: 20px;
            }
            
            .info-card h2 {
                font-size: 22px;
            }
            
            .info-card p {
                font-size: 16px;
            }
            
            .info-card button {
                font-size: 18px;
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="login.php" class="nav-link">Connexion</a>
        </div>
        <div class="nav-center">
            <div class="logo">
                <img src="img/logo.png" alt="Fais ta BA !">
            </div>
        </div>
        <div class="nav-right">
            <a href="register.php" class="nav-link">Inscription</a>
        </div>
    </nav>
    
    <div class="main-content">
        <h1 class="hero-title">FAIS TA BA !</h1>
        <p class="hero-subtitle">
            Rejoignez une communauté où chaque coup de main compte.<br> Demandez de l'aide ou aidez les autres et développez de nouvelles amitiés !
        </p>
        
        <div class="cards-container">
            <div class="info-card benevole">
                <h2>Vous voulez aider ?</h2>
                <p>
                    Devenez bénévole et réalisez des missions pour aider les personnes qui en ont besoin !
                </p>
                <a href="register.php" style="text-decoration: none;">
                    <button style="width: 100%; background: #059669; padding: 14px;">
                        Je veux aider
                    </button>
                </a>
            </div>
            
            <div class="info-card demandeur">
                <h2>Vous avez besoin d'aide ?</h2>
                <p>
                    Publiez une demande d'aide et recevez des propositions 
                    de bénévoles motivés et solidaires près de chez vous
                    <br>Laissez la communauté vous accompagner !
                </p>
                <a href="register.php" style="text-decoration: none;">
                    <button style="width: 100%; background: #2563eb; padding: 14px;">
                        Je demande de l'aide
                    </button>
                </a>
            </div>
            
            <div class="info-card les-deux">
                <h2>Les deux !</h2>
                <p>
                    Vous voulez à la fois recevoir de l'aide quand vous en avez besoin 
                    ET donner un coup de main quand vous le pouvez ? 
                    Rejoignez la communauté avec un double rôle !
                </p>
                <a href="register.php" style="text-decoration: none;">
                    <button style="width: 100%; background: #7c3aed; padding: 14px;">
                        Je m'inscris
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

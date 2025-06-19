<?php
// Rediriger automatiquement l'utilisateur après 5 secondes
header("refresh:5;url=commandes.php");
?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <meta http-equiv="refresh" content="5;url=commandes.php">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(49, 48, 48, 0.7);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            color: white;
            flex-wrap: wrap;
        }

        .logo {
            height: 50px;
            cursor: pointer;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .head {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .head:hover {
            color: #ccc;
        }

        .redirect-msg {
            margin-top: 100px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        footer {
            background-color: rgba(49, 48, 48, 0.7);
            color: white;
            font-size: 14px;
            align-items: center;
            height: 50px;
            justify-content: center;
            font-weight: 500;
            display: flex;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <img src="img/Frame_1-removebg-preview.png" alt="Logo" class="logo" />
    <div>
      <a href="index.html" class="head">Accueil</a>
      <a href="about.html" class="head">À propos</a>
      <a href="contact.html" class="head">Contact</a>
    </div>
</header>

<div class="redirect-msg">
    <p>Redirection vers la page des commandes en cours...</p>
</div>

<footer>
    <p>© 2025 Votre entreprise. Tous droits réservés.</p>
</footer>

</body>
</html>

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


        .redirect-msg {
            margin-top: 100px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="redirect-msg">
    <p>Redirection vers la page des commandes en cours...</p>
</div>
</body>
</html>

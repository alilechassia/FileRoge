<?php
// Redirection automatique vers commandes.php
header("refresh:2;url=details-commandes.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Redirecting</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      flex-direction: column;
      background-color: #f4f4f4;
      justify-content: center;
      align-items: center;
    }

    .container {
      text-align: center;
    }

    h1 {
      color: #333;
      margin-bottom: 20px;
    }

    .loading {
      color: #666;
      font-size: 18px;
    }


  </style>
</head>
<body>

  <div class="container">
    <h1>Welcome to the Dashboard</h1>
    <p class="loading">Redirecting to the orders page...</p>
  </div>

</body>
</html>

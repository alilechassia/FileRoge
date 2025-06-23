<?php
require 'connect.php';

$produit_id = isset($_GET['produit_id']) ? intval($_GET['produit_id']) : 0;
$prix_unitaire = isset($_GET['prix']) ? floatval($_GET['prix']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_commande'])) {
    $nom = trim($_POST['nom_client']);
    $tel = trim($_POST['telephone']);
    $produit_id = intval($_POST['produit_id']);
    $prix = floatval($_POST['prix_unitaire']);
    $quantite = intval($_POST['quantite']);
    $taille = trim($_POST['taille']);

    // Calcul du prix total de la commande
    $total = $prix * $quantite;

    try {
        $conn->beginTransaction();

        // Insérer les données dans la table des commandes
        $sql_commande = "INSERT INTO commandes (nom_client, telephone, mode_paiement, total_prix)
                         VALUES (:nom, :tel, 'cash', :total)";
        $stmt = $conn->prepare($sql_commande);
        $stmt->execute([
            ':nom' => $nom,
            ':tel' => $tel,
            ':total' => $total
        ]);

        // Récupérer l'ID de la commande insérée
        $commande_id = $conn->lastInsertId();

        $sql_detail = "INSERT INTO details_commande (commande_id, produit_id, quantité, prix_unitaire)
                       VALUES (:commande_id, :produit_id, :quantite, :prix)";
        $stmt = $conn->prepare($sql_detail);
        $stmt->execute([
            ':commande_id' => $commande_id,
            ':produit_id' => $produit_id,
            ':quantite' => $quantite,
            ':prix' => $prix
        ]);

        $conn->commit();

        // Préparer le message WhatsApp avec les infos de la commande
        $whatsapp_message = urlencode("New Order:\nName: $nom\nPhone: $tel\nSize: $taille\nQuantity: $quantite\nTotal: $total MAD");
        echo "<script>window.location.href='https://wa.me/212638417033?text=$whatsapp_message';</script>";
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
    }
    header {
      margin-bottom: 20px;
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
    form {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 15px;
      color: #333;
    }
    input[type="text"], input[type="number"], select {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #333;
      color: #fff;
      padding: 12px;
      border: none;
      margin-top: 20px;
      cursor: pointer;
      border-radius: 6px;
      width: 100%;
      font-size: 16px;
    }
    input[type="submit"]:hover {
      background-color: #444;
    }
    footer {
      margin-bottom: 40px;
      height: 50px;
      background-color: rgba(49, 48, 48, 0.7);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <header>
    <img src="img/Frame_1-removebg-preview.png" alt="Logo" class="logo" onclick="window.location.href='index.html'">
    <nav>
      <a href="index.html" class="head">Home</a>
      <a href="about.html" class="head">About</a>
      <a href="contact.html" class="head">Contact</a>
    </nav>
  </header>

  <form method="POST" action="commandes.php">
    <h2>🛒 Confirm Your Order</h2>
    <label>Full Name:</label>
    <input type="text" name="nom_client" required>
    <br><br>
    <label>Phone Number:</label>
    <input type="text" name="telephone" required>
    <br><br>
    <label>Size:</label>
    <select name="taille" required>
      <option value="">Select a size</option>
      <option value="M">M</option>
      <option value="L">L</option>
      <option value="XL">XL</option>
      <option value="XXL">XXL</option>
    </select>
    <br><br>
    <label>Quantity:</label>
    <input type="number" name="quantite" min="1" value="1" required>

    <input type="hidden" name="produit_id" value="<?= $produit_id; ?>">
    <input type="hidden" name="prix_unitaire" value="<?= $prix_unitaire; ?>">

    <input type="submit" name="submit_commande" value="Confirm Order">
  </form>

  <footer>
    <p>© 2025 Your Company. Tous droits réservés.</p>
  </footer>
</body>
</html>

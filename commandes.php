<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$produit_id = isset($_GET['produit_id']) ? intval($_GET['produit_id']) : 0;
$prix_unitaire = isset($_GET['prix']) ? floatval($_GET['prix']) : 0;

if (isset($_POST['submit_commande'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom_client']);
    $tel = mysqli_real_escape_string($conn, $_POST['telephone']);
    $produit_id = intval($_POST['produit_id']);
    $prix = floatval($_POST['prix_unitaire']);
    $quantite = intval($_POST['quantite']);
    $taille = mysqli_real_escape_string($conn, $_POST['taille']);

    $total = $prix * $quantite;

    $sql_commande = "INSERT INTO commandes (nom_client, telephone, mode_paiement, total_prix)
                     VALUES ('$nom', '$tel', 'cash', $total)";

    if (mysqli_query($conn, $sql_commande)) {
        $commande_id = mysqli_insert_id($conn);
        $sql_detail = "INSERT INTO details_commande (commande_id, produit_id, quantité, prix_unitaire)
                       VALUES ($commande_id, $produit_id, $quantite, $prix)";

        if (mysqli_query($conn, $sql_detail)) {
            $whatsapp_message = urlencode("New Order:\nName: $nom\nPhone: $tel\nSize: $taille\nQuantity: $quantite\nTotal: $total MAD");
            echo "<script>window.location.href='https://wa.me/212638417033?text=$whatsapp_message';</script>";
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Error in details_commande: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error in commandes: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
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
      padding: 40px 20px;
      background-color: #f9f9f9;
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
  </style>
</head>
<body>

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

    <input type="submit" name="submit_commande" value="Confirm Order" >
  </form>

</body>
</html>

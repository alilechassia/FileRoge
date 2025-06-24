<?php
require 'connect.php';

// récupérer les commandes et leurs détails
$sql = "
    SELECT 
        c.id AS commande_id,
        c.nom_client,
        c.telephone,
        c.total_prix,
        dc.quantité,
        dc.prix_unitaire,
        p.nom AS nom_produit,
        p.image_url
    FROM commandes c
    JOIN details_commande dc ON c.id = dc.commande_id
    JOIN produits p ON dc.produit_id = p.id
    ORDER BY c.id DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Ajouter chaque produit lié à la commande au tableau 
$grouped = [];
foreach ($commandes as $item) {
    $grouped[$item['commande_id']]['info'] = [
        'nom_client' => $item['nom_client'],
        'telephone' => $item['telephone'],
        'total_prix' => $item['total_prix']
    ];
    $grouped[$item['commande_id']]['produits'][] = [
        'nom_produit' => $item['nom_produit'],
        'image_url' => $item['image_url'],
        'quantite' => $item['quantité'],
        'prix_unitaire' => $item['prix_unitaire']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📦 Orders Overview</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;;
      margin: 0;
      padding: 30px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 40px;
    }

    .commande {
      background: #ffffff;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 35px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }

    .commande h2 {
      color: #444;
      margin-bottom: 10px;
    }

    .commande-info {
      margin-bottom: 20px;
    }

    .commande-info p {
      margin: 5px 0;
      color: #555;
    }

    .produit {
      display: flex;
      gap: 15px;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }

    .produit img {
      width: 90px;
      height: 90px;
      border-radius: 8px;
      object-fit: cover;
    }

    .produit-details {
      flex: 1;
    }

    .produit-details p {
      margin: 4px 0;
      color: #333;
    }

    .total {
      text-align: right;
      margin-top: 15px;
      font-weight: bold;
      color: #1c1c1c;
      font-size: 17px;
    }

    @media (max-width: 600px) {
      .produit {
        flex-direction: column;
        align-items: flex-start;
      }

      .produit img {
        margin-bottom: 10px;
      }

      .total {
        text-align: left;
      }
    }
  </style>
</head>
<body>

  <h1>📦 All Orders</h1>

  <?php foreach ($grouped as $id => $commande): ?>
    <div class="commande">
      <h2>Order #<?= $id ?></h2>
      <div class="commande-info">
        <p><strong>Name:</strong> <?= htmlspecialchars($commande['info']['nom_client']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($commande['info']['telephone']) ?></p>
      </div>

      <?php foreach ($commande['produits'] as $prod): ?>
        <div class="produit">
          <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['nom_produit']) ?>">
          <div class="produit-details">
            <p><strong>Product:</strong> <?= htmlspecialchars($prod['nom_produit']) ?></p>
            <p><strong>Quantity:</strong> <?= $prod['quantite'] ?></p>
            <p><strong>Price:</strong> <?= number_format($prod['prix_unitaire'], 2) ?> MAD</p>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="total">Total: <?= number_format($commande['info']['total_prix'], 2) ?> MAD</div>
    </div>
  <?php endforeach; ?>

</body>
</html>

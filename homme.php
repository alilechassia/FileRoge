<?php
require 'connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$size = isset($_GET['taille']) ? trim($_GET['taille']) : '';
$price_filter = isset($_GET['prix_filter']) ? trim($_GET['prix_filter']) : '';

$sql = "SELECT * FROM produits WHERE genre = 'homme'";
$params = [];

if (!empty($search)) {
    $sql .= " AND nom LIKE :search";
    $params['search'] = '%' . $search . '%';
}

if (!empty($size)) {
    $sql .= " AND FIND_IN_SET(:taille, taille)";
    $params['taille'] = $size;
}

if ($price_filter === 'moins_100') {
    $sql .= " AND prix < 100";
} elseif ($price_filter === 'plus_100') {
    $sql .= " AND prix >= 100";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="FemmeHomme.css"/>
  <title>Men's Products</title>
</head>
<body>

  <video autoplay muted loop class="background-video">
    <source src="img/WhatsApp Video 2025-03-27 at 01.27.59.mp4" type="video/mp4" />
  </video>

  <header>
    <img src="img/Frame_1-removebg-preview.png" alt="Logo" class="logo" />
    <div>
      <a href="index.html" class="head">Home</a>
      <a href="about.html" class="head">About</a>
      <a href="contact.html" class="head">Contact</a>
    </div>
  </header>

  <form method="GET" action="homme.php" style="text-align:center; margin: 30px 0; position: relative; z-index: 10;">
    <input type="text" name="search" placeholder="Search products..."
        value="<?= htmlspecialchars($search); ?>"
        style="padding: 10px; width: 200px; border-radius: 8px; border: 1px solid #ccc;" />

    <select name="taille" style="padding: 10px; border-radius: 8px; margin-left: 10px;">
        <option value="">All sizes</option>
        <option value="L" <?= $size == 'L' ? 'selected' : '' ?>>L</option>
        <option value="XL" <?= $size == 'XL' ? 'selected' : '' ?>>XL</option>
        <option value="XXL" <?= $size == 'XXL' ? 'selected' : '' ?>>XXL</option>
    </select>

    <select name="prix_filter" style="padding: 10px; border-radius: 8px; margin-left: 10px;">
        <option value="">All prices</option>
        <option value="moins_100" <?= $price_filter == 'moins_100' ? 'selected' : '' ?>>Price < 100 MAD</option>
        <option value="plus_100" <?= $price_filter == 'plus_100' ? 'selected' : '' ?>>Price ≥ 100 MAD</option>
    </select>

    <button type="submit" style="padding: 10px 20px; border: none; border-radius: 8px; background-color: #333; color: white;">
        Filter
    </button>
  </form>

  <div class="content">
    <h1>Welcome to our shop</h1>
    <p>Discover the best deals</p>
  </div>

  <div class="produits">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $row): ?>
            <div class="produit">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" />
                <?php if (!empty($row['image_hover'])): ?>
                    <img class="product-hover-image" src="<?= htmlspecialchars($row['image_hover']) ?>" alt="hover image" />
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['nom']) ?></h3>
                <p><?= htmlspecialchars($row['prix']) ?> MAD</p>
                <a href="details.php?produit_id=<?= htmlspecialchars($row['id']) ?>" style="color:#000;">More details</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">No products found.</p>
    <?php endif; ?>
  </div>

  <footer style="background-color: rgba(49, 48, 48, 0.7); color: white; font-size: 14px; align-items: center; height: 50px; justify-content: center; font-weight: 500; display: flex;">
    <p>© 2025 Your Company. All rights reserved.</p>
  </footer>

</body>
</html>

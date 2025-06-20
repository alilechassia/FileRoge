<?php
require 'connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$size = isset($_GET['taille']) ? trim($_GET['taille']) : '';
$price_filter = isset($_GET['prix_filter']) ? trim($_GET['prix_filter']) : '';

$sql = "SELECT * FROM produits WHERE genre = 'femme'"; // Changed genre to 'femme'
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
    <title>Women's Products</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Style de la vidéo en arrière-plan */
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 800px;
            object-fit: cover;
            z-index: -1;
        }

        /* Style du header */
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 10;
            justify-content: space-between;
        }

        .head {
            color: #ffffff;
            text-decoration: none;
            margin-right: 20px;
        }

        .logo {
            width: 180px;
            height: 70px;
        }

        /* Style du contenu */
        .content {
            position: relative;
            z-index: 1; 
            text-align: center;
            color: white;
            padding-top: 150px;
        }

        /* Style des produits */
        .produits {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            padding-top: 250px; /* Ajoute cet espace pour descendre les produits */
            margin-top: 400px; /* Tu peux ajuster si nécessaire */
        }

        .produit {
            position: relative;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
        }

        .produit img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: opacity 0.3s ease; /* تأثير ناعم عند التبديل */
        }

        /* صورة hover تكون مخفية في البداية */
        .product-hover-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0; /* إخفاء الصورة */
            transition: opacity 0.3s ease;
        }

        /* عند تمرير الفأرة، تختفي الصورة الأصلية وتظهر صورة hover */
        .produit:hover img:first-child {
            opacity: 0; /* إخفاء الصورة الأصلية */
        }

        .produit:hover .product-hover-image {
            opacity: 1; /* إظهار صورة hover */
        }
    </style>
</head>
<body>

    <video autoplay muted loop class="background-video">
        <source src="img/background.mp4" type="video/mp4" />
    </video>

    <header>
        <img src="img/Frame_1-removebg-preview.png" alt="Logo" class="logo" />
        <div>
            <a href="index.html" class="head">Home</a>
            <a href="about.html" class="head">About</a>
            <a href="contact.html" class="head">Contact</a>
        </div>
    </header>

    <form method="GET" action="femme.php" style="text-align:center; margin: 30px 0; position: relative; z-index: 10;">
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
                    <a href="details.php?produit_id=<?= htmlspecialchars($row['id']) ?>" style="color:blue; text-decoration:underline;">More details</a>
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

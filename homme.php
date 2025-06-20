<?php
// Inclut le fichier de connexion à la base de données.
require 'connect.php';

// --- Récupération et Nettoyage des Paramètres de Recherche ---
// Vérifie si un terme de recherche est présent dans l'URL (via GET) et le nettoie.
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
// Vérifie si une taille est présente dans l'URL et la nettoie.
$size = isset($_GET['taille']) ? trim($_GET['taille']) : '';
// Vérifie si un filtre de prix est présent dans l'URL et le nettoie.
$price_filter = isset($_GET['prix_filter']) ? trim($_GET['prix_filter']) : '';

// --- Construction de la Requête SQL ---
// Initialise la requête SQL pour sélectionner les produits de genre 'homme'.
$sql = "SELECT * FROM produits WHERE genre = 'homme'";
// Initialise un tableau pour stocker les paramètres de la requête préparée.
$params = [];

// Ajoute une condition de recherche par nom si un terme de recherche est fourni.
if (!empty($search)) {
    $sql .= " AND nom LIKE :search";
    $params['search'] = '%' . $search . '%'; // Utilise % pour la recherche partielle.
}

// Ajoute une condition de filtre par taille si une taille est sélectionnée.
if (!empty($size)) {
    $sql .= " AND FIND_IN_SET(:taille, taille)"; // FIND_IN_SET est utile pour les colonnes contenant des listes de valeurs.
    $params['taille'] = $size;
}

// Ajoute des conditions de filtre par prix.
if ($price_filter === 'moins_100') {
    $sql .= " AND prix < 100";
} elseif ($price_filter === 'plus_100') {
    $sql .= " AND prix >= 100";
}

// --- Exécution de la Requête Préparée ---
// Prépare la requête SQL pour éviter les injections SQL.
$stmt = $conn->prepare($sql);
// Exécute la requête avec les paramètres liés.
$stmt->execute($params);
// Récupère tous les résultats de la requête sous forme de tableau associatif.
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Men's Products</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 800px;
            object-fit: cover;
            z-index: -1;
        }

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

        .content {
            position: relative;
            z-index: 1; 
            text-align: center;
            color: white;
            padding-top: 150px;
        }

        .produits {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            padding-top: 250px;
            margin-top: 400px;
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
            transition: opacity 0.3s ease;
        }

        .product-hover-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .produit:hover img:first-child {
            opacity: 0;
        }

        .produit:hover .product-hover-image {
            opacity: 1;
        }
    </style>
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
        <?php
        // Vérifie si des produits ont été trouvés dans la base de données.
        if (count($products) > 0):
            // Boucle à travers chaque produit pour l'afficher.
            foreach ($products as $row):
        ?>
                <div class="produit">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" />
                    <?php
                    // Affiche l'image de survol (hover) si elle existe.
                    if (!empty($row['image_hover'])):
                    ?>
                        <img class="product-hover-image" src="<?= htmlspecialchars($row['image_hover']) ?>" alt="hover image" />
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($row['nom']) ?></h3>
                    <a href="details.php?produit_id=<?= htmlspecialchars($row['id']) ?>" style="color:blue; text-decoration:underline;">More details</a>
                </div>
        <?php
            endforeach;
        else:
        ?>
            <p style="text-align:center;">No products found.</p>
        <?php endif; ?>
    </div>

    <footer style="background-color: rgba(49, 48, 48, 0.7); color: white; font-size: 14px; align-items: center; height: 50px; justify-content: center; font-weight: 500; display: flex;">
        <p>© 2025 Your Company. All rights reserved.</p>
    </footer>

</body>
</html>

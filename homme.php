
<?php 
require 'connect.php';

$type_filter = isset($_GET['type']) ? trim($_GET['type']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM produits WHERE genre = 'femme'";
$params = [];

// Filtre par nom
if (!empty($search)) {
    $sql .= " AND nom LIKE :search";
    $params['search'] = '%' . $search . '%';
}

// Filtre par type
if (!empty($type_filter)) {
    $sql .= " AND nom LIKE :type";
    $params['type'] = '%' . $type_filter . '%';
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
        .order-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        .order-button:hover {
            background-color: #444;
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
        <input type="text" name="search" placeholder="Search for products..."
            value="<?= htmlspecialchars($search); ?>"
            style="padding: 10px; width: 200px; border-radius: 8px; border: 1px solid #ccc;" />

        <select name="type" style="padding: 10px; border-radius: 8px; margin-left: 10px;">
        <option value="">All typess</option>
        <option value="T-shirt" <?= $type_filter == 'T-shirt' ? 'selected' : '' ?>>T-shirt</option>
        <option value="Jeans" <?= $type_filter == 'Jeans' ? 'selected' : '' ?>>Jeans</option>
        <option value="Sweatshirt" <?= $type_filter == 'Sweatshirt' ? 'selected' : '' ?>>Sweatshirt</option>
        <option value="Coat" <?= $type_filter == 'Coat' ? 'selected' : '' ?>>Manteau</option>
        <option value="Dress" <?= $type_filter == 'Dress' ? 'selected' : '' ?>>Robe</option>
        <option value="Skirt" <?= $type_filter == 'Skirt' ? 'selected' : '' ?>>Jupe</option>
        <option value="Blouse" <?= $type_filter == 'Blouse' ? 'selected' : '' ?>>Blouse</option>
        <option value="Cardigan" <?= $type_filter == 'Cardigan' ? 'selected' : '' ?>>Cardigan</option>
        <option value="Hoodie" <?= $type_filter == 'Hoodie' ? 'selected' : '' ?>>Hoodie</option>
        <option value="Dress" <?= $type_filter == 'Dress' ? 'selected' : '' ?>>Dress</option>
        <option value="Kimono" <?= $type_filter == 'Kimono' ? 'selected' : '' ?>>Kimono</option>
        <option value="Pants" <?= $type_filter == 'Pants' ? 'selected' : '' ?>>Pants</option>
        <option value="Jumpsuit" <?= $type_filter == 'Jumpsuit' ? 'selected' : '' ?>>Jumpsuit</option>
        <option value="Tunic" <?= $type_filter == 'Tunic' ? 'selected' : '' ?>>Tunic</option>
        <option value="Jacket" <?= $type_filter == 'Jacket' ? 'selected' : '' ?>>Jacket</option>
       
    </select>


        <button type="submit" style="padding: 10px 20px; border: none; border-radius: 8px; background-color: #333; color: white;">
            Filtrer
        </button>
    </form>

    <div class="content">
        <h1>Welcome to our shop</h1>
        <p>Discover the best deals</p>
    </div>

    <div class="produits">
        <?php
        if (count($products) > 0):
            foreach ($products as $row):
        ?>
                <div class="produit">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" />

                    <?php if (!empty($row['image_hover'])): ?>
                        <img class="product-hover-image" src="<?= htmlspecialchars($row['image_hover']) ?>" alt="hover image" />
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($row['nom']) ?></h3>

                    <a href="details.php?produit_id=<?= htmlspecialchars($row['id']) ?>" style="display:inline-block; margin-top:5px; color:black; text-decoration:underline;">
                        More details
                    </a><br>

                    <a href="commandes.php?produit_id=<?= htmlspecialchars($row['id']) ?>&prix=<?= htmlspecialchars($row['prix']) ?>" class="order-button">
                        Buy now
                    </a>
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

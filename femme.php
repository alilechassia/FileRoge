<?php
require 'connect.php';

if (isset($_GET['produit_id']) && is_numeric($_GET['produit_id'])) {
    $produit_id = $_GET['produit_id'];

    $sql = "SELECT * FROM produits WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $produit_id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produit):
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du produit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        }

        body {
        background-color: #f9f9f9;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        }

        /* Header stylé en marron */
        header {
            background-color: rgba(49, 48, 48, 0.7);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            color: white;
            flex-wrap: wrap;
        }

        .logo {
            height: 50px;
            cursor: pointer;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        .head {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .head:hover {
        color: #ccc;
        }

        h1 {
            text-align: center;
            margin: 30px 0 15px;
        }

        .image-container {
            width: 350px;
            height: 400px;
            margin: 0 auto;
            position: relative;
        }

        .image-container img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.5s ease;
        }

        .image-container img.hidden {
            opacity: 0;
        }

        .image-thumbnails {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        .image-thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .image-thumbnails img:hover {
            transform: scale(1.1);
        }

        .product-details {
            text-align: center;
            margin-top: 20px;
        }

        .price {
            font-size: 22px;
            color:rgb(227, 25, 25);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .back-button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(49, 48, 48, 0.7);
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: rgba(89, 84, 84, 0.7);
        }

        footer {
            background-color: rgba(49, 48, 48, 0.7);
            color: white;
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
            margin-top: 40px;
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

<h1><?= htmlspecialchars($produit['nom']) ?></h1>

<div class="image-container">
    <img src="<?= htmlspecialchars($produit['image_url']) ?>" id="mainImage" alt="Image principale">
    <img src="<?= htmlspecialchars($produit['image_hover']) ?>" id="hoverImage" class="hidden" alt="Image hover">
</div>

<div class="image-thumbnails">
    <?php
    $images = [$produit['image_url'], $produit['image_hover']];
    foreach ($images as $img) {
        echo "<img src='".htmlspecialchars($img)."' class='thumbnail' data-image='".htmlspecialchars($img)."' />";
    }
    ?>
</div>

<div class="product-details">
    <p class="price"><?= number_format($produit['prix'], 2) ?> MAD</p>
    <p><strong>Description :</strong> <?= htmlspecialchars($produit['description'] ?? 'Aucune description.') ?></p>
    <p><strong>Tailles :</strong> <?= htmlspecialchars($produit['taille']) ?></p>

    <a href="javascript:history.back()" class="back-button">← Retour</a>
</div>

<footer>
    <p>© 2025 Votre Boutique. Tous droits réservés.</p>
</footer>

<script>
    const mainImage = document.getElementById('mainImage');
    const hoverImage = document.getElementById('hoverImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            const imageSrc = this.getAttribute('data-image');
            mainImage.src = imageSrc;
            hoverImage.classList.add('hidden');
        });
    });

    mainImage.addEventListener('mouseover', () => {
        hoverImage.classList.remove('hidden');
    });

    mainImage.addEventListener('mouseout', () => {
        hoverImage.classList.add('hidden');
    });
</script>

</body>
</html>
<?php
    else:
        echo "<p style='text-align:center; color:red;'>Produit introuvable.</p>";
    endif;
} else {
    echo "<p style='text-align:center; color:red;'>ID invalide.</p>";
}
?>

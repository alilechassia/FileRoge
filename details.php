<?php
require 'connect.php';

if (isset($_GET['produit_id']) && is_numeric($_GET['produit_id'])) {
    $produit_id = $_GET['produit_id'];

    $sql = "SELECT * FROM produits WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $produit_id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        // Display the product
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="details.css">
            <title>Product Details</title>
        </head>
        <body>
            <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>

            <!-- Main image with hover transition -->
            <div class="image-container">
                <img src="<?php echo htmlspecialchars($produit['image_url']); ?>" alt="Product Image" id="mainImage">
                <img src="<?php echo htmlspecialchars($produit['image_hover']); ?>" alt="Hover Image" class="hidden" id="hoverImage">
            </div>

            <!-- Thumbnails -->
            <div class="image-thumbnails">
                <?php 
                $images = [$produit['image_url'], $produit['image_hover']];
                foreach ($images as $image) {
                    echo "<img src='$image' alt='Thumbnail' class='thumbnail' data-image='$image'>";
                }
                ?>
            </div>

            <p class="price"><strong>Price:</strong> <?php echo htmlspecialchars($produit['prix']); ?> MAD</p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($produit['description'] ?? 'No description'); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($produit['taille']); ?></p>
            <a href="javascript:history.back()" class="back-button">Back</a>

            <script>
                const mainImage = document.getElementById('mainImage');
                const hoverImage = document.getElementById('hoverImage');
                const thumbnails = document.querySelectorAll('.thumbnail');

                thumbnails.forEach(thumbnail => {
                    thumbnail.addEventListener('click', function() {
                        const imageSrc = this.getAttribute('data-image');
                        if (imageSrc === mainImage.src) {
                            hoverImage.classList.add('hidden');
                        } else {
                            hoverImage.classList.remove('hidden');
                        }
                        mainImage.src = imageSrc;
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
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>

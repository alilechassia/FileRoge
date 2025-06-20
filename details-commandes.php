<?php
require 'connect.php';

// vérifier si commande_id موجود
if (isset($_GET['commande_id']) && is_numeric($_GET['commande_id'])) {
    $commande_id = intval($_GET['commande_id']);

    // جلب تفاصيل الطلب
    $sql = "
        SELECT 
            p.nom AS nom_produit,
            p.image_url,
            dc.quantité,
            dc.prix_unitaire
        FROM details_commande dc
        JOIN produits p ON dc.produit_id = p.id
        WHERE dc.commande_id = :commande_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $stmt->execute();
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$details) {
        echo "<p>Aucun produit trouvé pour cette commande.</p>";
        exit;
    }
} else {
    echo "<p>ID de commande invalide.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        img {
            width: 80px;
            height: auto;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>

    <h1>Détails de la commande #<?= htmlspecialchars($commande_id) ?></h1>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($details as $item): 
                $ligne_total = $item['quantité'] * $item['prix_unitaire'];
                $total += $ligne_total;
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Image produit"></td>
                <td><?= htmlspecialchars($item['nom_produit']) ?></td>
                <td><?= htmlspecialchars($item['quantité']) ?></td>
                <td><?= htmlspecialchars(number_format($item['prix_unitaire'], 2)) ?> MAD</td>
                <td><?= number_format($ligne_total, 2) ?> MAD</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total général</strong></td>
                <td><strong><?= number_format($total, 2) ?> MAD</strong></td>
            </tr>
        </tbody>
    </table>

    <a class="back" href="commandes.php">&larr; Retour aux commandes</a>

</body>
</html>

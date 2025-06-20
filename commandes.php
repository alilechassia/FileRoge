<?php
require 'connect.php';

// Supprimer une commande
if (isset($_GET['delete'])) {
    $commande_id = intval($_GET['delete']);

    // Supprimer les détails de la commande
    $deleteDetails = $conn->prepare("DELETE FROM details_commande WHERE commande_id = :commande_id");
    $deleteDetails->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteDetails->execute();

    // Supprimer la commande elle-même
    $deleteCommande = $conn->prepare("DELETE FROM commandes WHERE id = :commande_id");
    $deleteCommande->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteCommande->execute();
}

// Récupérer toutes les commandes
$sql = "SELECT * FROM commandes ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-danger {
            color: red;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<h1>Liste des Commandes</h1>

<?php if (count($commandes) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Produit</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($commandes as $commande): ?>
            <?php
            // Récupérer l'image d’un produit lié à la commande
            $imageStmt = $conn->prepare("
                SELECT p.image_url 
                FROM details_commande dc
                JOIN produits p ON dc.produit_id = p.id
                WHERE dc.commande_id = :commande_id
                LIMIT 1
            ");
            $imageStmt->execute(['commande_id' => $commande['id']]);
            $image = $imageStmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td><?= htmlspecialchars($commande['id']) ?></td>
                <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                <td>
                    <?php if ($image): ?>
                        <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Produit">
                    <?php else: ?>
                        Aucun produit
                    <?php endif; ?>
                </td>
                <td>
                    <a href="details_commandes.php?commande_id=<?= $commande['id'] ?>">View details</a> |
                    <a class="btn-danger" href="commandes.php?delete=<?= $commande['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucune commande trouvée.</p>
<?php endif; ?>

</body>
</html>

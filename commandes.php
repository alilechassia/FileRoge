<?php
require 'connect.php';

// supprimer une commande
if (isset($_GET['delete'])) {
    $commande_id = intval($_GET['delete']);

    // supprimer les détails de la commande
    $deleteDetails = $conn->prepare("DELETE FROM details_commande WHERE commande_id = :commande_id");
    $deleteDetails->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteDetails->execute();

    // supprimer la commande elle-même
    $deleteCommande = $conn->prepare("DELETE FROM commandes WHERE id = :commande_id");
    $deleteCommande->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteCommande->execute();
}

// récupérer et afficher toutes les commandes
$sql = "SELECT * FROM commandes ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
</head>
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
</style>
<body>

    <h1>Order List</h1>

    <?php if (count($commandes) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>

             <!-- afficher chaque commande -->
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['id']) ?></td>
                    <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                    <td>

                    <!-- voir les détails ou supprimer -->
                        <a href="details.php?commande_id=<?= $commande['id'] ?>">View details</a> |
                        <a class="btn-danger" href="commandes.php?delete=<?= $commande['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

</body>
</html>

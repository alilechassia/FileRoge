<?php
require 'connect.php';

if (isset($_GET['delete'])) {
    $commande_id = intval($_GET['delete']);

    // First, delete the order details
    $deleteDetails = $conn->prepare("DELETE FROM details_commande WHERE commande_id = :commande_id");
    $deleteDetails->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteDetails->execute();

    // Then, delete the order itself
    $deleteCommande = $conn->prepare("DELETE FROM commandes WHERE id = :commande_id");
    $deleteCommande->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
    $deleteCommande->execute();
}

// 📌 Fetch all orders
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
    <link rel="stylesheet" href="commandes.css">
    <title>Order List</title>
</head>
<body>

    <h1>Order List</h1>

    <?php if (count($commandes) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['id']) ?></td>
                    <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                    <td>
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

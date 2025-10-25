<?php
include 'config.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}


$user_id = $_SESSION['user']['id'];

$orders_query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders_query->bind_param("i", $user_id);
$orders_query->execute();
$orders_result = $orders_query->get_result();

$orders = [];
while ($order = $orders_result->fetch_assoc()) {

    $items_query = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $items_query->bind_param("i", $order['id']);
    $items_query->execute();
    $items_result = $items_query->get_result();

    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }

    $order['items'] = $items;
    $orders[] = $order;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Aklny</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-container">
    <h2>My Orders</h2>

    <?php if (empty($orders)) : ?>
        <p>You have no orders yet. <a href="restaurants.php">Order Now</a></p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Order #<?= $order['id'] ?> | <?= htmlspecialchars($order['payment_method']) ?> | <?= $order['created_at'] ?></h3>
                <table>
                    <tr>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['food_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['price'] ?> EGP</td>
                            <td><?= $item['quantity'] * $item['price'] ?> EGP</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?= $order['total_price'] ?> EGP</th>
                    </tr>
                </table>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="restaurants.php">Back to Restaurants</a> | <a href="logout.php">Logout</a></p>
</div>
</body>
</html>

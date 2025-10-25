<?php
include 'config.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}


if (!isset($_SESSION['order']) || empty($_SESSION['order'])) {
    header("Location: restaurants.php");
    exit;
}

$order_items = [];
$total = 0;
foreach ($_SESSION['order'] as $id => $item) {
    if (isset($item['selected']) && $item['selected'] == 1) {
        $qty = max(1, intval($item['quantity']));
        $price = floatval($item['price']);
        $subtotal = $qty * $price;
        $total += $subtotal;

        $order_items[] = [
            'id' => $id,
            'name' => $item['name'],
            'quantity' => $qty,
            'price' => $price,
            'subtotal' => $subtotal
        ];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];


    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $_SESSION['user']['id'], $total, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($order_items as $oi) {
        $stmt_item->bind_param("isid", $order_id, $oi['name'], $oi['quantity'], $oi['price']);
        $stmt_item->execute();
    }


    unset($_SESSION['order']);

    echo "<p>Order placed successfully! Payment method: $payment_method</p>";
    echo "<p><a href='restaurants.php'>Back to Restaurants</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Order - Aklny</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-container">
    <h2>Review Your Order</h2>

    <?php if (empty($order_items)) : ?>
        <p>No items selected. <a href="restaurants.php">Go back</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Food</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['price'] ?> EGP</td>
                    <td><?= $item['subtotal'] ?> EGP</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Total</th>
                <th><?= $total ?> EGP</th>
            </tr>
        </table>

        <h3>Select Payment Method</h3>
        <form method="POST">
            <input type="radio" name="payment_method" value="Cash" required> Cash<br>
            <input type="radio" name="payment_method" value="Visa" required> Visa<br><br>

            <div id="visa-info" style="display:none;">
                <label>Card Number:</label><br>
                <input type="text" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX"><br>
                <label>Expiry:</label><br>
                <input type="text" name="expiry" placeholder="MM/YY"><br>
                <label>CVV:</label><br>
                <input type="text" name="cvv" placeholder="123"><br>
            </div>

            <button type="submit">Pay</button>
        </form>
    <?php endif; ?>

    <p><a href="restaurants.php">Back to Restaurants</a> | <a href="logout.php">Logout</a></p>
</div>

<script>
const visaRadio = document.querySelector('input[value="Visa"]');
const cashRadio = document.querySelector('input[value="Cash"]');
const visaInfo = document.getElementById('visa-info');

visaRadio.addEventListener('change', () => visaInfo.style.display = 'block');
cashRadio.addEventListener('change', () => visaInfo.style.display = 'none');
</script>

</body>
</html>

<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}


$restaurants_query = $conn->query("SELECT * FROM restaurants");
$restaurants = [];

while ($r = $restaurants_query->fetch_assoc()) {
    $r_id = $r['id'];


    $foods_query = $conn->prepare("SELECT * FROM foods WHERE restaurant_id = ?");
    $foods_query->bind_param("i", $r_id);
    $foods_query->execute();
    $foods_result = $foods_query->get_result();

    $foods = [];
    while ($f = $foods_result->fetch_assoc()) {
        $foods[] = $f;
    }

    $r['foods'] = $foods;
    $restaurants[] = $r;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['order'] = $_POST['order'];
    header("Location: review_order.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurants - Aklny</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-container">
    <h2>Welcome to Aklny 🍽️</h2>
    <p>Select your favorite meals and place your order.</p>

    <form method="POST">
        <?php foreach ($restaurants as $r): ?>
            <div class="restaurant">
                <h3><?= htmlspecialchars($r['name']) ?></h3>
                <p><?= htmlspecialchars($r['description']) ?></p>
                <table>
                    <tr><th>Food</th><th>Price</th><th>Quantity</th><th>Select</th></tr>
                    <?php foreach ($r['foods'] as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['name']) ?></td>
                            <td><?= $f['price'] ?> EGP</td>
                            <td><input type="number" name="order[<?= $f['id'] ?>][quantity]" min="1" value="1"></td>
                            <td><input type="checkbox" name="order[<?= $f['id'] ?>][selected]" value="1"></td>
                            <input type="hidden" name="order[<?= $f['id'] ?>][name]" value="<?= htmlspecialchars($f['name']) ?>">
                            <input type="hidden" name="order[<?= $f['id'] ?>][price]" value="<?= $f['price'] ?>">
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endforeach; ?>

        <button type="submit">Order Now</button>
    </form>

    <p>
        <a href="logout.php">Logout</a>
        <a href="orders.php">View My Orders</a>
</p>
</div>
</body>
</html>

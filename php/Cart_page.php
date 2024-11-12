<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "client_management";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$total = 0;
$orderComplete = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['validate']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO Cart (client_id, order_date, plat_id, plat_name, quantity, price, total_amount) VALUES (:client_id, :order_date, :plat_id, :plat_name, :quantity, :price, :total_amount)");

            foreach ($_SESSION['cart'] as $plat_id => $item) {
                $item_total = $item['price'] * $item['quantity'];
                $stmt->execute([
                    ':client_id' => $_SESSION['client_id'],
                    ':order_date' => $_SESSION['order_date'],
                    ':plat_id' => $plat_id,
                    ':plat_name' => $item['name'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':total_amount' => $item_total
                ]);
                $total += $item_total;
            }

            $conn->commit();

            // Clear the cart and order date from session
            $_SESSION['cart'] = [];
            $_SESSION['order_date'] = null;
            $orderComplete = true;
        } catch (Exception $e) {
            $conn->rollBack();
            $error_message = "Error processing order: " . $e->getMessage();
        }
    }
}

// Calculate total (in case the order wasn't completed)
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .order-date {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
        }
        .plat-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .plat-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 5px;
        }
        .plat-details {
            flex-grow: 1;
        }
        .plat-name {
            font-weight: bold;
            font-size: 18px;
        }
        .plat-description {
            color: #666;
            margin: 5px 0;
        }
        .plat-price, .plat-quantity, .plat-subtotal {
            margin-top: 5px;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 20px;
            margin-top: 20px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        .button:hover {
            background-color: #45a049;
        }
        #orderCompleteModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Summary</h1>
        <?php if (isset($_SESSION['order_date'])): ?>
            <p class="order-date">Order Date: <?php echo $_SESSION['order_date']; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $plat_id => $item): ?>
                <div class="plat-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="plat-image">
                    <div class="plat-details">
                        <div class="plat-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="plat-description"><?php echo htmlspecialchars($item['description']); ?></div>
                        <div class="plat-price">Price: €<?php echo number_format($item['price'], 2); ?></div>
                        <div class="plat-quantity">Quantity: <?php echo $item['quantity']; ?></div>
                        <div class="plat-subtotal">Subtotal: €<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: €<?php echo number_format($total, 2); ?></div>
            <form method="post">
                <button type="submit" name="validate" class="button">Validate Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty</p>
        <?php endif; ?>
    </div>

    <div id="orderCompleteModal" class="modal">
        <div class="modal-content">
            <h2>Thank you for your visit!</h2>
            <p>Your order has been processed successfully.</p>
            <button onclick="closeModal()" class="button">Close</button>
        </div>
    </div>

    <script>
        <?php if ($orderComplete): ?>
        document.getElementById('orderCompleteModal').style.display = 'block';
        <?php endif; ?>

        function closeModal() {
            document.getElementById('orderCompleteModal').style.display = 'none';
            window.location.href = 'menu.php'; // Redirect to menu page after closing modal
        }
    </script>
</body>
</html>
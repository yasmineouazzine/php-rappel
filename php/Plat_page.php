<?php
// Your existing PHP code here
session_start();
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

$origin = isset($_GET['origin']) ? $_GET['origin'] : '';

if ($origin) {
    $stmt = $conn->prepare("SELECT * FROM Plat WHERE origin = :origin");
    $stmt->execute(['origin' => $origin]);
    $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->query("SELECT * FROM Plat");
    $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    foreach ($_POST['quantity'] as $plat_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$plat_id] = [
                'quantity' => $quantity,
                'name' => $_POST['name'][$plat_id],
                'price' => $_POST['price'][$plat_id],
                'description' => $_POST['description'][$plat_id],
                'image_url' => $_POST['image_url'][$plat_id]
            ];
        }
    }
    $_SESSION['order_date'] = date('Y-m-d H:i:s');
    header("Location: Cart_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet Plat Selection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2d3748;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .plat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .plat-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .plat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .plat-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .plat-info {
            padding: 20px;
        }
        .plat-info h3 {
            color: #2d3748;
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        .plat-info p {
            color: #4a5568;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .plat-price {
            font-weight: 600;
            color: #48bb78;
            font-size: 1.2em;
            margin-bottom: 15px;
        }
        .quantity {
            width: 60px;
            padding: 8px;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            font-size: 1em;
            margin-right: 10px;
        }
        .submit-btn {
            background: #4299e1;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
            display: block;
            width: 200px;
            margin: 0 auto;
        }
        .submit-btn:hover {
            background: #3182ce;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .plat-card {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .plat-card:nth-child(even) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gourmet Plat Selection <?php echo $origin ? "- {$origin}" : ''; ?></h1>
        <form method="post">
            <div class="plat-grid">
                <?php foreach ($plats as $plat): ?>
                    <div class="plat-card">
                        <img src="<?php echo htmlspecialchars($plat['image_url']); ?>" alt="<?php echo htmlspecialchars($plat['name']); ?>">
                        <div class="plat-info">
                            <h3><?php echo htmlspecialchars($plat['name']); ?></h3>
                            <p><?php echo htmlspecialchars($plat['description']); ?></p>
                            <div class="plat-price">€<?php echo number_format($plat['price'], 2); ?></div>
                            <input type="number" name="quantity[<?php echo $plat['plat_id']; ?>]" class="quantity" min="0" value="0">
                            <input type="hidden" name="name[<?php echo $plat['plat_id']; ?>]" value="<?php echo htmlspecialchars($plat['name']); ?>">
                            <input type="hidden" name="price[<?php echo $plat['plat_id']; ?>]" value="<?php echo $plat['price']; ?>">
                            <input type="hidden" name="description[<?php echo $plat['plat_id']; ?>]" value="<?php echo htmlspecialchars($plat['description']); ?>">
                            <input type="hidden" name="image_url[<?php echo $plat['plat_id']; ?>]" value="<?php echo htmlspecialchars($plat['image_url']); ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="add_to_cart" class="submit-btn">Add to Cart</button>
        </form>
    </div>
</body>
</html>
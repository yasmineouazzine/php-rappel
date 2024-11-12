<?php
session_start();
// Database connection and PHP logic remain the same
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

// Predefined lists
$ingredients_list = [
    "Eggs", "Milk and milk products", "Fats and oils", "Fruits", 
    "Grain, nuts and baking products", "Herbs and spices", "Meat, sausages and fish"
];

$origins_list = ["Irish", "Japan", "Morocco", "France", "America", "Russian"];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_Commande'])) {
    $selected_ingredients = isset($_POST['ingredients']) ? implode(", ", $_POST['ingredients']) : "";
    $plat_name = $_POST['plat_name'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $origine = $_POST['origine'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO commande (client_id, ingredients, plat_name, min_price, max_price, origine) 
                               VALUES (:client_id, :ingredients, :plat_name, :min_price, :max_price, :origine)");
        
        $stmt->execute([
            ':client_id' => $_SESSION['client_id'],
            ':ingredients' => $selected_ingredients,
            ':plat_name' => $plat_name,
            ':min_price' => $min_price,
            ':max_price' => $max_price,
            ':origine' => $origine
        ]);
        
        $success_message = "Order added successfully!";
    } catch(PDOException $e) {
        $error_message = "Error adding order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet Menu Selection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .ingredients-select {
            height: 150px;
        }

        .price-range {
            display: flex;
            gap: 20px;
        }

        .btn-container {
            display: flex;
            gap: 15px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #333;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error {
            background-color: #fee2e2;
            color: #dc2626;
        }

        footer {
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .price-range {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gourmet Menu Selection</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="ingredients">Select Ingredients (hold Ctrl/Cmd to select multiple):</label>
                <select name="ingredients[]" id="ingredients" class="ingredients-select" multiple required>
                    <?php foreach ($ingredients_list as $ingredient): ?>
                        <option value="<?php echo htmlspecialchars($ingredient); ?>">
                            <?php echo htmlspecialchars($ingredient); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="plat_name">Plate Name:</label>
                <input type="text" name="plat_name" id="plat_name" placeholder="Enter the name of your desired dish" required>
            </div>

            <div class="price-range">
                <div class="form-group">
                    <label for="min_price">Minimum Price (€):</label>
                    <input type="number" name="min_price" id="min_price" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label for="max_price">Maximum Price (€):</label>
                    <input type="number" name="max_price" id="max_price" min="0" step="0.01" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="origine">Origin/Category:</label>
                <select name="origine" id="origine" required>
                    <option value="">Select origin</option>
                    <?php foreach ($origins_list as $origin): ?>
                        <option value="<?php echo htmlspecialchars($origin); ?>">
                            <?php echo htmlspecialchars($origin); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="btn-container">
                <button type="submit" name="add_to_Commande" class="btn btn-primary">Add to Commande</button>
                <a href="Plat_page.php" class="btn btn-secondary">View Plat Choises</a>
            </div>
        </form>
    </div>
    <footer>
        Designed by YASMINE OUAZZINE
    </footer>
</body>
</html>
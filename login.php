<?php
// Database connection code
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

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code_client = $_POST['code_client'];
    $password = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM client WHERE code_client = :code_client");
        $stmt->bindParam(':code_client', $code_client);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $client = $stmt->fetch();
            if ($password == $client['password']) {
                session_start();
                $_SESSION['client_id'] = $client['client_id'];
                $_SESSION['code_client'] = $client['code_client'];
                header("Location: menu.php");  
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Client code not found";
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .error-message {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(1px);
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
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Please enter your credentials to login</p>
        </div>
        
        <?php if (isset($error)) { ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="code_client">Client Code</label>
                <input type="text" id="code_client" name="code_client" required maxlength="50" 
                       placeholder="Enter your client code">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required maxlength="50" 
                       placeholder="Enter your password">
            </div>
            
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
    <footer>
        Designed by YASMINE OUAZZINE
    </footer>
</body>
</html>
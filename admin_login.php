<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT admin_pass FROM users WHERE admin_user = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["admin_logged_in"] = true;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="images/logoo.png" type="image/png">
<title>Admin Login - Digital Age</title>
<style>
    :root {
        --primary: #051405;
        --secondary: #15b400;
        --secondary-dark: #0c8200;
        --accent: #d4ff00;
        --bg: #f4faf4;
        --surface: rgba(255, 255, 255, 0.95);
        --text-dark: #0f1c0f;
        --border: rgba(21, 180, 0, 0.2);
        --radius: 16px;
    }
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        font-family: "Inter", sans-serif;
        background: var(--primary);
        background-image: radial-gradient(circle at 50% 0%, #0d2e0d 0%, var(--primary) 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: var(--text-dark);
        overflow: hidden;
    }
    .ambient-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.3;
        z-index: -1;
    }
    .blob-1 { top: -10%; left: -10%; width: 400px; height: 400px; background: var(--secondary); }
    .blob-2 { bottom: -10%; right: -10%; width: 500px; height: 500px; background: var(--accent); opacity: 0.15; }

    .login-box {
        background: var(--surface);
        backdrop-filter: blur(20px);
        width: 400px;
        border-radius: var(--radius);
        padding: 3rem 2.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        text-align: center;
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255,255,255,0.15);
        position: relative;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .logo-container {
        margin-bottom: 2rem;
    }
    .logo-container img {
        height: 50px;
    }
    h2 {
        margin-bottom: 0.5rem;
        color: var(--primary);
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
    }
    p.subtitle {
        color: #4b5e4b;
        margin-bottom: 2rem;
        margin-top: 0;
        font-size: 0.95rem;
    }
    input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1rem;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.8);
    }
    input:focus {
        border-color: var(--secondary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(21, 180, 0, 0.1);
        background: #ffffff;
    }
    button {
        width: 100%;
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1.1rem;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -3px rgba(21, 180, 0, 0.4);
    }
    .error {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
</style>
</head>
<body>
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>
    <div class="login-box">
        <div class="logo-container">
            <img src="images/logoo.png" alt="Digital Age Logo">
        </div>
        <h2>Admin Portal</h2>
        <p class="subtitle">Enter your credentials to access the dashboard</p>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Secure Login</button>
        </form>
    </div>
</body>
</html>

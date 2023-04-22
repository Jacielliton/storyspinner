<?php
require_once 'includes/functions.php';
session_start(); // Adicione esta linha
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
// Processar login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (loginUser($email, $password)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Email ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Login</h1>
    <form class="form" action="login.php" method="post">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p>Não possui uma conta? <a href="register.php">Registre-se</a>.</p>
    <script src="js/scripts.js"></script>
</body>
</html>

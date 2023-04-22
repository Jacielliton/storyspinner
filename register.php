<?php
require_once 'includes/functions.php';

// Processar o registro do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerUser($username, $email, $password)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Erro ao registrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Registro</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Registro</h1>
    <form action="register.php" method="post">
        <label for="username">Nome de usuário:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Registrar</button>
    </form>
    <p>Já possui uma conta? <a href="login.php">Faça login</a>.</p>
    <script src="js/scripts.js"></script>
</body>
</html>
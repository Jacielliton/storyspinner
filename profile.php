<?php
session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = getUserById($_SESSION['user_id']);

// Processar a atualização do perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (updateUserProfile($_SESSION['user_id'], $username, $email)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar o perfil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Perfil</h1>
    <form action="profile.php" method="post">
        <label for="username">Nome de usuário:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <button type="submit">Atualizar Perfil</button>
    </form>
    <p><a href="index.php">Voltar ao início</a></p>
    <script src="js/scripts.js"></script>
</body>
</html>

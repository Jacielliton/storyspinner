<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';



if (!isset($_SESSION['user_id']) || !isAdmin($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $user = getUserById($user_id);
} else {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (updateUser($user_id, $name, $email, $is_admin)) {
        header("Location: admin.php");
        exit;
    } else {
        $error = "Erro ao atualizar o usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>
<body>
    <h1>Editar Usuário</h1>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        <br>
        <label for="is_admin">Administrador:</label>
        <input type="checkbox" name="is_admin" id="is_admin" <?php echo $user['is_admin'] ? 'checked' : ''; ?>>
        <br>
        <input type="submit" value="Salvar">
    </form>
</body>
</html>

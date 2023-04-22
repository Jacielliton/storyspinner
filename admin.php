
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

$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração</title>
</head>
<body>
    <h1>Painel de Administração</h1>
    <a href="manage_stories.php">Gerenciar Histórias</a>
    <h2>Usuários</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Administrador</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo $user['is_admin'] ? 'Sim' : 'Não'; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Editar</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>">Excluir</a>
                    <a href="promote_user.php?id=<?php echo $user['id']; ?>">Promover a Administrador</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

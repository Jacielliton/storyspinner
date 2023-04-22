<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'header.php';

$stories = getAllStories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Histórias</title>
</head>
<body>
    <h1>Gerenciar Histórias</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($stories as $story): ?>
            <tr>
                <td><?php echo $story['id']; ?></td>
                <td><?php echo htmlspecialchars($story['title']); ?></td>
                <td><?php echo htmlspecialchars($story['author']); ?></td>
                <td>
                    <a href="edit_story.php?id=<?php echo $story['id']; ?>">Editar</a>
                    <a href="delete_story.php?id=<?php echo $story['id']; ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

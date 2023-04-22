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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_id = $_POST['comment_id'];

    if (deleteComment($comment_id)) {
        header("Location: view_stories.php");
        exit;
    } else {
        echo "Erro ao excluir o comentário.";
    }
} else {
    $comment_id = $_GET['comment_id'];
    $comment = getComment($comment_id);

    if ($_SESSION['user_id'] != $comment['user_id']) {
        header("Location: view_stories.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excluir Comentário</title>
</head>
<body>
    <h1>Excluir Comentário</h1>
    <p>Tem certeza de que deseja excluir este comentário?</p>
    <form action="delete_comment.php" method="post">
        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
        <button type="submit">Sim, excluir</button>
    </form>
    <a href="view_stories.php">Cancelar</a>
</body>
</html>

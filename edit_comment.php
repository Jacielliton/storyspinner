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
    $content = $_POST['content'];

    if (editComment($comment_id, $content)) {
        header("Location: view_stories.php");
        exit;
    } else {
        echo "Erro ao editar o comentário.";
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
    <title>Editar Comentário</title>
</head>
<body>
    <h1>Editar Comentário</h1>
    <form action="edit_comment.php" method="post">
        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
        <textarea name="content" rows="4" cols="50" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
        <br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>

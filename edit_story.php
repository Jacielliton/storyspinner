<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';


if (!isset($_SESSION['user_id']) || (!isAdmin($_SESSION['user_id']) && $_SESSION['user_id'] != $story['user_id'])) {
    header("Location: login.php");
    exit;
}

$story_id = $_GET['story_id'];
$story = getStoryById($story_id);

// Verificar se a história pertence ao usuário logado
if ($story['user_id'] !== $_SESSION['user_id']) {
    header("Location: index.php");
    exit;
}

// Processar a atualização da história
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (updateStory($story_id, $title, $content)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar a história.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Editar História</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Editar História</h1>
    <form action="edit_story.php?story_id=<?php echo $story_id; ?>" method="post">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($story['title']); ?>" required>
        <br>
        <label for="content">Conteúdo:</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($story['content']); ?></textarea>
        <br>
        <button type="submit">Atualizar História</button>
    </form>
    <p><a href="index.php">Voltar ao início</a></p>
    <script src="js/scripts.js"></script>
</body>
</html>

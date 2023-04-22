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

$story_id = $_GET['story_id'];
$story = getStoryById($story_id);

// Verificar se a história pertence ao usuário logado ou se o usuário é administrador
if (!isAdmin($_SESSION['user_id']) && $_SESSION['user_id'] != $story['user_id']) {
    header("Location: index.php");
    exit;
}

// Processar a exclusão da história
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (deleteStory($story_id)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao excluir a história.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Excluir História</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Excluir História</h1>
    <p>Tem certeza de que deseja excluir a história "<?php echo htmlspecialchars($story['title']); ?>"?</p>
    <form action="delete_story.php?story_id=<?php echo $story_id; ?>" method="post">
        <button type="submit">Excluir História</button>
    </form>
    <p><a href="index.php">Voltar ao início</a></p>
    <script src="js/scripts.js"></script>
</body>
</html>

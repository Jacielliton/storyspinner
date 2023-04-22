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

// Processar a criação da história
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_ids = $_POST['categories'];

    $story_id = createStory($_SESSION['user_id'], $title, $content);
    addStoryCategories($story_id, $category_ids);

    header("Location: index.php");
    exit;
}

$categories = getCategories();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Criar História</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Criar História</h1>
    <form action="create_story.php" method="post">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Conteúdo:</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        
        <label for="categories">Categorias:</label>
        <select id="categories" name="categories[]" multiple>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Criar História</button>
    </form>
    <p><a href="index.php">Voltar ao início</a></p>
    <script src="js/scripts.js"></script>
</body>
</html>
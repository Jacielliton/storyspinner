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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StorySpinner - Resultados da pesquisa</title>
    <!-- Inclua seu CSS e outros arquivos necessários aqui -->
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <!-- Conteúdo da página de resultados da pesquisa -->
        <?php
        require_once 'includes/functions.php';

        $query = isset($_GET['q']) ? $_GET['q'] : '';
        $results = searchStories($query);
        ?>
        <h1>Resultados da pesquisa para: "<?php echo htmlspecialchars($query); ?>"</h1>
        <ul>
            <?php while ($story = $results->fetch_assoc()): ?>
                <li>
                    <h2><a href="view_story.php?id=<?php echo $story['id']; ?>"><?php echo htmlspecialchars($story['title']); ?></a></h2>
                    <p>Autor: <?php echo htmlspecialchars($story['author']); ?></p>
                    <p>Data de publicação: <?php echo htmlspecialchars($story['created_at']); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>

    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

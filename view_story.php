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

$userId = $_SESSION['user_id'];

require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StorySpinner - Visualizar História</title>
    <!-- Inclua seu CSS e outros arquivos necessários aqui -->
</head>
<body>
    <div class="container my-5">
   

    <main>
        <!-- Conteúdo da página de visualização de histórias -->
        <?php
        require_once 'includes/functions.php';

        $storyId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $story = getStoryById($storyId);

        if (!$story) {
            echo '<p>História não encontrada.</p>';
            exit;
        }
        ?>
        
        <article>
        <h1><?php echo htmlspecialchars($story['title']); ?></h1>
        <p>Autor: <?php echo htmlspecialchars($story['author']); ?></p>
        <p>Data de publicação: <?php echo htmlspecialchars($story['created_at']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($story['content'])); ?></p>
        </article>
    
    <!-- Botão de curtir história -->
        <?php
        if (isStoryLikedByUser($userId, $storyId)) {
            echo '<a href="unlike_story.php?id=' . $storyId . '">Descurtir</a>';
        } else {
            echo '<a href="like_story.php?id=' . $storyId . '">Curtir</a>';
        }
        ?>
    </main>
    
    <div class="social-sharing">
    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://yourwebsite.com/view_story.php?id=' . $story['id']); ?>" target="_blank">Compartilhar no Facebook</a>
    <a class="twitter" href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://yourwebsite.com/view_story.php?id=' . $story['id']) . '&text=' . urlencode('Confira esta história incrível: ' . $story['title']); ?>" target="_blank">Compartilhar no Twitter</a>
    </div>
     </div>
    <?php include 'footer.php'; ?>
</body>
</html>

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
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? max(1, intval($_GET['itemsPerPage'])) : 10;
$offset = ($page - 1) * $itemsPerPage;

$stories = getAllStories($itemsPerPage, $offset);

$totalStories = countStories();
$totalPages = ceil($totalStories / $itemsPerPage);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorySpinner - Visualizar Histórias</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container my-5">
    <h1>Visualizar Histórias</h1>
    <ul>
        <?php foreach ($stories as $story): ?>
            <li>
                <h2><?php echo htmlspecialchars($story['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($story['content'])); ?></p>
                <p>Votos: <?php echo getStoryVotes($story['id']); ?></p>
                <?php if ($story['user_id'] === $_SESSION['user_id']): ?>
                    <a href="edit_story.php?story_id=<?php echo $story['id']; ?>">Editar</a>
                    <a href="delete_story.php?story_id=<?php echo $story['id']; ?>">Excluir</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="vote_story.php" method="post">
                        <input type="hidden" name="story_id" value="<?php echo $story['id']; ?>">
                        <button type="submit" name="vote" value="1">Votar positivo</button>
                        <button type="submit" name="vote" value="-1">Votar negativo</button>
                    </form>
                <?php endif; ?>
                <ul>
                    <?php $comments = getStoryComments($story['id']); ?>
                    <?php foreach ($comments as $comment): ?>
                        <li>
                            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                <a href="edit_comment.php?comment_id=<?php echo $comment['id']; ?>">Editar</a>
                                <a href="delete_comment.php?comment_id=<?php echo $comment['id']; ?>">Excluir</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="create_comment.php" method="post">
                        <input type="hidden" name="story_id" value="<?php echo $story['id']; ?>">
                        <textarea name="content" rows="4" cols="50" required></textarea>
                        <br>
                        <button type="submit">Comentar</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        </ul>
    
    <nav>
    <ul>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li>
                <a href="?page=<?php echo $i; ?>&itemsPerPage=<?php echo $itemsPerPage; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
    </nav>

    
    <p><a href="index.php">Voltar ao início</a></p>
    <script src="js/scripts.js"></script>
    </div>
    </body>
</html>
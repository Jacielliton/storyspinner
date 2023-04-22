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
?>

<main class="container">
    <section>
        <h1>Bem-vindo ao StorySpinner!</h1>
        <p>Descubra e compartilhe histórias incríveis com nossa comunidade.</p>
    </section>

    <section>
        <h2>Historias em destaque</h2>
        <?php $featuredStories = getFeaturedStories(); ?>
        <ul>
            <?php foreach ($featuredStories as $story): ?>
                <li><a href="view_story.php?id=<?php echo $story['id']; ?>"><?php echo htmlspecialchars($story['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
        <section>
            <h2>Histórias recomendadas</h2>
            <?php $recommendedStories = getRecommendedStories($_SESSION['user_id']); ?>
            <ul>
                <?php foreach ($recommendedStories as $story): ?>
                    <li><a href="view_story.php?id=<?php echo $story['id']; ?>"><?php echo htmlspecialchars($story['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
    
</main>

<?php require_once 'footer.php'; ?>

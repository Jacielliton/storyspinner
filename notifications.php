<?php
session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$notifications = getUserNotifications($_SESSION['user_id'], true);
?>

<main class="container">
    <h1>Notificações</h1>
    <ul class="list-group">
        <?php foreach ($notifications as $notification): ?>
            <li class="list-group-item"><?php echo htmlspecialchars($notification['content']); ?></li>
        <?php endforeach; ?>
    </ul>
</main>

<?php require_once 'footer.php'; ?>

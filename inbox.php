<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';

$userId = getUserSessionId();
$messages = getUserMessages($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
</head>
<body>
    <h1>Inbox</h1>

    <h2>Send a message</h2>
    <form action="send_message.php" method="post">
        <label for="recipient">Recipient:</label>
        <input type="text" name="recipient" id="recipient" required>
        <br>
        <label for="content">Message:</label>
        <textarea name="content" id="content" required></textarea>
        <br>
        <input type="submit" value="Send">
    </form>

    <h2>Messages</h2>
    <?php if (count($messages) > 0): ?>
        <ul>
            <?php foreach ($messages as $message): ?>
                <li>
                    <strong><?= htmlspecialchars($message['sender_id'] == $userId ? 'To' : 'From') ?>:</strong> <?= htmlspecialchars($message['sender_id'] == $userId ? $message['recipient_id'] : $message['sender_id']) ?><br>
                    <strong>Content:</strong> <?= htmlspecialchars($message['content']) ?><br>
                    <strong>Created at:</strong> <?= htmlspecialchars($message['created_at']) ?><br>
                    <?php if ($message['sender_id'] != $userId): ?>
                        <?php if ($message['read_at'] === null): ?>
                            <a href="mark_message_as_read.php?id=<?= $message['id'] ?>">Mark as read</a>
                        <?php else: ?>
                            <strong>Read at:</strong> <?= htmlspecialchars($message['read_at']) ?><br>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="delete_message.php?id=<?= $message['id'] ?>">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

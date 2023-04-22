<?php
require_once 'includes/functions.php';

if (isset($_GET['id'])) {
    $messageId = intval($_GET['id']);
    markMessageAsRead($messageId);
}

header('Location: inbox.php');
exit;

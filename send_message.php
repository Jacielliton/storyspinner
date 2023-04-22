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

if (isset($_POST['recipient'], $_POST['content'])) {
    $recipientUsername = $_POST['recipient'];
    $content = $_POST['content'];
    $senderId = getUserSessionId();
    $recipient = getUserByUsername($recipientUsername);

    if ($senderId !== null && $recipient !== null) {
        sendMessage($senderId, $recipient['id'], $content);
    }
}

header('Location: inbox.php');
exit;

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

if (isset($_GET['id'])) {
    $storyId = intval($_GET['id']);
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($userId !== null) {
        likeStory($userId, $storyId);
    }
}

header('Location: view_story.php?id=' . $storyId);
exit;
?>

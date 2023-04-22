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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $story_id = $_POST['story_id'];
    $content = $_POST['content'];

    if (createComment($_SESSION['user_id'], $story_id, $content)) {
        header("Location: view_story.php?id=" . $story_id);
        exit;
    } else {
        echo "Erro ao criar o comentário.";
    }
}
?>

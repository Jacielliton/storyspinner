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
    $vote = $_POST['vote'];

    if (voteStory($_SESSION['user_id'], $story_id, $vote)) {
        header("Location: view_stories.php");
        exit;
    } else {
        echo "Erro ao votar na história.";
    }
}
?>

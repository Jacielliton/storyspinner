<?php
require_once 'config.php';

// Função para registrar usuários
function registerUser($username, $email, $password) {
    global $conn;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para fazer login
function loginUser($email, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        return true;
    } else {
        return false;
    }
}

function getUserById($user_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    } else {
        return false;
    }
}



// Função para criar histórias
function createStory($user_id, $title, $content) {
    global $conn;
    $sql = "INSERT INTO stories (title, content, author_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $created_at = $updated_at = date('Y-m-d H:i:s');
    
    // Adicione esta linha para associar os parâmetros à sua consulta SQL
    $stmt->bind_param('ssiss', $title, $content, $user_id, $created_at, $updated_at);

    $stmt->execute();
    return $conn->insert_id;
}





// Função para atualizar o perfil do usuário
function updateUserProfile($user_id, $username, $email) {
    global $conn;

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
// Função para buscar todas as categorias
function getCategories() {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);

    return $categories;
}

// Função para adicionar categorias a uma história
function addStoryCategories($story_id, $category_ids) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO story_categories (story_id, category_id) VALUES (?, ?)");

    foreach ($category_ids as $category_id) {
        $stmt->bind_param("ii", $story_id, $category_id);
        $stmt->execute();
    }
}

// Função para votar em uma história
function voteStory($user_id, $story_id, $vote) {
    global $conn;

    $stmt = $conn->prepare("REPLACE INTO votes (user_id, story_id, vote) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $story_id, $vote);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para obter o total de votos de uma história
function getStoryVotes($story_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT SUM(vote) as total_votes FROM votes WHERE story_id = ?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_votes'];
}

// Função para criar um comentário
function createComment($user_id, $story_id, $content) {
    global $conn;
    $sql = "INSERT INTO comments (user_id, story_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("iis", $user_id, $story_id, $content);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Função para obter comentários de uma história
function getStoryComments($story_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM comments WHERE story_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);

    return $comments;
}

// Função para obter um único comentário
function getComment($comment_id) {
    global $conn;
    $sql = "SELECT * FROM comments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


// Função para criar uma notificação
function createNotification($user_id, $event_type, $related_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, event_type, related_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $event_type, $related_id);
    return $stmt->execute();
}
function getUserNotifications($userId, $unread_only = false) {
    global $conn;
    $sql = "SELECT * FROM notifications WHERE user_id = ?";
    if ($unread_only) {
        $sql .= " AND is_read = 0";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    return $notifications;
}




function markNotificationAsRead($notification_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $notification_id);
    return $stmt->execute();
}


// Função para adicionar um amigo
function addFriend($user1_id, $user2_id) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO friendships (user1_id, user2_id) VALUES (?, ?), (?, ?)");
    $stmt->bind_param("iiii", $user1_id, $user2_id, $user2_id, $user1_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Função para verificar se dois usuários são amigos
function areFriends($user1_id, $user2_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM friendships WHERE user1_id = ? AND user2_id = ?");
    $stmt->bind_param("ii", $user1_id, $user2_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Função para remover um amigo
function removeFriend($user1_id, $user2_id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM friendships WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)");
    $stmt->bind_param("iiii", $user1_id, $user2_id, $user2_id, $user1_id);

    return $stmt->execute();
}

// Função para obter amigos de um usuário
function getUserFriends($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM friendships JOIN users ON (friendships.user2_id = users.id) WHERE friendships.user1_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $friends = $result->fetch_all(MYSQLI_ASSOC);

    return $friends;
}
// Função para verificar se um usuário é administrador
function isAdmin($user_id) {
    global $conn;
    $sql = "SELECT is_admin FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);    
    $bind_result = $stmt->bind_param("i", $user_id);    
    $execute_result = $stmt->execute();    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['is_admin'] == 1;
}


// Função para obter todos os usuários
function getAllUsers() {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    return $users;
}

function updateUser($user_id, $name, $email, $is_admin) {
    global $conn;

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, is_admin = ? WHERE id = ?");
    $stmt->bind_param("ssii", $name, $email, $is_admin, $user_id);

    return $stmt->execute();
}

function deleteUser($user_id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$user_id]);
}


function promoteUser($user_id) {
    global $conn;

    $stmt = $conn->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    return $stmt->execute();
}

function getAllStories($limit = 10, $offset = 0) {
    global $conn;
    // Altere a consulta SQL abaixo para incluir a coluna 'username' como 'author'
   $stmt = $conn->prepare("SELECT stories.*, users.username as author, users.id as user_id FROM stories JOIN users ON stories.author_id = users.id ORDER BY stories.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}





function searchStories($query) {
    global $conn;
    $search_query = '%' . $query . '%';
    $stmt = $conn->prepare("SELECT * FROM stories WHERE title LIKE ? OR content LIKE ?");
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    return $stmt->get_result();
}

function countStories() {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM stories");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    return $row[0];
}

// Função para buscar histórias
function getStories() {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM stories");
    $stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);

    return $stories;
}

// Função para buscar uma história pelo ID
function getStoryById($storyId) {
    global $conn;
    $sql = "SELECT stories.*, users.username as author FROM stories JOIN users ON stories.author_id = users.id WHERE stories.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $storyId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


// Função para atualizar uma história
function updateStory($story_id, $title, $content) {
    global $conn;
    $sql = "UPDATE stories SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$title, $content, $story_id]);
}


// Função para excluir uma história
function deleteStory($story_id) {
    global $conn; // Troque $db por $conn
    $sql = "DELETE FROM stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$story_id]);
}

function likeStory($userId, $storyId) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO likes (user_id, story_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $storyId);
    $stmt->execute();
}

function unlikeStory($userId, $storyId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND story_id = ?");
    $stmt->bind_param("ii", $userId, $storyId);
    $stmt->execute();
}

function isStoryLikedByUser($userId, $storyId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND story_id = ?");
    $stmt->bind_param("ii", $userId, $storyId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getFeaturedStories($limit = 5) {
    global $conn;
    $sql = "SELECT * FROM stories ORDER BY likes DESC, views DESC, created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
    die("Error: " . $conn->error);
    }
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);
    return $stories;
}

function getRecommendedStories($user_id, $limit = 5) {
    global $conn;
    $sql = "SELECT stories.* FROM stories
            JOIN likes ON stories.id = likes.story_id
            WHERE likes.user_id = ?
            ORDER BY stories.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param('ii', $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);
    return $stories;
}


function getMostLikedStories($limit = 10) {
    global $conn;
    $sql = "SELECT stories.*, COUNT(likes.id) AS num_likes
            FROM stories
            LEFT JOIN likes ON stories.id = likes.story_id
            GROUP BY stories.id
            ORDER BY num_likes DESC, stories.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $bind_result = $stmt->bind_param("i", $limit);
    $execute_result = $stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);
    return $stories;
}


// Faça o mesmo para os comentários
function likeComment($userId, $commentId) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO likes (user_id, comment_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $commentId);
    $stmt->execute();
}

function unlikeComment($userId, $commentId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND comment_id = ?");
    $stmt->bind_param("ii", $userId, $commentId);
    $stmt->execute();
}

function isCommentLikedByUser($userId, $commentId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND comment_id = ?");
    $stmt->bind_param("ii", $userId, $commentId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
function sendMessage($senderId, $recipientId, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO private_messages (sender_id, recipient_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $senderId, $recipientId, $content);
    $stmt->execute();
}

function deleteMessage($messageId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM private_messages WHERE id = ?");
    $stmt->bind_param("i", $messageId);
    $stmt->execute();
}

function getUserMessages($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM private_messages WHERE sender_id = ? OR recipient_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function markMessageAsRead($messageId) {
    global $conn;
    $stmt = $conn->prepare("UPDATE private_messages SET read_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $messageId);
    $stmt->execute();
}


function deleteComment($comment_id) {
    global $conn;
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($sql);    
    $bind_result = $stmt->bind_param("i", $comment_id);   
    $execute_result = $stmt->execute();    
    return $execute_result;
}

function editComment($comment_id, $content) {
    global $conn;
    $sql = "UPDATE comments SET content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $bind_result = $stmt->bind_param("si", $content, $comment_id);
    $execute_result = $stmt->execute();
    return $execute_result;
}



?>
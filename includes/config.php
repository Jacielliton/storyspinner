<?php
/*
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "storyspinner";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "storyspinner";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

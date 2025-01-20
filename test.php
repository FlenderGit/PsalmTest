<?php
$dsn = 'mysql:host=localhost;dbname=map_restaurant';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$conn = new PDO($dsn, $username, $password, $options);

$token = $_GET['token'] ?? 'McDonalds';
$t = $conn->query("SELECT * FROM resto WHERE name = '$token'")->fetchAll();

function getUserInfo(PDO $co, $userId = 4) {
    $query = "SELECT * FROM resto WHERE id = '$userId'";
    $stmt = $co->query($query);
    return $stmt->fetch();
}


function getUserInfo1(PDO $co, $userId = 4) {
    $query = "SELECT * FROM resto WHERE id = '$userId'";
    $stmt = $co->query($query);
    return $stmt->fetch();
}
$userId = $_GET['user_id'] ?? 1; 
$test = getUserInfo($conn, $userId);
$test2 = getUserInfo1($conn, $userId);

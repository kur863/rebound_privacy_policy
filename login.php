<?php
session_start();
$conn = new mysqli("localhost", "root", "", "book");

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT password FROM member WHERE account = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($db_password);
    $stmt->fetch();

    if ($password === $db_password) {
        $_SESSION['username'] = $username;
        header("Location: searchTmp.php");
        exit();
    } else {
        header("Location: login.php?error=wrong_password");
        exit();
    }
} else {
    header("Location: login.php?error=no_user");
    exit();
}
?>

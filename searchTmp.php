<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>書名查詢系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #fbc2eb, #a6c1ee);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .welcome {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #4a4a4a;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 60%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #667eea;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a67d8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .no-result {
            text-align: center;
            color: #b00;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>書籍查詢系統</h2>
    <div class="welcome">登入成功，歡迎使用者：<strong><?php echo htmlspecialchars($username); ?></strong></div>

    <form method="GET" action="searchTmp.php">
        <input type="text" name="bookname" placeholder="輸入書名關鍵字..." required>
        <button type="submit">查詢</button>
    </form>

    <?php
    if (isset($_GET['bookname'])) {
        $bookname = $_GET['bookname'];
        $conn = new mysqli("localhost", "root", "", "book");

        if ($conn->connect_error) {
            echo "<p class='no-result'>資料庫連線失敗！</p>";
        } else {
            $stmt = $conn->prepare("SELECT * FROM product WHERE bookname LIKE ?");
            $searchTerm = "%" . $bookname . "%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>書名</th><th>價格</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['booknum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bookname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-result'>沒有找到相關書籍</p>";
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
</div>
</body>
</html>

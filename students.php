<?php
// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "113dbb06";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}
echo "連線成功<br>";

// 分頁參數設置
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$cardsPerPage = isset($_GET['cardsPerPage']) ? (int)$_GET['cardsPerPage'] : 10;

// SQL 查詢
$sql_select_all = "SELECT * FROM student LIMIT ?, ?";
$stmt = $conn->prepare($sql_select_all);
$offset = ($page - 1) * $cardsPerPage;
$stmt->bind_param("ii", $offset, $cardsPerPage);

if ($stmt->execute() === false) {
    echo "查詢失敗: " . $stmt->error;
} else {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["studentid"] . " - 名字: " . $row["name"] . " - 學號: " . $row["number"] . "<br>";
        }
    } else {
        echo "0 結果";
    }
}

// 關閉資料庫連線
$stmt->close();
$conn->close();
?>


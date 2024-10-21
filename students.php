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
echo "目前連接的資料庫: " . $dbname . "<br>";

// 設置資料庫連線的字符集為 UTF-8
$conn->set_charset("utf8mb4");

// 查詢 student 資料表中的所有資料
$sql = "SELECT * FROM student";
$result = $conn->query($sql);

// 檢查是否查詢失敗
if ($result === false) {
    die("查詢失敗: " . $conn->error);
}

// 檢查查詢結果並輸出資料
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "學號: " . $row["studentid"] . " - 姓名: " . $row["name"] . "<br>";
    }
} else {
    echo "沒有找到資料";
}

// 關閉資料庫連線
$conn->close();
?>

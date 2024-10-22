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

// 設置資料庫連線的字符集為 UTF-8
$conn->set_charset("utf8mb4");

// 如果表單被提交
if (isset($_GET['student_field']) && isset($_GET['student_key'])) {
    $field = $_GET['student_field']; // 獲取用戶選擇的欄位
    $key = $_GET['student_key']; // 獲取用戶輸入的關鍵字

    // 防止SQL注入攻擊
    $field = $conn->real_escape_string($field);
    $key = $conn->real_escape_string($key);

    // 查詢student資料表中的資料
    $sql = "SELECT studentid, name, number, college, email FROM student WHERE $field LIKE '%$key%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 輸出資料
        while($row = $result->fetch_assoc()) {
            echo "學號: " . $row["studentid"]. " - 姓名: " . $row["name"]. " - 學號: " . $row["number"]. " - 學院: " . $row["college"]. " - Email: " . $row["email"]. "<br>";
        }
    } else {
        echo "沒有找到資料";
    }
}

// 關閉資料庫連線
$conn->close();
?>


<?php
if (mysqli_connect_errno()) {
    echo "连接至 MySQL 失败: " . mysqli_connect_error();
}

$conn = mysqli_connect('localhost', 'root', '', '113dbb06');
mysqli_query($conn, 'SET NAMES utf8');
mysqli_query($conn, 'SET CHARACTER_SET_CLIENT=utf8');
mysqli_query($conn, 'SET CHARACTER_SET_RESULTS=utf8');

// 定義當前頁數和每頁顯示的記錄數量
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$cardsPerPage = 10; // 每頁顯示的記錄數

$keepers = []; // 初始化为空数组
$key1 = ''; // 初始化key1

if (isset($_GET["keeper_key"]) && trim($_GET["keeper_key"]) != '') {
    $key1 = mysqli_real_escape_string($conn, $_GET["keeper_key"]);
    $start = ($page - 1) * $cardsPerPage;
    $sql = "SELECT keeperId, name, position, salary, department FROM keeper WHERE name LIKE '%$key1%' OR position LIKE '%$key1%' LIMIT $start, $cardsPerPage";
} else {
    $start = ($page - 1) * $cardsPerPage;
    $sql = "SELECT keeperId, name, position, salary, department FROM keeper LIMIT $start, $cardsPerPage";
}

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $keepers[] = $row; // 将每个结果添加到数组中
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// 获取总记录数以计算总页数
$sqlTotal = "SELECT COUNT(*) FROM keeper";
if (!empty($key1)) {
    $sqlTotal .= " WHERE name LIKE '%$key1%' OR position LIKE '%$key1%' OR department LIKE '%$key1%'";
}

$totalResult = mysqli_query($conn, $sqlTotal);

if ($totalResult) {
    $totalRow = mysqli_fetch_array($totalResult);
    $totalRecords = $totalRow[0];
    $totalPages = ceil($totalRecords / $cardsPerPage);
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
<?php
// 連接資料庫
$conn = mysqli_connect('localhost', '113dbb06', '2476-3247', '113dbb06');
if (mysqli_connect_errno()) {
    echo "连接至 MySQL 失败: " . mysqli_connect_error();
}
mysqli_query($conn, 'SET NAMES utf8');
mysqli_query($conn, 'SET CHARACTER_SET_CLIENT=utf8');
mysqli_query($conn, 'SET CHARACTER_SET_RESULTS=utf8');
$conn->set_charset("utf8mb4");

// 處理新增課程
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增課程
    if (isset($_POST['add'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $department = $conn->real_escape_string($_POST['department']);
        $prof = $conn->real_escape_string($_POST['prof']);
        $classroom = $conn->real_escape_string($_POST['classroom']);
        $date = $conn->real_escape_string($_POST['date']);

        $sql = "INSERT INTO lesson (name, department, prof, classroom, date) VALUES ('$name', '$department', '$prof', '$classroom', '$date')";
        if ($conn->query($sql) === TRUE) {
            // 成功新增課程後顯示提示框
            echo "<script>alert('課程新增成功！'); window.location.href='courses.php';</script>";
            exit();
        } else {
            echo "新增失敗: " . $conn->error;
        }
    }

    // 修改課程
    if (isset($_POST['edit'])) {
        $lessonid = $_POST['lessonid'];
        $name = $conn->real_escape_string($_POST['name']);
        $department = $conn->real_escape_string($_POST['department']);
        $prof = $conn->real_escape_string($_POST['prof']);
        $classroom = $conn->real_escape_string($_POST['classroom']);
        $date = $conn->real_escape_string($_POST['date']);

        $sql = "UPDATE lesson SET name='$name', department='$department', prof='$prof', classroom='$classroom', date='$date' WHERE lessonid='$lessonid'";
        if ($conn->query($sql) === TRUE) {
            // 成功修改課程後顯示提示框
            echo "<script>alert('課程修改成功！'); window.location.href='courses.php';</script>";
            exit();
        } else {
            echo "修改失敗: " . $conn->error;
        }
    }

    // 刪除課程
    if (isset($_POST['delete'])) {
        $lessonid = $_POST['lessonid'];

        $sql = "DELETE FROM lesson WHERE lessonid='$lessonid'";
        if ($conn->query($sql) === TRUE) {
            // 成功刪除課程後顯示提示框
            echo "<script>alert('課程刪除成功！'); window.location.href='courses.php';</script>";
            exit();
        } else {
            echo "刪除失敗: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>管理介面</title>
    <link href="assets/img/1.jpg" rel="icon" />
    <link href="assets/img/1.jpg" rel="apple-touch-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar vh-100 p-3">
                <h5 class="text-center">選課查詢系統</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="courses.php">課程查詢</a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php">學生查詢</a></li>
                    <li class="nav-item"><a class="nav-link" href="enrollment.php">選課資料</a></li>
                    <li class="nav-item"><a class="nav-link" href="statistics.php">選課統計</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">登出</a></li>
                </ul>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
               <!-- 頁面標頭 -->
                <header class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <a href="index.html" class="d-flex align-items-center text-dark text-decoration-none">
                        <img src="assets/img/2.jpg" width="40px" height="45px" alt="Logo" />
                        <h1 class="h5 ms-2">Top</h1>
                    </a>
                    <nav>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link" href="index.html">首頁</a></li>
                            <li class="nav-item"><a class="nav-link" href="news.html">最新消息</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.html">關於我們</a></li>
                            <li class="nav-item"><a class="nav-link" href="login.html">登入</a></li>
                        </ul>
                    </nav>
                </header>

                <div class="container">
                    <h2>課程查詢</h2>
                    <form action="" method="get">
                        <div class="mb-3">
                            <label for="lesson_name" class="form-label">課程名稱</label>
                            <input type="text" id="lesson_name" name="lesson_name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">系別</label>
                            <input type="text" id="department" name="department" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="prof" class="form-label">教授</label>
                            <input type="text" id="prof" name="prof" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="classroom" class="form-label">教室</label>
                            <input type="text" id="classroom" name="classroom" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">查詢</button>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">新增課程</button>
                        
                    </form>
                    <!-- 顯示查詢結果 -->
                   <?php
if ($_GET) {
    $conditions = [];
    if (!empty($_GET['lesson_name'])) $conditions[] = "name LIKE '%" . $conn->real_escape_string($_GET['lesson_name']) . "%'";
    if (!empty($_GET['department'])) $conditions[] = "department LIKE '%" . $conn->real_escape_string($_GET['department']) . "%'";
    if (!empty($_GET['prof'])) $conditions[] = "prof LIKE '%" . $conn->real_escape_string($_GET['prof']) . "%'";
    if (!empty($_GET['classroom'])) $conditions[] = "classroom LIKE '%" . $conn->real_escape_string($_GET['classroom']) . "%'";

    $sql = "SELECT * FROM lesson";
    if ($conditions) $sql .= " WHERE " . implode(' AND ', $conditions);

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<ul class='list-group'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li class='list-group-item'>
                    課程名稱: {$row['name']} - 系別: {$row['department']} - 教授: {$row['prof']} - 時間: {$row['date']}
                    <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['lessonid']}'>編輯</button>
                    <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['lessonid']}'>刪除</button>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "查無資料";
    }
}
?>

                </div>
            </main>
        </div>
    </div>

    <!-- 新增課程的模態框 -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">新增課程</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">課程名稱</label>
                            <input type="text" id="name" name="name" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">系別</label>
                            <input type="text" id="department" name="department" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="prof" class="form-label">教授</label>
                            <input type="text" id="prof" name="prof" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="classroom" class="form-label">教室</label>
                            <input type="text" id="classroom" name="classroom" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">時間</label>
                            <input type="text" id="date" name="date" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" name="add" class="btn btn-primary">新增</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 編輯課程的模態框 -->
    <?php
    // 編輯模態框
    $result = $conn->query("SELECT * FROM lesson");
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='modal fade' id='editModal{$row['lessonid']}' tabindex='-1' aria-labelledby='editModalLabel{$row['lessonid']}' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$row['lessonid']}'>編輯課程</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='' method='POST'>
                        <div class='modal-body'>
                            <input type='hidden' name='lessonid' value='{$row['lessonid']}' />
                            <div class='mb-3'>
                                <label for='name' class='form-label'>課程名稱</label>
                                <input type='text' id='name' name='name' class='form-control' value='{$row['name']}' required />
                            </div>
                            <div class='mb-3'>
                                <label for='department' class='form-label'>系別</label>
                                <input type='text' id='department' name='department' class='form-control' value='{$row['department']}' required />
                            </div>
                            <div class='mb-3'>
                                <label for='prof' class='form-label'>教授</label>
                                <input type='text' id='prof' name='prof' class='form-control' value='{$row['prof']}' required />
                            </div>
                            <div class='mb-3'>
                                <label for='classroom' class='form-label'>教室</label>
                                <input type='text' id='classroom' name='classroom' class='form-control' value='{$row['classroom']}' required />
                            </div>
                            <div class='mb-3'>
                                <label for='date' class='form-label'>時間</label>
                                <input type='text' id='date' name='date' class='form-control' value='{$row['date']}' required />
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>取消</button>
                            <button type='submit' name='edit' class='btn btn-primary'>更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        ";
    }
    ?>

    <!-- 刪除課程的模態框 -->
    <?php
    // 刪除模態框
    $result = $conn->query("SELECT * FROM lesson");
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='modal fade' id='deleteModal{$row['lessonid']}' tabindex='-1' aria-labelledby='deleteModalLabel{$row['lessonid']}' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='deleteModalLabel{$row['lessonid']}'>刪除課程</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='' method='POST'>
                        <div class='modal-body'>
                            <input type='hidden' name='lessonid' value='{$row['lessonid']}' />
                            <p>您確定要刪除課程: {$row['name']}?</p>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>取消</button>
                            <button type='submit' name='delete' class='btn btn-danger'>刪除</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        ";
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

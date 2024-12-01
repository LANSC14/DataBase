<?php
if (mysqli_connect_errno()) {
    echo "连接至 MySQL 失败: " . mysqli_connect_error();
}

$conn = mysqli_connect('localhost', '113dbb06', '2476-3247', '113dbb06');
mysqli_query($conn, 'SET NAMES utf8');
mysqli_query($conn, 'SET CHARACTER_SET_CLIENT=utf8');
mysqli_query($conn, 'SET CHARACTER_SET_RESULTS=utf8');

// 設置資料庫連線的字符集為 UTF-8
$conn->set_charset("utf8mb4");

// 新增選課資料處理
if (isset($_POST['add_student_id']) && isset($_POST['add_lesson_id'])) {
    $student_id = $conn->real_escape_string($_POST['add_student_id']);
    $lesson_id = $conn->real_escape_string($_POST['add_lesson_id']);
    
    // 檢查是否已經有相同的選課資料
    $check_sql = "SELECT * FROM selection WHERE studentID = '$student_id' AND lessonID = '$lesson_id'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo "<script>alert('該選課資料已存在');</script>";
    } else {
        // 插入選課資料
        $sql_insert = "INSERT INTO selection (studentID, lessonID) VALUES ('$student_id', '$lesson_id')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "<script>alert('選課資料新增成功');</script>";
        } else {
            echo "<script>alert('選課資料新增失敗');</script>";
        }
    }
}

// 刪除選課資料處理
if (isset($_GET['delete_id']) && isset($_GET['lesson_id'])) {
    $student_id = $conn->real_escape_string($_GET['delete_id']);
    $lesson_id = $conn->real_escape_string($_GET['lesson_id']);
    
    // 刪除對應學生和課程的選課資料
    $delete_sql = "DELETE FROM selection WHERE studentID = '$student_id' AND lessonID = '$lesson_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('選課資料刪除成功');</script>";
    } else {
        echo "<script>alert('選課資料刪除失敗');</script>";
    }
}

// 修改選課資料處理
if (isset($_POST['edit_student_id']) && isset($_POST['edit_lesson_id'])) {
    $student_id = $conn->real_escape_string($_POST['edit_student_id']);
    $lesson_id = $conn->real_escape_string($_POST['edit_lesson_id']);
    
    // 更新選課資料
    $update_sql = "UPDATE selection SET studentID = '$student_id', lessonID = '$lesson_id' WHERE studentID = '$student_id' AND lessonID = '$lesson_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('選課資料修改成功');</script>";
    } else {
        echo "<script>alert('選課資料修改失敗');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>管理介面</title>

    <!-- Favicons -->
    <link href="assets/img/1.jpg" rel="icon" />
    <link href="assets/img/1.jpg" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
                    <!-- 選課資料 -->
                    <div id="enrollment" class="my-4">
                        <h2>選課資料查詢</h2>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <select name="enroll_field_1" class="form-select">
                                    <option value="student.name">學生姓名</option>
                                    <option value="student.number">學號</option>
                                    <option value="lesson.name">課程名稱</option>
                                </select>
                                <input type="text" name="enroll_key_1" class="form-control" />
                            </div>
                            <div class="input-group mb-3">
                                <select name="enroll_field_2" class="form-select">
                                    <option value="student.name">學生姓名</option>
                                    <option value="student.number">學號</option>
                                    <option value="lesson.name">課程名稱</option>
                                </select>
                                <input type="text" name="enroll_key_2" class="form-control" />
                            </div>
                            <button class="btn btn-primary" type="submit">查詢</button>
                            <!-- 新增選課資料模態框 -->
                            <button class="btn btn-success my-4" data-bs-toggle="modal" data-bs-target="#addModal">新增選課資料</button>
                        </form>

                        <?php 
                         if (isset($_GET['enroll_field_1']) && isset($_GET['enroll_key_1']) &&
                             isset($_GET['enroll_field_2']) && isset($_GET['enroll_key_2'])) {
        
                            $field1 = $conn->real_escape_string($_GET['enroll_field_1']);
                            $key1 = $conn->real_escape_string($_GET['enroll_key_1']);
                            $field2 = $conn->real_escape_string($_GET['enroll_field_2']);
                            $key2 = $conn->real_escape_string($_GET['enroll_key_2']);
    
                            $sql = "SELECT student.name AS student_name, student.number, lesson.name AS lesson_name, lesson.department, selection.studentID, selection.lessonID 
                                    FROM selection 
                                    INNER JOIN student ON selection.studentID = student.studentid 
                                    INNER JOIN lesson ON selection.lessonID = lesson.lessonid 
                                    WHERE ($field1 LIKE '%$key1%') AND ($field2 LIKE '%$key2%')";
                            $result = $conn->query($sql);
    
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='border rounded p-3 mb-3'>
                                          <p>學生姓名: " . $row['student_name'] . "</p>
                                          <p>學號: " . $row['number'] . "</p>
                                          <p>課程名稱: " . $row['lesson_name'] . "</p>
                                          <p>系所: " . $row['department'] . "</p>
                                          <a href='?delete_id=" . $row['studentID'] . "' class='btn btn-danger'>刪除</a>
                                          <a href='#' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' data-student-id='" . $row['studentID'] . "' data-lesson-id='" . $row['lessonID'] . "'>修改</a>
                                          </div>";
                                }
                            } else {
                                echo "<p>無符合條件的選課資料。</p>";
                            }
                        }
                        ?>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- 新增選課資料模態框 -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">新增選課資料</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="add_student_id" class="form-label">學生</label>
                        <select name="add_student_id" class="form-select" required>
                            <option value="">請選擇學生</option>
                            <?php
                            // 获取所有学生
                            $student_sql = "SELECT studentid, name FROM student";
                            $student_result = $conn->query($student_sql);
                            while ($row = $student_result->fetch_assoc()) {
                                echo "<option value='" . $row['studentid'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_lesson_id" class="form-label">課程</label>
                        <select name="add_lesson_id" class="form-select" required>
                            <option value="">請選擇課程</option>
                            <?php
                            // 获取所有课程
                            $lesson_sql = "SELECT lessonid, name FROM lesson";
                            $lesson_result = $conn->query($lesson_sql);
                            while ($row = $lesson_result->fetch_assoc()) {
                                echo "<option value='" . $row['lessonid'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">新增</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- 修改選課資料模態框 -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">修改選課資料</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="edit_student_id" class="form-label">學生</label>
                            <select name="edit_student_id" class="form-select" id="edit_student_id" required>
                                <option value="">請選擇學生</option>
                                <?php
                                // 獲取所有學生
                                $student_sql = "SELECT studentid, name FROM student";
                                $student_result = $conn->query($student_sql);
                                while ($row = $student_result->fetch_assoc()) {
                                    echo "<option value='" . $row['studentid'] . "'>" . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_lesson_id" class="form-label">課程</label>
                            <select name="edit_lesson_id" class="form-select" id="edit_lesson_id" required>
                                <option value="">請選擇課程</option>
                                <?php
                                // 獲取所有課程
                                $lesson_sql = "SELECT lessonid, name FROM lesson";
                                $lesson_result = $conn->query($lesson_sql);
                                while ($row = $lesson_result->fetch_assoc()) {
                                    echo "<option value='" . $row['lessonid'] . "'>" . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">更新</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

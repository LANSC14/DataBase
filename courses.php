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

// 查詢課程
$search_sql = "SELECT * FROM lesson WHERE 1";
if (isset($_GET['lesson_name']) && !empty($_GET['lesson_name'])) {
    $lesson_name = mysqli_real_escape_string($conn, $_GET['lesson_name']);
    $search_sql .= " AND name LIKE '%$lesson_name%'";
}
if (isset($_GET['department']) && !empty($_GET['department'])) {
    $department = mysqli_real_escape_string($conn, $_GET['department']);
    $search_sql .= " AND department LIKE '%$department%'";
}
if (isset($_GET['prof']) && !empty($_GET['prof'])) {
    $prof = mysqli_real_escape_string($conn, $_GET['prof']);
    $search_sql .= " AND prof LIKE '%$prof%'";
}
if (isset($_GET['classroom']) && !empty($_GET['classroom'])) {
    $classroom = mysqli_real_escape_string($conn, $_GET['classroom']);
    $search_sql .= " AND classroom LIKE '%$classroom%'";
}
$result = $conn->query($search_sql);

// 新增課程
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $prof = mysqli_real_escape_string($conn, $_POST['prof']);
    $classroom = mysqli_real_escape_string($conn, $_POST['classroom']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $insert_sql = "INSERT INTO lesson (name, department, prof, classroom, date) 
                   VALUES ('$name', '$department', '$prof', '$classroom', '$date')";
    if ($conn->query($insert_sql)) {
        echo "<script>alert('課程新增成功'); window.location.href = 'courses.php';</script>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

// 更新課程
if (isset($_POST['edit'])) {
    $lessonid = mysqli_real_escape_string($conn, $_POST['lessonid']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $prof = mysqli_real_escape_string($conn, $_POST['prof']);
    $classroom = mysqli_real_escape_string($conn, $_POST['classroom']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $update_sql = "UPDATE lesson SET name = '$name', department = '$department', prof = '$prof', classroom = '$classroom', date = '$date' WHERE lessonid = $lessonid";
    if ($conn->query($update_sql)) {
        echo "<script>alert('課程更新成功'); window.location.href = 'courses.php';</script>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

// 刪除課程
if (isset($_POST['delete'])) {
    $lessonid = mysqli_real_escape_string($conn, $_POST['lessonid']);
    $delete_sql = "DELETE FROM lesson WHERE lessonid = $lessonid";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('課程刪除成功'); window.location.href = 'courses.php';</script>";
    } else {
        echo "錯誤: " . $conn->error;
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
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>課程名稱</th>
                                <th>系別</th>
                                <th>教授</th>
                                <th>教室</th>
                                <th>時間</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['department']; ?></td>
                                    <td><?php echo $row['prof']; ?></td>
                                    <td><?php echo $row['classroom']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $row['lessonid']; ?>" data-name="<?php echo $row['name']; ?>" data-department="<?php echo $row['department']; ?>" data-prof="<?php echo $row['prof']; ?>" data-classroom="<?php echo $row['classroom']; ?>" data-date="<?php echo $row['date']; ?>">編輯</button>
                                        <form action="" method="post" class="d-inline">
                                            <input type="hidden" name="lessonid" value="<?php echo $row['lessonid']; ?>" />
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm">刪除</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- 新增課程 Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">新增課程</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">課程名稱</label>
                                        <input type="text" class="form-control" name="name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="department" class="form-label">系別</label>
                                        <input type="text" class="form-control" name="department" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="prof" class="form-label">教授</label>
                                        <input type="text" class="form-control" name="prof" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="classroom" class="form-label">教室</label>
                                        <input type="text" class="form-control" name="classroom" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">時間</label>
                                        <input type="text" class="form-control" name="date" required />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary" name="add">新增</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 編輯課程 Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">編輯課程</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="lessonid" id="edit_lessonid" />
                                    <div class="mb-3">
                                        <label for="name" class="form-label">課程名稱</label>
                                        <input type="text" class="form-control" name="name" id="edit_name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="department" class="form-label">系別</label>
                                        <input type="text" class="form-control" name="department" id="edit_department" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="prof" class="form-label">教授</label>
                                        <input type="text" class="form-control" name="prof" id="edit_prof" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="classroom" class="form-label">教室</label>
                                        <input type="text" class="form-control" name="classroom" id="edit_classroom" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">時間</label>
                                        <input type="text" class="form-control" name="date" id="edit_date" required />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary" name="edit">更新</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 編輯課程時自動填充
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; 
            const lessonid = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const department = button.getAttribute('data-department');
            const prof = button.getAttribute('data-prof');
            const classroom = button.getAttribute('data-classroom');
            const date = button.getAttribute('data-date');

            document.getElementById('edit_lessonid').value = lessonid;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_department').value = department;
            document.getElementById('edit_prof').value = prof;
            document.getElementById('edit_classroom').value = classroom;
            document.getElementById('edit_date').value = date;
        });
    </script>
</body>
</html>

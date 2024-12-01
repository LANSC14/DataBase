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

// 查詢學生資料
$search_sql = "SELECT * FROM student WHERE 1";
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $search_sql .= " AND name LIKE '%$name%'";
}
if (isset($_GET['number']) && !empty($_GET['number'])) {
    $number = mysqli_real_escape_string($conn, $_GET['number']);
    $search_sql .= " AND number LIKE '%$number%'";
}
if (isset($_GET['college']) && !empty($_GET['college'])) {
    $college = mysqli_real_escape_string($conn, $_GET['college']);
    $search_sql .= " AND college LIKE '%$college%'";
}
$result = $conn->query($search_sql);

// 新增學生
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $college = mysqli_real_escape_string($conn, $_POST['college']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $insert_sql = "INSERT INTO student (name, number, college, email) 
                   VALUES ('$name', '$number', '$college', '$email')";
    if ($conn->query($insert_sql)) {
        echo "<script>alert('學生新增成功'); window.location.href = 'students.php';</script>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

// 更新學生資料
if (isset($_POST['edit'])) {
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $college = mysqli_real_escape_string($conn, $_POST['college']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $update_sql = "UPDATE student SET name = '$name', number = '$number', college = '$college', email = '$email' WHERE studentid = $studentid";
    if ($conn->query($update_sql)) {
        echo "<script>alert('學生資料更新成功'); window.location.href = 'students.php';</script>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

// 刪除學生資料
if (isset($_POST['delete'])) {
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $delete_sql = "DELETE FROM student WHERE studentid = $studentid";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('學生資料刪除成功'); window.location.href = 'students.php';</script>";
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
    <title>學生查詢管理介面</title>
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
                    <li class="nav-item"><a class="nav-link" href="courses.php">課程查詢</a></li>
                    <li class="nav-item"><a class="nav-link active" href="students.php">學生查詢</a></li>
                    <li class="nav-item"><a class="nav-link" href="enrollment.php">選課資料</a></li>
                    <li class="nav-item"><a class="nav-link" href="statistics.php">選課統計</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">登出</a></li>
                </ul>
            </nav>

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
                    <h2>學生查詢</h2>
                    <form action="" method="get">
                        <div class="mb-3">
                            <label for="name" class="form-label">學生姓名</label>
                            <input type="text" id="name" name="name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">學號</label>
                            <input type="text" id="number" name="number" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="college" class="form-label">學院</label>
                            <input type="text" id="college" name="college" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">查詢</button>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">新增學生</button>
                    </form>
                    <!-- 顯示查詢結果 -->
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>學生姓名</th>
                                <th>學號</th>
                                <th>學院</th>
                                <th>Email</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['number']; ?></td>
                                    <td><?php echo $row['college']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $row['studentid']; ?>" data-name="<?php echo $row['name']; ?>" data-number="<?php echo $row['number']; ?>" data-college="<?php echo $row['college']; ?>" data-email="<?php echo $row['email']; ?>">編輯</button>
                                        <form action="" method="post" class="d-inline">
                                            <input type="hidden" name="studentid" value="<?php echo $row['studentid']; ?>" />
                                            <button type="submit" name="delete" class="btn btn-danger btn-sm">刪除</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- 新增學生 Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">新增學生資料</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">學生姓名</label>
                                        <input type="text" class="form-control" id="name" name="name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="number" class="form-label">學號</label>
                                        <input type="text" class="form-control" id="number" name="number" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="college" class="form-label">學院</label>
                                        <input type="text" class="form-control" id="college" name="college" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required />
                                    </div>
                                    <button type="submit" name="add" class="btn btn-primary">新增</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 編輯學生 Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">編輯學生資料</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <input type="hidden" name="studentid" id="studentid" />
                                    <div class="mb-3">
                                        <label for="edit_name" class="form-label">學生姓名</label>
                                        <input type="text" class="form-control" id="edit_name" name="name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_number" class="form-label">學號</label>
                                        <input type="text" class="form-control" id="edit_number" name="number" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_college" class="form-label">學院</label>
                                        <input type="text" class="form-control" id="edit_college" name="college" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required />
                                    </div>
                                    <button type="submit" name="edit" class="btn btn-primary">更新</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var studentid = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var number = button.getAttribute('data-number');
            var college = button.getAttribute('data-college');
            var email = button.getAttribute('data-email');
            var modal = editModal.querySelector('form');
            modal.querySelector('#studentid').value = studentid;
            modal.querySelector('#edit_name').value = name;
            modal.querySelector('#edit_number').value = number;
            modal.querySelector('#edit_college').value = college;
            modal.querySelector('#edit_email').value = email;
        });
    </script>
</body>
</html>

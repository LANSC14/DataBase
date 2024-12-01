<?php
// 連接資料庫
$conn = mysqli_connect('localhost', '113dbb06', '2476-3247', '113dbb06');
if (mysqli_connect_errno()) {
    echo "连接至 MySQL 失败: " . mysqli_connect_error();
    exit();
}
mysqli_query($conn, 'SET NAMES utf8');
mysqli_query($conn, 'SET CHARACTER_SET_CLIENT=utf8');
mysqli_query($conn, 'SET CHARACTER_SET_RESULTS=utf8');
$conn->set_charset("utf8mb4");

// 新增學生
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $college = $_POST['college'];
    $email = $_POST['email'];
    $sql = "INSERT INTO student (name, number, college, email) VALUES ('$name', '$number', '$college', '$email')";
    if ($conn->query($sql)) {
        echo "<script>alert('新增成功');</script>";
    } else {
        echo "<script>alert('新增失敗');</script>";
    }
}

// 編輯學生
if (isset($_POST['edit'])) {
    $studentid = $_POST['studentid'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $college = $_POST['college'];
    $email = $_POST['email'];
    $sql = "UPDATE student SET name='$name', number='$number', college='$college', email='$email' WHERE studentid=$studentid";
    if ($conn->query($sql)) {
        echo "<script>alert('更新成功');</script>";
    } else {
        echo "<script>alert('更新失敗');</script>";
    }
}

// 刪除學生
if (isset($_POST['delete'])) {
    $studentid = $_POST['studentid'];
    $sql = "DELETE FROM student WHERE studentid=$studentid";
    if ($conn->query($sql)) {
        echo "<script>alert('刪除成功');</script>";
    } else {
        echo "<script>alert('刪除失敗');</script>";
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
                            <label for="student_name" class="form-label">學生姓名</label>
                            <input type="text" id="student_name" name="student_name" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="student_number" class="form-label">學號</label>
                            <input type="text" id="student_number" name="student_number" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="college" class="form-label">學院</label>
                            <input type="text" id="college" name="college" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">查詢</button>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">新增學生</button>
                    </form>

                    <?php
                    if ($_GET) {
                        $conditions = [];
                        if (!empty($_GET['student_name'])) $conditions[] = "name LIKE '%" . $conn->real_escape_string($_GET['student_name']) . "%'";
                        if (!empty($_GET['student_number'])) $conditions[] = "number LIKE '%" . $conn->real_escape_string($_GET['student_number']) . "%'";
                        if (!empty($_GET['college'])) $conditions[] = "college LIKE '%" . $conn->real_escape_string($_GET['college']) . "%'";

                        $sql = "SELECT * FROM student";
                        if ($conditions) $sql .= " WHERE " . implode(' AND ', $conditions);

                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            echo "<ul class='list-group'>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<li class='list-group-item'>
                                        學生姓名: {$row['name']} - 學號: {$row['number']} - 學院: {$row['college']} - 電子郵件: {$row['email']}
                                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['studentid']}'>編輯</button>
                                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['studentid']}'>刪除</button>
                                      </li>";
                                // 編輯學生模態框
                                echo "<div class='modal fade' id='editModal{$row['studentid']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='editModalLabel'>編輯學生</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action='' method='post'>
                                                        <input type='hidden' name='studentid' value='{$row['studentid']}' />
                                                        <div class='mb-3'>
                                                            <label for='name' class='form-label'>學生姓名</label>
                                                            <input type='text' name='name' class='form-control' value='{$row['name']}' required />
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label for='number' class='form-label'>學號</label>
                                                            <input type='text' name='number' class='form-control' value='{$row['number']}' required />
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label for='college' class='form-label'>學院</label>
                                                            <input type='text' name='college' class='form-control' value='{$row['college']}' required />
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label for='email' class='form-label'>電子郵件</label>
                                                            <input type='email' name='email' class='form-control' value='{$row['email']}' required />
                                                        </div>
                                                        <button type='submit' class='btn btn-primary' name='edit'>更新學生</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                      </div>";

                                // 刪除學生模態框
                                echo "<div class='modal fade' id='deleteModal{$row['studentid']}' tabindex='-1' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='deleteModalLabel'>刪除學生</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form action='' method='post'>
                                                        <input type='hidden' name='studentid' value='{$row['studentid']}' />
                                                        <p>確定要刪除學生 {$row['name']} 嗎？</p>
                                                        <button type='submit' class='btn btn-danger' name='delete'>刪除</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                      </div>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>沒有找到符合的資料</p>";
                        }
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- 新增學生模態框 -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">新增學生</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">學生姓名</label>
                            <input type="text" name="name" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">學號</label>
                            <input type="text" name="number" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="college" class="form-label">學院</label>
                            <input type="text" name="college" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">電子郵件</label>
                            <input type="email" name="email" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-success" name="add">新增學生</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

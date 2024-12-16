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

                <!-- Content Section -->
                <div id="statistics" class="my-4">
                    <h2>選課統計</h2>
                    <form action="" method="get">
                        <div class="input-group mb-3">
                            <select name="stat_type" class="form-select">
                                <option value="course" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'course') ? 'selected' : ''; ?>>每門課程選修人數</option>
                                <option value="student" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'student') ? 'selected' : ''; ?>>每位學生選修課程數量</option>
                                <option value="professor" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'professor') ? 'selected' : ''; ?>>每位教授授課的學生人數</option>
                            </select>
                            <button class="btn btn-primary" type="submit">查詢</button>
                            <button class="btn btn-secondary" type="reset" onclick="window.location.href='?';">重製</button>
                            <input type="hidden" name="section" value="statistics">
                        </div>
                    </form>

                    <?php
                    if (isset($_GET['stat_type'])) {
                        $statType = $_GET['stat_type'];
                        $sql = '';

                        if ($statType == 'course') {
                            $sql = "SELECT lesson.name AS course_name, COUNT(selection.studentID) AS student_count FROM lesson LEFT JOIN selection ON lesson.lessonid = selection.lessonID GROUP BY lesson.lessonid ORDER BY student_count DESC;";
                        } elseif ($statType == 'student') {
                            $sql = "SELECT student.name AS student_name, COUNT(selection.lessonID) AS course_count FROM student LEFT JOIN selection ON student.studentid = selection.studentID GROUP BY student.studentid ORDER BY course_count DESC;";
                        } elseif ($statType == 'professor') {
                            $sql = "SELECT lesson.prof AS professor_name, COUNT(selection.studentID) AS student_count FROM lesson LEFT JOIN selection ON lesson.lessonid = selection.lessonID GROUP BY lesson.prof ORDER BY student_count DESC;";
                        }

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo '<table class="table table-striped mt-3">';
                            echo '<thead><tr>';
                            echo $statType == 'course' ? '<th>課程名稱</th><th>選修人數</th>' :
                                ($statType == 'student' ? '<th>學生姓名</th><th>選修課程數量</th>' : '<th>教授姓名</th><th>授課學生人數</th>');
                            echo '</tr></thead><tbody>';

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo $statType == 'course' ? "<td>{$row['course_name']}</td><td>{$row['student_count']}</td>" :
                                    ($statType == 'student' ? "<td>{$row['student_name']}</td><td>{$row['course_count']}</td>" : "<td>{$row['professor_name']}</td><td>{$row['student_count']}</td>");
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo "<p class='text-muted'>沒有找到統計資料</p>";
                        }
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

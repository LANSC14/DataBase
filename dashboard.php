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

// 設定預設顯示區域
$defaultSection = 'courses';
if (isset($_GET['student_field']) && isset($_GET['student_key'])) {
    $defaultSection = 'students';
} elseif (isset($_GET['lesson_field']) && isset($_GET['lesson_key'])) {
    $defaultSection = 'courses';
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
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showSection('courses')">課程</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('students')">學生</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">登出</a>
                    </li>
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
                    <!-- 課程管理 -->
                    <div id="courses" class="my-4">
                        <h2>課程查詢</h2>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <select name="lesson_field" class="form-select">
                                    <option value="name">課程名稱</option>
                                    <option value="department">系別</option>
                                    <option value="prof">教授</option>
                                    <option value="classroom">教室</option>
                                </select>
                                <input type="text" name="lesson_key" class="form-control" />
                                <button class="btn btn-primary" type="submit">查詢</button>
                            </div>
                        </form>

                        <?php 
                        if (isset($_GET['lesson_field']) && isset($_GET['lesson_key'])) {
                            $field = $conn->real_escape_string($_GET['lesson_field']);
                            $key = $conn->real_escape_string($_GET['lesson_key']);

                            $sql = "SELECT lessonid, name, department, prof, classroom, date FROM lesson WHERE $field LIKE '%$key%'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='border-bottom py-2'>課程名稱: " . $row["name"] . " - 系別: " . $row["department"] . " - 教授: " . $row["prof"] . " - 教室: " . $row["classroom"] . " - 日期: " . $row["date"] . "</div>";
                                }
                            } else {
                                echo "<p class='text-muted'>沒有找到資料</p>";
                            }
                        } 
                        ?>
                    </div>

                    <!-- 學生管理 -->
                    <div id="students" class="my-4 d-none">
                        <h2>學生查詢</h2>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <select name="student_field" class="form-select">
                                    <option value="name">名字</option>
                                    <option value="number">學號</option>
                                    <option value="college">系級</option>
                                </select>
                                <input type="text" name="student_key" class="form-control" />
                                <button class="btn btn-primary" type="submit">查詢</button>
                            </div>
                        </form>

                        <?php 
                        if (isset($_GET['student_field']) && isset($_GET['student_key'])) {
                            $field = $conn->real_escape_string($_GET['student_field']);
                            $key = $conn->real_escape_string($_GET['student_key']);

                            $sql = "SELECT studentid, name, number, college, email FROM student WHERE $field LIKE '%$key%'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='border-bottom py-2'>姓名: " . $row["name"] . " - 學號: " . $row["number"] . " - 學院: " . $row["college"] . " - Email: " . $row["email"] . "</div>";
                                }
                            } else {
                                echo "<p class='text-muted'>沒有找到資料</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS 和依賴 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 顯示特定區域的 JavaScript -->
    <script>
        // 設定初始顯示的區域
        const defaultSection = "<?php echo $defaultSection; ?>";
        document.addEventListener("DOMContentLoaded", function() {
            showSection(defaultSection);
        });

        function showSection(sectionId) {
            // 隱藏所有區域，顯示指定的區域
            document.querySelectorAll('.my-4').forEach(section => {
                section.classList.add('d-none');
            });
            document.getElementById(sectionId).classList.remove('d-none');
        }
    </script>
</body>
</html>

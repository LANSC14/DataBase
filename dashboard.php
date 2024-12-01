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
} elseif (isset($_GET['enroll_field']) && isset($_GET['enroll_key'])) {
    $defaultSection = 'enrollment';
} elseif (isset($_GET['stat_type'])) {
    $defaultSection = 'statistics'; // 如果選課統計的參數存在，預設顯示為統計區域
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
                        <a class="nav-link active" href="#" onclick="showSection('courses')">課程查詢</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('students')">學生查詢</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('enrollment')">選課資料</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('statistics')">選課統計</a>
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
                                <!-- 重製按鈕 -->
                                <button class="btn btn-secondary" type="reset" onclick="window.location.href='?';">重製</button>
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
                                <!-- 重製按鈕 -->
                                <button class="btn btn-secondary" type="reset" onclick="window.location.href='?';">重製</button>
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
                  
            <!-- 選課資料 -->
<div id="enrollment" class="my-4 d-none">
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
        <button class="btn btn-secondary" type="reset" onclick="window.location.href='?';">重製</button>
    </form>

    <?php 
    if (isset($_GET['enroll_field_1']) && isset($_GET['enroll_key_1']) &&
        isset($_GET['enroll_field_2']) && isset($_GET['enroll_key_2'])) {
        
        $field1 = $conn->real_escape_string($_GET['enroll_field_1']);
        $key1 = $conn->real_escape_string($_GET['enroll_key_1']);
        $field2 = $conn->real_escape_string($_GET['enroll_field_2']);
        $key2 = $conn->real_escape_string($_GET['enroll_key_2']);
    
        $sql = "SELECT student.name AS student_name, student.number, lesson.name AS lesson_name, lesson.department 
                FROM selection 
                INNER JOIN student ON selection.studentID = student.studentid 
                INNER JOIN lesson ON selection.lessonID = lesson.lessonid 
                WHERE ($field1 LIKE '%$key1%') AND ($field2 LIKE '%$key2%')";
        
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='border-bottom py-2'>學生: " . $row['student_name'] . " - 學號: " . $row['number'] . " - 課程: " . $row['lesson_name'] . " - 系別: " . $row['department'] . "</div>";
            }
        } else {
            echo "<p class='text-muted'>沒有找到資料</p>";
        }
    }
    ?>
</div>


<!-- 選課統計 -->
<div id="statistics" class="my-4 d-none">
    <h2>選課統計</h2>
    <form action="" method="get">
        <div class="input-group mb-3">
            <!-- 搜尋條件選擇 -->
            <select name="stat_type" class="form-select">
                <option value="course" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'course') ? 'selected' : ''; ?>>每門課程選修人數</option>
                <option value="student" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'student') ? 'selected' : ''; ?>>每位學生選修課程數量</option>
                <option value="professor" <?php echo (isset($_GET['stat_type']) && $_GET['stat_type'] == 'professor') ? 'selected' : ''; ?>>每位教授授課的學生人數</option>
            </select>
            <button class="btn btn-primary" type="submit">查詢</button>
            <!-- 重製按鈕 -->
            <button class="btn btn-secondary" type="reset" onclick="window.location.href='?';">重製</button>
            <!-- 新增隱藏的 input 以保持顯示的區域 -->
            <input type="hidden" name="section" value="statistics">
        </div>
    </form>
    
    <?php
    if (isset($_GET['stat_type'])) {
        $statType = $_GET['stat_type'];

        // 根據不同統計類型執行合併查詢
        if ($statType == 'course') {
            // 統計每門課程的選修人數
            $sql = "
                SELECT 
                    lesson.name AS course_name, 
                    COUNT(selection.studentID) AS student_count 
                FROM 
                    lesson 
                LEFT JOIN 
                    selection 
                ON 
                    lesson.lessonid = selection.lessonID 
                GROUP BY 
                    lesson.lessonid
                ORDER BY 
                    student_count DESC;
            ";
        } elseif ($statType == 'student') {
            // 統計每位學生選修的課程數量
            $sql = "
                SELECT 
                    student.name AS student_name, 
                    COUNT(selection.lessonID) AS course_count 
                FROM 
                    student 
                LEFT JOIN 
                    selection 
                ON 
                    student.studentid = selection.studentID 
                GROUP BY 
                    student.studentid
                ORDER BY 
                    course_count DESC;
            ";
        } elseif ($statType == 'professor') {
            // 統計每位教授授課的學生人數
            $sql = "
                SELECT 
                    lesson.prof AS professor_name, 
                    COUNT(selection.studentID) AS student_count 
                FROM 
                    lesson 
                LEFT JOIN 
                    selection 
                ON 
                    lesson.lessonid = selection.lessonID 
                GROUP BY 
                    lesson.prof
                ORDER BY 
                    student_count DESC;
            ";
        }

        // 執行查詢
        $result = $conn->query($sql);

        // 顯示結果
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped mt-3">';
            echo '<thead><tr>';
            
            // 根據查詢類型顯示不同的標題
            if ($statType == 'course') {
                echo '<th>課程名稱</th><th>選修人數</th>';
            } elseif ($statType == 'student') {
                echo '<th>學生姓名</th><th>選修課程數量</th>';
            } elseif ($statType == 'professor') {
                echo '<th>教授姓名</th><th>授課學生人數</th>';
            }

            echo '</tr></thead>';
            echo '<tbody>';

            // 顯示查詢結果
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                if ($statType == 'course') {
                    echo '<td>' . $row['course_name'] . '</td>';
                    echo '<td>' . $row['student_count'] . '</td>';
                } elseif ($statType == 'student') {
                    echo '<td>' . $row['student_name'] . '</td>';
                    echo '<td>' . $row['course_count'] . '</td>';
                } elseif ($statType == 'professor') {
                    echo '<td>' . $row['professor_name'] . '</td>';
                    echo '<td>' . $row['student_count'] . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<p class='text-muted'>沒有找到統計資料</p>";
        }
    }
    

    ?>
</div>

                </div>
            </div>
        </main>
    </div>
</div>

    <!-- JavaScript -->
    <!-- JavaScript -->
<script>
    function showSection(sectionId) {
        document.querySelectorAll('div[id]').forEach(div => {
            div.classList.add('d-none');
        });
        document.getElementById(sectionId).classList.remove('d-none');
    }

    // 預設顯示的區域
    document.addEventListener('DOMContentLoaded', function() {
        showSection('<?php echo $defaultSection; ?>');
    });

    // 表單提交後顯示查詢結果區域
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // 防止默認提交
            const data = new URLSearchParams(new FormData(form));

            fetch(window.location.href.split('?')[0] + '?' + data.toString())
                .then(response => response.text())
                .then(html => {
                    document.body.innerHTML = html;
                    showSection('<?php echo $defaultSection; ?>'); // 顯示查詢結果區域
                });
        });
    });
</script>
</body>
</html>

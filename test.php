<?php
// 連接至 MySQL
$conn = mysqli_connect('localhost', 'root', '', '113dbb06');

if (!$conn) {
    die("連接至 MySQL 失敗: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// 查詢課程資料
if (isset($_POST['search_term'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);
    $query = "SELECT * FROM lesson WHERE name LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    $lessons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $lessons[] = $row;
    }
    echo json_encode($lessons);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>課程查詢</title>
    <link href="assets/img/1.jpg" rel="icon" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/main.css" rel="stylesheet" />
</head>

<body class="portfolio-page">
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="test.php" class="logo d-flex align-items-center">
            <h1 class="sitename">
                <img src="assets/img/2.jpg" width="40px" height="45px" />Top
            </h1>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.html">首頁</a></li>
                <li><a href="news.html">最新消息</a></li>
                <li><a href="students.php">學生查詢</a></li>
                <li><a href="test.php" class="active">課程查詢</a></li>
                <li><a href="personal.html">個人設置</a></li>
                <li><a href="contact.html">聯絡我們</a></li>
                <li><a href="login.html">登入</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

<main class="main">
    <div class="page-title light-background">
        <div class="container">
            <h1>課程查詢</h1>
        </div>
    </div>

    <div class="container">
        <h2>課程查詢</h2>
        <form action="" method="get">
            <select name="lesson_field">
                <option value="name">課程名稱</option>
                <option value="department">系別</option>
                <option value="prof">教授</option>
                <option value="classroom">教室</option>
            </select>
            <input type="text" name="lesson_key" />
            <input type="submit" value="查詢" />
        </form>

        <?php 
        // 如果表單被提交
        if (isset($_GET['lesson_field']) && isset($_GET['lesson_key'])) {
            $field = $_GET['lesson_field']; // 獲取用戶選擇的欄位
            $key = $_GET['lesson_key']; // 獲取用戶輸入的關鍵字

            // 防止SQL注入攻擊
            $field = $conn->real_escape_string($field);
            $key = $conn->real_escape_string($key);

            // 查詢 lesson 資料表中的資料
            $sql = "SELECT lessonid, name, department, prof, classroom, date FROM lesson WHERE $field LIKE '%$key%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo " - 課程名稱: " . $row["name"] . " - 系別: " . $row["department"] . " - 教授: " . $row["prof"] . " - 教室: " . $row["classroom"] . " - 日期: " . $row["date"] . "<br>";
                }
            } else {
                echo "沒有找到資料";
            }
        } ?>
        
        <h3 class="my-4">課程管理</h3>
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="查詢課程名稱" onkeyup="searchLessons()">
        <button class="btn btn-success mb-3" onclick="openLessonForm()">新增課程</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>課程ID</th>
                    <th>名稱</th>
                    <th>系別</th>
                    <th>教授</th>
                    <th>教室</th>
                    <th>日期</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="lessonTable"></tbody>
        </table>
    </div>
</main>

<footer id="footer" class="footer light-background">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-3 mb-3 mb-md-0">
                <div class="widget text-center">
                    <h3 class="widget-heading">聯絡我們</h3>
                    <p>
                        淡水校園：新北市淡水區英專路151號<br />
                        臺北校園：台北市大安區金華街199巷5號<br />
                        蘭陽校園：宜蘭縣礁溪鄉林美村林尾路180號
                    </p>
                    <p class="mb-0">
                <a href="contact.html" class="btn-learn-more">查看更多</a>
              </p>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright d-flex flex-column flex-md-row align-items-center justify-content-center">
        <p>
            © <span>搞笑出版，請勿當真</span>
            <strong class="px-1 sitename">DILS</strong>
            <span>版權所有，翻印必究</span>
        </p>
    </div>
</footer>

<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // 搜索課程
    function searchLessons() {
        const searchTerm = $('#searchInput').val();
        $.post('', { search_term: searchTerm }, function(response) {
            const lessons = JSON.parse(response);
            let rows = '';
            lessons.forEach(lesson => {
                rows += `
                    <tr>
                        <td>${lesson.lessonid}</td>
                        <td>${lesson.name}</td>
                        <td>${lesson.department}</td>
                        <td>${lesson.prof}</td>
                        <td>${lesson.classroom}</td>
                        <td>${lesson.date}</td>
                        <td>
                            <button class="btn btn-warning" onclick="openLessonForm(${lesson.lessonid})">編輯</button>
                            <button class="btn btn-danger" onclick="deleteLesson(${lesson.lessonid})">刪除</button>
                        </td>
                    </tr>
                `;
            });
            $('#lessonTable').html(rows);
        });
    }

    // 開啟新增/編輯課程表單
    function openLessonForm(id = null) {
        $('#formContent').html(`
            <div class="mb-3">
                <label for="lessonName" class="form-label">課程名稱</label>
                <input type="text" class="form-control" id="lessonName" required>
            </div>
            <div class="mb-3">
                <label for="lessonDepartment" class="form-label">系別</label>
                <input type="text" class="form-control" id="lessonDepartment" required>
            </div>
            <div class="mb-3">
                <label for="lessonProf" class="form-label">教授</label>
                <input type="text" class="form-control" id="lessonProf" required>
            </div>
            <div class="mb-3">
                <label for="lessonClassroom" class="form-label">教室</label>
                <input type="text" class="form-control" id="lessonClassroom" required>
            </div>
            <div class="mb-3">
                <label for="lessonDate" class="form-label">日期</label>
                <input type="date" class="form-control" id="lessonDate" required>
            </div>
            <input type="hidden" id="lessonId" value="${id}">
            <button class="btn btn-primary" onclick="saveLesson()">儲存</button>
        `);
        $('#lessonFormModal').modal('show');
    }

    // 儲存課程
    function saveLesson() {
        const id = $('#lessonId').val();
        const name = $('#lessonName').val();
        const department = $('#lessonDepartment').val();
        const prof = $('#lessonProf').val();
        const classroom = $('#lessonClassroom').val();
        const date = $('#lessonDate').val();
        
        const data = {
            lessonid: id,
            name: name,
            department: department,
            prof: prof,
            classroom: classroom,
            date: date,
        };

        $.post('save_lesson.php', data, function(response) {
            alert(response);
            searchLessons(); // Refresh lesson table
            $('#lessonFormModal').modal('hide');
        });
    }

    // 刪除課程
    function deleteLesson(id) {
        if (confirm('確定要刪除這個課程嗎？')) {
            $.post('delete_lesson.php', { lessonid: id }, function(response) {
                alert(response);
                searchLessons(); // Refresh lesson table
            });
        }
    }
</script>

<!-- Modal for lesson form -->
<div class="modal fade" id="lessonFormModal" tabindex="-1" aria-labelledby="lessonFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lessonFormModalLabel">新增/編輯課程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="formContent"></div>
        </div>
    </div>
</div>

</body>
</html>

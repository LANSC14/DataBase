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
    $query = "SELECT * FROM lessons WHERE name LIKE '%$searchTerm%'";
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
    <title>管理介面</title>

    <!-- Favicons -->
    <link href="assets/img/1.jpg" rel="icon" />
    <link href="assets/img/1.jpg" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    
    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet" />
</head>

<body class="services-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <h1 class="sitename">
                    <img src="assets/img/2.jpg" width="40px" height="45px" alt="Logo" />Top
                </h1>
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.html" class="active">首頁</a></li>
                    <li><a href="news.html">最新消息</a></li>
                    <li><a href="students.php">學生查詢</a></li>
                    <li><a href="lesson.html">課程查詢</a></li>
                    <li><a href="personal.html">個人設置</a></li>
                    <li><a href="contact.html">聯絡我們</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                    <div class="position-sticky pt-3">
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
                    </div>
                </nav>

                <!-- Main Content -->
                <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4">
                    <!-- 課程管理 -->
                    <div id="courses" class="content-section">
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

                    <!-- 學生管理 -->
                    <div id="students" class="content-section d-none">
                        <h3 class="my-4">學生管理</h3>
                        <button class="btn btn-success mb-3" onclick="openStudentForm()">新增學生</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>學生ID</th>
                                    <th>姓名</th>
                                    <th>學號</th>
                                    <th>學院</th>
                                    <th>Email</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="studentTable"></tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>

        <!-- 新增/編輯表單 Modal -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">新增/編輯</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formContent"></form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                        <button type="button" class="btn btn-primary" onclick="submitForm()">儲存變更</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Javascript & AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 顯示指定的內容區域
        function showSection(section) {
            $('.content-section').addClass('d-none'); // 隱藏所有內容區域
            $('#' + section).removeClass('d-none'); // 顯示所選的內容區域
            if (section === 'courses') {
                loadLessons(); // 加載課程資料
            }
        }

        // 加載課程資料
        function loadLessons() {
            $.get('load_lessons.php', function(data) {
                $('#lessonTable').html(data);
            });
        }

        // 搜索課程
        function searchLessons() {
            const searchTerm = $('#searchInput').val();
            $.post('', { search_term: searchTerm }, function(response) {
                const lessons = JSON.parse(response);
                let rows = '';
                lessons.forEach(lesson => {
                    rows += `
                        <tr>
                            <td>${lesson.id}</td>
                            <td>${lesson.name}</td>
                            <td>${lesson.department}</td>
                            <td>${lesson.prof}</td>
                            <td>${lesson.classroom}</td>
                            <td>${lesson.date}</td>
                            <td>
                                <button class="btn btn-warning" onclick="openLessonForm(${lesson.id})">編輯</button>
                                <button class="btn btn-danger" onclick="deleteLesson(${lesson.id})">刪除</button>
                            </td>
                        </tr>
                    `;
                });
                $('#lessonTable').html(rows);
            });
        }

        // 開啟新增/編輯課程表單
        function openLessonForm(id = null) {
            // 當 id 不為 null 時，載入該課程的資訊以供編輯
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
            `);
            $('#formModal').modal('show');
        }

        // 提交表單
        function submitForm() {
            const id = $('#lessonId').val();
            const name = $('#lessonName').val();
            const department = $('#lessonDepartment').val();
            const prof = $('#lessonProf').val();
            const classroom = $('#lessonClassroom').val();
            const date = $('#lessonDate').val();
            const action = id ? 'edit' : 'add';

            $.post('lesson_action.php', { id, name, department, prof, classroom, date, action }, function() {
                $('#formModal').modal('hide');
                loadLessons(); // 重新加載課程
            });
        }

        // 刪除課程
        function deleteLesson(id) {
            if (confirm('確定要刪除這個課程嗎？')) {
                $.post('lesson_action.php', { id, action: 'delete' }, function() {
                    loadLessons(); // 重新加載課程
                });
            }
        }
    </script>
</body>
</html>

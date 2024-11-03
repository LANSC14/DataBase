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
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>課程查詢</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Favicons -->
    <link href="assets/img/1.jpg" rel="icon" />
    <link href="assets/img/1.jpg" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />
    <link
      href="assets/vendor/glightbox/css/glightbox.min.css"
      rel="stylesheet"
    />

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet" />
  </head>

  <body class="portfolio-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
      <div
        class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between"
      >
        <a href="index.html" class="logo d-flex align-items-center">
          <h1 class="sitename">
            <img src="assets/img/2.jpg" width="40px" height="45px" />Top
          </h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="index.html" >首頁</a></li>
            <li><a href="news.html">最新消息</a></li>
            <li><a href="students.php" >學生查詢</a></li>
            <li><a href="lesson.html" class="active">課程查詢</a></li>
            
            <li><a href="contact.html">關於我們</a></li>
            <li><a href="login.html">登入</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </header>

<main class="main">
      <!-- Page Title -->
      <div class="page-title light-background">
        <div class="container">
          <h1>課程查詢</h1>
          <nav class="breadcrumbs">
            <ol>
              <li><a href="index.html">首頁</a></li>
              <li class="current">課程查詢</li>
            </ol>
          </nav>
        </div>
      </div>
      <!-- End Page Title -->

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
        } 
        ?>
  
    </div>
</main>

 <footer id="footer" class="footer light-background">
      <div class="container">
        <div class="row g-4 justify-content-center">
          <div class="col-md-6 col-lg-3 mb-3 mb-md-0">
            <div class="widget text-center">
              <!-- Added text-center class here -->
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

      <div
        class="copyright d-flex flex-column flex-md-row align-items-center justify-content-center"
      >
        <p>
          © <span>搞笑出版，請勿當真</span>
          <strong class="px-1 sitename">DILS</strong>
          <span>版權所有，翻印必究</span>
        </p>
      </div>
    </footer>

   
    <!-- Scroll Top -->
    <a
      href="#"
      id="scroll-top"
      class="scroll-top d-flex align-items-center justify-content-center"
      ><i class="bi bi-arrow-up-short"></i
    ></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/js/main.js"></script>
  </body>
</html>
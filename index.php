
<?php
header("Content-Type: text/html; charset=UTF-8");
include 'database.php';

// เปิดการแสดงข้อผิดพลาดเพื่อดีบั๊ก
error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT id, title, author, publisher, image_url, status FROM books";
$result = $conn->query($sql);
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ห้องสมุดวิทยาลัย</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            background: url('พื้นหลัง.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .main-container {
            background: rgba(255, 255, 255, 0.8);
            margin-left: 280px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: calc(100% - 280px);
            box-sizing: border-box;
            border-radius: 10px;
        }
        .header {
            background: rgba(179, 58, 58, 0.9);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 1.8em;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .header img {
            height: 80px;
            max-width: 120px;
            margin-right: 20px;
        }
        .navbar {
            background: rgba(204, 85, 85, 0.9);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
        }
        .sidebar {
            width: 250px;
            background: rgba(216, 108, 108, 0.9);
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .sidebar h3 {
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 5px;
            text-align: center;
            background: rgba(224, 123, 123, 0.9);
            transition: 0.3s;
        }
        .sidebar ul li:hover {
            background: rgba(255, 153, 153, 0.9);
        }
        .book-list {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            width: 100%;
        }
        .book {
            width: 280px;
            background: rgba(251, 228, 228, 0.9);
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            padding: 20px;
            transition: 0.3s;
            text-align: center;
        }
        .book:hover {
            background: rgba(247, 217, 217, 0.9);
        }
        .book img {
            width: 100%;
            border-radius: 5px;
        }
        /* 🎨 ปุ่มล็อกอินที่มุมขวาล่าง */
.login-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #007bff;
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 18px;
    border: none;
    border-radius: 30px;
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

/* ✅ ไอคอนล็อกอิน */
.login-btn i {
    font-size: 18px;
    margin-right: 8px;
}

/* ✅ เอฟเฟกต์ hover */
.login-btn:hover {
    background: #0056b3;
    transform: scale(1.05);
}
    </style>
</head>
<body>
<a href="login.php" class="login-btn">
    <i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ
</a>
    <div class="sidebar">
        <h3>เมนูหลัก</h3>
        <ul>
            <li href="#">หน้าหลัก</li>
        </ul>
    </div>
    <div class="main-container">
        <div class="header">
            <img src="รูปภาพ1.png" alt="โลโก้วิทยาลัย" onerror="this.style.display='none'">
            <h2>ห้องสมุดวิทยาลัย</h2>
        </div>
        <div class="navbar">
            <a href="borrow.php">ยืมหนังสือ</a>
            <a href="history.php">ประวัติการยืม</a>
            <a href="#">ติดต่อ</a>
        </div>
        <h3>รายการหนังสือ</h3>
        <input type="text" id="searchBar" placeholder="ค้นหาหนังสือ..." onkeyup="searchBooks()" style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 25px; border: 1px solid #ccc; font-size: 1.1em; text-align: center; transition: 0.3s; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); outline: none;">
        <div id="book-list" class="book-list">
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <img src="<?= $book['image_url'] ?>" alt="Book Cover">
                    <div>
                        <strong><?= $book['title'] ?></strong><br>
                        <small>ผู้แต่ง: <?= $book['author'] ?></small><br>
                        <small>สำนักพิมพ์: <?= $book['publisher'] ?></small><br>
                        <small>สถานะ: <span style="color: <?= $book['status'] === 'available' ? 'green' : 'red' ?>;">
                            <?= $book['status'] === 'available' ? 'พร้อมให้ยืม' : 'ถูกยืมไปแล้ว' ?></span></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function searchBooks() {
            let input = document.getElementById('searchBar').value.toLowerCase();
            let books = document.getElementsByClassName('book');
            for (let i = 0; i < books.length; i++) {
                let title = books[i].getElementsByTagName('strong')[0].innerText.toLowerCase();
                if (title.includes(input)) {
                    books[i].style.display = "block";
                } else {
                    books[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>

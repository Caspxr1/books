<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $class = $_POST['class'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    if ($role === 'admin') {
        $table = 'registered_admins';
        $sql = "INSERT INTO $table (fullname, email, username, password) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $fullname, $email, $username, $password);
        }
    } else {
        $table = 'registered_users';
        $sql = "INSERT INTO $table (fullname, student_id, class, department, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $fullname, $student_id, $class, $department, $email, $username, $password);
        }
    }
    
    if ($stmt->execute()) {
        header("Location: login.php?registered=true");
        exit();
    } else {
        $error = "ไม่สามารถสมัครสมาชิกได้";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: url('พื้นหลัง.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        .role-selection {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }
        .role-selection button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
        }
        .user-btn {
            background: #4CAF50;
            color: white;
        }
        .admin-btn {
            background: #b33a3a;
            color: white;
        }
        .selected {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }
        input {
            width: calc(100% - 20px);
            padding: 12px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        button[type="submit"] {
            width: calc(100% - 20px);
            padding: 12px;
            background: #b33a3a;
            color: white;
            border: none;
            border-radius: 8px;
            margin-top: 15px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
        }
        button[type="submit"]:hover {
            background: #922d2d;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .login-link {
            margin-top: 15px;
            font-size: 0.9em;
        }
        .login-link a {
            color: #b33a3a;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        input[name='role'] {
            display: none;
        }
    </style>
    <script>
        function setRole(role) {
            document.getElementById('role').value = role;
            document.querySelector('.user-btn').classList.remove('selected');
            document.querySelector('.admin-btn').classList.remove('selected');
            document.querySelector(`.${role}-btn`).classList.add('selected');
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>สมัครสมาชิก</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="hidden" name="role" id="role" value="user">
            <div class="role-selection">
                <button type="button" class="user-btn selected" onclick="setRole('user')">ผู้ใช้</button>
                <button type="button" class="admin-btn" onclick="setRole('admin')">แอดมิน</button>
            </div>
            <input type="text" name="fullname" placeholder="ชื่อ - นามสกุล" required>
            <input type="text" name="student_id" placeholder="รหัสนักศึกษา" required>
            <input type="text" name="class" placeholder="ระดับชั้น" required>
            <input type="text" name="department" placeholder="สาขา" required>
            <input type="email" name="email" placeholder="อีเมล์" required>
            <br>
            <br>
            <h3>กรอกชื่อผู้ใช้และรหัสผ่าน</h3>
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit">สมัครสมาชิก</button>
        </form>
        <p class="login-link">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>

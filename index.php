<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "gym");

if (isset($_SESSION['username'])) {
    echo "<script>location.replace('home.php')</script>";
}

$error = false;
if (isset($_POST['login'])) {
    $select = "SELECT * FROM `admin` where username = '{$_POST['username']}' and `password` = '{$_POST['password']}'";

    $selectResult = mysqli_query($con, $select);
    if (mysqli_num_rows($selectResult) > 0) {
        $_SESSION['username'] = $_POST['username'];
        echo "<script>location.replace('home.php')</script>";
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <div id="login">
        <h1>GYMNAST</h1>

        <?php if ($error === true) { ?>
            <div class="alert-danger">
                <p>أدخل اسم المستخدم و كلمة المرور بطريقة صحيحة</p>
            </div>
        <?php } ?>

        <form action="" method="POST" autocomplete="off">
            <h2>تسجيل الدخول</h2>
            <div class="box">
                <label for="username">اسم المستخدم</label>
                <input type="text" name="username" id="username" required autofocus>
            </div>
            <div class="box">
                <label for="pass">كلمة المرور</label>
                <input type="password" name="password" id="pass" required>
            </div>
            <input type="submit" name="login" value="تسجيل الدخول">
        </form>
    </div>
</body>

</html>
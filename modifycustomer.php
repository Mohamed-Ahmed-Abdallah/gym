<?php

session_start();
if (!isset($_SESSION['username']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>location.replace('index.php')</script>";
}

$con = mysqli_connect("localhost", "root", "", "gym");

if (isset($_GET['id'])) {
    $_SESSION['id'] = $_GET['id'];
}

if (isset($_POST["modifycustomer"])) {
    $_POST['name'] = htmlspecialchars($_POST['name']);
    $_POST['phone'] = htmlspecialchars($_POST['phone']);

    $update = "UPDATE users SET `Name` = '{$_POST['name']}', Phone = '{$_POST['phone']}' WHERE ID = {$_SESSION['id']}";
    mysqli_query($con, $update);

    echo   "<script>
                window.onload = function() {
                    document.querySelector('.alert-success').style.display = 'block';
                    setTimeout(function() {
                        document.querySelector('.alert-success').style.display = null;
                        location.replace('home.php');
                    }, 3000)
                }
            </script>";
}

$select = "SELECT * FROM training";
$selectResult1 = mysqli_query($con, $select);

$select = "SELECT * FROM users where ID = {$_SESSION['id']}";
$selectResult2 = mysqli_fetch_assoc(mysqli_query($con, $select));
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
    <div id="container">
        <div class='alert-success'>
            <p>تم تعديل البيانات بنجاح</p>
        </div>

        <div class="nav">
            <h1>GYMNAST</h1>
            <div class="links">
                <a href="./home.php">الصفحة الرئيسية (عرض الزبائن)</a>
                <a href="?logout=l">تسجيل الخروج</a>
            </div>
        </div>

        <form action="" autocomplete="off" method="POST" class="update">
            <h2>تعديل بيانات الزبون</h2>
            <div class="bigbox">
                <div class="smallbox">
                    <div>
                        <label for="name">الاسم</label>
                        <input type="text" name="name" id="name" value="<?php echo $selectResult2['Name'] ?>">
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">اكتب اسم لا يقل عن ثلاثة أحرف</p>
                    </div>

                    <label for="trainingtype">نوع التدريب</label>
                    <select name="trainingtype" id="trainingtype" disabled>
                        <?php foreach ($selectResult1 as $data) { ?>
                            <?php if ($data["Type"] === $selectResult2["TrainingType"]) { ?>
                                <option value="<?php echo $data["TrainingID"] ?>" selected>
                                    <?php echo $data["Type"] ?>
                                </option>
                            <?php } else { ?>
                                <option value="<?php echo $data["TrainingID"] ?>">
                                    <?php echo $data["Type"] ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

                <div class="smallbox">
                    <div>
                        <label for="phone">رقم التليفون</label>
                        <input type="text" name="phone" id="phone" value="<?php echo $selectResult2['Phone'] ?>">
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">أدخل رقم الهاتف بحيث يكون 11 رقم فقط</p>
                    </div>
                    <label for="subscriptionduration">مدة الاشتراك</label>
                    <select name="subscriptionduration" id="subscriptionduration" disabled>
                        <option value="1">شهر</option>
                        <option value="2">سنة كاملة</option>
                    </select>
                </div>

                <div class="smallbox">
                    <input type="submit" value="تعديل البيانات" name="modifycustomer">
                </div>
            </div>
        </form>
    </div>

    <script>
        document.forms[0].onsubmit = function(e) {
            let name = document.querySelector("[name='name']");
            let phone = document.querySelector("[name='phone']");

            let validation = true;

            if (name.value.trim().length < 3) {
                name.nextElementSibling.style.display = "block";
                validation = false;
            } else {
                name.nextElementSibling.style.display = "none";
            }

            if (phone.value.trim().length !== 11) {
                phone.nextElementSibling.style.display = "block";
                validation = false;
            } else {
                phone.nextElementSibling.style.display = "none";
            }

            if (validation === false) {
                e.preventDefault();
            }
        }


        let durationOptions = document.querySelectorAll("#subscriptionduration option");
        for (let i = 0; i < durationOptions.length; i++) {
            if (durationOptions[i].innerText.includes("<?php echo $selectResult2['SubscriptionDuration'] ?>")) {
                durationOptions[i].setAttribute("selected", "");
                break;
            }
        }
    </script>

</body>

</html>
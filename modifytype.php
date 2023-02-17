<?php

session_start();
if (!isset($_SESSION['username']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>location.replace('index.php')</script>";
}

$con = mysqli_connect("localhost", "root", "", "gym");

if (isset($_POST["modifycustomer"])) {
    $_POST['price'] = htmlspecialchars($_POST['price']);

    $update = "UPDATE training SET `Price` = {$_POST['price']} WHERE TrainingID = {$_POST['trainingtype']}";
    mysqli_query($con, $update);

    echo   "<script>
                window.onload = function() {
                    document.querySelector('.alert-success').style.display = 'block';
                    setTimeout(function() {
                        document.querySelector('.alert-success').style.display = null;
                        location.replace('modifytype.php');
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
            <p>تم تعديل الصنف بنجاح</p>
        </div>

        <div class="nav">
            <h1>GYMNAST</h1>
            <div class="links">
                <a href="./home.php">الصفحة الرئيسية (عرض الزبائن)</a>
                <a href="?logout=l">تسجيل الخروج</a>
            </div>
        </div>

        <form action="" autocomplete="off" method="POST" class="update">
            <h2>تعديل الأصناف</h2>
            <div class="bigbox">
                <div class="smallbox">
                    <label for="trainingtype">نوع التدريب</label>
                    <select name="trainingtype" id="trainingtype">
                        <?php foreach ($selectResult1 as $data) { ?>
                            <option value="<?php echo $data["TrainingID"] ?>" selected>
                                <?php echo $data["Type"] ?> (<?php echo $data["Price"] ?> <bdi>جنيه شهريا</bdi>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="smallbox">
                    <label for="price">السعر</label>
                    <input type="number" name="price" id="price">
                    <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">اكتب سعر لا يقل عن صفر</p>
                </div>
                <div class="smallbox">
                    <input type="submit" value="تعديل الصنف" name="modifycustomer">
                </div>
            </div>
        </form>
    </div>

    <script>
        document.forms[0].onsubmit = function(e) {
            let price = document.querySelector("[name='price']");

            let validation = true;

            if (price.value.trim() === "" || Number(price.value) < 0) {
                price.nextElementSibling.style.display = "block";
                validation = false;
            } else {
                price.nextElementSibling.style.display = "none";
            }

            if (validation === false) {
                e.preventDefault();
            }
        }
    </script>

</body>

</html>
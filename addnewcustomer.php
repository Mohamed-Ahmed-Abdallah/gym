<?php

session_start();
if (!isset($_SESSION['username']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>location.replace('index.php')</script>";
}

$con = mysqli_connect("localhost", "root", "", "gym");


if (isset($_POST["addcustomer"])) {

    $_POST['name'] = htmlspecialchars($_POST['name']);
    $_POST['phone'] = htmlspecialchars($_POST['phone']);
    $_POST['discount'] = htmlspecialchars($_POST['discount']);

    $select = "SELECT * FROM training WHERE TrainingID = '{$_POST['trainingtype']}'";
    $selectResult1 = mysqli_fetch_assoc(mysqli_query($con, $select));

    if ($_POST['subscriptionduration'] === "1") {
        $totalPayment = $selectResult1['Price'];
        $durationWord = "شهر";
        $subscriptionTime = strtotime(date("Y/m/d"));
        $resubscriptionTime = strtotime("+1 Months");
    } else {
        $totalPayment = (12 * $selectResult1['Price']) - (12 * $selectResult1['Price'] * ($_POST['discount'] / 100));
        $durationWord = "سنة كاملة";
        $subscriptionTime = strtotime(date("Y/m/d"));
        $resubscriptionTime = strtotime("+1 Years");
    }

    $insert = "INSERT INTO users VALUES (
        NULL, '{$_POST['name']}', '{$_POST['phone']}', '{$selectResult1['Type']}', $subscriptionTime,
        '{$durationWord}', {$totalPayment}, $resubscriptionTime)";

    mysqli_query($con, $insert);

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
$selectResult = mysqli_query($con, $select);
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
            <p>تم إضافة زبون جديد بنجاح</p>
        </div>

        <div class="nav">
            <h1>GYMNAST</h1>
            <div class="links">
                <a href="./home.php">الصفحة الرئيسية (عرض الزبائن)</a>
                <a href="?logout=l">تسجيل الخروج</a>
            </div>
        </div>

        <form autocomplete="off" method="POST">
            <h2>إضافة زبون جديد</h2>
            <div class="bigbox">
                <div class="smallbox">
                    <div>
                        <label for="name">الاسم</label>
                        <input type="text" name="name" id="name" autofocus>
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">اكتب اسم لا يقل عن ثلاثة أحرف</p>
                    </div>

                    <div>
                        <label for="trainingtype">نوع التدريب</label>
                        <select name="trainingtype" id="trainingtype">
                            <option value="">-----</option>
                            <?php foreach ($selectResult as $data) { ?>
                                <option value="<?php echo $data["TrainingID"] ?>">
                                    <?php echo $data["Type"] ?> (<?php echo $data["Price"] ?> <bdi>جنيه شهريا</bdi>)
                                </option>
                            <?php } ?>
                        </select>
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">اختر صنف من القائمة</p>
                    </div>
                </div>

                <div class="smallbox">
                    <div>
                        <label for="phone">رقم التليفون</label>
                        <input type="text" name="phone" id="phone">
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">أدخل رقم الهاتف بحيث يكون 11 رقم فقط</p>
                    </div>
                    <div>
                        <label for="subscriptionduration">مدة الاشتراك</label>
                        <select name="subscriptionduration" id="subscriptionduration">
                            <option value="">-----</option>
                            <option value="1">شهر</option>
                            <option value="2">سنة كاملة</option>
                        </select>
                        <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">اختر صنف من القائمة</p>
                    </div>
                </div>

                <div class="smallbox" style="display: none" id="discountdiv">
                    <label for="discount">نسبة الخصم للاشتراك السنوي</label>
                    <input type="number" name="discount" id="discount">
                    <p style="width: fit-content; margin-bottom: 8px; padding: 5px; font-weight: bold; background-color: rgb(238, 172, 195); color: red; display: none">أدخل نسبة خصم لا تقل عن صفر</p>
                </div>

                <div class="smallbox">
                    <input type="submit" value="سجل الزبون الأن" name="addcustomer">
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    document.forms[0].onsubmit = function(e) {
        let name = document.querySelector("[name='name']");
        let phone = document.querySelector("[name='phone']");
        let selectList = document.querySelector("#subscriptionduration");
        let selectList2 = document.getElementById("trainingtype");
        let discount = document.getElementById("discount");

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

        if (selectList.value === "") {
            selectList.nextElementSibling.style.display = "block";
            validation = false;
        } else {
            selectList.nextElementSibling.style.display = "none";
        }

        if (selectList2.value === "") {
            selectList2.nextElementSibling.style.display = "block";
            validation = false;
        } else {
            selectList2.nextElementSibling.style.display = "none";
        }


        if (discount.parentElement.style.display !== "none") {
            if (discount.value.trim() === "" || Number(discount.value) < 0) {
                discount.nextElementSibling.style.display = "block";
                validation = false;
            } else {
                discount.nextElementSibling.style.display = "none";
            }
        }

        if (validation === false) {
            e.preventDefault();
        }
    }

    let discountDiv = document.getElementById("discountdiv");
    // important code for select.
    let selectList = document.querySelector("#subscriptionduration");
    selectList.onchange = () => {
        if (selectList.value === "2") {
            discountDiv.style.display = 'block';
        } else {
            discountDiv.style.display = 'none';
        }
    }
</script>

</html>
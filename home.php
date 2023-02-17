<?php
session_start();
if (!isset($_SESSION['username']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>location.replace('index.php')</script>";
}

$con = mysqli_connect("localhost", "root", "", "gym");

if (isset($_GET['remove'])) {
    $delete = "DELETE FROM users WHERE ID = {$_GET['remove']}";
    mysqli_query($con, $delete);

    echo   "<script>
                window.onload = function() {
                    document.querySelector('.alert-success').style.display = 'block';
                    setTimeout(function() {
                        document.querySelector('.alert-success').style.display = null;
                        location.replace('home.php');
                    }, 2000)
                }
            </script>";
}

$select = "SELECT * FROM users";
$selectResult = mysqli_query($con, $select);

// print_r($selectResult)
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
            <p>تم حذف الزبون بنجاح</p>
        </div>

        <div class="nav">
            <h1>GYMNAST</h1>
            <div class="links">
                <a href="./addnewcustomer.php">إضافة زبون جديد</a>
                <a href="./modifytype.php">تعديل صنف</a>
                <a href="?logout=l">تسجيل الخروج</a>
            </div>
        </div>

        <input type="search" class="search" oninput="filterNames()" placeholder="ابحث باسم الزبون">
        <table>
            <tr>
                <th>الاسم</th>
                <th>رقم التليفون</th>
                <th>نوع التدريب</th>
                <th>بداية الاشتراك</th>
                <th>مدة الاشتراك</th>
                <th>تجديد الاشتراك</th>
                <th>القيمة المدفوعة</th>
                <th colspan="2">إجراءات</th>
            </tr>
            <?php if ($selectResult !== NULL) { ?>
                <?php foreach ($selectResult as $data) { ?>
                    <tr>
                        <td><?php echo $data['Name'] ?></td>
                        <td><?php echo $data['Phone'] ?></td>
                        <td><?php echo $data['TrainingType'] ?></td>
                        <td><?php echo date("Y/m/d", $data['SubscriptionStart']) ?></td>
                        <td><?php echo $data['SubscriptionDuration'] ?></td>
                        <td><?php echo date("Y/m/d", $data['Resubscripe']) ?></td>
                        <td><?php echo $data['Payment'] ?> <bdi>جنيه</bdi></td>
                        <td><a href="./modifycustomer.php?id=<?php echo $data['ID'] ?>">تعديل البيانات</a></td>
                        <td><a href="?remove=<?php echo $data['ID'] ?>">حذف الزبون</a></td>
                    </tr>
            <?php }
            } ?>
        </table>
    </div>

    <script>
        function filterNames() {
            let customerTableRows = document.querySelectorAll("#container table tr");
            let searchValue = document.querySelector("#container .search");

            let sv = searchValue.value.trim(); // sv variable stores value after removing unneeded spaces.
            for (let i = 1; i < customerTableRows.length; i++) {
                if (!customerTableRows[i].children[0].innerText.toLowerCase().startsWith(sv.toLowerCase())) {
                    customerTableRows[i].style.display = "none";
                } else {
                    customerTableRows[i].style.display = null;
                }
            }
        }
    </script>
</body>

</html>
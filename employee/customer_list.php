<?php
    session_start();
    if (!isset($_SESSION['employee_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE id = :id");
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        #ลบข้อมูล user
        $delete_stmt = $db->prepare('DELETE FROM masterlogin WHERE id = :id');
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();

        header('Location:customer_list.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMPLOYEE PAGE</title>
    
    <link rel="stylesheet" href="css/employee.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="text-center">
        <div class="container1 background-container-header">

            <?php if(isset($_SESSION['success'])) : ?>
                <div class="alert alert-success">
                    <h3>
                        <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <h1>Employee Page</h1>

            <h3>
                <?php if(isset($_SESSION['employee_login'])) { ?>
                Welcome, <?php echo $_SESSION['employee_login']; }?>
            </h3>

        </div>
    </div>

    <div class="container1 background-container-menu">
        <div class="container2">
            <div class="sidebar">

                <a href="employee_home.php" class="sidebar-menu">
                    หน้าแรก
                </a>

                <a href="customer_list.php#" class="sidebar-menu">
                    รายชื่อลูกค้า
                </a>

                <a href="product_list.php" class="sidebar-menu">
                    รายการสินค้า
                </a>

                <a href="order_history.php" class="sidebar-menu">
                    ประวัติรายการสั่งซื้อ
                </a>

                <a href="employee_product.php" class="sidebar-menu">
                    สินค้า
                </a>


                <?php
                    if (isset($_SESSION['employee_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["employee_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <a href="employee_profile.php?update_id=<?php echo $row["id"]; ?>" class="sidebar-menu">
                    โปรไฟล์
                </a>
                

                <?php } }?>

                <hr>
                
                <a href="../logout.php" class="sidebar-menu btn-danger" style="border-radius: 10px;">ออกจากระบบ</a>
            </div>

            <div class="filter2">
                <div class="display-5 text-center">รายชื่อลูกค้า</div>
                    <table class="table table-light table-bordered table-hover mt-3 align-middle">
                    <thead class="table-primary">
                            <tr>
                                <th></th>
                                <th>ชื่อ</th>
                                <th>นามสกุล</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>รหัสผ่าน</th>
                                <th>ประเภท</th>
                                <th>เบอร์โทรศัพ์</th>
                                <th>ที่อยู่</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE role = 'user'");
                            $select_stmt->execute();
                            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo $row["firstname"]; ?></td>
                                <td><?php echo $row["lastname"]; ?></td>
                                <td><?php echo $row["username"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td class="password-cell"><?php echo $row["password"]; ?></td>
                                <td><?php echo $row["role"]; ?></td>
                                <td><?php echo $row["phone"]; ?></td>
                                <td class="text-nowrap"><?php echo $row["address"]; ?></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>
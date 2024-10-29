<?php
    session_start();
    if (!isset($_SESSION['admin_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $db->prepare("SELECT * FROM sp_product WHERE id = :id");
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        #ลบข้อมูล user
        $delete_stmt = $db->prepare('DELETE FROM sp_product WHERE id = :id');
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();

        header('Location:product_list.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PAGE</title>
    
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
    <div class="text-center mt-3">
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

            <h1>Admin Page</h1>
            <hr>

            <h3>
                <?php if(isset($_SESSION['admin_login'])) { ?>
                Welcome, <?php echo $_SESSION['admin_login']; }?>
                <a href="../logout.php" class="btn btn-danger">ออกจากระบบ</a>
            </h3>

        </div>
    </div>
    
    <div class="container2 background-container-menu">
        <div class="container2">
            <div class="sidebar">

                <a href="admin_home.php" class="sidebar-menu">
                    หน้าแรก
                </a>

                <a href="admin_administrator.php" class="sidebar-menu">
                    ผู้ดูแลระบบ
                </a>

                <a href="customer_list.php" class="sidebar-menu">
                    รายชื่อลูกค้า
                </a>

                <a href="product_list.php" class="sidebar-menu">
                    รายการสินค้า
                </a>

                <a href="order_history.php" class="sidebar-menu">
                    ประวัติรายการสั่งซื้อ
                </a>

            </div>

            <div class="filter2">
                <div class="display-5 text-center">รายการสินค้า</div>
                <a href="add_product.php" class="btn btn-primary mt-3">เพิ่ม +</a>
                <table class="table table-light table-bordered table-hover mt-3">
                    <thead class="table-primary">
                        <tr>
                            <th>Id</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อ</th>
                            <th>ราคา</th>
                            <th>ประเภท</th>
                            <th>รายละเอียด</th>
                            <th>จำนวน</th>
                            <th>แก้ไข</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            // ปรับ SQL Query เพื่อเรียงลำดับ id จากมากไปน้อย
                            $select_stmt = $db->prepare("SELECT * FROM sp_product ORDER BY id DESC");
                            $select_stmt->execute();

                            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><img class="img_product" src="../img/<?php echo $row['img']; ?>" alt=""></td>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["price"]; ?></td>
                                <td><?php echo $row["type"]; ?></td>
                                <td style="width: 30%"><?php echo $row["description"]; ?></td>
                                <td><center><?php echo $row["stock"]; ?> ชิ้น</center></td>
                                <td class="text-nowrap">
                                    <a href="edit_product.php?update_id=<?php echo $row["id"]; ?>" class="btn btn-warning">แก้ไข</a>
                                    <a href="?delete_id=<?php echo $row["id"]; ?>" class="btn btn-danger">ลบ</a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
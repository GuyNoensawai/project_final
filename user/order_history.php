<?php
    session_start();
    if (!isset($_SESSION['user_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt = $db->prepare("SELECT * FROM sp_transaction WHERE id = :id");
        $select_stmt->bindParam(':id', $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

        #ลบข้อมูล user
        $delete_stmt = $db->prepare('DELETE FROM sp_transaction WHERE id = :id');
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();

        header('Location:order_history.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER PAGE</title>
    
    <script src="../colorOperation.js"></script>
    
    <link rel="stylesheet" href="css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
    <div class=" text-center mt-3">
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

            <h1>User Page</h1>
            <hr>

            <h3>
                <?php if(isset($_SESSION['user_login'])) { ?>
                Welcome, <?php echo $_SESSION['user_login']; }?>
                <a href="../logout.php" class="btn btn-danger">ออกจากระบบ</a>
            </h3>

        </div>
    </div>
    
    <div class="container2 background-container-menu">
        <div class="container2">
            <div class="sidebar">

            <a href="user_home.php" class="sidebar-menu">
                    สินค้า
                </a>

                <a href="order_history.php" class="sidebar-menu">
                    คำสั่งซื้อของคุณ
                </a>

                <hr>

                <?php
                    if (isset($_SESSION['user_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["user_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <a href="user_profile.php?update_id=<?php echo $row["id"]; ?>" class="sidebar-menu">
                    โปรไฟล์
                </a>
                

                <?php } }?>
            </div>

            <div class="filter2">
                <div class="display-5 text-center">ประวัติการสั่งซื้อ</div>

                <br>

                <center>
                    <div class="payment">
                        <center>
                            <div>การชำระเงิน</div>
                            <p>โอนเข้าบัญชี ภูธเนศ เนินไสว ธนาคารกรุงไทย ออมทรัพย์</p>
                            <p style="font-size: 1.2vw; color: red;">เลขบัญชี 237-0-58388-6</p>
                            <p>โอนแล้วกรุณาส่งสลิปเพื่อรอตรวจสอบ หากมีปัญหาโทรแจ้งหมายเลข 099-609-7312</p>
                        </center>
                    </div>
                </center>
                
                <table class="table table-light table-bordered table-hover mt-3">
                    <thead class="table-primary">
                        <tr>
                            <th>Id</th>
                            <th>รหัสสินค้า</th>
                            <th>รายการสินค้า</th>
                            <th>ราคารวมสินค้า</th>
                            <th>เวลาที่สั่ง</th>
                            <th>ชื่อผู้สั่ง</th>
                            <th>ที่อยู่	</th>
                            <th>เบอร์โทรศัพ์</th>
                            <th>สถานะสินค้า</th>
                            <th>สลิปจ่ายเงิน</th>
                            <th>แก้ไข</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $select_stmt = $db->prepare("SELECT * FROM sp_transaction WHERE email = '".$_SESSION["user_login"]."'");
                            $select_stmt->execute();

                            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

                                // Decode the JSON order list
                                $orderlist = json_decode($row["orderlist"], true);
                                // Create an HTML table for the orderlist
                                $orderTable = '<table class="table table-light table-bordered table-hover"><tr><th>ชื่อ</th><th>จำนวน</th><th>ราคาต่อชิ้น</th></tr>';
                                foreach ($orderlist as $item) {
                                    $price = isset($item['price']) ? htmlspecialchars($item['price']) . ' บาท' : '';
                                    $orderTable .= '<tr><td>' . htmlspecialchars($item['name']) . '</td><td>' . htmlspecialchars($item['count']) . '</td><td>' . $price . '</td></tr>';
                                }
                                $orderTable .= '</table>';

                        ?>

                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo $row["transid"]; ?></td>
                                <td><?php echo $orderTable; ?></td>
                                <td><?php echo $row["netamount"]; ?></td>
                                <td><?php echo $row["updated_at"]; ?></td>
                                <td><?php echo $row["username"]; ?></td>
                                <td><?php echo $row["address"]; ?></td>
                                <td><?php echo $row["phone"]; ?></td>
                                <td><?php echo $row["operation"]; ?></td>
                                <td><img class="img_product" src="../uploads/<?php echo $row["slip"]; ?>" ></td>
                                <td class="mt-5">
                                    <center>
                                        <a href="add_slip.php?update_id=<?php echo $row["id"]; ?>" class="btn btn-warning">แก้ไข</a>
                                    </center>
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
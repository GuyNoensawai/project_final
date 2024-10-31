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

    // Get the email of the user whose orders you want to display (this could be set based on some input or selection)
    $user_email = isset($_GET['email']) ? $_GET['email'] : $_SESSION['user_login'];

    // Pagination setup
    $limit = 5; // Items per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Count total items for pagination
    $total_stmt = $db->prepare("SELECT COUNT(*) FROM sp_transaction WHERE email = :email");
    $total_stmt->bindParam(':email', $user_email);
    $total_stmt->execute();
    $total_items = $total_stmt->fetchColumn();
    $total_pages = ceil($total_items / $limit);

    // Fetch orders for the specific user with pagination
    $select_stmt = $db->prepare("SELECT * FROM sp_transaction WHERE email = :email ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $select_stmt->bindParam(':email', $user_email);
    $select_stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $select_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $select_stmt->execute();

    $orders = [];
    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
        $order = [
            'id' => $row['id'],
            'orderid' => $row['transid'],
            'orderlist' => json_decode($row['orderlist'], true),
            'netamount' => $row['netamount'],
            'updated_at' => $row['updated_at'],
            'username' => $row['username'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'operation' => $row['operation'],
            'slip' => $row['slip'],
        ];
        $orders[] = $order;
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
    
    <div class=" text-center">
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

            <h3>
                <?php if(isset($_SESSION['user_login'])) { ?>
                Welcome, <?php echo $_SESSION['user_login']; }?>
            </h3>

        </div>
    </div>
    
    <div class="container1 background-container-menu">
        <div class="container2">
            <div class="sidebar">

            <a href="user_home.php" class="sidebar-menu">
                    สินค้า
                </a>

                <a href="order_history.php" class="sidebar-menu">
                    คำสั่งซื้อของคุณ
                </a>

                <?php
                    if (isset($_SESSION['user_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["user_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <a href="user_profile.php?update_id=<?php echo $row["id"]; ?>" class="sidebar-menu">
                    โปรไฟล์
                </a>
                
                <hr>
                
                <a href="../logout.php" class="sidebar-menu btn-danger" style="border-radius: 10px;">ออกจากระบบ</a>

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

                <br>

                <nav>
                    <ul class="pagination justify-content">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link link-dark" href="?page=<?php echo $page - 1; ?>">ก่อนหน้า</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link link-dark" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link link-dark" href="?page=<?php echo $page + 1; ?>">ถัดไป</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                
                <table class="table table-light table-bordered table-hover mt-3">
                    <thead class="table-primary">
                        <tr>
                            <th></th>
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
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order["id"]; ?></td>
                                <td><?php echo $order["orderid"]; ?></td>
                                <td>
                                    <table class="table table-light table-bordered table-hover">
                                        <tr>
                                            <th>ชื่อ</th>
                                            <th>จำนวน</th>
                                            <th>ราคาต่อชิ้น</th>
                                        </tr>
                                        <?php foreach ($order["orderlist"] as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td><?php echo htmlspecialchars($item['count']); ?></td>
                                                <td><?php echo isset($item['price']) ? htmlspecialchars($item['price']) . ' บาท' : ''; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </td>
                                <td><?php echo $order["netamount"]; ?></td>
                                <td><?php echo $order["updated_at"]; ?></td>
                                <td><?php echo $order["username"]; ?></td>
                                <td><?php echo $order["address"]; ?></td>
                                <td><?php echo $order["phone"]; ?></td>
                                <td><?php echo $order["operation"]; ?></td>
                                <td>
                                    <?php if (!empty($order["slip"])): ?>
                                        <img class="img_product" src="../uploads/<?php echo $order["slip"]; ?>" alt="Slip Image">
                                    <?php endif; ?>
                                </td>
                                <td class="text-nowrap">
                                    <center>
                                        <a href="edit_operation.php?update_id=<?php echo $order["id"]; ?>" class="btn btn-warning">แก้ไข</a>
                                        <a href="?delete_id=<?php echo $order["id"]; ?>&email=<?php echo htmlspecialchars($user_email); ?>" class="btn btn-danger">ลบ</a>
                                    </center>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
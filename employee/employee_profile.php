<?php
    session_start();
    if (!isset($_SESSION['employee_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';

    if (isset($_REQUEST['update_id'])) {
        try {
            $id =$_REQUEST['update_id'];
            $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE id = :id");
            $select_stmt->bindParam(':id', $id);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

    if (isset($_REQUEST['btn_update'])) {
        $id_up = $_REQUEST['txt_id'];
        $username_up = $_REQUEST['txt_username'];
        $email_up = $_REQUEST['txt_email'];
        $password_up = sha1(md5($_POST['txt_password']));
        $role_up = $_REQUEST['txt_role'];

        if (empty($username_up)) {
            $errorMsg = 'Please enter Username';
        } else if (empty($email_up)) {
            $errorMsg[] = "Please enter Email";
        } else if (empty($password_up)) {
            $errorMsg = 'Please enter Password';
        } else if (empty($role_up)) {
            $errorMsg = 'Please enter Role';
        } else {
            try {
                if (!isset($errorMsg)) {
                    $update_stmt = $db->prepare("UPDATE masterlogin SET id = :id_up, username = :username_up, email = :email_up, password = :password_up, role = :role_up WHERE id = :id");
                    $update_stmt->bindParam(':id_up', $id_up);
                    $update_stmt->bindParam(':username_up', $username_up);
                    $update_stmt->bindParam(':email_up', $email_up);
                    $update_stmt->bindParam(':password_up', $password_up);
                    $update_stmt->bindParam(':role_up', $role_up);
                    $update_stmt->bindParam(':id', $id);

                    if ($update_stmt->execute()) {
                        $updateMsg = "Record update successfully...";
                        header("refresh:1;employee_home.php");
                    }
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
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
            <hr>

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

               
                <a href="employee_profile.php?update_id=<?php echo $row["id"]; ?>" class="sidebar-menu">
                    โปรไฟล์
                </a>

                <hr>
                
                <a href="../logout.php" class="sidebar-menu btn-danger" style="border-radius: 10px;">ออกจากระบบ</a>            
            </div>
                    <div class="filter">
                        <div class="container background-container">
                            <div class="div">
                                <h2 class="div-login-register">แก้ไขข้อมูล</h2>
                                <hr>

                            <?php
                                if(isset($errorMsg)) {
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Wrong! <?php echo $errorMsg; ?></strong>
                                </div>
                            <?php } ?>
                                
                            <?php
                                if(isset($updateMsg)) {
                            ?>
                                <div class="alert alert-success">
                                    <strong>Success! <?php echo $updateMsg; ?></strong>
                                </div>
                            <?php } ?>
                                
                                
                            <form method="post" class="form-horizontal">

                                <div class="form-group">
                                    <label for="id" class="col-sm-3 control-label">Id</label>
                                    <div>
                                        <input type="text" name="txt_id" class="form-control" value="<?php echo $id; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="username" class="col-sm-3 control-label">Username</label>
                                    <div>
                                        <input type="text" name="txt_username" class="form-control" value="<?php echo $username; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div>
                                        <input type="text" name="txt_email" class="form-control" value="<?php echo $email; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label">รหัสผ่าน</label>
                                    <div>
                                        <input type="text" name="txt_password" class="form-control" value="<?php echo $password; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-sm-3 control-label">เบอร์โทรศัพ์</label>
                                    <div>
                                        <input type="text" name="txt_phone" class="form-control" value="<?php echo $phone; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="col-sm-3 control-label">ที่อยู่</label>
                                    <div>
                                        <input type="text" name="txt_address" class="form-control" value="<?php echo $address; ?>">
                                    </div>
                                </div>

                                <div class="from-group">
                                    <label for="type" class="col-sm-3 control-label">ประเภท</label>
                                    <div class="col-sm-12">
                                        <select name="txt_role" id="form-control">
                                            <option value="<?php echo $role; ?>" select="selected"><?php ; echo $role; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9 mt-4">
                                        <input type="submit" name="btn_update" class="btn btn-success" value="ตกลง">
                                        <a href="employee_home.php" class="btn btn-danger">ยกเลิก</a>
                                        <p></p>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  

</body>
</html>
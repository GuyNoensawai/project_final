<?php
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
        $firstname_up = $_REQUEST['txt_firstname'];
        $lastname_up = $_REQUEST['txt_lastname'];
        $username_up = $_REQUEST['txt_username'];
        $email_up = $_REQUEST['txt_email'];
        $password_up =  $password = sha1(md5($_POST['txt_password']));
        $tel_up = $_REQUEST['txt_tel'];
        $address_up = $_REQUEST['txt_address'];
        $role_up = $_REQUEST['txt_role'];

        if (empty($id_up)) {
            $errorMsg = 'Please enter Id';
        } else if (empty($firstname_up)) {
            $errorMsg = 'Please enter Firstname';
        } else if (empty($lastname_up)) {
            $errorMsg = 'Please enter Lastname';
        } else if (empty($username_up)) {
            $errorMsg = 'Please enter Username';
        } else if (empty($email_up)) {
            $errorMsg[] = "Please enter Email";
        } else if (empty($password_up)) {
            $errorMsg = 'Please enter Password';
        } else if (empty($tel_up)) {
            $errorMsg = 'Please enter Tel';
        } else if (empty($address_up)) {
            $errorMsg = 'Please enter Address';
        } else if (empty($role_up)) {
            $errorMsg = 'Please enter Role';
        } else {
            try {
                if (!isset($errorMsg)) {
                    $update_stmt = $db->prepare("UPDATE masterlogin SET id = :id_up, firstname = :firstname_up, lastname = :lastname_up, username = :username_up, email = :email_up, password = :password_up, phone = :tel_up, address = :address_up, role = :role_up WHERE id = :id");
                    $update_stmt->bindParam(':id_up', $id_up);
                    $update_stmt->bindParam(':firstname_up', $firstname_up);
                    $update_stmt->bindParam(':lastname_up', $lastname_up);
                    $update_stmt->bindParam(':username_up', $username_up);
                    $update_stmt->bindParam(':email_up', $email_up);
                    $update_stmt->bindParam(':password_up', $password_up);
                    $update_stmt->bindParam(':tel_up', $tel_up);
                    $update_stmt->bindParam(':address_up', $address_up);
                    $update_stmt->bindParam(':role_up', $role_up);
                    $update_stmt->bindParam(':id', $id);

                    if ($update_stmt->execute()) {
                        $updateMsg = "Record update successfully...";
                        header("refresh:1;admin_administrator.php");
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
    <title>Add User</title>

    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <div class="container">
    <div class="div1">
        <h2 class="div-login-register"><img src="../img/edit.png" width="70px" class="img">แก้ไขข้อมูลผูใช้</h2>
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
            <label for="firstname" class="col-sm-3 control-label">ชื่อ</label>
            <div>
                <input type="text" name="txt_firstname" class="form-control" value="<?php echo $firstname; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="lastname" class="col-sm-3 control-label">นามสกุล</label>
            <div>
                <input type="text" name="txt_lastname" class="form-control" value="<?php echo $lastname; ?>">
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
            <label for="phone" class="col-sm-3 control-label">เบอร์โทรศัพท์</label>
            <div>
                <input type="text" name="txt_tel" class="form-control" value="<?php echo $phone; ?>">
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
                    <option value="<?php echo $role; ?>" select="selected"><?php echo $role; ?></option>
                    <option value="admin" select="">admin</option>
                    <option value="employee" select="">employee</option>
                    <option value="user" select="">user</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 mt-4">
                <input type="submit" name="btn_update" class="btn btn-success" value="ตกลง">
                <a href="admin_administrator.php" class="btn btn-danger">ยกเลิก</a>
            </div>
        </div>

    </form>
    </div>
</body>
</html>
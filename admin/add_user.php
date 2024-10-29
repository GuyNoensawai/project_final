<?php
    require_once '../connection.php';

    if (isset($_REQUEST['btn_insert'])) {
        $id = $_REQUEST['txt_id'];
        $firstname = $_REQUEST['txt_firstname'];
        $lastname = $_REQUEST['txt_lastname'];
        $username = $_REQUEST['txt_username'];
        $email = $_REQUEST['txt_email'];
        $password = sha1(md5($_POST['txt_password']));
        $role = $_REQUEST['txt_role'];
        $phone = $_REQUEST['txt_phone'];


        if (empty($id)) {
            $errorMsg = 'Please enter Id';
        } else if (empty($firstname)) {
            $errorMsg = 'Please enter Firstname';
        } else if (empty($lastname)) {
            $errorMsg = 'Please enter Lastname';
        } else if (empty($username)) {
            $errorMsg = 'Please enter Username';
        } else if (empty($email)) {
            $errorMsg[] = "Please enter Email";
        } else if (empty($password)) {
            $errorMsg = 'Please enter Password';
        } else if (empty($role)) {
            $errorMsg = 'Please enter Role';
        } else if (empty($phone)) {
            $errorMsg = 'Please enter Tel';
        } else {
            try {
                if (!isset($errorMsg)) {
                    $insert_stmt = $db->prepare("INSERT INTO masterlogin(id, firstname, lastname, username, email, password, role, phone) VALUES (:id, :fname, :lname, :uname, :email, :password, :role, :phone)");
                    $insert_stmt->bindParam(':id', $id);
                    $insert_stmt->bindParam(':fname', $firstname);
                    $insert_stmt->bindParam(':lname', $lastname);
                    $insert_stmt->bindParam(':uname', $username);
                    $insert_stmt->bindParam(':email', $email);
                    $insert_stmt->bindParam(':password', $password);
                    $insert_stmt->bindParam(':role', $role);
                    $insert_stmt->bindParam(':phone', $phone);

                    if ($insert_stmt->execute()) {
                        $insertMsg = "Insert Successfully...";
                        header("refresh:1;admin_administrator.php");
                    }
                }
            } catch (PDOException $e) {
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
        <h2 class="div-login-register"><img src="../img/add-user.png" width="70px" class="img">เพิ่มผู้ใช้</h2>
        <hr>

    <?php
        if(isset($errorMsg)) {
    ?>
        <div class="alert alert-danger">
            <strong>Wrong! <?php echo $errorMsg; ?></strong>
        </div>
    <?php } ?>

    <?php
        if(isset($insertMsg)) {
    ?>
        <div class="alert alert-success">
            <strong>Success! <?php echo $insertMsg; ?></strong>
        </div>
    <?php } ?>

    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label for="id" class="col-sm-3 control-label">Id</label>
            <div>
                <input type="text" name="txt_id" class="form-control" placeholder="Enter Id">
            </div>
        </div>

        <div class="form-group">
            <label for="firstname" class="col-sm-3 control-label">ชื่อ</label>
            <div>
                <input type="text" name="txt_firstname" class="form-control" placeholder="Enter Firstname">
            </div>
        </div>

        <div class="form-group">
            <label for="lastname" class="col-sm-3 control-label">นามสกุล</label>
            <div>
                <input type="text" name="txt_lastname" class="form-control" placeholder="Enter Lastname">
            </div>
        </div>

        <div class="form-group">
            <label for="username" class="col-sm-3 control-label">Username</label>
            <div>
                <input type="text" name="txt_username" class="form-control" placeholder="Enter Username">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="col-sm-3 control-label">Email</label>
            <div>
                <input type="text" name="txt_email" class="form-control" placeholder="Enter Email">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col-sm-3 control-label">รหัสผ่าน</label>
            <div>
                <input type="text" name="txt_password" class="form-control" placeholder="Enter Password">
            </div>
        </div>

        <div class="form-group">
            <label for="phone" class="col-sm-3 control-label">เบอร์โทรศัพท์</label>
            <div>
                <input type="text" name="txt_phone" class="form-control" placeholder="Enter Phone">
            </div>
        </div>

        <div class="from-group">
            <label for="type" class="col-sm-3 control-label">ประเภท</label>
            <div class="col-sm-12">
                <select name="txt_role" id="form-control">
                    <option value="" select="selected">- Select Role -</option>
                    <option value="admin" select="">Admin</option>
                    <option value="employee" select="">Employee</option>
                    <option value="user" select="">User</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 mt-4">
                <input type="submit" name="btn_insert" class="btn btn-success" value="เพิ่ม">
                <a href="admin_administrator.php" class="btn btn-danger">ยกเลิก</a>
            </div>
        </div>

    </form>
    </div>
</body>
</html>
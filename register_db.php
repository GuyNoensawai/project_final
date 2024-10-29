<?php 

require_once "connection.php";

session_start();

if (isset($_POST['btn_register'])) {
    $firstname = $_REQUEST['txt_firstname'];
    $lastname = $_REQUEST['txt_lastname'];
    $username = $_REQUEST['txt_username'];
    $email = $_REQUEST['txt_email'];
    $password = sha1(md5($_POST['txt_password']));
    $role = $_REQUEST['txt_role'];
    $phone = $_REQUEST['txt_phone'];


    if (empty($username)) {
        $errorMsg[] = "Please enter username, กรุณากรอกชื่อผู้ใช้";
    } else if (empty($email)) {
        $errorMsg[] = "Please enter email, กรุณากรอกอีเมล์";
    } else if (empty($password)) {
        $errorMsg[] = "Please enter password";
    } else if (strlen($password) < 6) {
        $errorMsg[] = "The code must have more than 6 characters, รหัสต้องมีมากกว่า 6 ตัว";
    } else if (empty($role)) {
        $errorMsg[] = "Please enter role, กรุณากรอกตำแหน่ง";
    } else if (empty($phone)) {
        $errorMsg = 'Please enter Tel, กรุณากรอกเบอร์';
    } else {
        try {
            $select_stmt = $db->prepare("SELECT username, email FROM masterlogin WHERE username = :uname OR email = :uemail");
            $select_stmt->bindParam(":uname", $username);
            $select_stmt->bindParam(":uemail", $email);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['username'] == $username) {
                $errorMsg[] = "Sorry username already exists";
            } else if ($row['email'] == $email) {
                $errorMsg[] = "Sorry email already exists";
            } else if (!isset($errorMsg)) {
                $insert_stmt = $db->prepare("INSERT INTO masterlogin(firstname, lastname, username, email, password, role, phone) VALUES (:fname, :lname, :uname, :email, :password, :role, :phone)");
                    $insert_stmt->bindParam(':fname', $firstname);
                    $insert_stmt->bindParam(':lname', $lastname);
                    $insert_stmt->bindParam(':uname', $username);
                    $insert_stmt->bindParam(':email', $email);
                    $insert_stmt->bindParam(':password', $password);
                    $insert_stmt->bindParam(':role', $role);
                    $insert_stmt->bindParam(':phone', $phone);

                if ($insert_stmt->execute()) {
                    $_SESSION['success'] = "Register Successfully...";
                    header("location: index.php");
                }
            }
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }
}
?>
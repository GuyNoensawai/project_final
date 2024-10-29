<?php
    require_once '../connection.php';

    if (isset($_REQUEST['update_id'])) {
        try {
            $id = $_REQUEST['update_id'];
            $select_stmt = $db->prepare("SELECT * FROM sp_transaction WHERE id = :id");
            $select_stmt->bindParam(':id', $id);
            $select_stmt->execute();
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

    if (isset($_REQUEST['btn_update'])) {
        try {
        $id_up = $_REQUEST['txt_id'];
        $operation_up = $_REQUEST['txt_operation'];
        $image_file = $_FILES['txt_file']['name'];
        $type = $_FILES['txt_file']['type'];
        $size = $_FILES['txt_file']['size'];
        $temp = $_FILES['txt_file']['tmp_name'];

        $path = "../uploads/".$image_file;
        $directory = "../uploads/";

        if (empty($id_up)) {
            $errorMsg = 'Please enter Id';
        } else if (empty($image_file)) {
            $errorMsg = 'Please enter Image';
        }  else if ($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png") { //เช็คประเภทรูป
            if (!file_exists($path)) {
                if ($size < 5000000) { //เช็ค size รูปที่จะอัฟ
                    move_uploaded_file($temp, '../uploads/'.$image_file); //อัปไฟล์ลง โหเดอร์
                } else {
                    $errorMsg = "ขนาดไฟล์ใหญ่กว่า 5MB";
                }
            } else {
                $errorMsg = "มีการผิดพลาดในการอัปโหลด";
            }
        } else {
            $errorMsg = $row['slip'];
        }

                if (!isset($errorMsg)) {
                    $update_stmt = $db->prepare("UPDATE sp_transaction SET id = :id_up, operation = :operation_up, slip = :file_up WHERE id = :id");
                    $update_stmt->bindParam(':id_up', $id_up);
                    $update_stmt->bindParam(':operation_up', $operation_up);
                    $update_stmt->bindParam(':file_up', $image_file);
                    $update_stmt->bindParam(':id', $id);

                    if ($update_stmt->execute()) {
                        $updateMsg = "File upload successfully...";
                        header("refresh:1;order_history.php");
                    }
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
            } 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Slip</title>

    <link rel="stylesheet" href="css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <div class="div1">
            <h2 class="div-login-register"><img src="../img/slip.png" width="70px" class="img">เพิ่มสลิป</h2>
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

            <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Id</label>
                    <div>
                        <input type="text" name="txt_id" class="form-control" value="<?php echo $id; ?>">
                    </div>
                </div>  

                <div class="form-group">
                    <label for="image" class="col-sm-3 control-label">Slip</label>
                    <div>
                        <input type="file" name="txt_file" class="form-control" accept="image/jpeg, image/png">
                        <p class="small mb-0 mt-2">อัปได้เฉพาะไฟล์ jpeg, png</p>
                    </div>
                </div>

                <div class="from-group" style="display: none;">
                    <div class="col-sm-12">
                        <select name="txt_operation" id="form-control">
                            <option value="รอตรวจสอบ" select="selected">รอตรวจสอบ</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9 mt-4">
                        <input type="submit" name="btn_update" class="btn btn-success" value="ตกลง">
                        <a href="order_history.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <?php if(!empty($statusMsg)) { ?>
            <div class="alert alert-secondary" role="alert">
                <?php echo $statusMsg; ?>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
<?php
    require_once '../connection.php';

    if (isset($_REQUEST['update_id'])) {
        try {
            $id =$_REQUEST['update_id'];
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
        $id_up = $_REQUEST['txt_id'];
        $operation_up = $_REQUEST['txt_operation'];

        if (empty($id_up)) {
            $errorMsg = 'Please enter Id';
        } else if (empty($operation_up)) {
            $errorMsg = 'Please enter Operation';
        } else {
            try {
                if (!isset($errorMsg)) {
                    $update_stmt = $db->prepare("UPDATE sp_transaction SET id = :id_up, operation = :operation_up WHERE id = :id");
                    $update_stmt->bindParam(':id_up', $id_up);
                    $update_stmt->bindParam(':operation_up', $operation_up);
                    $update_stmt->bindParam(':id', $id);

                    if ($update_stmt->execute()) {
                        $updateMsg = "Record update successfully...";
                        header("refresh:1;order_history.php");
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
    <title>Edit Operation</title>

    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <div class="container">
    <div class="div1">
        <h2 class="div-login-register"><img src="../img/edit.png" width="70px" class="img">แก้ไขสถานะ</h2>
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

        <div class="from-group">
            <label for="type" class="col-sm-3 control-label">สถานะสินค้า</label>
            <div class="col-sm-12">
                <select name="txt_operation" id="form-control">
                    <option value="กำลังดำเนินการ" select="selected"><?php echo $operation; ?></option>
                    <option value="รอตรวจสอบ" select="">รอตรวจสอบ</option>
                    <option value="กำลังจัดส่ง" select="">กำลังจัดส่ง</option>
                    <option value="จัดส่งสําเร็จ" select="">จัดส่งสําเร็จ</option>
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
</body>
</html>
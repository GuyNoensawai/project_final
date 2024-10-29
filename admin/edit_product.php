<?php
    require_once '../connection.php';

    if (isset($_REQUEST['update_id'])) {
        try {
            $id = $_REQUEST['update_id'];
            $select_stmt = $db->prepare("SELECT * FROM sp_product WHERE id = :id");
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
        $name_up = $_REQUEST['txt_name'];
        $price_up = $_REQUEST['txt_price'];
        $type_up = $_REQUEST['txt_type'];
        $description_up = $_REQUEST['txt_description'];
        $stock_up = $_REQUEST['txt_stock'];


        $image_file = $_FILES['txt_file']['name'];
        $type = $_FILES['txt_file']['type'];
        $size = $_FILES['txt_file']['size'];
        $temp = $_FILES['txt_file']['tmp_name'];

        $path = "../img/".$image_file;
        $directory = "../img/";

        if (empty($id_up)) {
            $errorMsg = 'Please enter Id';
        }  else if (empty($name_up)) {
            $errorMsg = 'Please enter Name';
        } else if (empty($price_up)) {
            $errorMsg = 'Please enter Price';
        } else if (empty($type_up)) {
            $errorMsg[] = "Please enter Type";
        } else if (empty($description_up)) {
            $errorMsg = 'Please enter Description';
        } else if (empty($stock_up)) {
            $errorMsg = 'Please enter Stock';
        } 
        
        if (empty($image_file)) {
            $image_file = $row['img']; // ใช้รูปเดิมถ้าไม่มีการอัปโหลดรูปใหม่
        } else if ($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png") { //เช็คประเภทรูป
            if (!file_exists($path)) {
                if ($size < 5000000) { //เช็ค size รูปที่จะอัฟ
                    unlink($directory.$row['img']);
                    move_uploaded_file($temp, '../img/'.$image_file); //อัปไฟล์ลง โหเดอร์
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
                    $update_stmt = $db->prepare("UPDATE sp_product SET id = :id_up, img = :file_up, name = :name_up, price = :price_up, type = :type_up, description = :description_up, stock = :stock_up WHERE id = :id");
                    $update_stmt->bindParam(':id_up', $id_up);
                    $update_stmt->bindParam(':file_up', $image_file);
                    $update_stmt->bindParam(':name_up', $name_up);
                    $update_stmt->bindParam(':price_up', $price_up);
                    $update_stmt->bindParam(':type_up', $type_up);
                    $update_stmt->bindParam(':description_up', $description_up);
                    $update_stmt->bindParam(':stock_up', $stock_up);
                    $update_stmt->bindParam(':id', $id);

                    if ($update_stmt->execute()) { 
                        $updateMsg = "Record update successfully...";
                        header("refresh:1;product_list.php");
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
    <title>Edit Product</title>

    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <div class="div2">
            <h2 class="div-login-register"><img src="../img/edit.png" width="70px" class="img">แก้ไขสินค้า</h2>
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

            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id" class="col-sm-3 control-label">Id</label>
                    <div>
                        <input type="text" name="txt_id" class="form-control" value="<?php echo $id; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="image" class="col-sm-3 control-label">รูปที่จะอัฟ</label>
                    <div>
                        <?php if (!empty($row['img'])) { ?>
                            <p class="small mb-0">รูปปัจจุบัน</p>
                            <img src="../img/<?php echo $row['img']; ?>" alt="Current Image" width="150">
                        <?php } ?>
                    </div>
                    
                    <br>

                    <div>
                        <input type="file" name="txt_file" class="form-control" accept="image/jpeg, image/png">
                        <p class="small mb-0 mt-2">อัปได้เฉพาะไฟล์ jpeg, png</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">ชื่อสินค้า</label>
                    <div>
                        <input type="text" name="txt_name" class="form-control" value="<?php echo $name; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="price" class="col-sm-3 control-label">ราคาสินค้า</label>
                    <div>
                        <input type="text" name="txt_price" class="form-control" value="<?php echo $price; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">ประเภท</label>
                    <div>
                        <input type="text" name="txt_type" class="form-control" value="<?php echo $type; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">รายละเอียด</label>
                    <div>
                        <input type="text" name="txt_description" class="form-control" value="<?php echo $description; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="stock" class="col-sm-3 control-label">จำนวน</label>
                    <div>
                        <input type="text" name="txt_stock" class="form-control" value="<?php echo $stock; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9 mt-4">
                        <input type="submit" name="btn_update" class="btn btn-success" value="ตกลง">
                        <a href="product_list.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
<?php
    require_once '../connection.php';

    if (isset($_REQUEST['btn_insert'])) {
        try {
        $id_up = $_REQUEST['txt_id'];
        $name_up = $_REQUEST['txt_name'];
        $price_up = $_REQUEST['txt_price'];
        $type_up = $_REQUEST['txt_type'];
        $description_up = $_REQUEST['txt_description'];
        $stock_up = $_REQUEST['txt_stock'];
        $weight_up = $_REQUEST['txt_weight'];
        $productid_up = round(microtime(true) * 1000);


        $image_file = $_FILES['txt_file']['name'];
        $type = $_FILES['txt_file']['type'];
        $size = $_FILES['txt_file']['size'];
        $temp = $_FILES['txt_file']['tmp_name'];

        $path = "../img/".$image_file;


        if (empty($id_up)) {
            $errorMsg = 'Please enter Id';
        } else if (empty($name_up)) {
            $errorMsg = 'Please enter Name';
        } else if (empty($price_up)) {
            $errorMsg = 'Please enter Price';
        } else if (empty($type_up)) {
            $errorMsg[] = "Please enter Type";
        } else if (empty($description_up)) {
            $errorMsg = 'Please enter Description';
        } else if (empty($stock_up)) {
            $errorMsg = 'Please enter Stock';
        } else if (empty($weight_up)) {
            $errorMsg = 'Please enter Weight';
        } else if (empty($image_file)) {
            $errorMsg = 'Please enter Image';
        } else if ($type == "image/jpg" || $type == "image/jpeg" || $type == "image/png") { //เช็คประเภทรูป
            if (!file_exists($path)) {
                if ($size < 5000000) { //เช็ค size รูปที่จะอัฟ
                    move_uploaded_file($temp, '../img/'.$image_file); //อัปไฟล์ลง โหเดอร์
                } else {
                    $errorMsg = "ขนาดไฟล์ใหญ่กว่า 5MB";
                }
            } else {
                $errorMsg = "มีการผิดพลาดในการอัปโหลด";
            }
        } else {
            $errorMsg = "อัปโหลด jpg, jpeg, png";
        }
                if (!isset($errorMsg)) {
                    $insert_stmt = $db->prepare("INSERT INTO sp_product(id, img, name, productid, price, type, description, stock, weight) VALUES (:id, :fimage, :name, :productid, :price, :type, :description, :stock, :weight)");
                    $insert_stmt->bindParam(':id', $id_up);
                    $insert_stmt->bindParam(':fimage', $image_file);
                    $insert_stmt->bindParam(':name', $name_up);
                    $insert_stmt->bindParam(':productid', $productid_up);
                    $insert_stmt->bindParam(':price', $price_up);
                    $insert_stmt->bindParam(':type', $type_up);
                    $insert_stmt->bindParam(':description', $description_up);
                    $insert_stmt->bindParam(':stock', $stock_up);
                    $insert_stmt->bindParam(':weight', $weight_up);

                    if ($insert_stmt->execute()) {
                        $insertMsg = "Insert Successfully...";
                        header("refresh:1;product_list.php");
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <link rel="stylesheet" href="css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <div class="container">
    <div class="div1">
        <h2 class="div-login-register"><img src="../img/add-product.png" width="70px" class="img">เพิ่มสินค้า</h2>
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

    <form method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="id" class="col-sm-3 control-label">Id</label>
            <div>
                <input type="text" name="txt_id" class="form-control" placeholder="Enter Id">
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="col-sm-3 control-label">รูปภาพ</label>
            <div>
                <input type="file" name="txt_file" class="form-control" placeholder="Select Image">
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">ชื่อ</label>
            <div>
                <input type="text" name="txt_name" class="form-control" placeholder="Enter Name">
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="col-sm-3 control-label">ราคา</label>
            <div>
                <input type="text" name="txt_price" class="form-control" placeholder="Enter Price">
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-sm-3 control-label">ประเภท</label>
            <div>
                <input type="text" name="txt_type" class="form-control" placeholder="Enter Type">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">รายละเอียด</label>
            <div>
                <input type="text" name="txt_description" class="form-control" placeholder="Enter Description">
            </div>
        </div>

        <div class="form-group">
            <label for="stock" class="col-sm-3 control-label">จำนวน</label>
            <div>
                <input type="text" name="txt_stock" class="form-control" placeholder="Enter Stock">
            </div>
        </div>

        <div class="form-group">
            <label for="weight" class="col-sm-3 control-label">น้ำหนัก</label>
            <div>
                <input type="text" name="txt_weight" class="form-control" placeholder="Enter Weight">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 mt-4">
                <input type="submit" name="btn_insert" class="btn btn-success" value="เพิ่ม">
                <a href="product_list.php" class="btn btn-danger">ยกเลิก</a>
            </div>
        </div>

    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
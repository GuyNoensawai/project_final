<?php
    session_start();
    if (!isset($_SESSION['employee_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMPLOYEE PAGE</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../colorTypeProduct.js"></script>

    <link rel="stylesheet" href="css/employee.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>

    
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
                            header("refresh:1;employee_home.php");
                            unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <h1>Employee Page</h1>

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


                <?php
                    if (isset($_SESSION['employee_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["employee_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <a href="employee_profile.php?update_id=<?php echo $row["id"]; ?>" class="sidebar-menu">
                    โปรไฟล์
                </a>
                

                <?php } }?>

                <hr>
                
                <a href="../logout.php" class="sidebar-menu btn-danger" style="border-radius: 10px;">ออกจากระบบ</a>
            </div>

            
            <div class="container1">
                <div class="div">
                    <div class="itemcart">
                        <a class="sidebar-menu-cart" onclick="openCart()">
                            <center><i style="width: 35px;" class="fa-solid fa-cart-shopping"></i></center>
                            <div id="cartcount" class="cartcount" style="display: none;">
                                0
                            </div>
                        </a>
                        <a class="sidebar-menu-cart" onclick="openQr()">
                            <center><i style="width: 35px;" class="fa-solid fa-qrcode"></i></center>
                        </a>
                    </div>

                    <div class="filter">
                        <input onkeyup="searchsome(this)" id="txt_search" type="text" class="sidebar-search sidebar-menu-filter" placeholder="ค้นหา">

                        <br>

                        <a onclick="searchproduct('all')" class="sidebar-menu-filter" style="cursor: pointer;">
                            ทั้งหมด
                        </a>

                        <a id="menufilterlist"></a>
                

                        <div id="productlist" class="product"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDesc" class="modal" style="display: none;">
        <div class="modal-bg"></div>
        <div class="modal-page">
            <div class="styleh2">
                <h2>รายละเอียด</h2>
                <img onclick="cancelModal()" class="close-size" src="../img/close.png" alt="">
            </div>
                <div class="modaldesc-content">
                    <img id="md-img" class="modaldese-image" src="https://images.unsplash.com/photo-1531390979850-32568e0159ce?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">

                    <div class="modaldesc-detail">
                            <p id="md-productname" style="font-size: 1.5vw">Product Name</p>
                            <p id="md-price" style="font-size: 1.2vw">500 THB</p>
                            <p id="md-stock">0</p>
                            <br>
                            <p id="md-description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, suscipit.</p>
                            <br>
                            <div class="btn-control" id="button-add">
                                <div id="button-add"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalQr" class="modal" style="display: none;">
        <div class="modal-bg"></div>
        <div class="modal-page">
            <div class="styleh2">
                <h2>แสกนคิวอาร์</h2>
                <img onclick="cancelModal()" class="close-size" src="../img/close.png" alt="">
            </div>

            <div class="container">
                <div class="row" >
                    <center>
                        <div class="col-md-6">
                            <video id="preview" width="100%"></video>
                        
                            <label>SCAN QR</label>
                            <input onkeyup="searchsome(this)" type="text" name="text" id="text" placeholder="scan" class="form-control">
                        </div>
                    </center>
                </div>
            </div>

            <audio id="scan-sound" src="../audio/success.mp3"></audio>
            <audio id="error-sound" src="../audio/error.mp3"></audio>

            <script>
                let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        alert('No cameras found');
                    }
                }).catch(function(e) {
                    console.error(e);
                });
            
                scanner.addListener('scan', function(c) {
                    const scannedId = c;
                    addProductToCartById(scannedId);

                    // เล่นสแกนโค้ด QR สำเร็จ
                    document.getElementById('scan-sound').play();
                });
            
                function addProductToCartById(productid) {
               
                    console.log("Looking for product with ID:", productid); // Debug line
                    console.log("Product list:", product); // Debug line to see the product list

                    const productToAdd = product.find(item => item.productid === productid);

                    if (productToAdd) {
                        let existingItem = cart.find(item => item.productid === productid);
                    
                        if (existingItem) {
                            existingItem.count++;
                        } else {
                            cart.push({
                                index: product.indexOf(productToAdd),
                                productid: productToAdd.productid,
                                id: productToAdd.id,
                                name: productToAdd.name,
                                price: productToAdd.price,
                                img: productToAdd.img,
                                count: 1
                            });
                        }
                    
                        Swal.fire({
                            icon: 'success',
                            title: `Added ${productToAdd.name} to cart!`
                        });
                        $("#cartcount").css('display','flex').text(cart.length)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Product not found',
                            text: `No product with ID ${productid} was found.`
                        });
                    }
                }

                function cancelModal() {
                    $(".modal").css('display', 'none');
                }
            
                // Initialize product and cart arrays
                var product = []; // Populate this with your product data
                var cart = [];
            </script>
            </div>
                
        </div>
        </div>
    </div>

    <div id="modalCart" class="modal" style="display: none;">
        <div class="modal-bg"></div>
        <div class="modal-page">
            <div class="styleh2">
                <h2>รถเข็นของฉัน</h2>
                <img onclick="cancelModal()" class="close-size" src="../img/close.png" alt="">
            </div>
            
            <br>

            <div id="mycart" class="cartlist">
                    
            </div>

            <hr>



            <div class="btn-control">
                <div id="myprice">

                </div>
            </div>


            <div class="btn-control">
                <button onclick="buynow()" class="btn btn-success btn-add-to-card">ซื้อสินค้า</button>
            </div>
        </div>
    </div>

</body>
</html>
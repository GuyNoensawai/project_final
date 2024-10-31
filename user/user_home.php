<?php
    session_start();
    if (!isset($_SESSION['user_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER PAGE</title>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../colorTypeProduct.js"></script>

    <link rel="stylesheet" href="css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="text-center">
        <div class="container1 background-container-header">

            <?php if(isset($_SESSION['success'])) : ?>
                <div class="alert alert-success">
                    <h3>
                        <?php
                            echo $_SESSION['success'];
                            header("refresh:1;user_home.php");
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

            
            <div class="container1">
                <div class="">
                    <div class="itemcart">
                        <a class="sidebar-menu-cart" onclick="openCart()">
                            <center><i style="width: 35px;" class="fa-solid fa-cart-shopping"></i></center>
                            <div id="cartcount" class="cartcount" style="display: none;">
                                0
                            </div>
                        </a>
                    </div>

                    <div class="filter3">
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
                <h2>รายละเอียดสินค้า</h2>
                <img onclick="cancelModal()" class="close-size" src="../img/close.png" alt="" id="close">
            </div>
                <div class="modaldesc-content">
                    <img id="md-img" class="modaldese-image" src="https://images.unsplash.com/photo-1531390979850-32568e0159ce?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">

                    <div class="modaldesc-detail">
                            <p id="md-productname" style="font-size: 1.5vw">Product Name</p>
                            <p id="md-price" style="font-size: 1.2vw">500 THB</p>
                            <p id="md-stock">0</p>
                            <br>
                            <p id="md-description" class="description-size">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, suscipit.</p>
                            <br>
                            <div class="btn-control" >
                                <div id="button-add"></div>
                            </div>
                    </div>
                </div>
        </div>
    </div>

    <div id="modalCart" class="modal" style="display: none;">
        <div class="modal-bg"></div>
        <div class="modal-page">
            <div class="styleh2">
                <h2>รถเข็นของฉัน</h2>
                <img onclick="cancelModal()" class="close-size" src="../img/close.png" alt="" id="close">
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
                <button onclick="buynow()"class="btn btn-success btn-add-to-card">ซื้อสินค้า</button>
            </div>
        </div>
    </div>

</body>
</html>
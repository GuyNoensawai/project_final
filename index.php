<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi Login PHP</title>

    <link rel="stylesheet" href="css/st.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <div class="div1">
        <h2 class="div-login-register"><img src="img/login.png" width="70px" class="img">Login Page</h2>
        <hr>

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

            <form action="login_db.php" method="post" class="form-horizontal my-3">

                <label for="email" class="col-sm-3 control-label">Email</label>
                <div>
                     <input type="text" name="txt_email" class="form-control" required placeholder="Enter email">
                </div>

                <label for="password" class="col-sm-3 control-label">Password</label>
                <div>
                    <input type="password" name="txt_password" class="form-control" required placeholder="Enter password">
                </div>

                <div class="from-group">
                    <label for="type" class="col-sm-3 control-label">Select Type</label>
                    <div class="col-sm-12">
                        <select name="txt_role" id="form-control">
                            <option value="" select="selected">- Select Role -</option>
                            <option value="admin" select="">Admin</option>
                            <option value="employee" select="">Employee</option>
                            <option value="user" select="">User</option>
                        </select>
                    </div>
                </div>

                <div class="from-group">
                    <div class="col-sm-12 mt-3">
                        <input type="submit" name="btn_login" class="btn btn-success" style="width: 100%;" value="Login">
                    </div>
                </div>

                <div class="from-group text-center">
                    <div class="col-sm-12 mt-3">
                        <p>You don't have a account register here?</p>
                        <a href="register.php">Register Account</a>
                    </div>
                </div>   
            </form>
        </div>

    </div>
</body>
</html>
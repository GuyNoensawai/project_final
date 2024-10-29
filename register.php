<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>

    <link rel="stylesheet" href="css/st.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <div class="div1">
        <h2 class="div-login-register"><img src="img/register.png" width="70px" class="img"><center>Register Page</center></h2>
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

        <form action="register_db.php" method="post" class="form-horizontal my-3">

        <div class="form-group">
            <label for="firstname" class="col-sm-3 control-label">Firstname</label>
            <div>
                <input type="text" name="txt_firstname" class="form-control" placeholder="Enter Firstname">
            </div>
        </div>

        <div class="form-group">
            <label for="lastname" class="col-sm-3 control-label">Lastname</label>
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
                <label for="password" class="col-sm-3 control-label">Password</label>
                <div>
                    <input type="text" name="txt_password" class="form-control" placeholder="Enter Password">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-3 control-label">Tel</label>
                <div>
                    <input type="text" name="txt_phone" class="form-control" placeholder="Enter Phone">
                </div>
            </div>

            <div class="from-group">
                <label for="type" class="col-sm-3 control-label">Select Type</label>
                <div class="col-sm-12">
                    <select name="txt_role" id="form-control">
                        <option value="user" select="">User</option>
                    </select>
                </div>
            </div>

            <div class="from-group">
                <div class="col-sm-12 mt-3">
                    <input type="submit" name="btn_register" class="btn btn-primary" style="width: 100%;" value="Register">
                </div>
            </div>

            <div class="from-group text-center">
                <div class="col-sm-12 mt-3">
                    <p>You have a account login here?</p>
                    <a href="index.php">Login Account</a>
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
<?php
session_start();
if (isset($_SESSION["admin"])) {
    header("location: admin.php");
    exit();
} elseif (isset($_SESSION["worker"])) {
    header("location: worker.php");
    exit();
} elseif (isset($_SESSION["supplier"])) {
    header("location: supplier.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/login.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Login</title>
    </head>

    <body>

        <div class="container">
            <div class="picture">
                <img src="images/logo.png" alt="business-logo">
            </div>

            <div class="login-body">

                <h1>
                    Badong Lechon Manok
                </h1>

                <form action="check_login.php" class="login-form" method="post">

                    <p id="incorrect"><?php if (isset($_SESSION["missing"])) {
                                        echo "<script>document.getElementById('incorrect').style.display = 'block';
                    </script>";
                                        echo $_SESSION["missing"];
                                        unset($_SESSION["missing"]);
                                    } ?></p>

                    <div class="username-body">
                        <input type="text" id="username" name="username" placeholder="Username">
                        <p id="username-error">Username cannot be blank.</p>
                    </div>

                    <div class="password-body">
                        <input type="password" id="password" name="password" placeholder="Password">
                        <p id="password-error">Password cannot be blank.</p>
                    </div>

                    <div class="checkbox-body">
                        <input type="checkbox" id="checkbox">
                        <label for="checkbox">Show Password</label>
                    </div>
                    <button type="submit" name="submit" id="submit" disabled>Login</button>
                </form>
            </div>
        </div>
    </body>

    <script src="javascript/login.js"></script>

</html>
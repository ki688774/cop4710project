<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<header>
    <!-- INCLUDE FOR HEADER.PHP SHOULD GO HERE-->
    <!-- SHOULD ALSO INCLUDE THE EXTRA NEEDED BUTTONS FOR JOINING AN RSO ETC-->
    <?php include 'header.php';?>
</header>


    <body>
        <form action="../php/users/login.php" method="POST" enctype="multipart/form-data">
        <!-- Below form action is for checking the input -->
        <!-- <form action="process-signup.php" method="POST"> -->
            <!-- Error Checking might not be useful-->
            <?php if(isset($_GET['error'])) { ?>
                <p> class="error"> <?php echo $_GET['error']; ?></p>
                <?php } ?>
            <div>
                <p>
                    <label for="username">Username:</label>    
                    <input type="text" name="username" id="username" required>
                </p>
            </div>
            <div>
                <p>
                    <label for="password">Password:</label>    
                    <input type="text" name="password" id="password" required>
                </p>
            </div>
            <button>Log in</button>
        </form>

        <a href="signup.php"><button id="signUpBtn">Sign up</button></a>
        
        <button id="resetPassBtn">Reset Password</button>
    </body>
</html>
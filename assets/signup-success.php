<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<header>
    <!-- INCLUDE FOR HEADER.PHP SHOULD GO HERE-->
    <!-- SHOULD ALSO INCLUDE THE EXTRA NEEDED BUTTONS FOR JOINING AN RSO ETC-->
    <?php include 'header.php';?>
</header>


<body>
    <h1>Signup</h1>
    <p>Signup successful.
       You can now <a href="login-form.php">log in</a>.</p>
</body>

<?php include 'footer.php';?>

</html>

<style>
    .button-container {
        /* Use flexbox to line up horizontally */
        display: flex;
        /* Center the buttons horizontally in container */
        justify-content: center;
        /* Adds top padding so they're not at the very top of the page */
        padding-top: 20px;
    }

    .button-container button {
        margin: 0 10px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    .button-container button:hover {
        opacity: 0.8;
    }
</style>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="./styles.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <h1>Login</h1>
    <form id="loginForm">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <?php include '../templates/errorModal.php';?>

    <script src="login.js"></script>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
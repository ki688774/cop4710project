<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="./register.css">
    <link rel="stylesheet" href="../templates/styles.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <h1>Register Account</h1>
    <form id="registerForm">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required><br><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required><br><br>

        <label for="email">E-Mail Address:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

        <input type="submit" value="Register">
    </form>

    <button class="button" onclick="window.location.href='../users/universityCreation.php';">
        Register University and Super-Admin
    </button>

    <script src="register.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?>    
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>



</html>
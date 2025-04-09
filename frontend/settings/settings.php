<!DOCTYPE html>
<html>
<head>
    <title>Settings Page</title>
    <link rel="stylesheet" href="./settings.css">
    <link rel="stylesheet" href="../templates/styles.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <form class="form" id="updateUserForm">
    <h1>Update User Information</h1>
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required><br><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required><br><br>

        <label for="email">E-Mail Address:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <input type="submit" value="Update">
    </form>

    <form class="form" id="updatePasswordForm">
    <h1>Update Password</h1>

        <label for="oldPassword">Old Password:</label>
        <input type="password" id="oldPassword" name="oldPassword" required><br><br>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

        <input type="submit" value="Update">
    </form>

    <form class="form" id="deleteAccountForm">
    <h1>Delete Account</h1>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

        <input type="submit" value="Delete">
    </form>

    <form class="form" id="deleteUniversityForm">
    <h1>Delete University</h1>
        <label for="email">University Email Domain:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

        <input type="submit" value="Delete">
    </form>

    <script type="module" src="settings.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?>    
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>



</html>
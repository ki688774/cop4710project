<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="./register.css">
    <link rel="stylesheet" href="./styles.css">
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

    <div id="errorModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <span id="errorClose" class="close">&times;</span>
                <h2>Error</h2>
            </div>
            <div id="errorText" class="modal-body">
                <p>This text gets overwritten anyhow.</p>
            </div>
        </div>
    </div>

    <div id="successModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <span id="successClose" class="close">&times;</span>
                <h2>Success!</h2>
            </div>
            <div id="successText" class="modal-body">
                <p>This text gets overwritten anyhow.</p>
            </div>
        </div>
    </div>
    
<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

    <script src="register.js"></script>
</body>
</html>
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

    <script src="login.js"></script>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
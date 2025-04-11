<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./createEvent.css">
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

        <input class="button" type="submit" value="Update">
    </form>

    <script type="module" src="createEvent.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?>    
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>



</html>
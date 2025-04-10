<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Creation</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./universityCreation.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <form id="universityCreation">
    <h1>Register University</h1>
    <p>Make sure this information is correct!</p>

        <label for="university_name">University Name:</label>
        <input type="text" id="university_name" name="university_name" required><br><br>

        <label for="location_name">Location Name:</label>
        <input type="text" id="location_name" name="location_name" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" name="longitude" required><br><br>

        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" name="latitude" required><br><br>
       
    <h1>Register Super Admin Credentials</h1>
    <p>This information is required for registering a super admin.<br> 
        This super admin will need to pass on the super admin to the next when needed. 
    </p>

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

        <input class="button" type="submit" value="Register">
 
    </form>
    
    <script src="universityCreation.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
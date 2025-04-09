<!DOCTYPE html>
<html>
<head>
    <title>Settings Page</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./settings.css">
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

    <form class="form dependentForm" id="deleteAccountForm">
    <h1>Delete Account</h1>
        <label for="deleteAccountPassword">Password:</label>
        <input type="password" id="deleteAccountPassword" name="deleteAccountPassword" required><br><br>

        <label for="deleteAccountConfirmPassword">Confirm Password:</label>
        <input type="password" id="deleteAccountConfirmPassword" name="deleteAccountConfirmPassword" required><br><br>

        <input type="submit" value="Delete">
    </form>

    <form class="form dependentForm" id="updateUniversityForm">
    <h1>Update University</h1>
        <label for="university_name">University Name:</label>
        <input type="text" id="university_name" name="university_name" required><br><br>

        <label for="updateUniversityEmail">University Email Domain:</label>
        <input type="text" id="updateUniversityEmail" name="updateUniversityEmail" required><br><br>

        <label for="location_name">Location Name:</label>
        <input type="text" id="location_name" name="location_name" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" name="longitude" required><br><br>

        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" name="latitude" required><br><br><br>

        <label for="updateUniversityPassword">Password:</label>
        <input type="password" id="updateUniversityPassword" name="updateUniversityPassword" required><br><br>

        <label for="updateUniversityConfirmPassword">Confirm Password:</label>
        <input type="password" id="updateUniversityConfirmPassword" name="updateUniversityConfirmPassword" required><br><br>

        <input type="submit" value="Update">
    </form>

    <form class="form dependentForm" id="transferUniversityForm">
    <h1>Transfer University Ownership</h1>
        <label for="transferUniversityEmail">New Super-Admin Email:</label>
        <input type="text" id="transferUniversityEmail" name="transferUniversityEmail" required><br><br><br>

        <label for="transferUniversityPassword">Password:</label>
        <input type="password" id="transferUniversityPassword" name="transferUniversityPassword" required><br><br>

        <label for="transferUniversityConfirmPassword">Confirm Password:</label>
        <input type="password" id="transferUniversityConfirmPassword" name="transferUniversityConfirmPassword" required><br><br>

        <input type="submit" value="Transfer">
    </form>

    <form class="form dependentForm" id="deleteUniversityForm">
    <h1>Delete University</h1>
        <label for="universityDeleteEmail">University Email Domain:</label>
        <input type="text" id="universityDeleteEmail" name="universityDeleteEmail" required><br><br><br>

        <label for="deleteUniversityPassword">Password:</label>
        <input type="password" id="deleteUniversityPassword" name="deleteUniversityPassword" required><br><br>

        <label for="deleteUniversityConfirmPassword">Confirm Password:</label>
        <input type="password" id="deleteUniversityConfirmPassword" name="deleteUniversityConfirmPassword" required><br><br>

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
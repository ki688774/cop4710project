<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./rsoDetails.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>


<body>
    <div id="rsoInformation">
        <div id="rsoHeader">
            <h1 id="rsoName">Temporary RSO Name</h1>
        </div>
        <form class="form" id="editRSOForm">
            <label for="rsoNameTextBox">Event Name:&nbsp;</label>
            <input type="text" id="rsoNameTextBox" name="rsoNameTextBox" required><br><br>
            <div class="editButtons">
                <input class="button editButton" id="editButton" value="Edit">
                <input class="button cancelButton" id="cancelButton" value="Cancel">
            </div>
        </form>
        <div class="actionButtons" id="actionButtons">
            <input class="button joinButton" id="joinButton" value="Join">
            <input class="button leaveButton" id="leaveButton" value="Leave">
        </div>
    </div>

    <script type="module" src="rsoDetails.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
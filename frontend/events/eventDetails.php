<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./eventDetails.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>


<body>
    <div id="eventInformation">
        <h1 id="eventName">Temporary Event Name</h1>
        <p id="eventDescription">This is a temporary description for the event.</p>
        <p id="eventTime">01/02/2003, 12:00:00 PM to 02/03/2004, 12:00:00 PM</p>
        <p id="eventAddress">123 Example Rd. (12.34567890, 12.34567890)</p>
        <p id="contactInformation">example@example.org, (123) 456-7890</p>
    </div>

    <form class="form" id="commentForm">
    <h2>Leave A Comment</h2>
        <textarea id="commentBox" name="commentBox" rows="5" cols="50" required></textarea><br>
        <input class="button" type="submit" value="Post">
    </form>

    <script type="module" src="eventDetails.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
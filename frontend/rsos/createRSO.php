<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./createRSO.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <h1>Create RSO</h1>
    <form class="form" id="createEventForm">
        <label for="rsoNameTextBox">RSO Name:&nbsp;</label>
        <input type="text" id="rsoNameTextBox" name="rsoNameTextBox" required><br><br>
        <input class="button" type="submit" value="Create">
    </form>

    <script type="module" src="createRSO.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?>    
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>



</html>
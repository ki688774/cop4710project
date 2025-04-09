<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../templates/styles.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>


<body>
    <h2>Registered Student Organizations</h2>
    <div class="button-container">
        <a href="searchRSO.php"><button>Find an RSO</button></a>
        <a href="createRSO.php"><button>Create RSO</button></a>
        <a href="userRSOs.php"><button>My RSOs</button></a>
    </div>
    <h2>Events</h2>
    <div class="button-container">
        <button>Find Events</button>
        <button>Create an Event</button>
        <button>Manage your Events</button>
    </div>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>

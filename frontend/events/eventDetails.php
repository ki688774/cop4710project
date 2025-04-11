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
        <textarea class="textarea" id="commentBox" name="commentBox" rows="5" cols="70" required></textarea>
        <input class="button" type="submit" value="Post">
    </form>

    <div class="comments-container" id="comments-container">
        <div class="form" id="searchForm">
            <span class="form-element">
                <label for="minTime">From</label>
                <input type="datetime-local" id="minTime" name="minTime">
            </span>

            <span class="form-element">
                <label for="maxTime">To</label>
                <input type="datetime-local" id="maxTime" name="maxTime">
            </span>
            <input class="button" type="submit" value="Search">
            <br>
            <span class="form-element">
                <label for="sort">Sort By:</label>
                <select id="sort" name="sort">
                    <option value=0 selected>Time</option>
                    <option value=2>First Name</option>
                    <option value=4>Last Name</option>
                </select>
                <button class="text-button" type="button" id="ascendingDescending">▲</button>
            </span>
            <span class="form-element">
                <label for="search">Search:</label>
                <input type="text" id="search" name="search">
            </span>
        </div>
        <ul class="comments-list" id="list">

        </ul>
    </div>

    <script type="module" src="eventDetails.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
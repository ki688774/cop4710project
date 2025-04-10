<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./events.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>


<body>
    <h1>Events</h1>
    <div class="button-container">
        <button>Create an Event</button>
        <button>Manage your Events</button>
    </div>
    <form class="form" id="eventSearchForm">
    <h2>Search Events</h2>
        <span class="form-element">
            <label for="search">Event Name:</label>
            <input type="text" id="search" name="search">
        </span><br>

        <span class="form-element">
            <label for="sort">Sort By:</label>
            <select id="sort" name="sort">
                <option value=0 selected>Start Time</option>
                <option value=2>End Time</option>
                <option value=4>Rating</option>
                <option value=6>Event Name</option>
            </select>

            <button class="text-button" type="button" id="ascendingDescending">▲</button>
        </span>

        <span class="form-element">
            <label for="minTime">From</label>
            <input type="datetime-local" id="minTime" name="minTime">
        </span>

        <span class="form-element">
            <label for="maxTime">To</label>
            <input type="datetime-local" id="maxTime" name="maxTime">
        </span>

        <br><br><input class="button" type="submit" value="Search">
    </form>
    <div class="results-container" id="results-container">
       <ul class="results-list" id="list">

       </ul>
    </div>
    <script type="module" src="events.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
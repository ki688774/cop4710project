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
        <div id="eventHeader">
            <h1 id="eventName">Temporary Event Name</h1>
        </div>
        <p id="eventDescription">This is a temporary description for the event.</p>
        <div id="eventTime">
            <span id="startTime">01/02/2003, 12:00:00 PM</span>
            <span> to </span>
            <span id="endTime">02/03/2004, 12:00:00 PM</span>
        </div>
        <div id="eventLocation">
            <span id="locationName">Example</span><!--
         --><span>,&nbsp;</span><!--
         --><span id="locationAddress">123 Example Rd.</span><!--
         --><span>&nbsp;(</span><!--
         --><span id="longitude">12.34567890</span><!--
         --><span>,&nbsp;</span><!--
         --><span id="latitude">12.34567890</span><!--
         --><span>)</span>
        </div>
        <div id="contactInformation">
            <span id="contactEmail">example@example.org</span><!--
         --><span>,&nbsp;</span><!--
         --><span id="contactPhone">(123) 456-7890</span>
        </div>

        <form class="form" id="editEventForm">
            <label for="eventNameTextBox">Event Name:&nbsp;</label>
            <input type="text" id="eventNameTextBox" name="eventNameTextBox" required><br><br>

            <label for="eventDescriptionTextBox">Event Description:&nbsp;</label>
            <textarea class="textarea" id="eventDescriptionTextBox" name="eventDescriptionTextBox" rows="5" required></textarea><br><br>

            <label for="startTimeInput">From:&nbsp;</label>
            <input type="datetime-local" id="startTimeInput" name="startTimeInput" required>

            <label for="endTimeInput">To:&nbsp;</label>
            <input type="datetime-local" id="endTimeInput" name="endTimeInput" required><br><br>

            <label for="locationInput">Location:&nbsp;</label>
            <input type="text" id="locationInput" name="locationInput" required><br>

            <label for="addressInput">Address:&nbsp;</label>
            <input type="text" id="addressInput" name="addressInput" required><br>

            <label for="longitudeInput">Longitude:&nbsp;</label>
            <input type="text" id="longitudeInput" name="longitudeInput" required><br>

            <label for="latitudeInput">Latitude:&nbsp;</label>
            <input type="text" id="latitudeInput" name="latitudeInput" required><br><br>

            <label for="emailInput">Contact Email:&nbsp;</label>
            <input type="text" id="emailInput" name="emailInput" required><br>

            <label for="phoneInput">Contact Phone:&nbsp;</label>
            <input type="text" id="phoneInput" name="phoneInput" required><br>

            <div class="editButtons">
                <input class="button editButton" id="editButton" value="Edit">
                <input class="button cancelButton" id="cancelButton" value="Cancel">
            </div>
        </form>
    </div>

    <form class="form" id="commentForm">
    <h2>Leave A Comment</h2>
        <textarea class="textarea" id="commentBox" name="commentBox" rows="5" cols="70" required></textarea>
        <div id="submitButtonDiv">
            <input class="button" id="submitButton" type="submit" value="Post">
        </div>
    </form>

    <div class="comments-container" id="comments-container">
        <form class="form" id="searchForm">
            <span class="form-element">
                <label for="minTime">From</label>
                <input type="datetime-local" id="minTime" name="minTime">
            </span>

            <span class="form-element">
                <label for="maxTime">To</label>
                <input type="datetime-local" id="maxTime" name="maxTime">
            </span>
            <input class="button right-button" type="submit" value="Search">
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
        </form>
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./createEvent.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <h1>Create Event</h1>
    <form class="form" id="createEventForm">
        <label for="eventNameTextBox">Event Name:&nbsp;</label>
        <input type="text" id="eventNameTextBox" name="eventNameTextBox" required><br><br>

        <label for="eventDescriptionTextBox">Event Description:&nbsp;</label>
        <textarea class="textarea" id="eventDescriptionTextBox" name="eventDescriptionTextBox" rows="5" required></textarea><br><br>

        <label for="startTimeInput">From:&nbsp;</label>
        <input type="datetime-local" id="startTimeInput" name="startTimeInput" required>

        <label for="endTimeInput">&nbsp;To:&nbsp;</label>
        <input type="datetime-local" id="endTimeInput" name="endTimeInput" required><br><br>

        <label for="locationInput">Location Name:&nbsp;</label>
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
        <input type="text" id="phoneInput" name="phoneInput" required><br><br>

        <span id="publicEventSpan">
            <label for="publicEvent">Public Event?&nbsp;</label>
            <input type="checkbox" id="publicEvent" name="publicEvent" unchecked>
            <span>&nbsp;</span>
        </span>

        <span id="rsoSpan">
            <label for="rsoEvent">RSO Event?&nbsp;</label>
            <input type="checkbox" id="rsoEvent" name="rsoEvent" unchecked>
            <label for="rsoSelect">&nbsp;RSO:&nbsp;</label>
            <select id="rsoSelect" name="rsoSelect"></select>
        </span>

        <br><br><input class="button" type="submit" value="Create">
    </form>

    <script type="module" src="createEvent.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?>    
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>



</html>
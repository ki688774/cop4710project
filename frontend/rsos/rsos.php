<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSOs Page</title>
    <link rel="stylesheet" href="../templates/styles.css">
    <link rel="stylesheet" href="./rsos.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>


<body>
    <h1>RSOs</h1>
    <form class="form" id="rsoSearchForm">
        <br>
        <span class="form-element">
            <label for="search">RSO Name:</label>
            <input type="text" id="search" name="search">
        </span><br>

        <span class="form-element">
            <label for="yourRSOs">Only Show Owned RSOs:&nbsp;</label>
            <input type="checkbox" id="yourRSOs" name="yourRSOs">
        </span>

        <br><br><input class="button" type="submit" value="Search">
        <button id="createRSOButton" class="button" onclick="location.href='./createRSO.php'">Create an RSO</button>
    </form>
    <div class="results-container" id="results-container">
       <ul class="results-list" id="list">

       </ul>
    </div>
    <script type="module" src="rsos.js"></script>
    <?php include '../templates/errorModal.php';?>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>

</html>
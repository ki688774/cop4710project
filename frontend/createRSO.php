<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<header>
    <?php include 'templates/header.php';?>
</header>
    <h2> Name your RSO</h2>
    <body>
    <form id="createRSOForm">
        <label for="nameRSO">RSO Name:</label>
        <input type="nameRSO" id="nameRSO" name="nameRSO" required><br><br>
        <input type="submit" value="createRSO">
    </form>
    </body>

    <footer><?php include 'templates/footer.php';?></footer>
</html>
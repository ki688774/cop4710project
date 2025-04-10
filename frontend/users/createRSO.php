<!DOCTYPE html>
<html>
<head>
    <title>Create RSO</title>
    <link rel="stylesheet" href="../templates/styles.css">
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    <h1>Create Your RSO</h1>
    <form id="createRSO">

        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <input class="button" type="submit" value="Delete">
    </form>
</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>
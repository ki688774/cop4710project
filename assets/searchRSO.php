<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SearchRSOs</title>
    </head>
    <header>
        <!-- INCLUDE FOR HEADER.PHP SHOULD GO HERE-->
        <!-- SHOULD ALSO INCLUDE THE EXTRA NEEDED BUTTONS FOR JOINING AN RSO ETC-->
        <?php include 'header.php';?>
    </header>
    <body>
        <div class="search-container">
            <h2>Search</h2>
            <form action="../php/rsos/searchJoinableRSOs.php" method="get">
                <input type="text" name="q" class="search-input" placeholder="Enter your search" required>
                <br>
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
    </body>

    <footer><?php include 'footer.php';?></footer>
</html>

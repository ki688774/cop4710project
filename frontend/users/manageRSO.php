<!DOCTYPE html>
<html>
<head>
    <title>Manage RSOs</title>
    <link rel="stylesheet" href="./manageRSO.css">
    <link rel="stylesheet" href="../templates/styles.css">
    
</head>

<header>
    <?php include '../templates/header.php';?>
</header>

<body>
    
    <form class="form" id="createRSO">
        <h1>Create Your RSO:</h1>

        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <input type="submit">
    </form>

    
    <!-- <form class="form dependentForm" id="updateRSO"> -->
    <form class="form" id="updateRSO">
        <h1>Update RSO:</h1>

        <label for="rso_ID">RSO ID:</label>
        <input type="text" id="rso_ID" name="rso_ID" required><br><br>

        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <label for="rso_name_confirm">RSO Name Confirm:</label>
        <input type="text" id="rso_name_confirm" name="rso_name_confirm" required><br><br>

        <input type="submit">
    </form>


    
    <!-- <form class="form dependentForm" id="deleteRSO"> -->
    <form class="form" id="deleteRSO">
        <h1>Delete RSO:</h1>

        <label for="rso_ID">RSO ID:</label>
        <input type="text" id="rso_ID" name="rso_ID" required><br><br>

        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <label for="deleteRSOPassword">Password:</label>
        <input type="password" id="deleteRSOPassword" name="deleteRSOPassword" required><br><br>

        <label for="deleteRSOConfirmPassword">Confirm Password:</label>
        <input type="password" id="deleteRSOConfirmPassword" name="deleteRSOConfirmPassword" required><br><br>

        <input type="submit" value="Delete">
    </form>

    <!-- <form class="form dependentForm" id="transferRSO"> -->
    <form class="form" id="transferRSO">
        <h1>Transfer RSO:</h1>

        <label for="transferRSOEmail">New Admin Email:</label>
        <input type="email" id="transferRSOEmail" name="transferRSOEmail" required><br><br><br>

        <label for="rso_ID">RSO ID:</label>
        <input type="text" id="rso_ID" name="rso_ID" required><br><br>

        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <label for="transferRSOPassword">Password:</label>
        <input type="password" id="transferRSOPassword" name="transferRSOPassword" required><br><br>

        <label for="transferRSOPassword">Confirm Password:</label>
        <input type="password" id="transferRSOPassword" name="transferRSOPassword" required><br><br>

        <input type="submit" value="Transfer">
    </form>

    
    <form class="form" id="leaveRSO">
        <h1>Leave RSO:</h1>
        
        <label for="rso_name">RSO Name:</label>
        <input type="text" id="rso_name" name="rso_name" required><br><br>

        <label for="rso_name_confirm">RSO Name Confirm:</label>
        <input type="text" id="rso_name_confirm" name="rso_name_confirm" required><br><br>

        <input type="submit">
    </form>



    <script type="module" src="manageRSO.js"></script>
    <?php include '../templates/errorModal.php';?>
    <?php include '../templates/successModal.php';?> 

</body>

<footer id="footer">
    <?php include '../templates/footer.php';?>
</footer>
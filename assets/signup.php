<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<header>
    <!-- INCLUDE FOR HEADER.PHP SHOULD GO HERE-->
    <!-- SHOULD ALSO INCLUDE THE EXTRA NEEDED BUTTONS FOR JOINING AN RSO ETC-->
    <?php include 'header.php';?>
</header>


<body>
    <body>
        <!-- <form action="../php/users/register.php" method="POST" enctype="multipart/form-data"></form> -->
        <!-- Below form action is for checking the input --> 
        <!-- <form action="process-signup.php" method="POST"> -->
        <?php if(isset($_GET['error'])) { ?>
                <p> class="error"> <?php echo $_GET['error']; ?></p>
                <?php } ?>
        <form id="signup-form">
            <p>
                <label for="firstName">Firstname:</label>    
                <input type="text" name="firstName" id="firstName" required>
            </p>
            <p>
                <label for="lastName">Lastname:</label>    
                <input type="text" name="lastName" id="lastName" required>
            </p>
            <p>
                <label for="email">Email:</label>    
                <input type="email" name="email" id="email" required>
            </p>
            <p>
                <label for="username">Username:</label>    
                <input type="text" name="username" id="username" required>
            </p>
            <p>
                <label for="password">Password:</label>    
                <input type="password" name="password" id="password" required>
            </p>
            <p>
                <label for="password_confirmation">Password:</label>    
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </p>
            <!-- <input type="submit"> -->
            <!-- <button>Sign Up</button> -->
            <input type="hidden" name="redirect" value="homepage.php" />
            <input type="submit" class="btn btn-danger" value="Submit" class="form-control" />
        </form>
    </body>
</html>

<script type="text/javascript">
    //get the form from DOM (Document object model) 
    var form = document.getElementById('signup-form');
    form.onsubmit = function(event){
        var xhr = new XMLHttpRequest();
        var data = new FormData(form);
        //Add extra data to form before submission.
        data.append("referer","https://example.com");
        //open the request
        xhr.open('POST','http://localhost:7000/tests/v1.0/form')
        //send the form data
        xhr.send(data);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                form.reset(); //reset form after AJAX success.
            }
        }

        //Dont submit the form.
        return false; 
    }
</script>
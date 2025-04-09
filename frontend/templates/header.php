<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Manager Header</title>
</head>

<html>
<div class="header">
  <a href="#default" id="mainPage" class="logo">Event Manager</a>
  <div class="header-right">
    <a href="../events/events.php">Events</a>
    <a href="">RSOs</a>
    <a href="../settings/settings.php">Settings</a>
    <a href="" id="signOut">Sign Out</a>
    <a href="../users/login.php" id="login">Log In</a>
    <a href="../users/register.php" id="register">Register</a>
  </div>
</div>
</html>

<script type="module">
  import {getCookie, deleteCookie} from '../templates/cookieFunctions.js';

  let userData = getCookie("userData");
  if (userData != "" && JSON.parse(userData).uid != null) {
    let login = document.getElementById("login");
    let register = document.getElementById("register");
    document.querySelectorAll(".header a").forEach(a=>a.style.display = "block");
    login.style.display = "none";
    register.style.display = "none";
  } else {
    var path = window.location.pathname;
    var page = path.split("/").pop();
    
    if (page != "login.php" && page != "register.php" && page != "universityCreation.php")
      window.location.assign("../users/login.php");
  }

  document.getElementById("signOut").addEventListener("click", async function (event) {
    event.preventDefault();
    deleteCookie("userData");
    window.location.assign("../users/login.php");
  });

</script>

<style>
  /* Style the header with a grey background and some padding */
  .header {
    overflow: hidden;
    background-color: #0D1B2A;
    padding: 5px 10px;
  }

  /* Style the header links */
  .header a {
    float: left;
    color: black;
    text-align: center;
    padding: 12px;
    text-decoration: none;
    display: none;
    font-size: 18px;
    line-height: 25px;
    border-radius: 4px;
    color: #E0E1DD;
  }

  #register {
    display:block;
  }

  #login {
    display:block;
  }

  #mainPage {
    display: block;
  }

  /* Style the logo link (notice that we set the same value of line-height and font-size to prevent the header to increase when the font gets bigger */
  .header a.logo {
    font-size: 25px;
    font-weight: bold;
  }

  /* Change the background color on mouse-over */
  .header a:hover {
    background-color: #415A77;
    color: black;
  }

  /* Style the active/current link*/
  /* Use this to change a specfic element of the page to a certain color*/
  .header a.active {
    background-color: dodgerblue;
    color: white;
  }

  /* Float the link section to the right */
  .header-right {
    float: right;
  }
</style>
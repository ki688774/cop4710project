document.getElementById("universityCreation").addEventListener("submit", async function (event) {
    event.preventDefault();

    //university information
    let university_name = document.getElementById("university_name").value;
    let location_name = document.getElementById("location_name").value;
    let address = document.getElementById("address").value;
    let longitude = document.getElementById("longitude").value;
    let latitude = document.getElementById("latitude").value;

    //super admin information
    let firstName = document.getElementById("firstName").value;
    let lastName = document.getElementById("lastName").value;
    let email = document.getElementById("email").value;
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

<<<<<<< HEAD:frontend/users/universityCreation.js
    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }

    let payload = JSON.stringify({university_name: university_name, location_name: location_name, 
        address: address, longitude: longitude, latitude: latitude, firstName: firstName, 
        lastName: lastName, email: email, username: username, password: password,})
=======
    let payload = JSON.stringify({
        unviersity_name: unviersity_name, location_name: location_name,
        address: address, longitude: longitude, latitude: latitude, firstName: firstName,
        lastName: lastName, email: email, username: username, password: password,
        confirmPassword: confirmPassword
    })
>>>>>>> 3abd3bb (formatting change):frontend/unviersityCreation.js
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/createUniversityAndSuperAdmin.php", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: payload
        });
    } catch (error) {
        summonErrorModal(error);
        return;
    }

    let returnedData = await returnedResponse.json();
<<<<<<< HEAD:frontend/users/universityCreation.js
    
    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }
    
    window.location.assign("./login.php");
=======

    if (typeof returnedData.error !== 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    saveCookie("userData", returnedData.result);
    //window.location.assign("../homepage.php");
>>>>>>> 3abd3bb (formatting change):frontend/unviersityCreation.js
});
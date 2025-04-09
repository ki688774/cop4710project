import {saveCookie, getCookie, deleteCookie, refreshCookie} from '../templates/cookieFunctions.js';

// Causes the Delete University form to appear and the Delete User form to disappear.
let userData = getCookie("userData");
if (userData != "" && JSON.parse(userData).uid != null) {

    userData = JSON.parse(userData);
    let payload = JSON.stringify({university_id: userData.university_id});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/getUniversity.php", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: payload
        });

        let returnedData = await returnedResponse.json();

        if (typeof returnedData.result === 'undefined') {
            summonErrorModal(returnedData.error);
        } else if (returnedData.result.super_admin_id == userData.uid) {
            document.getElementById("updateUniversityForm").style.display="block";
            document.getElementById("transferUniversityForm").style.display="block";
            document.getElementById("deleteUniversityForm").style.display="block";
        } else {
            document.getElementById("deleteAccountForm").style.display="block";
        }
    } catch (error) {
        summonErrorModal(error);
    }
}

document.getElementById("updateUserForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    let firstName = document.getElementById("firstName").value;
    let lastName = document.getElementById("lastName").value;
    let email = document.getElementById("email").value;
    let username = document.getElementById("username").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, firstName: firstName, lastName: lastName, email: email, username: username})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/users/updateUser.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    saveCookie("userData", JSON.stringify({uid: userData.uid, firstName: firstName, lastName: lastName, email: email, username: username}));
    summonSuccessModal("User information updated successfully.");
});

document.getElementById("updatePasswordForm").addEventListener("submit", async function (event) {
    // Process input and verify new password.
    event.preventDefault();
    let oldPassword = document.getElementById("oldPassword").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }



    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, password: oldPassword, new_password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/users/updatePassword.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    refreshCookie();
    summonSuccessModal("User password updated successfully.");
});

document.getElementById("deleteAccountForm").addEventListener("submit", async function (event) {
    // Process input and confirm password.
    event.preventDefault();
    let password = document.getElementById("deleteAccountPassword").value;
    let confirmPassword = document.getElementById("deleteAccountConfirmPassword").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }



    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/users/deleteUser.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    deleteCookie("userData");
    window.location.assign("../users/login.php");
});

document.getElementById("updateUniversityForm").addEventListener("submit", async function (event) {
    // Process input and verify new password.
    event.preventDefault();
    let universityName = document.getElementById("university_name").value;
    let universityEmail = document.getElementById("updateUniversityEmail").value;
    let locationName = document.getElementById("location_name").value;
    let address = document.getElementById("address").value;
    let longitude = document.getElementById("longitude").value;
    let latitude = document.getElementById("latitude").value;
    
    let password = document.getElementById("updateUniversityPassword").value;
    let confirmPassword = document.getElementById("updateUniversityConfirmPassword").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }



    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, university_name: universityName, university_domain: universityEmail, 
            location_name: locationName, address: address, longitude: longitude, latitude: latitude, password: password});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/updateUniversity.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    refreshCookie();
    summonSuccessModal("University data updated successfully.");
});

document.getElementById("transferUniversityForm").addEventListener("submit", async function (event) {
    // Process input and verify new password.
    event.preventDefault();
    let email = document.getElementById("transferUniversityEmail").value;
    let password = document.getElementById("transferUniversityPassword").value;
    let confirmPassword = document.getElementById("transferUniversityConfirmPassword").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }

    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, new_super_admin_email: email, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/transferUniversity.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    refreshCookie();
    window.location.reload();
});

document.getElementById("deleteUniversityForm").addEventListener("submit", async function (event) {
    // Process input and confirm password.
    event.preventDefault();
    
    let email = document.getElementById("universityDeleteEmail").value;
    let password = document.getElementById("deleteUniversityPassword").value;
    let confirmPassword = document.getElementById("deleteUniversityConfirmPassword").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }



    // Prepare payload and send command.
    let payload = JSON.stringify({current_user: userData.uid, university_domain: email, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/deleteUniversity.php", {
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

    // Process return value.
    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    deleteCookie("userData");
    window.location.assign("../users/login.php");
});
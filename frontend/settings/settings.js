import {saveCookie, getCookie, deleteCookie, refreshCookie} from '../templates/cookieFunctions.js';

document.addEventListener("DOMContentLoaded", async function() {
    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null)
        return;

    userData = JSON.parse(userData);
    let userDomain = (userData.email.split("@"))[1];
    let payload = JSON.stringify({university_domain: userDomain});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/getUniversity.php", {
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

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    if (returnedData.result.super_admin_id == userData.uid) {
        document.getElementById("deleteUniversityForm").style.display="block";
    }
});

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

document.getElementById("deleteUniversityForm").addEventListener("submit", async function (event) {
    // Process input and confirm password.
    event.preventDefault();
    let email = document.getElementById("email").value;
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
    let payload = JSON.stringify({current_user: userData.uid, university_domain: email, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/users/deleteUniversity.php", {
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
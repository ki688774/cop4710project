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
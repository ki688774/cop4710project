import {saveCookie, getCookie, deleteCookie, refreshCookie} from '../templates/cookieFunctions.js';

document.addEventListener("DOMContentLoaded", async function() {
    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }
});


document.getElementById("createRSO").addEventListener("submit", async function (event) {
    event.preventDefault();

    let rso_name = document.getElementById("rso_name");

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    let payload = JSON.stringify({current_user: current_user, rso_name: rso_name});
    let returnedResponse = null;

    try{
        returnedResponse = await fetch("../../php/rsos/createRSO.php", {
            method: "POST",
            headers: {
                "content-type": "applicationd/json"
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

    summonSuccessModal("RSO successfully made!")
});

document.getElementById("updateRSO").addEventListener("submit", async function (event) {
    event.preventDefault();
    
    
})


//deleteRSO 
document.getElementById("deleteRSO").addEventListener("submit", async function (event) {
    event.preventDefault();
    let rso_ID = document.getElementById("rso_ID").value;
    let rso_name = document.getElementById("rso_name").value;
    let password = document.getElementById("deleteRSOPassword").value;
    let confirmpassword = document.getElementById("deleteRSOConfirmPassword").value;

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
    
    //prep payload
    let payload = JSON.stringify({current_user: userData.uid, rso_ID: rso_ID, rso_name: rso_name, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/rsos/deleteRSO.php", {
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


});
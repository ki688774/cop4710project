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
    let payload = JSON.stringify({rso_name: rso_name});
    let returnedResponse = null;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

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

});
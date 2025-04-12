import {saveCookie, getCookie, deleteCookie, refreshCookie} from '../templates/cookieFunctions.js';

// Causes publicEventSpan and rsoSpan to appear as necessary.
let userData = getCookie("userData");
if (userData !== undefined)
    userData = JSON.parse(userData);



document.getElementById("createEventForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    let rsoName = document.getElementById("rsoNameTextBox").value;

    let payload = JSON.stringify({current_user: userData.uid, rso_name: rsoName});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/rsos/createRSO.php", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: payload
        });
    } catch (error) {
        summonErrorModal(error);
    }

    let returnedData = await returnedResponse.json();

    if (typeof returnedData.rso_id !== undefined) {
        refreshCookie("userData");
        window.location.assign("./rsoDetails.php?rso_id=" + returnedData.rso_id);
    } else
        summonErrorModal(returnedData.error);
});
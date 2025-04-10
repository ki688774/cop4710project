import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

document.addEventListener("DOMContentLoaded", async function () {
    const urlParams = new URLSearchParams(window.location.search);
    const eventID = urlParams.get('event_id');

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, event_id: eventID});
    let returnedResponse = null;


    try {
        returnedResponse = await fetch("../../php/events/getEvent.php", {
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

    document.getElementById("eventName").textContent = returnedData.result.event_name;
    document.getElementById("eventDescription").textContent = returnedData.result.event_description;
    document.getElementById("eventTime").textContent = convertToUserFriendlyTime(returnedData.result.start_time) + " to " + convertToUserFriendlyTime(returnedData.result.end_time);
    document.getElementById("eventAddress").textContent = returnedData.result.location_name + ", " + returnedData.result.address + " (" + returnedData.result.longitude + ", " + returnedData.result.latitude + ")";
    document.getElementById("contactInformation").textContent = returnedData.result.contact_email + ", " + returnedData.result.contact_phone;
});

document.getElementById("commentForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
});

// yyyy-mm-dd hh:mm:ss -> mm/dd/yyyy hh:mm:ss am/pm
function convertToUserFriendlyTime (inString) {
    let date = Date(inString.replace(" ", "T"));
    return date.toLocaleString("en-US", { timeZone: "UTC" });
}


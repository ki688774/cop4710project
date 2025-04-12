import {saveCookie, getCookie, deleteCookie, refreshCookie} from '../templates/cookieFunctions.js';

// Causes publicEventSpan and rsoSpan to appear as necessary.
let userData = getCookie("userData");
if (userData !== undefined)
    userData = JSON.parse(userData);

document.getElementById("publicEvent").checked = false;
document.getElementById("rsoEvent").checked = false;

if (userData.uid != null) {
    let payload = JSON.stringify({current_user: userData.uid});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/universities/checkIfSuperAdmin.php", {
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
    let isSuperAdmin = (typeof returnedData.result !== 'undefined');



    try {
        returnedResponse = await fetch("../../php/rsos/getYourActiveRSOs.php", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: payload
        });
    } catch (error) {
        summonErrorModal(error);
    }

    returnedData = await returnedResponse.json();
    let rsoSelect = document.getElementById("rsoSelect");

    if (typeof returnedData.result !== 'undefined' && returnedData.result.length > 0) {
        for (const rso of returnedData.result) {
            const option = document.createElement("option");
            option.value = rso.rso_id;
            option.text = rso.rso_name;
            rsoSelect.add(option);
        }
    
        document.getElementById("rsoSpan").style.display = 'inline';
    
        if (isSuperAdmin)
            document.getElementById("publicEventSpan").style.display = 'inline';
    } else {
        if (isSuperAdmin)
            document.getElementById("publicEvent").checked = true;
        else
            window.location.assign("./events.php");
    }
    
}

document.getElementById("createEventForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    let isPublic = document.getElementById("publicEvent").checked;
    let response = false;

    if (isPublic)
        response = createPublicEvent();
    else
        response = createNonPublicEvent();
});

async function createPublicEvent() {
    // Process input.
    let eventName = document.getElementById("eventNameTextBox").value;
    let eventDescription = document.getElementById("eventDescriptionTextBox").value;

    let startTime = document.getElementById("startTimeInput").value.replace("T", " ") + ":00";
    let endTime = document.getElementById("endTimeInput").value.replace("T", " ") + ":00";

    let locationName = document.getElementById("locationInput").value;
    let address = document.getElementById("addressInput").value;
    let longitude = document.getElementById("longitudeInput").value;
    let latitude = document.getElementById("latitudeInput").value;

    let phone = document.getElementById("phoneInput").value;
    let email = document.getElementById("emailInput").value;



    let payload = JSON.stringify({current_user: userData.uid, location_name: locationName, address, longitude, latitude,
        start_time: startTime, end_time: endTime, event_name: eventName, event_description: eventDescription,
        contact_phone: phone, contact_email: email});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/events/createPublicEvent.php", {
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

    if (typeof returnedData.event_id !== undefined) {
        refreshCookie("userData");
        window.location.assign("./eventDetails.php?event_id=" + returnedData.event_id);
    } else
        summonErrorModal(returnedData.error);
    
}

async function createNonPublicEvent() {
    let eventName = document.getElementById("eventNameTextBox").value;
    let eventDescription = document.getElementById("eventDescriptionTextBox").value;

    let startTime = document.getElementById("startTimeInput").value.replace("T", " ") + ":00";
    let endTime = document.getElementById("endTimeInput").value.replace("T", " ") + ":00";

    let locationName = document.getElementById("locationInput").value;
    let address = document.getElementById("addressInput").value;
    let longitude = document.getElementById("longitudeInput").value;
    let latitude = document.getElementById("latitudeInput").value;

    let phone = document.getElementById("phoneInput").value;
    let email = document.getElementById("emailInput").value;

    let isRSOEvent = document.getElementById("rsoEvent").checked;
    let targetRSO = document.getElementById("rsoSelect").value;



    let payload = JSON.stringify({current_user: userData.uid, location_name: locationName, address, longitude, latitude,
        start_time: startTime, end_time: endTime, event_name: eventName, event_description: eventDescription,
        contact_phone: phone, contact_email: email, rso_id: targetRSO});
    let returnedResponse = null;

    let targetEndpoint = isRSOEvent ? "../../php/events/createRSOEvent.php" : "../../php/events/createPrivateEvent.php";

    try {
        returnedResponse = await fetch(targetEndpoint, {
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

    if (typeof returnedData.event_id !== undefined) {
        refreshCookie("userData");
        window.location.assign("./eventDetails.php?event_id=" + returnedData.event_id);
    } else
        summonErrorModal(returnedData.error);
}
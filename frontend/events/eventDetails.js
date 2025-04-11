import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let descending = false;
let date = new Date();
date.setTime(date.getTime());
const urlParams = new URLSearchParams(window.location.search);
const eventID = Number(urlParams.get('event_id'));

// Invert the value of descending and switch ascendingDescending's text on click.
document.getElementById("ascendingDescending").addEventListener("click", async function (event) {
    event.preventDefault();
    descending = !descending;
    if (descending)
        document.getElementById("ascendingDescending").textContent="▼";
    else
    document.getElementById("ascendingDescending").textContent="▲";
});

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

    getComments();
});

document.getElementById("commentForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    getComments();
});

// yyyy-mm-dd hh:mm:ss -> mm/dd/yyyy hh:mm:ss am/pm
function convertToUserFriendlyTime (inString) {
    let date = Date(inString.replace(" ", "T"));
    return date.toLocaleString("en-US", { timeZone: "UTC" });
}

async function getComments () {
    let search = document.getElementById("search").value;
    let sortType = Number(document.getElementById("sort").value) + (descending ? 1 : 0);
    let minTime = document.getElementById("minTime").value.replace("T", " ") + ":00";
    let maxTime = document.getElementById("maxTime").value.replace("T", " ") + ":00";

    if (minTime == ":00")
        minTime = "";

    if (maxTime == ":00")
        maxTime = "";

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);


    
    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, event_id: eventID, search: search, sort_type: sortType, minimum_time: minTime, maximum_time: maxTime})
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/comments/searchComments.php", {
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

    let list = document.getElementById("list");

    while (list.firstChild){
        list.removeChild(list.firstChild)
    }

    for (const entry of returnedData.result) {
        const resultItem = document.createElement('li');
        resultItem.classList.add('comment')

        const commenterName = document.createElement('h3');
        commenterName.classList.add("commenterName", "forceInline");
        commenterName.appendChild(document.createTextNode(entry.fullName));
        resultItem.appendChild(commenterName);

        const timestamp = document.createElement('p');
        timestamp.classList.add("timestamp", "forceInline");
        timestamp.appendChild(document.createTextNode(entry.timestamp));
        resultItem.appendChild(timestamp);

        if (entry.uid == userData.uid) {
            const optionsSpan = document.createElement('span');
            optionsSpan.classList.add("optionsSpan", "forceInline");

            const editButton = document.createElement('p');
            editButton.classList.add("editButton", "text-button", "forceInline");
            editButton.appendChild(document.createTextNode("✎"));
            optionsSpan.appendChild(editButton);

            const deleteButton = document.createElement('p');
            deleteButton.classList.add("deleteButton", "text-button", "forceInline");
            deleteButton.appendChild(document.createTextNode("×"));
            optionsSpan.appendChild(deleteButton);

            resultItem.appendChild(optionsSpan);
        }

        const commentText = document.createElement('p');
        commentText.classList.add("commentText");
        commentText.appendChild(document.createTextNode(entry.text));
        resultItem.appendChild(commentText);

        const userID = document.createElement('p');
        userID.classList.add("userID");
        userID.appendChild(document.createTextNode(entry.uid));
        resultItem.appendChild(userID);

        const commentID = document.createElement('p');
        commentID.classList.add("commentID");
        commentID.appendChild(document.createTextNode(entry.comment_id));
        resultItem.appendChild(commentID);

        list.appendChild(resultItem);
    }
    
    refreshCookie("userData");
}

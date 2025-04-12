import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let descending = false;
let date = new Date();
date.setTime(date.getTime());

let userData = getCookie("userData");
if (userData == "" || JSON.parse(userData).uid == null) {
    summonErrorModal("User is not signed in.");
} else {
    userData = JSON.parse(userData);
}



document.addEventListener("DOMContentLoaded", async function () {
    let payload = JSON.stringify({current_user: userData.uid});
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/events/canCreateEvent.php", {
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

    if (typeof returnedData.result !== 'undefined')
        document.getElementById("createClassButton").style.display = 'inline';

    searchEvents();
});

// Invert the value of descending and switch ascendingDescending's text on click.
document.getElementById("ascendingDescending").addEventListener("click", async function (event) {
    event.preventDefault();
    descending = !descending;
    if (descending)
        document.getElementById("ascendingDescending").textContent="▼";
    else
    document.getElementById("ascendingDescending").textContent="▲";
});

document.getElementById("eventSearchForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    searchEvents();
});

async function searchEvents () {
    let search = document.getElementById("search").value;
    let sortType = Number(document.getElementById("sort").value) + (descending ? 1 : 0);
    let minTime = document.getElementById("minTime").value.replace("T", " ") + ":00";
    let maxTime = document.getElementById("maxTime").value.replace("T", " ") + ":00";
    let onlyYourEvents = document.getElementById("yourEvents").checked ? 1 : 0;

    if (minTime == ":00")
        minTime = "";

    if (maxTime == ":00")
        maxTime = "";


    
    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, search: search, sort_type: sortType, minimum_time: minTime, maximum_time: maxTime, only_your_events: onlyYourEvents})
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/events/searchEvents.php", {
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
        resultItem.classList.add('event')

        const eventName = document.createElement('h3')
        eventName.classList.add("eventName");
        eventName.appendChild(document.createTextNode(entry.event_name));
        resultItem.appendChild(eventName);

        const eventDescription = document.createElement('p')
        eventDescription.classList.add("eventDescription");
        eventDescription.appendChild(document.createTextNode(entry.event_description));
        resultItem.appendChild(eventDescription);

        const times = document.createElement('p')
        times.classList.add("times");
        times.appendChild(document.createTextNode(convertToUserFriendlyTime(entry.start_time) + " to " + convertToUserFriendlyTime(entry.end_time)));
        resultItem.appendChild(times);

        const address = document.createElement('p')
        address.classList.add("address");
        address.appendChild(document.createTextNode(entry.address));
        resultItem.appendChild(address);

        const eventID = document.createElement('p')
        eventID.classList.add("eventID");
        eventID.appendChild(document.createTextNode(entry.event_id));
        resultItem.appendChild(eventID);

        list.appendChild(resultItem);
    }
    
    refreshCookie("userData");
}

document.getElementById("results-container").addEventListener("click", async function (event) {
    let target = event.target;
    if (target.tagName != 'LI')
        if (target.parentNode.tagName != 'LI')
            return;
        else
            target = target.parentNode;
    
    let eventID = target.querySelector('.eventID');
    refreshCookie("userData");
    window.location.assign("./eventDetails.php?event_id=" + eventID.textContent);
});

// yyyy-mm-dd hh:mm:ss -> mm/dd/yyyy hh:mm:ss am/pm
// There is technically a function that already does this, but it was giving me trouble.
function convertToUserFriendlyTime (inString) {
    let year = inString.substring(0, 4);
    let month = inString.substring(5, 7);
    let day = inString.substring(8, 10);
    let hour = Number(inString.substring(11, 13));
    let minute = inString.substring(14, 16);
    let seconds = inString.substring(17, 19);

    let amPm = "AM";

    if (hour >= 12) {
        amPm = "PM";
        if (hour > 12)
            hour -= 12;
    }

    if (hour == 0) {
        hour = 12;
    }

    return month + "/" + day + "/" + year + " " + hour + ":" + minute + ":" + seconds + " " + amPm;
}
import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let descending = false;
let date = new Date();
date.setTime(date.getTime());

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
    let payload = JSON.stringify({current_user: userData.uid, search: search, sortType: sortType, minimum_time: minTime, maximum_time: maxTime})
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

        list.appendChild(resultItem);
    }
    
    refreshCookie("userData");
});

// yyyy-mm-dd hh:mm:ss -> 
function convertToUserFriendlyTime (inString) {
    let date = Date(inString.replace(" ", "T"));
    return date.toLocaleString();
}
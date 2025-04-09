import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let descending = false;

let date = new Date();
date.setTime(date.getTime());
document.getElementById("minTime").value = (date.toISOString()).substring(0,16);

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
    let sortType = document.getElementById("sort").value + (descending ? 1 : 0);
    let minTime = document.getElementById("minTime").value.replace("T", " ") + ":00";
    let maxTime = document.getElementById("maxTime").value.replace("T", " ") + ":00";

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

    summonErrorModal(returnedData.result);
    refreshCookie();
});
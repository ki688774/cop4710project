import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let date = new Date();
date.setTime(date.getTime());

let userData = getCookie("userData");
if (userData == "" || JSON.parse(userData).uid == null) {
    summonErrorModal("User is not signed in.");
} else {
    userData = JSON.parse(userData);
}



document.addEventListener("DOMContentLoaded", async function () {
    searchRSOs();
});

document.getElementById("rsoSearchForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    searchRSOs();
});

async function searchRSOs () {
    let search = document.getElementById("search").value;
    let onlyYourRSOs = document.getElementById("yourRSOs").checked ? 1 : 0;


    
    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, search: search, your_rsos_only: onlyYourRSOs})
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/rsos/searchJoinableRSOs.php", {
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

        const rsoName = document.createElement('h3')
        rsoName.classList.add("rsoName");
        rsoName.appendChild(document.createTextNode(entry.rso_name));
        resultItem.appendChild(rsoName);

        const rsoID = document.createElement('p')
        rsoID.classList.add("rsoID");
        rsoID.appendChild(document.createTextNode(entry.rso_id));
        resultItem.appendChild(rsoID);

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
    
    let rsoID = target.querySelector('.rsoID');
    refreshCookie("userData");
    window.location.assign("./rsoDetails.php?rso_id=" + rsoID.textContent);
});
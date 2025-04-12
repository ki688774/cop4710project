import {refreshCookie, getCookie} from '../templates/cookieFunctions.js';

let date = new Date();
date.setTime(date.getTime());
const urlParams = new URLSearchParams(window.location.search);
const rsoID = Number(urlParams.get('rso_id'));

document.addEventListener("DOMContentLoaded", async function () {
    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);

    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
    let returnedResponse = null;


    try {
        returnedResponse = await fetch("../../php/rsos/getRSO.php", {
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

    document.getElementById("rsoName").textContent = returnedData.result.rso_name;
    document.getElementById("rsoNameTextBox").value = returnedData.result.rso_name;



    // Create payload and push.
    payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
    returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/rsos/canEditRSO.php", {
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

    returnedData = await returnedResponse.json();

    // If you're the owner of this RSO, then create the edit and delete buttons.
    if (typeof returnedData.result !== 'undefined') {
        const eventOptionsSpan = document.createElement('span');
        eventOptionsSpan.classList.add("eventOptionsSpan", "forceInline");

        const editButton = document.createElement('p');
        editButton.classList.add("editRSOButton", "text-button", "forceInline");
        editButton.appendChild(document.createTextNode("✎"));
        editButton.addEventListener('click', editRSO, false);
        eventOptionsSpan.appendChild(editButton);

        const deleteButton = document.createElement('p');
        deleteButton.classList.add("deleteRSOButton", "text-button", "forceInline");
        deleteButton.appendChild(document.createTextNode("×"));
        deleteButton.addEventListener('click', deleteRSO, false);
        eventOptionsSpan.appendChild(deleteButton);

        document.getElementById("rsoHeader").appendChild(eventOptionsSpan); 
    } else {
        // Otherwise, show the join and leave buttons.
        document.getElementById("actionButtons").style.display = "block";
    }

    

    payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
    returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/rsos/isMemberOfRSO.php", {
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

    returnedData = await returnedResponse.json();
    
    if (typeof returnedData.result !== 'undefined')
        document.getElementById("joinButton").style.display = "none";
    else
        document.getElementById("leaveButton").style.display = "none";
    
});

async function editRSO (event) {
    let eventInformation = event.target.parentNode.parentNode.parentNode;

    // Hide existing elements and show editRSOForm.
    eventInformation.querySelector("#rsoHeader").style.display = 'none';
    eventInformation.querySelector("#editRSOForm").style.display = 'block';
}

document.getElementById("joinButton").addEventListener("click", async function (event) {
    // Process input.
    event.preventDefault();

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/rsos/joinRSO.php", {
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

    refreshCookie("userData");
    location.reload();
});

document.getElementById("leaveButton").addEventListener("click", async function (event) {
    event.preventDefault();

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/rsos/leaveRSO.php", {
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

    refreshCookie("userData");
    location.reload();
});

async function deleteRSO (event) {
    // Check if the user is signed in.
    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, rso_id: rsoID});
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

    let returnedData = await returnedResponse.json();

    if (typeof returnedData.result === 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    window.location.assign("./rsos.php");
}
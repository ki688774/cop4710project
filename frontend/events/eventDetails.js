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
    let text = document.getElementById("commentBox").value;

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, event_id: eventID, text: text})
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/comments/createComment.php", {
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
    getComments();
});

document.getElementById("searchForm").addEventListener("submit", async function (event) {
    // Process input.
    event.preventDefault();
    getComments();
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
        amPM = "PM";
        if (hour > 12)
            hour -= 12;
    }

    if (hour == 0) {
        hour = 12;
    }

    return month + "/" + day + "/" + year + " " + hour + ":" + minute + ":" + seconds + " " + amPm;
}

async function deleteComment (event) {
    let listItem = event.target.parentNode.parentNode;
    let targetCommentID = Number(listItem.querySelector(".commentID").innerHTML);

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, comment_id: targetCommentID});
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/comments/deleteComment.php", {
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

    getComments();
}

async function enterEditMode (event) {
    let listItem = event.target.parentNode.parentNode;
    let currentText = listItem.querySelector(".commentText").innerHTML;

    // Hide existing elements.
    listItem.querySelector(".commenterName").style.display = 'none';
    listItem.querySelector(".timestamp").style.display = 'none';
    listItem.querySelector(".optionsSpan").style.display = 'none';
    listItem.querySelector(".commentText").style.display = 'none';



    // Set up update mode elements and append them to the entry.
    const textUpdateBox = document.createElement('textarea');
    textUpdateBox.classList.add("textUpdateBox");
    textUpdateBox.setAttribute("rows", 5);
    textUpdateBox.setAttribute("cols", 75);
    textUpdateBox.innerText = currentText;
    listItem.appendChild(textUpdateBox);

    const buttonDiv = document.createElement('div');
    buttonDiv.classList.add("editButtons");

    const pushEditButton = document.createElement('input');
    pushEditButton.classList.add("button", "cancelButton");
    pushEditButton.setAttribute("type", "edit");
    pushEditButton.setAttribute("value", "Edit");
    pushEditButton.addEventListener('click', pushEdit, false);
    buttonDiv.appendChild(pushEditButton);

    const cancelEditButton = document.createElement('input');
    cancelEditButton.classList.add("button", "editButton");
    cancelEditButton.setAttribute("type", "cancelEdit");
    cancelEditButton.setAttribute("value", "Cancel");
    cancelEditButton.addEventListener('click', exitEditMode, false);
    buttonDiv.appendChild(cancelEditButton);

    listItem.appendChild(buttonDiv);
}

async function pushEdit (event) {
    // Process input.
    let listItem = event.target.parentNode.parentNode;
    let currentText = listItem.querySelector(".textUpdateBox").value;
    let targetCommentID = Number(listItem.querySelector(".commentID").innerHTML);

    let userData = getCookie("userData");
    if (userData == "" || JSON.parse(userData).uid == null) {
        summonErrorModal("User is not signed in.");
        return;
    }

    userData = JSON.parse(userData);



    // Create payload and push.
    let payload = JSON.stringify({current_user: userData.uid, comment_id: targetCommentID, text: currentText})
    let returnedResponse = null;
    
    try {
        returnedResponse = await fetch("../../php/comments/updateComment.php", {
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
    getComments();
}

async function exitEditMode (event) {
    // Delete all edit elements.
    let listItem = event.target.parentNode.parentNode;
    listItem.removeChild(listItem.querySelector(".textUpdateBox"));
    listItem.removeChild(listItem.querySelector(".editButtons"));

    // Return all non-edit elements.
    listItem.querySelector(".commenterName").style.display = 'inline';
    listItem.querySelector(".timestamp").style.display = 'inline';
    listItem.querySelector(".optionsSpan").style.display = 'inline';
    listItem.querySelector(".commentText").style.display = 'block';
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

    // Get the current comment list, and empty it out if there is anything.
    let list = document.getElementById("list");

    while (list.firstChild){
        list.removeChild(list.firstChild)
    }

    // Create each individual comment.
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
            editButton.addEventListener('click', enterEditMode, false);
            optionsSpan.appendChild(editButton);

            const deleteButton = document.createElement('p');
            deleteButton.classList.add("deleteButton", "text-button", "forceInline");
            deleteButton.appendChild(document.createTextNode("×"));
            deleteButton.addEventListener('click', deleteComment, false);
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

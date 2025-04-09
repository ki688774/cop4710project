import {saveCookie} from '../templates/cookieFunctions.js';

document.getElementById("loginForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let payload = JSON.stringify({username: username, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../../php/users/login.php", {
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

    if (typeof returnedData.error !== 'undefined') {
        summonErrorModal(returnedData.error);
        return;
    }

    saveCookie("userData", JSON.stringify(returnedData.result));
    window.location.assign("../homepage.php");
});
import {saveCookie} from 'templates/cookieFunctions.js';

document.getElementById("universitySearch").addEventListener("submit", async function (event) {
    event.preventDefault();

    let email = document.getElementById("email").value;

    let payload = JSON.stringify({email: email})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("../php/universities/getUniversity.php", {
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

    summonSuccessModal(returnedData.result);
    //console.log(returnedData.result)
});
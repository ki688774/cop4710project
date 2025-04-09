import {saveCookie} from '../templates/cookieFunctions.js';

document.getElementById("createRSO").addEventListener("submit", async function (event) {
    event.preventDefault();

    let rso_name = document.getElementById("rso_name");
    let payload = JSON.stringify({rso_name: rso_name});
    let returnedResponse = null;

    try{
        returnedResponse = await fetch("../../rsos/createRSO.php", {
            method: "POST",
            headers: {
                "content-type": "applicationd/json"
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

});
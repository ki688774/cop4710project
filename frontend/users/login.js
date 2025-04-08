let errorModal = document.getElementById("errorModal");
let errorText = document.getElementById("errorText");
let successModal = document.getElementById("successModal");
let successText = document.getElementById("successText");

document.getElementById("loginForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let payload = JSON.stringify({username: username, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("./../../php/users/login.php", {
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


    summonSuccessModal(returnedData.result);
    
})

document.getElementById("errorClose").onclick = function () {
    errorModal.style.display = "none";
}

document.getElementById("successClose").onclick = function () {
    successModal.style.display = "none";
}
function summonErrorModal (errorString) {
    errorText.innerText = errorString;
    errorModal.style.display = "block";
}

function summonSuccessModal (successString) {
    successText.innerText = successString;
    successModal.style.display = "block";
    // code for redirection goes here!
    window.location.assign("../homepage.php");
}
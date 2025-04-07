let errorModal = document.getElementById("errorModal");
let errorText = document.getElementById("errorText");
let successModal = document.getElementById("successModal");
let successText = document.getElementById("successText");

document.getElementById("registerForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    
    let firstName = document.getElementById("firstName").value;
    let lastName = document.getElementById("lastName").value;
    let email = document.getElementById("email").value;
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        summonErrorModal("The passwords do not match.");
        return;
    }

    let payload = JSON.stringify({firstName: firstName, lastName: lastName, email: email, username: username, password: password})
    let returnedResponse = null;

    try {
        returnedResponse = await fetch("./../../php/users/register.php", {
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
    // code to redirect to login.html goes here, i think
}

function summonErrorModal (errorString) {
    errorText.innerText = errorString;
    errorModal.style.display = "block";
}

function summonSuccessModal (successString) {
    successText.innerText = successString;
    successModal.style.display = "block";
}
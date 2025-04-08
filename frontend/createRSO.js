let errorModal = document.getElementById("errorModal");
let errorText = document.getElementById("errorText");
let successModal = document.getElementById("successModal");
let successText = document.getElementById("successText");

document.getElementById("createRSO").addEventListener("submit", async function (event) {
    event.preventDefault();

    let rsoName = document.getElementById("rsoName").value;
    
    try {
        returnedResponse = await fetch("../../../php/users/createRSO.php", {
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

    let returnData = await returnedResponse.json();

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
}
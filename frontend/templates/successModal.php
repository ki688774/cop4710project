<!DOCTYPE html>
<html>
<body>
    <div id="successModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <h2>Success!</h2>
                <span id="successClose" class="close">&times;</span>
            </div>
            <div id="successText" class="modal-body">
                This text gets overwritten anyhow.
            </div>
        </div>
    </div>
</body>
</html>

<style>
    #successHeader {
        display: inline
    }

    #successModal {
        display: none;
        width: 400px;
        background-color: #0D1B2A;
        padding: 10px 20px 20px 10px;
        border-radius: 5%;
        overflow-wrap: break-word;
    }

    .close {
        float: right;
        font-size: x-large;
        color: #E0E1DD;
    }

    .modal-body {
        font-family: Arial, sans-serif;
        color: #E0E1DD;
    }
</style>

<script>
    let successModal = document.getElementById("successModal");
    let successText = document.getElementById("successText");

    document.getElementById("successClose").onclick = function () {
        successModal.style.display = "none";
    }

    function summonSuccessModal (successString) {
        successText.innerText = successString;
        successModal.style.display = "block";
    }
</script>
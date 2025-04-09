<!DOCTYPE html>
<html>
<body>
    <div id="successModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <span id="successClose" class="close">&times;</span>
                <h2>Success!</h2>
            </div>
            <div id="successText" class="modal-body">
                <p>This text gets overwritten anyhow.</p>
            </div>
        </div>
    </div>
</body>
</html>

<style>
    #successModal {
        display: none;
        width: 400px;
    }

    #successHeader {
        text-align: left;
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
<!DOCTYPE html>
<html>
<body>
    <div id="errorModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <h2 id=errorHeader>Error</h2>
                <span id="errorClose" class="close">&times;</span>
            </div>
            <div id="errorText" class="modal-body">
                This text gets overwritten anyhow.
            </div>
        </div>
    </div>
</body>
</html>

<style>
    #errorHeader {
        display: inline;
    }

    #errorModal {
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
    let errorModal = document.getElementById("errorModal");
    let errorText = document.getElementById("errorText");
    
    document.getElementById("errorClose").onclick = function () {
        errorModal.style.display = "none";
    }

    function summonErrorModal (errorString) {
        errorText.innerText = errorString;
        errorModal.style.display = "block";
    }
</script>
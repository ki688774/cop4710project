<!DOCTYPE html>
<html>
<body>
    <div id="errorModal" class="modal">        
        <div class="modal-content">
            <div class="modal-header">
                <span id="errorClose" class="close">&times;</span>
                <h2 id=errorHeader>Error</h2>
            </div>
            <div id="errorText" class="modal-body">
                <p>This text gets overwritten anyhow.</p>
            </div>
        </div>
    </div>
</body>
</html>

<style>
    #errorModal {
        display: none;
        width: 400px;
    }

    #errorHeader {
        text-align: left;
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
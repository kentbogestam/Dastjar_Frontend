<style>
  .confirm-modal {
    display: none; 
    position: fixed; 
    z-index: 1;
    padding-top: 100px; 
    left: 0;
    top: 50px;
    width: 100%;
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4); 
  }
  .confirm-modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: fit-content;
  }
</style>

<button id="myConfirmBtn" style="display:none">Open Modal</button>
<div id="myConfirmModal" class="confirm-modal">
  <div class="confirm-modal-content">
    <p class="confirm-text"></p><br>
    <button type="button" class="btn confirm-conti">{{__('messages.continue')}}</button>
    <button type="button" class="btn confirm-close">{{__('messages.Cancel')}}</button>
  </div>

</div>

<script>
var confirmModal = document.getElementById("myConfirmModal");
var confirmBtn = document.getElementById("myConfirmBtn");
var confirmSpan = document.getElementsByClassName("confirm-close")[0];
confirmBtn.onclick = function() {
  confirmModal.style.display = "block";
}
confirmSpan.onclick = function() {
  confirmModal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == confirmModal) {
    confirmModal.style.display = "none";
  }
}
</script>
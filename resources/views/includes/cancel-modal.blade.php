<style>
  .cancel-modal {
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
  .cancel-modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: fit-content;
  }
</style>

<button id="myCancelBtn" style="display:none">Open Modal</button>
<div id="myCancelModal" class="cancel-modal">
  <div class="cancel-modal-content">
    <p class="cancel-text">{{ __('messages.doYoureallywantstoCancel') }}</p><br>
    <button type="button" class="btn cancel-conti">{{__('messages.yes')}}</button>
    <button type="button" class="btn cancel-close">{{__('messages.no')}}</button>
  </div>

</div>

<script>
var cancelModal = document.getElementById("myCancelModal");
var cancelBtn = document.getElementById("myCancelBtn");
var cancelSpan = document.getElementsByClassName("cancel-close")[0];
cancelBtn.onclick = function() {
  cancelModal.style.display = "block";
}
cancelSpan.onclick = function() {
  cancelModal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == cancelModal) {
    cancelModal.style.display = "none";
  }
}
</script>
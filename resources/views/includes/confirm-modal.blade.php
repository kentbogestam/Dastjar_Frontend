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
    <center style="border:2px solid black;padding:5px;">
      <p style="color:red;font-weight: bold;font-size: 18px"><img src="{{ asset('kitchenImages/warning.jpg') }}" width="18" height="18" style="margin: 0px 10px"> {{__('messages.warning')}}!</p>
      <p class="confirm-text"></p>
      <p>{{__('messages.areYouSure')}}</p>
    </center>
    <div class="rowfull" style="width:100%;margin-top:10px">
      <div class="colmd6" style="width:50% !important;float:left">
        <button type="button" class="btn confirm-close" style="border:2px solid black;padding:5px;width:100%">{{__('messages.no')}} <img src="{{ asset('kitchenImages/enter.png') }}" width="30" height="30"></button>
      </div>
      <div class="colmd6" style="width:42% !important;float:left;margin-left: 8%;margin-top:3px">
        <button type="button" class="btn confirm-conti" style="background-color:red;text-shadow:none;color:black;width:100%">{{__('messages.yes')}}!</button>
      </div>
      <div style="clear:both"></div>
    </div>
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
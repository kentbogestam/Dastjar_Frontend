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
    <p class="confirm-text1">{{ __('messages.doYouWantsToShareOverPhone') }}?<br></p>
    <div class="row confirm-text2">
      <div class="col-xs-12">{{ __('messages.enterYourPhoneNumber') }}?</div>
      <div class="col-xs-4">
        @php $phone_number_prifix = 46; @endphp
        @if( !is_null(@$order->phone_number_prifix) )
          @php $phone_number_prifix = @$order->phone_number_prifix; @endphp
        @endif
        <select class="form-control" id="phone_number_prifix" required>
          <option value="">{{ __('messages.select') }}</option>
          <option value="91" {{ ($phone_number_prifix == 91) ? "selected" : '' }}>+91</option>
          <option value="46" {{ ($phone_number_prifix == 46) ? "selected" : '' }}>+46</option>
        </select>
      </div>
      <div class="col-xs-8">
        <input type="text" id="phone_number" value="{{ !is_null($order->phone_number) ? $order->phone_number : '' }}" placeholder="{{ __('messages.mobileNumber') }}*" class="form-control" required>
      </div>
    </div><br>
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
</script>
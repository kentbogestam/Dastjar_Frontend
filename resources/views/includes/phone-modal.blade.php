<style>
  .phone-modal {
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
  .phone-modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: fit-content;
  }
</style>

<button id="myPhoneBtn" style="display:none">Open Modal</button>
<div id="myPhoneModal" class="phone-modal">
  <div class="phone-modal-content">
    <div class="row">
      <div class="col-xs-12"><b>{{ __('messages.doYouWantsToShareOverPhone') }}?</b><br><br></div>
      <div class="col-xs-12">{{ __('messages.enterYourPhoneNumber') }}.<br><br></div>
      <div class="col-xs-3">
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
      <div class="col-xs-9">
        <input type="text" id="phone_number" value="{{ !is_null($order->phone_number) ? $order->phone_number : '' }}" placeholder="{{ __('messages.mobileNumber') }}*" class="form-control" required>
      </div>
    </div><br>
    <button type="button" class="btn phone-conti">{{__('messages.continue')}}</button>
    <button type="button" class="btn phone-close">{{__('messages.Cancel')}}</button>
  </div>

</div>

<script>
var phoneModal = document.getElementById("myPhoneModal");
var phoneBtn = document.getElementById("myPhoneBtn");
var phoneSpan = document.getElementsByClassName("phone-close")[0];
phoneBtn.onclick = function() {
  phoneModal.style.display = "block";
}
phoneSpan.onclick = function() {
  phoneModal.style.display = "none";
}
</script>
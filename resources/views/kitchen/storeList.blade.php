@extends('layouts.blank')

@section('content')
        <div data-role="header" data-theme="c">
             <div class="logo_header">
                <img src="{{asset('kitchenImages/logo-img.png')}}">
                <a  href = "{{ url('kitchen/logout') }}" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">{{ __('messages.Logout') }}
                </a>
            </div>
        </div><!-- /header -->
        <form id="form" class="form-horizontal" data-ajax="false" method="post" action="{{ url('kitchen/store') }}">
        {{ csrf_field() }}
            <div role="main" class="ui-content">
                <h3 class="store_title">Store List</h3>
                <div class="store_container">
                    @if ($message = Session::get('error'))
                        <div class="notifications">
                            <div class="alert alert-danger alert-block">
                                <h4>Error</h4>
                                @if(is_array($message))
                                    @foreach ($message as $m)
                                        {{ $languageStrings[$m] ?? $m }}
                                    @endforeach
                                @else
                                    {{ $languageStrings[$message] ?? $message }}
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($storeDetails)
                        @foreach($storeDetails as $storeName)
                             <label class="storeId">
                                <input type="radio" name="storeId" id="{{$storeName->store_id}}" value="{{$storeName->store_id}}" required>{{$storeName->store_name}}
                            </label>
                        @endforeach
                    <div style="display:none">
                        <button type="submit" class="select_store">Done</button>
                    </div>
                    @else <h3> Store is not avaliable.</h3>
                    @endif
                </div>
            </div>
        </form>    
@endsection

@section('footer-script')
<script type="text/javascript">
    window.addEventListener( "pageshow", function ( event ) {
  var historyTraversal = event.persisted || ( typeof window.performance != "undefined" && window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    // Handle page restore.
    window.location.reload();
  }
});

$('body').on('click touchstart', '.storeId', function(){
    setTimeout(function(){
        $('.select_store').trigger('touchstart');
        $('.select_store').trigger('click');
    },500);
});
</script>
   
@endsection
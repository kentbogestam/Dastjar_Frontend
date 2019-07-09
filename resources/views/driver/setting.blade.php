@extends('driver.layouts.app')

@section('content')
	<div class="container-fluid">
		<div class="row" style="padding: 7px 0 7px 0; background-color: #ccc; margin-bottom: 10px;">
			<div class="col-xs-4 text-left">
				<a href="{{ url('driver') }}" title="{{ __('messages.back') }}" style="padding: 10px 10px 0px 0">
					<i class="fas fa-chevron-left" style="font-size: 32px;"></i>
				</a>
			</div>
			<div class="col-xs-4 text-center"><i class="fas fa-ellipsis-h" style="font-size: 32px;"></i></div>
			<div class="col-xs-4 text-right"><!-- {{ __('messages.Done') }} --></div>
		</div>
		@include('common.errors')
    	@include('common.flash')
		<div class="panel-group" id="settings">
			<div class="panel panel-default">
		        <div class="panel-heading">
		            <h4 class="panel-title">
		            	<a data-toggle="collapse" data-parent="#settings" href="#personal-information">{{ __('messages.personalInformation') }}</a>
		            </h4>
		        </div>
		        <div id="personal-information" class="panel-collapse collapse">
		            <div class="panel-body">
		            	<form method="POST" action="{{ url('driver/update-driver') }}" id="form-driver">
							{{ csrf_field() }}
							<div class="form-group">
								<input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" placeholder="{{ __('messages.phEnterMobile') }}" class="form-control" id="phone" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-number="true" data-msg-number="{{ __('messages.fieldNumber') }}">
							</div>
							<div class="form-group">
								<input type="text" name="email" value="{{ old('email', $driver->email) }}" placeholder="{{ __('messages.phEnterEmail') }}" class="form-control" id="email" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}" data-rule-email="true" data-msg-email="{{ __('messages.fieldEmail') }}">
							</div>
							<button type="submit" class="btn btn-primary btn-block">{{ __('messages.save') }}</button>
						</form>
		            </div>
		        </div>
		    </div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#settings" href="#collapse2">{{ __('messages.changePassword') }}</a>
					</h4>
				</div>
				<div id="collapse2" class="panel-collapse collapse">
					<div class="panel-body">
						<form method="POST" action="{{ url('driver/change-password') }}" id="form-change-password">
							{{ csrf_field() }}
							<div class="form-group">
								<input type="password" name="password" placeholder="{{ __('messages.phPassword') }}" class="form-control" id="password" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
							</div>
							<div class="form-group">
								<input type="password" name="password_confirmation" placeholder="{{ __('messages.phConfirmPassword') }}" class="form-control" id="password_confirmation" data-rule-required="true" data-msg-required="{{ __('messages.fieldRequired') }}">
							</div>
							<button type="submit" class="btn btn-primary btn-block">{{ __('messages.save') }}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ url('assets/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
	$(document).ready(function() {
        // Form validation
        $("#form-driver").validate();
        $("#form-change-password").validate();
    });
</script>
@endsection
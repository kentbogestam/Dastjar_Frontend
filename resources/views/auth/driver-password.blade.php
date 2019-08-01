@extends('driver.layouts.login')

@section('content')
<div class="container">
    <h2>{{ __('messages.resetPassword') }}</h2><br>
    @include('common.errors')
    @include('common.flash')
    <form method="POST" action="{{ url('driver/reset-password') }}">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <label for="phone">{{ __('messages.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ __('messages.mobileNumber') }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">{{ __('messages.submit') }}</button>
    </form>
    <p></p>
    <p>{{ __('messages.login') }}? <a href="{{ url('driver/login') }}">{{ __('messages.click_here') }}
</div>
@endsection
@extends('driver.layouts.login')

@section('content')
<div class="container">
    <h2>{{ __('messages.driverLogin') }}</h2><br>
    <form method="POST" action="{{ route('driver.login.submit') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">{{ __('messages.email') }}</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password">{{ __('messages.password') }}</label>
            <input type="password" name="password" id="password" class="form-control" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary btn-block">{{ __('messages.login') }}</button>
    </form>
    <p></p>
    <p>{{ __('messages.forgetPassword') }} <a href="{{ url('driver/forget-password') }}">{{ __('messages.click_here') }}</a></p>
</div>
@endsection
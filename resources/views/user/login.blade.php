<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
  </head>
  <body class="antialiased border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
          <a href="."><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="{{ route('login.post') }}" method="post" autocomplete="off">
            @csrf
          <div class="card-body">
              @if (session('success'))
                  <div class="alert alert-success">
                      {{ session('success') }}
                  </div>
              @endif
            <h2 class="card-title text-center mb-4">{{ __('messages.login') }}</h2>
            <div class="mb-3">
              <label class="form-label">{{ __('messages.email_address') }}</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="{{ __('messages.email_address') }}" required autofocus>
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="mb-2">
              <label class="form-label">
                  {{ __('messages.password') }}
                <span class="form-label-description">
                  <a href="{{ route('forgotPassword.index') }}">{{ __('messages.i_forgot_password') }}</a>
                </span>
              </label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control"  placeholder="{{ __('messages.password') }}"  autocomplete="off" required>
              </div>
                @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">{{ __('messages.sign_in') }}</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
            {{ __('messages.dont_have_account_yet') }} <a href="{{ route('register') }}" tabindex="-1">{{ __('messages.register') }}</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
  </body>
</html>

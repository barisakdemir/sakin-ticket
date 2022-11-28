<!doctype html>
<!--
* Sakin Ticket is a ticket management software
* @link https://github.com/barisakdemir/sakin-ticket
* Licensed under GNU (https://github.com/barisakdemir/sakin-ticket/blob/main/LICENSE.txt)
-->
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MGCPKQNPQ0"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-MGCPKQNPQ0');
    </script>
  </head>
  <body class="antialiased border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
            <a href="."><img src="./static/st-logo-dark.png" height="50" alt=""></a>
        </div>
        <form class="card card-md" action="{{ route('register.post') }}" method="POST">
          @csrf
          <div class="card-body">
            <h2 class="card-title text-center mb-4">{{ __('messages.register') }}</h2>
            <div class="mb-3">
              <label class="form-label">{{ __('messages.name') }}</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="{{ __('messages.name') }}" required autofocus>
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="mb-3">
              <label class="form-label">{{ __('messages.email_address') }}</label>
              <input type="email" id="email_address" name="email" class="form-control" placeholder="{{ __('messages.email_address') }}" required autofocus >
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="mb-3">
              <label class="form-label">{{ __('messages.password') }}</label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control"  placeholder="{{ __('messages.password') }}"  autocomplete="off" required autofocus>
              </div>
              @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="mb-3">
              <label class="form-check">
                <input type="checkbox" class="form-check-input"/>
                <span class="form-check-label"><a href="./terms-of-service.html" tabindex="-1">{{ __('messages.agree_the_terms_and_policy') }}</a>.</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">{{ __('messages.create_new_account') }}</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
            {{ __('messages.already_have_account') }} <a href="{{ route('login') }}" tabindex="-1">{{ __('messages.login') }}</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
  </body>
</html>

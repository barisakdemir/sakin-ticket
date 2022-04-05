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
        <form class="card card-md" action="{{ route('forgotPassword.store') }}" method="post" autocomplete="off">
            @csrf
          <div class="card-body">
              @if (session('success'))
                  <div class="alert alert-success">
                      {{ session('success') }}
                  </div>
              @endif
              @if(count($errors) > 0)
                  <div class="alert alert-danger">
                      @foreach ($errors->all() as $error)
                          <div class="text-muted">{{ $error }}</div>
                      @endforeach
                  </div>
              @endif
            <h2 class="card-title text-center mb-4">Forgot Password</h2>
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required autofocus>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Send</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
          Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Register</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
  </body>
</html>

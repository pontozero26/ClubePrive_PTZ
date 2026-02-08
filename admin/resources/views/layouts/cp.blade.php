<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- FONTES -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  @yield('css')

  <title>Clube Privê</title>
</head>

<body>
  <div class="screen">
    <div class="screen-header">
      <div class="logo">
        <img src="{{ asset('img/logo2.png')}}" alt="Clube Privê">
      </div>
      @yield('steps')
    </div>

    @yield('content')

  </div>
  @yield('js')
</body>

</html>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="{{ asset('assets/dev.png') }}" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite('resources/js/app.js')
  @inertiaHead
  @routes
</head>

<body>
  @inertia
</body>

</html>
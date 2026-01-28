<!DOCTYPE html>
<html lang="en">

<meta charset="utf-8" />
<title>@yield('title', 'Iniciar Sesión  | AnaliSys')</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta content="Admin & Dashboards Template" name="description" />
<meta content="Pixeleyez" name="author" />

<!-- layout setup -->
<!--<script type="module" src="js/layout-setup.js"></script>-->

<!-- App faviconx -->
<link rel="shortcut icon" href="images/k_favicon_32x.png">

@yield('css')
@include('partials.head-css') 

<body>

@yield('content')

@include('partials.vendor-scripts')  

@yield('js')

</body>

</html>
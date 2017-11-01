<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<link href="/css/all.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="/js/all.js"></script>

</head>
<body data-title="@yield('data_title')">

@include('partials.nav')

    <div class="container" id="maincontent">

        @yield('content')

    </div>





</body>
</html>

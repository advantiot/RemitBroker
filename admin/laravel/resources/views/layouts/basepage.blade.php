<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>RemitBroker | Admin Console5</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
</head>
<body>
	@yield('body')

    @include('widgets.footer')

    <!-- This is the default script file that came with the template files -->
    <!-- Has some user interaction functions -->
    <!-- Located in: ~/projects/RemitBroker/admin/laravel/public/assets/scripts -->
    <!-- NOTE: There is a copy of this file in /resources/assets - THAT IS NOT USED -->
    <script src="{{ asset("assets/scripts/frontend.js") }}" type="text/javascript"></script>

    <!-- Adding a custom file for RemitBroker functions -->
    <script src="{{ asset("assets/scripts/common.js") }}" type="text/javascript"></script>


</body>
</html>

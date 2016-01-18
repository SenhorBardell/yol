<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Yol админ панель | {{$title or ''}}</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	{{--@include('includes.apple-icons', ['sizes' => [57, 60, 72, 76, 114, 120, 144, 152, 180]]) --}}
	{{--@include('includes.favicon', ['sizes' => [16, 32, 96, 194]])--}}
	<link rel="manifest" href="<?php asset('img/favicon/manifest.json') ?>">
	<link rel="shortcut icon" href="<?php asset('img/favicon/favicon.ico') ?>">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="/img/favicon/mstile-144x144.png">
	<meta name="msapplication-config" content="/img/favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<!-- Bootstrap 3.3.2 -->
	<link href="<?php echo asset('bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
	<!-- Font Awesome Icons -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Ionicons -->
	<link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<!-- Тип кузова -->
	<link href="<?php echo asset('plugins/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet" type="text/css" />
	<!-- iCheck -->
	<link href="<?php echo asset('plugins/iCheck/square/blue.css') ?>" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="<?php echo asset('css/AdminLTE.css') ?>" rel="stylesheet" type="text/css" />
	<!-- AdminLTE Skins. Choose a skin from the css/skins
		 folder instead of downloading all of them to reduce the load. -->
	<link href="<?php echo asset('css/skins/_all-skins.min.css') ?>" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>
<body class="skin-blue sidebar-collapse sidebar-open">
<div class="wrapper">

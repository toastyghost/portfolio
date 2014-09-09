<?php header('Content-Type: text/html; charset=utf-8'); ?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Portfolio of Joshua Clark, experienced front-end and back-end web developer</title>

		<meta name="description" content="Seasoned full-stack web developer seeks new programming challenges! Take a look at my past work portfolio here.">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Pretty fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Roboto:400" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" rel="stylesheet" type="text/css">
		
		<link href="assets/css/normalize.min.css" rel="stylesheet">
		<link href="assets/css/style.css?<?=rand()?>" rel="stylesheet" media="screen" type="text/css">
		
		<!-- Apple/MS pageweight rape -->
		<link rel="apple-touch-icon" sizes="57x57" href="assets/icons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="114x114" href="assets/icons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="72x72" href="assets/icons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="144x144" href="assets/icons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="60x60" href="assets/icons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="120x120" href="assets/icons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="76x76" href="assets/icons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="152x152" href="assets/icons/apple-touch-icon-152x152.png">
		<link rel="icon" type="image/png" href="assets/icons/favicon-196x196.png" sizes="196x196">
		<link rel="icon" type="image/png" href="assets/icons/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="assets/icons/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="assets/icons/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="assets/icons/favicon-32x32.png" sizes="32x32">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-TileImage" content="assets/icons/mstile-144x144.png">
		
		<!-- jQuery + plugins & bindings -->
		<script src="//code.jquery.com/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
		<script async src="assets/js/script.js?<?=rand()?>"></script>

		<!-- More bullshit for people who are bad at browser -->
		<script src="assets/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

		<!-- Fancy front-end app type stuff -->
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
		<script src="https://cdn.firebase.com/js/client/1.0.17/firebase.js"></script>
		<script src="//cdn.firebase.com/libs/angularfire/0.7.1/angularfire.min.js"></script>
		<script src="assets/js/angular/app.js"></script>
	</head>
	
	<body>
		<header id="header">
			<div id="header-inner">
				<h1><a href="#" id="logo"><span id="first-letter">j</span>oshua d. clark</a></h1>
				
				<nav id="nav">
					<a id="intro-navlink" class="navlink active" href="#intro">Intro</a>
					<a id="clients-navlink" class="navlink" href="#resume">Clients</a>
					<a id="development-navlink" class="navlink" href="#development">Development</a>
					<a id="design-navlink" class="navlink" href="#design">Design</a>
					<a id="resume-navlink" class="navlink" href="#resume">R&eacute;sum&eacute;</a>
					<a id="contact-navlink" class="navlink" href="#contact">Contact</a>
				</nav>
			</div>
		</header>
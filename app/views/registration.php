<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
	<script src="jquery.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundation/5.3.0/css/foundation.min.css">
</head>
<body>

	<nav class="top-bar" data-topbar>
	  <ul class="title-area">
		<li class="name">
		  <h1><a href="<?php echo  URL::to('/') ?>">Home</a></h1>
		</li>
		 <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	  </ul>

	  <section class="top-bar-section">

		<!-- Left Nav Section -->
		<ul class="left">
		  <li><a href="<?php echo URL::to('/register') ?>">Register</a></li>
		</ul>
	  </section>
	</nav>

	<section class="row">
		<div class="large-6 columns">
			<h3><a href="<?php echo URL::to('/') ?>" class="current">&larr; Back</a></h3>
		</div>
	</section>

	<section class="row">

		<h1>Registration</h1>

		<div class="large-6 columns">
			<input type="email" name="username" id="username" placeholder="почтовый адресс">
			<input type="password" name="password" id="password" placeholder="пароль">
			<input type="text" name="name" id="name" placeholder="имя" required>
			<a href="#" id="submit" class="button">register</a>
		</div>
	</section>

	<script>
		(function() {
		
			$('#submit').click(function(e) {
				e.preventDefault();

				data = {
					email: $('#username').val(),
					password: $('#password').val(),
					name: $('#name').val()
				}

				$.post('<?php echo URL::to("/api/users/register") ?>', data, function(data) {
					$('.row').append(data);
				});
			});

		})()
	</script>
</body>
</html>
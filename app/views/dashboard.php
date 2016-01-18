<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Test long polling</title>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundation/5.3.0/css/foundation.min.css">
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js"></script> -->
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js"></script> -->
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
	<script src="jquery.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/foundation-essential/5.3.0/js/foundation.min.js"></script>
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

	<aside class="row">
		<div class="large-4 columns">
			<ul class="breadcrumbs" style="margin-top: 20px">
				<li><?php echo $post->category->title ?></li>
				<li class="current"><?php echo $post->title ?></li>
			</ul>
		</div>
	</aside>

	<section class="row">
		<heading class="large-4 large-centered columns">
			<h1><?php echo $post->title ?></h1>
		</heading>

	</section>

	<section class="post row">
		<p class="large-12 columns">
			<?php echo $post->text ?>
		</p>
	</section>
	
	<section class="comments row">

	<ul class="no-bullet">
		<?php if ($comments->count() > 0) foreach($comments as $comment) : ?>
			<li class="large-12 large-centered columns">
				<span class="name"><?php echo $comment->user['name'] ?>:</span>
				<span class="text"><?php echo $comment->text ?></span>
				<span class="time"><?php echo $comment->created_at ?></span>
			</li>
		<?php endforeach ?>
	</ul>

	</section>

	<section class="row auth">
		<div class="large-12 columns">
			<h1>Authenticate before send any message</h1>
			<input id="username" type="text" name="username" placeholder="почтовый адрес">
			<input id="password" name="password" type="password" placeholder="пароль">
			<a href="#" id="authenticate" class="button">Authenticate</a>
		</div>
	</section>

	<section class="entry row">
		<textarea name="entry" id="entry"></textarea>
		<a href="#" id="send" class="button">Send</a>
	</section>
	
</body>

	<script>
		(function() {
			$(document).foundation();
			var credentials = {};

			$('.entry').hide();

			window.poll = function() {
					$.ajax({ 
					url: '<?php echo URL::to('/').'/api/posts/1/comments' ?>',
					type: 'GET',
					data: {
						timestamp: new Date().toISOString().slice(0, 19).replace('T', ' '),
					}, 
					success: function(data) {
						if (data !== null && typeof data === 'object') {
							$.each(data, function(key, data) {
								$('.comments ul').append(
									'<li class="large-12 large-centered columns">' +
										'<span class="name">' + data.user_name + ': </span>' +
										'<span class="text">' + data.text + ' </span>' +
										'<span class="time">' + data.created_at.date + ' </span>' +
									'</li>');
								})
							}
					},
					beforeSend: function(xhr) {
						xhr.setRequestHeader("Authorization", "Basic " + btoa(credentials.username + ":" + credentials.password));
					}, 
					dataType: "json", 
					complete: window.poll,
					async: true, 
					timeout: 30000 
				})
			}
			
			$('#authenticate').click(function(e) {
				e.preventDefault();
				credentials.username = $('.auth #username').val();
				credentials.password = $('.auth #password').val();
				console.log(credentials);
				$('.auth').remove();
				$('.entry').show();

				$.ajax({
					url: '<?php echo URL::to('/').'/api/users/self' ?>',
					type: 'GET',
					beforeSend: function(xhr) {
						xhr.setRequestHeader("Authorization", "Basic " + btoa(credentials.username + ":" + credentials.password));
					},
					dataType: 'json',
					success: function(data) {
						console.log(data)
						credentials.user_id = data.id
					}
				});

				window.poll();
			});

			$('#send').submit(function() {
				console.log('Submit fired');
			});

			$('#send').click(function(e) {
				e.preventDefault();

				if (!credentials.username || !credentials.password) {
					alert('Authenticate');
					return false;
				}

				
				data = {
					text: $('#entry').val(),
					user_id: credentials.user_id
				};

				$.ajax({
					url: '<?php echo URL::to('/').'/api/posts/1/comments' ?>',
					type: 'POST',
					data: data,
					beforeSend: function(xhr) {
						xhr.setRequestHeader("Authorization", "Basic " + btoa(credentials.username + ":" + credentials.password));
					}, 
					dataType: "json", 
					success: function(data) {
						console.log('Done');
						$('#entry').val('');
					}
				});

			});

		})();
	</script>
</html>

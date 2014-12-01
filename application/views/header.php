<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<title><?=$title; ?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/datepicker3.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<script>
		<?php if(isset($referer)): ?>
			var referer = "<?= $referer; ?>";
		<?php endif ?>
		//var baseURL = <?= URL::base(); ?>;
		$(function(){
			<?php if(isset($referer)): ?>
				$("#previousPage").on('click', function(){
					window.location = referer;
				});
			<?php endif ?>
			$("#registerButton").on('click', function(){
				window.location = "<?=URL::base();?>user/register";
			});
			$("#logoutButton").on('click', function(){
				window.location = "<?=URL::base();?>user/logout";
			});
		});
		</script>
		<style>
			body
			{
				font-family: sans-serif;
			}
			.container
			{
				width: 900px;
			}
			.error
			{
				color: red;
			}
		</style>
	</head>
	<body>
		<header>
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?=URL::base();?>">Game Online Shop</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						
						<?php if(!isset($_SESSION['name'])): ?>
							<form class="navbar-form navbar-right" role="form" action="<?= URL::base();?>user/login" method="POST">
								<div class="form-group">
									<input class="form-control" name="email" type="text" placeholder="Email"></input>
								</div>
								<div class="form-group">
									<input class="form-control" name="password" type="password" placeholder="Password"></input>
								</div>
								<button class="btn btn-success" type="submit">Sign in</button>
								<button class="btn btn-success" id="registerButton" type="button">Register</button>
							</form>
						<?php else: ?>
							<div class="navbar-form navbar-right" >
								<button class="btn btn-success" id="logoutButton" type="button">Logout</button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</nav>
		</header>
<!DOCTYPE HTML>
<html lang="fr-FR">
<head>
	<title>Admin panel</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/admin.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="<?= URL::base()?>assets/js/tinymce/tinymce.min.js"></script>
	
	<script>
	//tinyMCE
	tinymce.init({
		selector:'.tinymce',
		plugins: [
			"advlist autolink autosave link image lists charmap preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
			"table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
		],

		toolbar1: "newdocument | cut copy paste searchreplace | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | link unlink anchor image code",
		toolbar2: "bullist numlist | outdent indent blockquote | insertdatetime preview | forecolor backcolor | styleselect formatselect fontselect fontsizeselect",
		toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | fullscreen | ltr rtl | visualchars visualblocks nonbreaking pagebreak restoredraft",

		menubar: false,
		toolbar_items_size: 'small',

		min_height : 200,
		max_height : 500,
		height : 200,
		setup : function(ed) {
			ed.on('init', function(ed) {
				ed.setContent
			});
		}
	});
	$(function(){
		$("#logoutButton").on('click', function(){
			window.location = "<?=URL::base();?>admin/logout";
		});
	});
	</script>
	<style>
		body
		{
			font-family: sans-serif;
			padding-top: 51px;
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
					<div class="navbar-form navbar-right" >
						<button class="btn btn-danger" id="logoutButton" type="button"><?=__('Logout');?></button>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<div class="main">
		<form id="" action="" class="form-horizontal" method="post" enctype="multipart/form-data">
			<div class="jumbotron">
				<div class="container">
					<h1>Add product</h1>
				</div>
			</div>
			<fieldset class="container">
				<fieldset name ="productInfo">
					<legend>Product Info</legend>
					<?php if(isset($error)): ?>
						<p class="error"><?=$error;?></p>
					<?php endif ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="name">Name</label>
						<div class="col-sm-10">
							<input required class="form-control" name="name" id="name" type="text" value="<?= isset($_POST['name'])?$_POST['name']:'';?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="price">Price</label>
						<div class="col-sm-10">
							<input required class="form-control" name="price" id="price" type="number" value="<?= isset($_POST['price'])?$_POST['price']:'';?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="image">Image</label>
						<div class="col-sm-10">
							<input required name="image" id="image" type="file"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="frdesc">French description</label>
						<div class="col-sm-10">
							<textarea name="frdesc" id="frdesc" class="tinymce"><?= isset($_POST['frdesc'])?$_POST['frdesc']:'';?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="endesc">English description</label>
						<div class="col-sm-10">
							<textarea name="endesc" id="endesc" class="tinymce"><?= isset($_POST['endesc'])?$_POST['endesc']:'';?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Platforms</label>
						<div class="checkbox">
							<div class="col-sm-10">
								<?php foreach($platforms as $platform):?>
									<label class="col-sm-3">
										<input type="checkbox" name="plat<?= $platform['id'] ?>"<?= isset($_POST['plat'.$platform['id']])?'checked':''; ?>><?= $platform['name'] ?>
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Categories</label>
						<div class="checkbox">
							<div class="col-sm-10">
								<?php foreach($categories as $category):?>
									<label class="col-sm-3">
										<input type="checkbox" name="cat<?= $category['id'] ?>"<?= isset($_POST['cat'.$category['id']])?'checked':''; ?>><?= $category['name'] ?>
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</fieldset>
			</fieldset>
			<div class="jumbotron">
				<div class="container">
					<button class="btn btn-primary" type="submit">Envoyer</button>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
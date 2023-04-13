<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php echo $this->fetch('meta'); ?>

	<title><?php echo $this->fetch('title'); ?> | Amateur IMDB</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
		  crossorigin="anonymous">
	<?php echo $this->Html->css('main'); ?>
	<?php echo $this->fetch('css'); ?>
</head>
<body class="d-flex flex-column h-100">
<header class="primary-header">
	<nav class="navbar navbar-expand-sm navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="/" title="Productive Time Waste">
				<span class="navbar-brand-icon mr-1"><i class="bi bi-clock"></i></span>
				<span class="navbar-brand-text">
					<strong>P</strong><span class="text-muted">roductive</span><strong>T</strong><span class="text-muted">ime</span><strong>W</strong><span class="text-muted">aste</span>
				</span>
			</a>
			<button class="navbar-toggler"
					type="button"
					data-toggle="collapse"
					data-target="#navbarMenu">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarMenu">
				<div class="navbar-left d-flex flex-wrap align-items-center ml-auto">
					<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>"
					   class="btn btn-outline-dark mr-3">
						Login
					</a>
					<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'registration')); ?>"
					   class="btn btn-dark">
						Register
					</a>
				</div>
				<!--./navbar-left-->
			</div>
			<!--./navbar-collapse-->
		</div>
		<!--./container-->
	</nav>
</header>

<?php echo $this->Flash->render() ?>

<main class="d-flex flex-column flex-grow-1 flex-shrink-0">
	<?php echo $this->fetch('content'); ?>
</main>

<footer class="primary-footer mt-auto text-center">
	<div class="container">
		<p>&copy; 2023 PTW by <a href="https://github.com/dragi-ns/ptw" target="_blank">dragi-ns</a></p>
	</div>
	<!--./container-->
</footer>

<div class="border border-primary p-4">
	<p class="font-weight-bold">SQL DEBUG</p>
	<?php echo $this->element('sql_dump'); ?>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<?php echo $this->fetch('script');?>
</body>
</html>

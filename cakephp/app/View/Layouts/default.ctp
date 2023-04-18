<?php
/**
 * @var bool $isAuth
 * @var bool $isAdmin
 */
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php echo $this->fetch('meta'); ?>

	<title><?php echo $this->fetch('title'); ?> | Amateur IMDB</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet"
		  href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
		  integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
		  crossorigin="anonymous">
	<?php echo $this->Html->css('main'); ?>
	<?php echo $this->fetch('css'); ?>
</head>
<body class="d-flex flex-column h-100">

<?php echo $this->element('header', array(
	'isAuth' => $isAuth,
	'isAdmin' => $isAdmin
)); ?>

<main class="d-flex flex-column flex-grow-1 flex-shrink-0">
	<div class="container py-4 h-100">
		<?php echo $this->Flash->render() ?>

		<?php echo $this->fetch('content'); ?>
	</div>
</main>

<?php echo $this->element('footer'); ?>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<?php echo $this->Html->script('main'); ?>
<?php echo $this->fetch('script');?>
</body>
</html>

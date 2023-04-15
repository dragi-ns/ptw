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
	<?php echo $this->Html->css('admin'); ?>
	<?php echo $this->fetch('css'); ?>
</head>
<body class="d-flex flex-column h-100">

<?php echo $this->element('header', array(
	'isAuth' => $isAuth,
	'isAdmin' => $isAdmin
)); ?>

<?php echo $this->Flash->render() ?>

<main class="d-flex flex-column flex-grow-1 flex-shrink-0">
	<div class="admin-container row no-gutters">
		<div class="sidebar col-12 col-md-3 col-lg-2 px-3 py-4">
			<ul class="nav flex-column">
				<li class="nav-item">
					<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?>"
					   class="nav-link<?php echo strpos($this->here, '/admin/users') !== false ? ' active' : '' ?>">
					   Users
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo $this->Html->url(array('controller' => 'types', 'action' => 'index', 'admin' => true)); ?>"
					   class="nav-link<?php echo strpos($this->here, '/admin/types') !== false ? ' active' : '' ?>">
						Types
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index', 'admin' => true)); ?>"
					   class="nav-link<?php echo strpos($this->here, '/admin/categories') !== false ? ' active' : '' ?>">
						Categories
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo $this->Html->url(array('controller' => 'resources', 'action' => 'index', 'admin' => true)); ?>"
					   class="nav-link<?php echo strpos($this->here, '/admin/resources') !== false ? ' active' : '' ?>">
						Resources
					</a>
				</li>
			</ul>
		</div>
		<div class="col-12 col-md-9 col-lg-10 px-3 py-4 p-lg-4 pr-lg-5">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</main>

<?php echo $this->element('footer'); ?>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script>const csrfToken = "<?php echo $this->request->params['_Token']['key']; ?>";</script>
<?php echo $this->Html->script('main'); ?>
<?php echo $this->Html->script('admin_main'); ?>
<?php echo $this->fetch('script');?>
</body>
</html>

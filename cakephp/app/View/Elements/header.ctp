<?php
/**
 * @var bool $isAuth
 * @var bool $isAdmin
 */
?>

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
					<?php if ($isAuth): ?>
						<?php if ($isAdmin): ?>
							<a href="#" class="btn btn-outline-dark mr-3">Dashboard</a>
						<?php endif; ?>

						<div class="dropdown">
							<button class="btn btn-light dropdown-toggle" data-toggle="dropdown">
								<?php echo AuthComponent::user('username'); ?>
							</button>

							<div class="dropdown-menu">
								<a href="#" class="dropdown-item">History</a>
								<a href="#" class="dropdown-item">Settings</a>
								<div class="dropdown-divider"></div>
								<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>"
								   class="dropdown-item">
									Logout
								</a>
							</div>
							<!--./dropdown-menu-->
						</div>
						<!--./dropdown-->
					<?php else: ?>
						<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>"
						   class="btn btn-outline-dark mr-3">
							Login
						</a>
						<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'registration')); ?>"
						   class="btn btn-dark">
							Register
						</a>
					<?php endif; ?>
				</div>
				<!--./navbar-left-->
			</div>
			<!--./navbar-collapse-->
		</div>
		<!--./container-->
	</nav>
</header>

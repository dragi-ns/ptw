<?php
/**
 * @var User[] $users
 * @var integer $totalNumOfUsers
 * @var integer $perPage
 */

$this->Html->script('manage_users', array('inline' => false));
?>

<div class="row no-gutters justify-content-between align-items-center mb-3">
	<h2>Users <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfUsers; ?>)</span></h2>
	<button id="add-user-btn" class="btn btn-primary">Add User</button>
</div>

<div class="table-responsive">
	<table id="users-table" class="table table-sm table-hover">
		<thead>
		<tr>
			<th class="id-col text-center">
				<?php echo $this->Paginator->sort('id', '#', array('direction' => 'desc')); ?>
			</th>
			<th class="username-col">
				<?php echo $this->Paginator->sort('username', 'Username', array('direction' => 'desc')); ?>
			</th>
			<th class="email-col">
				<?php echo $this->Paginator->sort('email', 'Email', array('direction' => 'desc')); ?>
			</th>
			<th class="role-col text-center">
				<?php echo $this->Paginator->sort('role', 'Role', array('direction' => 'desc')); ?>
			</th>
			<th class="joined-col text-center">
				<?php echo $this->Paginator->sort('created', 'Joined', array('direction' => 'desc')); ?>
			</th>
			<th class="approved-col text-center">
				<?php echo $this->Paginator->sort('approved', 'Approved', array('direction' => 'desc')); ?>
			</th>
			<th class="actions-col text-center">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php if ($totalNumOfUsers === 0): ?>
			<tr>
				<td colspan="100%" class="text-center">There are no users.</td>
			</tr>
		<?php else: ?>
			<?php foreach ($users as $user): ?>
				<tr data-id="<?php echo $user['User']['id']; ?>" data-user='<?php echo h(json_encode($user['User'])); ?>'>
					<td class="text-center"><?php echo h($user['User']['id']); ?></td>
					<td><?php echo h($user['User']['username']); ?></td>
					<td><pre class="m-0"><?php echo h($user['User']['email']); ?></pre></td>
					<td class="text-center"><?php echo h($user['User']['role']); ?></td>
					<td class="text-center"><?php echo $this->Time->format('d/m/Y H:i:s', $user['User']['created'], new DateTimeZone('Europe/Belgrade')); ?></td>
					<td class="text-center"><?php echo $user['User']['approved'] ? 'true' : 'false'; ?></td>
					<td class="text-center d-flex justify-content-center align-items-center cg-1">
						<button class="toggle-approved-btn btn btn-light btn-sm">
							<span>
								<?php echo $user['User']['approved'] ? '<i class="bi bi-check2-all"></i>' : '<i class="bi bi-x-lg"></i>'; ?>
							</span>
						</button>
						<button class="edit-user-btn btn btn-warning btn-sm">
							<span>
								<i class="bi bi-pencil-fill"></i>
							</span>
						</button>
						<button class="delete-user-btn btn btn-danger btn-sm">
							<span>
								<i class="bi bi-trash-fill"></i>
							</span>
						</button>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
</div>

<?php
echo $this->element('pagination', array(
	'totalNum' => $totalNumOfUsers,
	'perPage' => $perPage
));
?>

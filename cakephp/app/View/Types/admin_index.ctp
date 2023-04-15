<?php
/**
 * @var Type[] $types
 * @var integer $totalNumOfTypes
 * @var integer $perPage
 */

$this->Html->script('manage_types', array('inline' => false));
?>

<div class="row no-gutters justify-content-between align-items-center mb-3">
	<h2>Types <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfTypes; ?>)</span></h2>
	<button id="add-type-btn" class="btn btn-primary">Add Type</button>
</div>

<div class="table-responsive">
	<table id="types-table" class="table table-sm table-hover">
		<thead>
		<tr>
			<th class="id-col text-center">
				<?php echo $this->Paginator->sort('id', '#', array('direction' => 'desc')); ?>
			</th>
			<th class="name-col">
				<?php echo $this->Paginator->sort('name', 'Name', array('direction' => 'desc')); ?>
			</th>
			<th class="actions-col text-center">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php if ($totalNumOfTypes === 0): ?>
			<tr>
				<td colspan="100%" class="text-center">There are no types.</td>
			</tr>
		<?php else: ?>
			<?php foreach ($types as $type): ?>
				<tr data-id="<?php echo $type['Type']['id']; ?>" data-type='<?php echo json_encode($type['Type']); ?>'>
					<td class="text-center"><?php echo h($type['Type']['id']); ?></td>
					<td><?php echo h($type['Type']['name']); ?></td>
					<td class="text-center d-flex justify-content-center align-items-center cg-1">
						<button class="edit-type-btn btn btn-warning btn-sm">
							<span>
								<i class="bi bi-pencil-fill"></i>
							</span>
						</button>
						<button class="delete-type-btn btn btn-danger btn-sm">
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
	'totalNum' => $totalNumOfTypes,
	'perPage' => $perPage
));
?>

<?php
/**
 * @var Category[] $categories
 * @var integer $totalNumOfCategories
 * @var integer $perPage
 */

$this->Html->script('manage_categories', array('inline' => false));
?>

<div class="row no-gutters justify-content-between align-items-center mb-3">
	<h2>Categories <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfCategories; ?>)</span></h2>
	<button id="add-category-btn" class="btn btn-primary">Add Category</button>
</div>

<div class="table-responsive">
	<table id="categories-table" class="table table-sm table-hover">
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
		<?php if ($totalNumOfCategories === 0): ?>
			<tr>
				<td colspan="100%" class="text-center">There are no categories.</td>
			</tr>
		<?php else: ?>
			<?php foreach ($categories as $category): ?>
				<tr data-id="<?php echo $category['Category']['id']; ?>" data-category='<?php echo h(json_encode($category['Category'])); ?>'>
					<td class="text-center"><?php echo h($category['Category']['id']); ?></td>
					<td><?php echo h($category['Category']['name']); ?></td>
					<td class="text-center d-flex justify-content-center align-items-center cg-1">
						<button class="edit-category-btn btn btn-warning btn-sm">
							<span>
								<i class="bi bi-pencil-fill"></i>
							</span>
						</button>
						<button class="delete-category-btn btn btn-danger btn-sm">
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
	'totalNum' => $totalNumOfCategories,
	'perPage' => $perPage
));
?>

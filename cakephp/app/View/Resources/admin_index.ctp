<?php
/**
 * @var Resource[] $resources
 * @var Type[] $types
 * @var integer $selectedTypeId
 * @var integer $selectedStatus
 * @var Category[] $categories
 * @var integer[] $selectedCategoriesIds
 * @var integer $totalNumOfResources
 * @var integer $perPage
 */

$sortDir = $this->Paginator->sortDir();
$this->Html->script('manage_resources', array('inline' => false));
?>

<div class="d-flex flex-wrap justify-content-between align-items-center cg-3 mb-3">
	<h2>Resources <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfResources; ?>)</span></h2>
	<div class="d-flex flex-grow-1 justify-content-end cg-2">
		<button id="add-resource-btn" class="btn btn-primary">Add Resource</button>
		<button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#filters-modal">
			Filters
		</button>
		<?php
		echo $this->Paginator->sort(
			'created',
			($sortDir === 'desc' ? 'Newest' : 'Oldest') . ' First',
			array('class' => 'btn btn-outline-dark')
		);
		?>
	</div>
</div>

<div id="cards-container" class="cards d-flex flex-column rg-3 mb-3">
	<?php if ($totalNumOfResources === 0): ?>
		<p class="text-center my-2">There are no resources.</p>
	<?php else: ?>
		<?php foreach ($resources as $resource): ?>
			<?php
			$dataPayload = array_merge(
				$resource['Resource'],
				array('type' => $resource['Type']),
				array(
					'categories' => array_map(
						function($category) { return array('id' => $category['id'], 'name' => $category['name']); },
						$resource['Category']
					)
				),
			);
			?>
			<div data-id="<?php echo $resource['Resource']['id']; ?>" data-resource='<?php echo h(json_encode($dataPayload)); ?>' class="card bg-light">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between rg-2 cg-2">
					<div class="d-flex rg-2 cg-2">
						<p class="mb-0">#<?php echo $resource['Resource']['id']; ?></p>
						<p class="mb-0">
							<?php echo $this->Time->format('d/m/Y H:i:s', $resource['Resource']['created']); ?>
							<?php if ($resource['Resource']['created'] !== $resource['Resource']['modified']): ?>
								(<span class="font-italic"><?php echo $this->Time->format('d/m/Y H:i:s', $resource['Resource']['modified']); ?></span>)
							<?php endif; ?>
						</p>
					</div>
					<div class="d-flex align-items-center justify-content-end cg-1">
						<button class="toggle-approved-btn btn btn-light btn-sm">
							<span>
								<?php echo $resource['Resource']['approved'] ? '<i class="bi bi-check2-all"></i>' : '<i class="bi bi-x-lg"></i>'; ?>
							</span>
						</button>
						<button class="edit-resource-btn btn btn-warning btn-sm">
							<span>
								<i class="bi bi-pencil-fill"></i>
							</span>
						</button>
						<button class="delete-resource-btn btn btn-danger btn-sm">
							<span>
								<i class="bi bi-trash-fill"></i>
							</span>
						</button>
					</div>
				</div>
				<div class="card-body">
					<?php echo $this->element('tags', array('resource' => $resource)) ?>
					<h5 class="card-title mt-2">
						<a href="<?php echo $resource['Resource']['url']; ?>" target="_blank">
							<?php echo h($resource['Resource']['title']); ?>
						</a>
					</h5>
					<p class="card-text"><?php echo h($resource['Resource']['description']); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php
echo $this->element('pagination', array(
	'totalNum' => $totalNumOfResources,
	'perPage' => $perPage
));
?>

<?php
echo $this->element('filters-modal', array(
	'categories' => $categories,
	'selectedCategoriesId' => $selectedCategoriesIds,
	'types' => $types,
	'selectedTypeId' => $selectedTypeId,
	'selectedStatus' => $selectedStatus,
	'adminFilter' => true
));
?>
<script>
	const TYPES = JSON.parse('<?php echo json_encode(array_map(function ($typeName, $typeId) { return array('id' => $typeId, 'name' => $typeName); }, $types, array_keys($types))); ?>');
	const CATEGORIES = JSON.parse('<?php echo json_encode(array_map(function ($categoryName, $categoryId) { return array('id' => $categoryId, 'name' => $categoryName); }, $categories, array_keys($categories))); ?>');
</script>

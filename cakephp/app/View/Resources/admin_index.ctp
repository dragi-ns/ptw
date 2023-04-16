<?php
/**
 * @var Resource[] $resources
 * @var Type[] $types
 * @var Category[] $categories
 * @var integer $totalNumOfResources
 * @var integer $perPage
 */

$this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css', array('inline' => false));
$this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js', array('inline' => false));
$this->Html->script('manage_resources', array('inline' => false));
?>

<div class="row no-gutters justify-content-between align-items-center mb-3">
	<h2>Resources <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfResources; ?>)</span></h2>
	<button id="add-resource-btn" class="btn btn-primary">Add Resource</button>
</div>

<div class="cards d-flex flex-column rg-3 mb-3">
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
						<p class="mb-0"><?php echo $this->Time->format('d/m/Y H:i:s', $resource['Resource']['created'], new DateTimeZone('Europe/Belgrade')); ?></p>
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
					<div class="d-flex flex-wrap justify-content-start align-items-center rg-1 cg-1">
						<span class="badge badge-pill badge-primary"><?php echo h($resource['Type']['name']); ?></span>
						<?php foreach ($resource['Category'] as $category): ?>
							<span class="badge badge-pill badge-info"><?php echo h($category['name']); ?></span>
						<?php endforeach; ?>
					</div>
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

<script>
	const TYPES = JSON.parse('<?php echo json_encode($types); ?>');
	const CATEGORIES = JSON.parse('<?php echo json_encode($categories); ?>');
</script>

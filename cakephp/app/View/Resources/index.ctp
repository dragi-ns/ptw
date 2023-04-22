<?php
/**
 * @var Resource $resource
 * @var Category[] $categories
 * @var integer[] $selectedCategoriesIds
 * @var Type[] $types
 * @var integer $selectedTypeId
 */

$this->Html->script('random_resource', array('inline' => false));
?>

<div id="resource" class="d-flex flex-column h-md-100">
	<?php if (!empty($resource)): ?>
		<div class="resource d-flex flex-column justify-content-md-center h-md-100 pb-lg-5">
			<?php echo $this->element('tags', array('resource' => $resource)) ?>
			<h2>
				<a href="<?php echo $resource['Resource']['url']; ?>" target="_blank">
					<?php echo h($resource['Resource']['title']); ?>
				</a>
			</h2>
			<p class="mb-4"><?php echo h($resource['Resource']['description']); ?></p>
			<div class="controls d-flex justify-content-end rg-2 cg-2">
				<button class="toggle-favorite-btn btn btn-light btn-lg">
					<i class="bi bi-heart text-danger"></i>
				</button>
				<button id="next-resource-btn" class="btn btn-light btn-lg">
					<i class="bi bi-shuffle"></i>
				</button>
			</div>
		</div>
	<?php else: ?>
		<div class="d-flex justify-content-center align-items-center h-md-100">
			<p class="text-center" style="font-size: 1.5rem;">There are no more random resources.</p>
		</div>
	<?php endif; ?>
</div>

<?php
if ($this->here === '/') {
	echo $this->element('filters-modal', array(
		'categories' => $categories,
		'selectedCategoriesId' => $selectedCategoriesIds,
		'types' => $types,
		'selectedTypeId' => $selectedTypeId
	));
}
?>

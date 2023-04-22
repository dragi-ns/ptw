<?php
/**
 * @var History[] $history
 * @var integer $totalNumOfResources
 * @var integer $perPage
 * @var Category[] $categories
 * @var integer[] $selectedCategoriesIds
 * @var Type[] $types
 * @var integer $selectedTypeId
 * @var bool $showFavorite
 */
$this->Html->script('favorite_resource', array('inline' => false));
?>

<div class="d-flex justify-content-between align-items-center cg-3 mb-3">
	<h2>History <span id="total-number-of-items" class="text-muted">(<?php echo $totalNumOfResources; ?>)</span></h2>
	<button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#filters-modal">
		Filters
	</button>
</div>

<div id="cards-container" class="cards d-flex flex-column rg-3 mb-3">
	<?php if ($totalNumOfResources === 0): ?>
		<p class="text-center my-2">You haven't seen any resources.</p>
	<?php else: ?>
		<?php foreach ($history as $item): ?>
			<div data-id="<?php echo $item['Resource']['id']; ?>" class="card bg-light">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between rg-2 cg-2">
					<p class="mb-0">Seen: <?php echo $this->Time->format('d/m/Y H:i:s', $item['History']['created']); ?></p>
					<button class="toggle-favorite-btn btn btn-light btn-sm">
						<i class="bi bi-heart<?php echo $item['Resource']['Favorite'] ? '-fill' : ''; ?> text-danger"></i>
					</button>
				</div>
				<div class="card-body">
					<?php echo $this->element('tags', array('resource' => $item['Resource'])) ?>
					<h5 class="card-title mt-2">
						<a href="<?php echo $item['Resource']['url']; ?>" target="_blank">
							<?php echo h($item['Resource']['title']); ?>
						</a>
					</h5>
					<p class="card-text"><?php echo h($item['Resource']['description']); ?></p>
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
	'showFavorite' => $showFavorite
));
?>

<?php
/**
 * @var Resource $resource
 */
?>

<div class="d-flex flex-wrap justify-content-start align-items-center rg-1 cg-1 mb-2">
	<span class="badge badge-pill badge-primary"><?php echo h($resource['Type']['name']); ?></span>
	<?php foreach ($resource['Category'] as $category): ?>
		<span class="badge badge-pill badge-info"><?php echo h($category['name']); ?></span>
	<?php endforeach; ?>
</div>

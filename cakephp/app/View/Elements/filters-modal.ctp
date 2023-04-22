<?php
/**
 * @var Category[] $categories
 * @var integer[] $selectedCategoriesIds
 * @var Type[] $types
 * @var integer $selectedTypeId
 * @var integer $selectedStatus
 * @var bool $adminFilter
 * @var bool $showFavorite
 */

$this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css', array('inline' => false));
$this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js', array('inline' => false));
?>

<div class="modal fade" id="filters-modal" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Filters</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->create(null, array(
					'type' => 'get',
					'id' => 'filters-form',
					'class' => 'd-flex flex-column'
				));
				echo $this->Form->input('category_id', array(
					'hiddenField' => false,
					'type' => 'select',
					'label' => 'Categories',
					'class' => 'selectpicker',
					'title' => 'Filter by category',
					'multiple' => true,
					'options' => $categories,
					'selected' => $selectedCategoriesIds,
					'data-width' => '100%',
					'data-live-search' => true,
					'data-actions-box' => true
				));
				echo $this->Form->input('type_id', array(
					'hiddenField' => false,
					'type' => 'select',
					'label' => 'Types',
					'class' => 'selectpicker',
					'title' => 'Filter by type',
					'empty' => 'All',
					'options' => $types,
					'selected' => $selectedTypeId,
					'required' => false,
					'data-width' => '100%'
				));
				?>

				<?php if (isset($adminFilter) && $adminFilter): ?>
					<?php
					echo $this->Form->input('status', array(
						'hiddenField' => false,
						'type' => 'select',
						'label' => 'Status',
						'class' => 'selectpicker',
						'title' => 'Filter by type',
						'empty' => 'All',
						'options' => array(1 => 'Approved', 0 => 'Not Approved'),
						'selected' => $selectedStatus,
						'data-width' => '100%'
					));
					?>
				<?php else: ?>
					<div class="form-check">
						<input class="form-check-input"
							   type="checkbox"
							   value="1"
							   id="show_favorite"
							   name="show_favorite"<?php echo $showFavorite ? ' checked' : ''; ?>>
						<label class="form-check-label" for="show_favorite">
							Only show favorite
						</label>
					</div>
				<?php endif; ?>

				<?php echo $this->Form->end(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="filters-form">Apply</button>
			</div>
		</div>
	</div>
</div>

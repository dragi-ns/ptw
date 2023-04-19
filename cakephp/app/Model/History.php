<?php

App::uses('AppModel', 'Model');

class History extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Resource' => array(
			'className' => 'Resource',
			'foreignKey' => 'resource_id'
		)
	);

	public function getCount($conditions = array()) {
		$settings = array(
			'conditions' => $conditions,
			'recursive' => 0
		);

		if (array_key_exists('Category.id', $conditions)) {
			$settings['joins'][] = array(
				'table' => 'categories_resources',
				'alias' => 'CategoriesResources',
				'type' => 'inner',
				'conditions' => array(
					'CategoriesResources.resource_id = Resource.id',
					'CategoriesResources.category_id' => $conditions['Category.id']
				)
			);

			$settings['joins'][] = array(
				'table' => 'categories',
				'alias' => 'Category',
				'type' => 'inner',
				'conditions' => array(
					'CategoriesResources.category_id = Category.id'
				)
			);
		}

		return $this->find('count', $settings);
	}
}

<?php

App::uses('AppModel', 'Model');

class Resource extends AppModel {
	public $displayField = 'title';

	public $belongsTo = array('Type');

	public $hasMany = array(
		'History' => array(
			'className' => 'History',
			'foreignKey' => 'resource_id',
			'dependent' => true
		),
		'Favorite' => array(
			'className' => 'Favorite',
			'foreignKey' => 'resource_id',
			'dependent' => true
		)
	);

	public $hasAndBelongsToMany = array('Category');

	public $validate = array(
		'title' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a title.'
			),
			'between' => array(
				'rule' => array('lengthBetween', 2, 64),
				'message' => 'Title must be between 2 and 64 characters long.'
			)
		),
		'description' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a description.'
			),
			'between' => array(
				'rule' => array('lengthBetween', 16, 512),
				'message' => 'Description must be between 16 and 512 characters long.'
			)
		),
		'url' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a url.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 1024),
				'message' => 'URL must be at most 1024 characters long.'
			),
			'url' => array(
				'rule' => array('url', true),
				'message' => 'URL must be a valid url.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That url is already added.'
			)
		),
		'type_id' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please select a type.'
			),
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Type must be a number.'
			)
		),
		'Category' => array(
			'rule' => array('multiple', array('min' => 1)),
			'message' => 'Please select at least one category.'
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

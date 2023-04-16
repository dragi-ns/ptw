<?php

App::uses('AppModel', 'Model');

class Resource extends AppModel {
	public $displayField = 'title';

	public $belongsTo = array('Type');

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
}

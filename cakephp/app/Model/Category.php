<?php

App::uses('AppModel', 'Model');

class Category extends AppModel {
	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a name.'
			),
			'between' => array(
				'rule' => array('lengthBetween', 2, 32),
				'message' => 'Name must be between 2 and 32 characters long.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That name is already taken.'
			)
		),
	);
}

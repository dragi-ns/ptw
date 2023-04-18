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
}

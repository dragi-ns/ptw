<?php

App::uses('AppModel', 'Model');

class Favorite extends AppModel {
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

	public function isUsersFavorite($id, $userId) {
		return $this->find('first', array(
			'conditions' => array(
				'resource_id' => $id,
				'user_id' => $userId
			),
			'recursive' => -1
		));
	}
}

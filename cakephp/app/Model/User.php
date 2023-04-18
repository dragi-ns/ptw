<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $displayField = 'username';

	public $hasMany = array(
		'History' => array(
			'className' => 'History',
			'foreignKey' => 'user_id',
			'dependent' => true
		)
	);

	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a username.'
			),
			'alphaNumericDashUnderscore' => array(
				'rule' => 'alphaNumericDashUnderscore',
				'message' => 'Username should only contain letters, numbers, underscores and dashes.'
			),
			'between' => array(
				'rule' => array('lengthBetween', 4, 32),
				'message' => 'Username must be between 4 and 32 characters long.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That username is already taken.'
			)
		),
		'email' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter an email address.'
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid email address.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'Email address must be at most 255 characters long.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'That email address is already taken.'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a password.'
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'message' => 'Password must at least 4 characters long.'
			)
		),
		'password_confirm' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please confirm the password.'
			),
			'matchPasswords' => array(
				'rule' => array('matchPasswords', 'password'),
				'message' => 'Your passwords do not match.'
			)
		),
		'new_password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'allowEmpty' => true,
				'message' => 'Please enter a new password.',
				'on' => 'update'
			),
			'minLength' => array(
				'rule' => array('minLength', 4),
				'message' => 'Password must at least 4 characters long.',
				'on' => 'update'
			),
		),
		'new_password_confirm' => array(
			'matchPasswords' => array(
				'rule' => array('matchPasswords', 'new_password'),
				'message' => 'Your passwords do not match.',
				'on' => 'update'
			)
		),
		'role' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please select a role.'
			),
			'valid' => array(
				'rule' => array('inList', array('admin', 'user')),
				'message' => 'Invalid role.'
			)
		)
	);

	public function alphaNumericDashUnderscore($check) {
		$value = array_values($check)[0];
		return preg_match('/^[0-9a-zA-Z_-]*$/', $value) === 1;
	}

	public function matchPasswords($check, $fieldName) {
		$passwordConfirm = array_values($check)[0];
		$password = $this->data[$this->alias][$fieldName];
		return $passwordConfirm === $password;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		} else if (isset($this->data[$this->alias]['new_password']) && $this->data[$this->alias]['new_password']) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['new_password']
			);
		}
	}
}

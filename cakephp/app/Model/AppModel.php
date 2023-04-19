<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	public $actsAs = array('Containable');

	public function getCount($conditions = array()) {
		return $this->find('count', array(
			'conditions' => $conditions,
			'recursive' => 0
		));
	}

	public function save($data = null, $validate = true, $fieldList = array(), $fields = array()) {
		if (!$this->_save($data, $validate, $fieldList)) {
			return false;
		}

		return $this->read($fields);
	}

	public function saveAll($data = array(), $options = array(), $fields = array()) {
		if (!$this->_saveAll($data, $options)) {
			return false;
		}

		return $this->read($fields);
	}

	public function beforeSave($options = array()) {
		foreach ($this->data[$this->alias] as $key => $value) {
			$this->data[$this->alias][$key] = strip_tags($value);
		}
		return parent::beforeSave($options);
	}

	protected function _save($data = null, $validate = true, $fieldList = array()) {
		return parent::save($data, $validate, $fieldList);
	}

	protected function _saveAll($data = array(), $options = array()) {
		return parent::saveAll($data, $options);
	}
}

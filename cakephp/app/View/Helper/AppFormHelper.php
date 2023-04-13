<?php

App::uses('FormHelper', 'View/Helper');

class AppFormHelper extends FormHelper {
	public function create($model = null, $options = array()) {
		$default = array(
			'inputDefaults' => array(
				'class' => 'form-control',
				'div' => array('class' => 'form-group'),
				'error' => array('attributes' => array('wrap' => 'div', 'class' => 'invalid-feedback')),
			)
		);
		$options = Hash::merge($default, $options);
		return parent::create($model, $options);
	}
}

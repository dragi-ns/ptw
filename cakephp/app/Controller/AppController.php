<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array(
		'Auth' => array(
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login',
				'admin' => false
			),
			'loginRedirect' => array(
				'controller' => 'pages',
				'action' => 'display',
				'home'
			),
			'logoutRedirect' => array(
				'controller' => 'pages',
				'action' => 'display',
				'home'
			),
			'authenticate' => array(
				'AppForm' => array(
					'userModel' => 'User',
					'fields' => array('username' => 'email'),
					'passwordHasher' => 'Blowfish'
				)
			),
			'authorize' => array('Controller')
		),
		'Flash',
		'Security',
		'Session',
		'Paginator'
	);

	public $helpers = array(
		'Form' => array('className' => 'AppForm')
	);

	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		}
		return false;
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->set(array(
			'isAuth' => $this->isAuth(),
			'isAdmin' => $this->isAdmin()
		));
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('index', 'view'));
	}

	protected function isAuth() {
		return $this->Auth->user('id') !== NULL;
	}

	protected function isAdmin() {
		return $this->Auth->user('role') === 'admin';
	}
}

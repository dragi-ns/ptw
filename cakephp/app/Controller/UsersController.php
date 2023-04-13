<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array(
			'login',
			'registration',
		));
	}
	public function registration() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('You can login now.');
				return $this->redirect(array('action' => 'login'));
			}
		}
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->User->recursive = -1;
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if (!empty($user) && !$user['User']['approved']) {
				$this->Flash->error('Your account is not approved yet. Please try again later.');
			} else {
				$this->Flash->error('Invalid username or password. Please try again.');
			}
		}
	}

	public function logout() {
		$this->Flash->success('You have been logged out.');
		return $this->redirect($this->Auth->logout());
	}
}

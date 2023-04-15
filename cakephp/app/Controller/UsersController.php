<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function isAuthorized($user) {
		if ($this->action === 'logout') {
			return true;
		}

		return parent::isAuthorized($user);
	}

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->response->type('json');
			$this->Security->validatePost = false;
		}

		$this->Auth->allow(array(
			'login',
			'registration',
		));
	}

	public function admin_index() {
		$this->layout = 'admin';
		$this->set(array(
			'users' => $this->getPaginatedUsers(15),
			'totalNumOfUsers' => $this->User->getCount(),
			'perPage' => 15
		));
	}

	public function admin_add() {
		$this->request->allowMethod(array('ajax'));

		$this->User->clear();
		$success = $this->User->save(
			$this->request->data,
			true,
			array(),
			array('id', 'username', 'email', 'approved', 'role', 'created', 'modified')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$success['User']['created'] = date('d/m/Y H:i:s', strtotime($success['User']['created']));
			$success['User']['modified'] = date('d/m/Y H:i:s', strtotime($success['User']['modified']));
			$result['user'] = $success['User'];
		} else {
			$result['errors'] = $this->User->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->User->id = $id;
		$success = $this->User->save(
			$this->request->data,
			true,
			array(),
			array('id', 'username', 'email', 'approved', 'role', 'created', 'modified')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$success['User']['created'] = date('d/m/Y H:i:s', strtotime($success['User']['created']));
			$success['User']['modified'] = date('d/m/Y H:i:s', strtotime($success['User']['modified']));
			$result['user'] = $success['User'];
		} else {
			$result['errors'] = $this->User->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$success = $this->User->delete($id);
		$this->response->body(json_encode(array('success' => (bool) $success, 'user_id' => $id)));
		return $this->response;
	}

	public function admin_approve($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->User->id = $id;
		$approved = $this->User->field('approved', array('id' => $id));
		$success = $this->User->save(array('approved' => !$approved));

		$this->response->body(json_encode(array(
			'success' => (bool) $success,
			'user_id' => $id,
			'approved' => $success ? !$approved : $approved
		)));
		return $this->response;
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

	protected function getPaginatedUsers($limit = 15) {
		$this->Paginator->settings = array(
			'recursive' => -1,
			'fields' => array('id', 'username', 'email', 'role', 'created', 'approved'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('User.created' => 'desc')
		);
		return $this->Paginator->paginate('User');
	}
}

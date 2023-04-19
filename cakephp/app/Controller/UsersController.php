<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

class UsersController extends AppController {
	public function isAuthorized($user) {
		if (in_array($this->action, array('history', 'logout'))) {
			return true;
		}
		return parent::isAuthorized($user);
	}

	public function beforeFilter() {
		parent::beforeFilter();
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
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->generateNotFoundResponse('User');
			return $this->response;
		}

		$this->User->id = $id;
		$success = $this->User->save(
			$this->request->data,
			true,
			array(),
			array('id', 'username', 'email', 'approved', 'role', 'created', 'modified')
		);
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->generateNotFoundResponse('User');
			return $this->response;
		}

		$success = $this->User->delete($id);
		$this->response->body(json_encode(array(
			'success' => $success,
			'data' => array('id' => $id)
		)));
		return $this->response;
	}

	public function admin_approve($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->User->exists($id)) {
			$this->generateNotFoundResponse('User');
			return $this->response;
		}

		$this->User->id = $id;
		$approved = $this->User->field('approved', array('id' => $id));
		$success = $this->User->save(array('approved' => !$approved));

		$this->response->body(json_encode(array(
			'success' => (bool) $success,
			'data' => array(
				'id' => $id,
				'approved' => $success ? !$approved : $approved
			)
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

	public function history() {
		$this->loadModel('Category');
		$this->loadModel('Type');
		$this->loadModel('History');

		$conditions = array(
			'user_id' => $this->Auth->user('id')
		);

		$selectedCategoriesIds = array();
		if (isset($this->request->query['category_id'])) {
			$selectedCategoriesIds = $this->request->query['category_id'];
			if (!empty($selectedCategoriesIds)) {
				$conditions['Category.id'] = $selectedCategoriesIds;
			}
		}

		$selectedTypeId = null;
		if (isset($this->request->query['type_id'])) {
			$selectedTypeId = $this->request->query['type_id'];
			if ($selectedTypeId) {
				$conditions['Resource.type_id'] = $selectedTypeId;
			}
		}

		$this->set(array(
			'history' => $this->getPaginatedHistory($conditions, 10),
			'categories' => $this->Category->find('list', array('recursive' => -1)),
			'selectedCategoriesIds' => $selectedCategoriesIds,
			'types' => $this->Type->find('list', array('recursive' => -1)),
			'selectedTypeId' => $selectedTypeId,
			'totalNumOfResources' => $this->History->getCount($conditions),
			'perPage' => 10
		));
	}

	private function getPaginatedUsers($limit = 15) {
		$this->Paginator->settings = array(
			'recursive' => -1,
			'fields' => array('id', 'username', 'email', 'role', 'created', 'modified', 'approved'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('User.created' => 'desc')
		);
		return $this->Paginator->paginate('User');
	}

	private function getPaginatedHistory($conditions = array(), $limit = 15) {
		$settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'Resource',
				'Resource.Type',
				'Resource.Category'
			),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('History.created' => 'desc')
		);

		if (array_key_exists('Category.id', $conditions)) {
			$settings['joins'][] = array(
				'table' => 'categories_resources',
				'alias' => 'CategoriesResources',
				'type' => 'inner',
				'conditions' => array(
					'CategoriesResources.resource_id = Resource.id',
					'CategoriesResources.category_id' => $conditions['Category.id']
				)
			);

			$settings['joins'][] = array(
				'table' => 'categories',
				'alias' => 'Category',
				'type' => 'inner',
				'conditions' => array(
					'CategoriesResources.category_id = Category.id'
				)
			);
		}

		$this->Paginator->settings = $settings;
		return $this->Paginator->paginate('History');
	}

	private function generateResponse($resource) {
		$result = array('success' => (bool) $resource);
		if ($resource) {
			$resource['User']['created'] = CakeTime::format('d/m/Y H:i:s', $resource['User']['created']);
			$resource['User']['modified'] = CakeTime::format('d/m/Y H:i:s', $resource['User']['modified']);
			$result['data'] = $resource['User'];
		} else {
			$result['errors'] = $this->User->validationErrors;
		}
		$this->response->body(json_encode($result));
	}
}

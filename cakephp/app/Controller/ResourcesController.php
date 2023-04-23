<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

class ResourcesController extends AppController {
	public $uses = array('Resource', 'Type', 'Category');

	public function isAuthorized($user) {
		if (in_array($this->action, array('favorite'))) {
			return true;
		}
		return parent::isAuthorized($user);
	}

	public function index() {
		$this->loadModel('History');

		$conditions = array('Resource.approved' => true);
		$userId = $this->Auth->user('id');
		if ($userId) {
			$conditions['NOT'] = array(
				'Resource.id' => $this->History->find('list', array(
					'fields' => array('History.resource_id'),
					'conditions' => array('History.user_id' => $userId)
				))
			);
		}

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
				$conditions['type_id'] = $selectedTypeId;
			}
		}

		$randomResource = $this->getRandomResource($conditions, $userId);
		if ($userId && !empty($randomResource)) {
			$this->History->save(array(
				'user_id' => $this->Auth->user('id'),
				'resource_id' => $randomResource['Resource']['id']
			));
		}

		if ($this->request->is('ajax')) {
			$this->response->body(json_encode(array(
				'success' => true,
				'data' => $randomResource
			)));
			return $this->response;
		}

		$this->set(array(
			'resource' => $randomResource,
			'categories' => $this->Category->find('list', array('recursive' => -1)),
			'selectedCategoriesIds' => $selectedCategoriesIds,
			'types' => $this->Type->find('list', array('recursive' => -1)),
			'selectedTypeId' => $selectedTypeId
		));
	}

	public function favorite($id = null) {
		$this->request->allowMethod('ajax');
		$this->loadModel('Favorite');

		if (!$id || !$this->Resource->exists($id)) {
			$this->generateNotFoundResponse('Resource');
			return $this->response;
		}

		$userId = $this->Auth->user('id');
		$isUsersFavorite = $this->Favorite->isUsersFavorite($id, $userId);

		if ($isUsersFavorite) {
			$success = $this->Favorite->delete($isUsersFavorite['Favorite']['id']);
		} else {
			$success = $this->Favorite->save(array(
				'resource_id' => $id,
				'user_id' => $userId
			));
		}

		$this->response->body(json_encode(array(
			'success' => (bool) $success,
			'data' => array(
				'id' => $id,
				'isFavorite' => (bool) ($success ? !$isUsersFavorite : $isUsersFavorite)
			)
		)));
		return $this->response;
	}

	public function admin_index() {
		$this->layout = 'admin';


		$conditions = array('Resource.approved' => true);
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
				$conditions['type_id'] = $selectedTypeId;
			}
		}

		$selectedStatus = null;
		if (isset($this->request->query['status'])) {
			$selectedStatus = $this->request->query['status'];
			if ($selectedStatus !== '') {
				$conditions['approved'] = $selectedStatus;
			}
		}

		$this->set(array(
			'resources' => $this->getPaginatedResources($conditions, 10),
			'types' => $this->Type->find('list', array('recursive' => -1)),
			'selectedTypeId' => $selectedTypeId,
			'categories' => $this->Category->find('list', array('recursive' => -1)),
			'selectedCategoriesIds' => $selectedCategoriesIds,
			'totalNumOfResources' => $this->Resource->getCount($conditions),
			'selectedStatus' => $selectedStatus,
			'perPage' => 10
		));
	}

	public function admin_add() {
		$this->request->allowMethod(array('ajax'));

		$this->Resource->clear();
		$success = $this->Resource->saveAll($this->request->data, array('deep' => true));
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->generateNotFoundResponse('Resource');
			return $this->response;
		}

		$this->Resource->id = $id;
		$success = $this->Resource->saveAll($this->request->data, array('deep' => true));
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->generateNotFoundResponse('Resource');
			return $this->response;
		}

		$success = $this->Resource->delete($id);
		$this->response->body(json_encode(array(
			'success' => $success,
			'data' => array(
				'id' => $id
			)
		)));
		return $this->response;
	}

	public function admin_approve($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->generateNotFoundResponse('Resource');
			return $this->response;
		}

		$this->Resource->id = $id;
		$approved = $this->Resource->field('approved', array('id' => $id));
		$success = $this->Resource->save(array('approved' => !$approved));

		$this->response->body(json_encode(array(
			'success' => (bool) $success,
			'data' => array(
				'id' => $id,
				'approved' => $success ? !$approved : $approved
			)
		)));
		return $this->response;
	}

	private function getPaginatedResources($conditions = array(), $limit = 15) {
		$settings = array(
			'conditions' => $conditions,
			'contain' => array('Type', 'Category'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('Resource.created' => 'desc')
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
		return $this->Paginator->paginate('Resource');
	}

	private function generateResponse($resource) {
		$result = array('success' => (bool) $resource);
		if ($resource) {
			$result['data'] = $resource['Resource'];
			$result['data']['created'] = CakeTime::format('d/m/Y H:i:s', $resource['Resource']['created']);
			$result['data']['modified'] = CakeTime::format('d/m/Y H:i:s', $resource['Resource']['modified']);
			$result['data']['type'] = $resource['Type'];
			$result['data']['categories'] = array_map(
				function ($category) { return array('id' => $category['id'], 'name' => $category['name']); },
				$resource['Category']
			);
		} else {
			$result['errors'] = $this->Resource->validationErrors;
		}
		$this->response->body(json_encode($result));
	}

	private function getRandomResource($conditions, $userId = null) {
		$settings = array(
			'conditions' => $conditions,
			'contain' => array(
				'Category',
				'Type',
				'Favorite' => array(
					'conditions' => array(
						'Favorite.user_id' => $userId
					)
				)
			),
			'joins' => array(),
			'order' => 'RAND(NOW())',
			'limit' => 1
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

		return $this->Resource->find('first', $settings);
	}
}

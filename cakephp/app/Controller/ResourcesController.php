<?php

App::uses('AppController', 'Controller');

class ResourcesController extends AppController {
	public $uses = array('Resource', 'Type', 'Category');

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->response->type('json');
			$this->Security->validatePost = false;
		}
	}

	public function admin_index() {
		$this->layout = 'admin';
		$this->set([
			'resources' => $this->getPaginatedResources(10),
			'types' => array_map(
				function($type) { return $type['Type']; },
				$this->Type->find('all', ['recursive' => -1])
			),
			'categories' => array_map(
				function($category) { return $category['Category']; },
				$this->Category->find('all', ['recursive' => -1])
			),
			'totalNumOfResources' => $this->Resource->getCount(),
			'perPage' => 10
		]);
	}

	public function admin_add() {
		$this->request->allowMethod(array('ajax'));

		$this->Resource->clear();
		$success = $this->Resource->saveAll($this->request->data, array('deep' => true));
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['resource'] = $success['Resource'];
			$result['resource']['created'] = date('d/m/Y H:i:s', strtotime($success['Resource']['created']));
			$result['resource']['modified'] = date('d/m/Y H:i:s', strtotime($success['Resource']['modified']));
			$result['resource']['type'] = $success['Type'];
			$result['resource']['categories'] = array_map(
				function ($category) { return array('id' => $category['id'], 'name' => $category['name']); },
				$success['Category']
			);
		} else {
			$result['errors'] = $this->Resource->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->Resource->id = $id;
		$success = $this->Resource->saveAll($this->request->data, array('deep' => true));
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['resource'] = $success['Resource'];
			$result['resource']['created'] = date('d/m/Y H:i:s', strtotime($success['Resource']['created']));
			$result['resource']['modified'] = date('d/m/Y H:i:s', strtotime($success['Resource']['modified']));
			$result['resource']['type'] = $success['Type'];
			$result['resource']['categories'] = array_map(
				function ($category) { return array('id' => $category['id'], 'name' => $category['name']); },
				$success['Category']
			);
		} else {
			$result['errors'] = $this->Resource->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$success = $this->Resource->delete($id);
		$this->response->body(json_encode(array('success' => (bool) $success, 'resource_id' => $id)));
		return $this->response;
	}

	public function admin_approve($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Resource->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->Resource->id = $id;
		$approved = $this->Resource->field('approved', array('id' => $id));
		$success = $this->Resource->save(array('approved' => !$approved));

		$this->response->body(json_encode(array(
			'success' => (bool) $success,
			'resource_id' => $id,
			'approved' => $success ? !$approved : $approved
		)));
		return $this->response;
	}

	protected function getPaginatedResources($limit = 15) {
		$this->Paginator->settings = array(
			'contain' => array('Type', 'Category'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('Resource.created' => 'desc')
		);
		return $this->Paginator->paginate('Resource');
	}
}

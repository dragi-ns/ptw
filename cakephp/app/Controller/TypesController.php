<?php

App::uses('AppController', 'Controller');

class TypesController extends AppController {
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
		$this->set(array(
			'types' => $this->getPaginatedTypes(15),
			'totalNumOfTypes' => $this->Type->getCount(),
			'perPage' => 15
		));
	}

	public function admin_add() {
		$this->request->allowMethod(array('ajax'));

		$this->Type->clear();
		$success = $this->Type->save(
			$this->request->data,
			true,
			array(),
			array('id', 'name')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['type'] = $success['Type'];
		} else {
			$result['errors'] = $this->Type->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Type->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->Type->id = $id;
		$success = $this->Type->save(
			$this->request->data,
			true,
			array(),
			array('id', 'name')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['type'] = $success['Type'];
		} else {
			$result['errors'] = $this->Type->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Type->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$success = $this->Type->delete($id);
		$this->response->body(json_encode(array('success' => (bool) $success, 'type_id' => $id)));
		return $this->response;
	}

	protected function getPaginatedTypes($limit = 15) {
		$this->Paginator->settings = array(
			'recursive' => -1,
			'fields' => array('id', 'name'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('Type.created' => 'desc')
		);
		return $this->Paginator->paginate('Type');
	}
}

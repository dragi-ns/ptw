<?php

App::uses('AppController', 'Controller');

class TypesController extends AppController {
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
		$success = $this->Type->save($this->request->data);
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Type->exists($id)) {
			$this->generateNotFoundResponse('Type');
			return $this->response;
		}

		$this->Type->id = $id;
		$success = $this->Type->save($this->request->data);
		$this->generateResponse($success);
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Type->exists($id)) {
			$this->generateNotFoundResponse('Type');
			return $this->response;
		}

		$success = $this->Type->delete($id);
		$this->response->body(json_encode(array(
			'success' => $success,
			'data' => array(
				'id' => $id
			)
		)));
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

	protected function generateResponse($resource) {
		$result = array('success' => (bool) $resource);
		if ($resource) {
			$result['data'] = $resource['Type'];
		} else {
			$result['errors'] = $this->Type->validationErrors;
		}
		$this->response->body(json_encode($result));
	}
}

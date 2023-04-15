<?php

App::uses('AppController', 'Controller');

class CategoriesController extends AppController {
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
			'categories' => $this->getPaginatedCategories(15),
			'totalNumOfCategories' => $this->Category->getCount(),
			'perPage' => 15
		));
	}

	public function admin_add() {
		$this->request->allowMethod(array('ajax'));

		$this->Category->clear();
		$success = $this->Category->save(
			$this->request->data,
			true,
			array(),
			array('id', 'name')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['category'] = $success['Category'];
		} else {
			$result['errors'] = $this->Category->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_edit($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Category->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$this->Category->id = $id;
		$success = $this->Category->save(
			$this->request->data,
			true,
			array(),
			array('id', 'name')
		);
		$result = array('success' => (bool) $success);
		if ($success) {
			$result['category'] = $success['Category'];
		} else {
			$result['errors'] = $this->Category->validationErrors;
		}

		$this->response->body(json_encode($result));
		return $this->response;
	}

	public function admin_delete($id = null) {
		$this->request->allowMethod(array('ajax'));

		if (!$id || !$this->Category->exists($id)) {
			$this->response->statusCode(404);
			$this->response->body(json_encode(array('success' => false)));
			return $this->response;
		}

		$success = $this->Category->delete($id);
		$this->response->body(json_encode(array('success' => (bool) $success, 'category_id' => $id)));
		return $this->response;
	}

	protected function getPaginatedCategories($limit = 15) {
		$this->Paginator->settings = array(
			'recursive' => -1,
			'fields' => array('id', 'name'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('Category.created' => 'desc')
		);
		return $this->Paginator->paginate('Category');
	}
}

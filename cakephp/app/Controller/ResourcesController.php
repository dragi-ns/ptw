<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

class ResourcesController extends AppController {
	public $uses = array('Resource', 'Type', 'Category');

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

	protected function getPaginatedResources($limit = 15) {
		$this->Paginator->settings = array(
			'contain' => array('Type', 'Category'),
			'limit' => $limit,
			'maxLimit' => $limit,
			'order' => array('Resource.created' => 'desc')
		);
		return $this->Paginator->paginate('Resource');
	}

	protected function generateResponse($resource) {
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
}

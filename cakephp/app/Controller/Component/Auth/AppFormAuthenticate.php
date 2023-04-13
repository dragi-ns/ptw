<?php

App::uses('FormAuthenticate', 'Controller/Component/Auth');

class AppFormAuthenticate extends FormAuthenticate {
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$result = parent::authenticate($request, $response);

		if ($result && isset($result['approved']) && $result['approved']) {
			return $result;
		}

		return false;
	}
}

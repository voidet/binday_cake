<?php

App::uses('AppController', 'Controller');
class PagesController extends AppController {

	public function info() {
		$info = $this->Page->find('all');
		$info = Set::extract('{n}.Page', $info);
		$this->set(compact('info'));
	}

	public function home() {

	}

}

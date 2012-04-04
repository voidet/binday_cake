<?php

class SchedulesController extends AppController {

	public function view() {
		$schedule = $this->Schedule->findAddress($this->request->params);
		$this->set(compact('schedule'));
	}

}
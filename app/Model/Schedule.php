<?php

class Schedule extends AppModel {
	
	public function findAddress($address = array()) {

		$currentTime = time();

		$street = explode(' ', $address["street"]);
		foreach ($street as &$v) {
			$v = '"%'.$v.'%"';
		}

		$street = implode(' OR ', $street);
		$conditions = array('(street LIKE '.$street.')', 'suburb' => $address['suburb']);

		$schedule = $this->find('first', array('conditions' => $conditions, 'order' => 'ABS(`house_number` - '.$address['street_number'].')'));

		if (empty($schedule)) {
			return false;
		}

		$day = explode(' ', $schedule['Schedule']['collection_day']);
		$targetDate = strtotime(reset($day));

		if ($currentTime > $targetDate) {
			$targetDate = strtotime(' +1 week', $targetDate);
		}

		$general = ceil(($targetDate - $currentTime) / (60*60*24));

		if (((date('W') % 2) && $day[1] == 'ODD') || (!(date('W') % 2) && $day[1] == 'EVEN')) {
			$targetDate = strtotime('+1 week', $targetDate);
		}

		$nextFortnight = ceil(($targetDate - $currentTime) / (60*60*24));
		
		$schedule = array(
			"general" => $general,
			"nextFortnight" => $nextFortnight,
			"day" => date('l', $targetDate)
		);
		return $schedule;
	}

}
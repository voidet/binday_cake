<?php

class Schedule extends AppModel {
	
	public function findAddress($address = array()) {
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

		if (time() > $targetDate) {
			$targetDate = strtotime(' +1 week', $targetDate);
		}

		$general = ceil(($targetDate - time()) / (60*60*24));

		if ($day[1] == 'ODD' && !(date('W', $targetDate) % 2)) {
			$targetDate = strtotime('+1 week', $targetDate);
		}

		$nextFortnight = ceil(($targetDate - time()) / (60*60*24));
		
		$schedule = array(
			"general" => $general,
			"nextFortnight" => $nextFortnight,
			"day" => date('l', $targetDate)
		);
		return $schedule;
	}

}
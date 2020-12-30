<?php

namespace VendingMachine;

class NoChangeAvailable extends \Exception {

	public function __construct() {
		parent::__construct('There is no change available for this transaction. Please come back later!');
	}
}

<?php

namespace VendingMachine;

class NotEnoughMoneyIntoTheMachine extends \Exception {

	public function __construct() {
		parent::__construct('There is not enough money to buy it');
	}
}

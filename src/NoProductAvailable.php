<?php

namespace VendingMachine;

class NoProductAvailable extends \Exception {

	public function __construct(string $productCode) {
		parent::__construct("There is no {$productCode} available for purchase. Please come back later!");
	}
}

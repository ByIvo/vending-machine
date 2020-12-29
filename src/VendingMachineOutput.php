<?php

namespace VendingMachine;

class VendingMachineOutput {

	private $product;
	private $changes;

	public function __construct($product, array $changes) {
		$this->product = $product;
		$this->changes = $changes;
	}

	public function product() {
		return $this->product;
	}

	public function changes() {
		return $this->changes;
	}
}

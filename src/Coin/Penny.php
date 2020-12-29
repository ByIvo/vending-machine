<?php

namespace VendingMachine\Coin;

class Penny implements Coin {

	public function valueOf(): float {
		return 0.01;
	}

	public function inCents(): int {
		return $this->valueOf() * 100;
	}
}

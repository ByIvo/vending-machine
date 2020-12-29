<?php

namespace VendingMachine\Coin;

class Quarter implements Coin {

	public function valueOf(): float {
		return 0.25;
	}

	public function inCents(): int {
		return $this->valueOf() * 100;
	}
}

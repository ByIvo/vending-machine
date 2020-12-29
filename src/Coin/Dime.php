<?php

namespace VendingMachine\Coin;

class Dime implements Coin {

	public function inCents(): int {
		return 10;
	}
}

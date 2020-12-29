<?php

namespace VendingMachine\Coin;

class Penny implements Coin {

	public function inCents(): int {
		return 1;
	}
}

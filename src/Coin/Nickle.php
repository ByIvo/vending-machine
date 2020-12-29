<?php

namespace VendingMachine\Coin;

class Nickle implements Coin {

	public function inCents(): int {
		return 5;
	}
}

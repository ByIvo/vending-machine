<?php

namespace VendingMachine\Coin;

class Quarter implements Coin {

	public function inCents(): int {
		return 25;
	}
}

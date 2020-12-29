<?php

namespace VendingMachine\Coin;

class Dime implements Coin {

	public function valueOf(): float {
		return 0.10;
	}
}

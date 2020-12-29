<?php

namespace VendingMachine\Coin;

class Nickle implements Coin {

	public function valueOf(): float {
		return 0.05;
	}
}

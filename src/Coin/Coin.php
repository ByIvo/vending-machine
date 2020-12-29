<?php

namespace VendingMachine\Coin;

interface Coin {

	/** @deprecated  */
	public function valueOf(): float;
	public function inCents(): int;
}

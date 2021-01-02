<?php

namespace VendingMachine\Stash;

use VendingMachine\Coin\Coin;

interface CoinStash {

	public function supplyCoinForChange(Coin $stash): void;
	public function pickCoinsForAmount(int $amount): array;
}

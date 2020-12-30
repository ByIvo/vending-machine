<?php

namespace Test\VendingMachine;

use VendingMachine\Coin\Coin;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\Stash\Stash;

class InfiniteStash implements Stash {

	public function pickCoinsForAmount(int $amount): array {
		$changes = [];
		$availableCoins = [
			new Quarter(),
			new Dime(),
			new Nickle(),
			new Penny(),
		];

		while ($amount > 0) {
			/** @var Coin $coin */
			foreach ($availableCoins as $coin) {
				if ($coin->inCents() <= $amount) {
					$changes[] = clone $coin;
					$amount -= $coin->inCents();
				}
			}
		}

		return $changes;
	}
}

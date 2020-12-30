<?php

namespace VendingMachine\Stash;

use VendingMachine\Coin\Coin;
use VendingMachine\NoChangeAvailable;

class SupplierStash implements Stash {

	private $availableCoinsForChange;

	public function __construct() {
		$this->availableCoinsForChange = [];
	}

	public function pickCoinsForAmount(int $amount): array {
		$changes = [];
		$remainingAmount = $amount;

		while ($remainingAmount > 0) {
			$coin = $this->pickGreaterAvailableCoinForRemainingAmount($remainingAmount);

			if (!isset($coin)) {
				throw new NoChangeAvailable();
			}

			$remainingAmount -= $coin->inCents();
			$changes[] = $coin;
		}

		return $changes;
	}

	private function pickGreaterAvailableCoinForRemainingAmount(int $remainingAmount): ?Coin {
		foreach ($this->availableCoinsForChange as $index => $coin) {
			if ($coin->inCents() <= $remainingAmount) {
				unset($this->availableCoinsForChange[$index]);
				return $coin;
			}
		}

		return null;
	}

	public function supplyCoinForChange(Coin $coin) {
		$this->availableCoinsForChange[] = $coin;
	}
}

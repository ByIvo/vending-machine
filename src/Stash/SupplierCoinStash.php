<?php

namespace VendingMachine\Stash;

use VendingMachine\Coin\Coin;
use VendingMachine\NoChangeAvailable;

class SupplierCoinStash implements CoinStash {

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

	public function supplyCoinForChange(Coin $coin): void {
		$this->availableCoinsForChange[] = $coin;

		usort($this->availableCoinsForChange, 'self::higherCoinValueFirst');
	}

	public static function higherCoinValueFirst(Coin $coin1, Coin $coin2): int {
		// higher value comes first
		return $coin1->inCents() > $coin2->inCents() ? -1 : 1;
	}
}

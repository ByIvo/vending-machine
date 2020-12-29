<?php

namespace VendingMachine;

use VendingMachine\Coin\Coin;

class VendingMachine {

	private $depositedCoins;

	public function depositCoin(Coin $coin): void {
		$this->depositedCoins[] = $coin;
	}

	public function depositedAmount(): float {
		return array_reduce($this->depositedCoins, function (float $sum, Coin $currentCoin) {
			return $sum + $currentCoin->valueOf();
		}, $sumInit = .0);
	}
}

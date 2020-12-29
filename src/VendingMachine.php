<?php

namespace VendingMachine;

use VendingMachine\Coin\Coin;

class VendingMachine {

	private const PRICE_OF_PRODUCTS = [
		'coke' => .25,
		'pepsi' => .35,
		'soda' => .45,
	];

	private $depositedCoins = [];

	public function putCoinInto(Coin $coin): void {
		$this->depositedCoins[] = $coin;
	}

	public function depositedAmount(): float {
		return array_reduce($this->depositedCoins, function (float $sum, Coin $currentCoin) {
			return $sum + $currentCoin->valueOf();
		}, $sumInit = .0);
	}

	public function buy(string $product): VendingMachineOutput {
		if (!$this->hasEnoughMoneyToBuyProduct($product)) {
			throw new NotEnoughMoneyIntoTheMachine();
		}

		return new VendingMachineOutput($product, $changes = []);
	}

	private function hasEnoughMoneyToBuyProduct(string $product): bool {
		return $this->depositedAmount() >= self::PRICE_OF_PRODUCTS[ $product ];
	}
}

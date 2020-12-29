<?php

namespace VendingMachine;

use VendingMachine\Coin\Coin;
use VendingMachine\Product\Coke;
use VendingMachine\Product\Pepsi;
use VendingMachine\Product\Product;
use VendingMachine\Product\Soda;

class VendingMachine {

	private $depositedCoins = [];

	public function putCoinInto(Coin $coin): void {
		$this->depositedCoins[] = $coin;
	}

	public function depositedAmount(): float {
		return array_reduce($this->depositedCoins, function (float $sum, Coin $currentCoin) {
			return $sum + $currentCoin->valueOf();
		}, $sumInit = .0);
	}

	public function buy(string $productCode): VendingMachineOutput {
		$product = $this->getProductFromStashByCode($productCode);

		if (!$this->hasEnoughMoneyToBuyProduct($product)) {
			throw new NotEnoughMoneyIntoTheMachine();
		}

		return new VendingMachineOutput($product, $changes = []);
	}

	private function getProductFromStashByCode(string $productCode): Product {
		$foundProducts = array_filter($this->availableProducts(), function (Product $product) use ($productCode) {
			return $product->code() === $productCode;
		});

		return  array_shift($foundProducts);
	}

	private function availableProducts(): array {
		return [
			new Coke(),
			new Pepsi(),
			new Soda(),
		];
	}

	private function hasEnoughMoneyToBuyProduct(Product $product): bool {
		return $this->depositedAmount() >= $product->price();
	}
}

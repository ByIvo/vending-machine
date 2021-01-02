<?php

namespace Test\VendingMachine;

use VendingMachine\Coin\Coin;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\Product\Coke;
use VendingMachine\Product\Pepsi;
use VendingMachine\Product\Product;
use VendingMachine\Product\Soda;
use VendingMachine\Stash\CoinStash;
use VendingMachine\Stash\ProductStash;

class InfiniteStash implements CoinStash, ProductStash {

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

	public function removeProductFromStash(string $productCode): Product {
		$foundProducts = array_filter($this->availableProducts(), function (Product $product) use ($productCode) {
			return $product->code() === $productCode;
		});

		return array_shift($foundProducts);
	}

	private function availableProducts(): array {
		return [
			new Coke(),
			new Pepsi(),
			new Soda(),
		];
	}
}

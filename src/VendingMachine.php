<?php

namespace VendingMachine;

use VendingMachine\Coin\Coin;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\Product\Coke;
use VendingMachine\Product\Pepsi;
use VendingMachine\Product\Product;
use VendingMachine\Product\Soda;
use VendingMachine\Stash\Stash;

class VendingMachine {

	/** @var Stash */
	private $stash;
	private $depositedCoins;

	public function __construct(Stash $stash) {
		$this->stash = $stash;
		$this->clearDepositedCoins();
	}

	public function putCoinInto(Coin $coin): void {
		$this->depositedCoins[] = $coin;
	}

	public function giveUpOnBuying(): array {
		$depositedCoins = $this->depositedCoins;
		$this->clearDepositedCoins();

		return $depositedCoins;
	}

	public function depositedAmount(): float {
		return array_reduce($this->depositedCoins, function (float $sum, Coin $currentCoin) {
			return $sum + $currentCoin->inCents();
		}, $sumInit = .0);
	}

	public function buy(string $productCode): VendingMachineOutput {
		$product = $this->getProductFromStashByCode($productCode);

		if (!$this->hasEnoughMoneyToBuyProduct($product)) {
			throw new NotEnoughMoneyIntoTheMachine();
		}

		$changes = $this->pickChangesForProduct($product);
		$this->clearDepositedCoins();
		return new VendingMachineOutput($product, $changes);
	}

	private function getProductFromStashByCode(string $productCode): Product {
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

	private function hasEnoughMoneyToBuyProduct(Product $product): bool {
		return $this->depositedAmount() >= $product->priceInCents();
	}

	private function pickChangesForProduct(Product $product): array {
		$refundAmount = $this->depositedAmount() - $product->priceInCents();

		if ($refundAmount > 0) {
			return $this->stash->pickCoinsForAmount($refundAmount);
		}

		return [];
	}

	private function clearDepositedCoins(): void {
		$this->depositedCoins = [];
	}
}

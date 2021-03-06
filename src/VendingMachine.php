<?php

namespace VendingMachine;

use VendingMachine\Coin\Coin;
use VendingMachine\Product\Product;
use VendingMachine\Stash\CoinStash;
use VendingMachine\Stash\ProductStash;

class VendingMachine {

	/** @var CoinStash */
	private $coinStash;
	/** @var ProductStash */
	private $productStash;
	private $depositedCoins;

	public function __construct(CoinStash $coinStash, ProductStash $productStash) {
		$this->coinStash = $coinStash;
		$this->productStash = $productStash;
		$this->clearDepositedCoins();
	}

	public function putCoinInto(Coin $coin): void {
		$this->depositedCoins[] = $coin;
	}

	public function cancelPurchase(): array {
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
		$product = $this->productStash->pickProductFromStash($productCode);

		if (!$this->hasEnoughMoneyToBuyProduct($product)) {
			throw new NotEnoughMoneyIntoTheMachine();
		}

		$changes = $this->pickChangesForProduct($product);

		$this->supplyDepositedAmountForChanges();
		$this->productStash->removeSoldProduct($product);

		return new VendingMachineOutput($product, $changes);
	}

	private function hasEnoughMoneyToBuyProduct(Product $product): bool {
		return $this->depositedAmount() >= $product->priceInCents();
	}

	private function pickChangesForProduct(Product $product): array {
		$refundAmount = $this->depositedAmount() - $product->priceInCents();

		if ($refundAmount > 0) {
			return $this->coinStash->pickCoinsForAmount($refundAmount);
		}

		return [];
	}

	private function supplyDepositedAmountForChanges(): void {
		foreach ($this->depositedCoins as $depositedCoin) {
			$this->coinStash->supplyCoinForChange($depositedCoin);
		}

		$this->clearDepositedCoins();
	}

	private function clearDepositedCoins(): void {
		$this->depositedCoins = [];
	}
}

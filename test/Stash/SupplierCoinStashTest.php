<?php

namespace Test\VendingMachine\Stash;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\Stash\SupplierCoinStash;

class SupplierCoinStashTest extends TestCase {

	/** @test */
	public function shouldPickCoinWhateverTheOrderTheyAreInserted(): void {
		$supplierStash = new SupplierCoinStash();

		$supplierStash->supplyCoinForChange(new Dime());
		$supplierStash->supplyCoinForChange(new Nickle());
		$supplierStash->supplyCoinForChange(new Penny());
		$supplierStash->supplyCoinForChange(new Penny());
		$supplierStash->supplyCoinForChange(new Quarter());
		$supplierStash->supplyCoinForChange(new Penny());

		$changes = $supplierStash->pickCoinsForAmount(43);
		Assert::assertEquals([
			new Quarter(),
			new Dime(),
			new Nickle(),
			new Penny(),
			new Penny(),
			new Penny(),
		], $changes);
	}

	/** @test */
	public function shouldAllowSupplierToClearTheStash(): void {
		$supplierCoinStash = new SupplierCoinStash();
		$supplierCoinStash->supplyCoinForChange(new Quarter());
		$supplierCoinStash->supplyCoinForChange(new Dime());
		$supplierCoinStash->supplyCoinForChange(new Nickle());
		$supplierCoinStash->supplyCoinForChange(new Nickle());

		$coinsFromStash = $supplierCoinStash->clearStash();

		Assert::assertEquals([new Quarter(), new Dime(), new Nickle(), new Nickle()], $coinsFromStash);
	}

}

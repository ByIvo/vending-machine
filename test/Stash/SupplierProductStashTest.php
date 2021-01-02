<?php

namespace Test\VendingMachine\Stash;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use VendingMachine\Product\Coke;
use VendingMachine\Product\Pepsi;
use VendingMachine\Stash\SupplierProductStash;

class SupplierProductStashTest extends TestCase {

	/** @test */
	public function shouldAllowSupplierToClearTheStash(): void {
		$supplierProductStash = new SupplierProductStash();
		$supplierProductStash->supplyProduct(new Coke());
		$supplierProductStash->supplyProduct(new Pepsi());
		$supplierProductStash->supplyProduct(new Pepsi());

		$productsFromStash = $supplierProductStash->clearStash();

		Assert::assertEquals([new Coke(), new Pepsi(), new Pepsi()], $productsFromStash);
	}

}

<?php

namespace VendingMachine\Stash;

use VendingMachine\Product\Product;

interface ProductStash {

	public function pickProductFromStash(string $productCode): Product;
	public function removeSoldProduct(Product $product): void;
}

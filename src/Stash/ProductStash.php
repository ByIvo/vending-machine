<?php

namespace VendingMachine\Stash;

use VendingMachine\Product\Product;

interface ProductStash {

	public function removeProductFromStash(string $productCode): Product;
}

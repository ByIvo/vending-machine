<?php

namespace VendingMachine\Stash;

use VendingMachine\NoProductAvailable;
use VendingMachine\Product\Product;

class SupplierProductStash implements ProductStash {

	/** @var array Product */
	private $availableProductsForPurchase;

	public function __construct() {
		$this->availableProductsForPurchase = [];
	}

	public function pickProductFromStash(string $productCode): Product {
		foreach ($this->availableProductsForPurchase as $availableProduct) {
			if ($availableProduct->code() === $productCode) {
				return $availableProduct;
			}
		}

		throw new NoProductAvailable($productCode);
	}

	public function supplyProduct(Product $product) {
		$this->availableProductsForPurchase[] = $product;
	}

	public function removeSoldProduct(Product $product): void {
		$productKeys = array_keys($this->availableProductsForPurchase, $product, $strict = true);
		$index = array_shift($productKeys);
		unset($this->availableProductsForPurchase[$index]);
	}
}

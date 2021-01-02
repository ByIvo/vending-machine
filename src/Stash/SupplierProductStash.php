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

	public function removeProductFromStash(string $productCode): Product {
		foreach ($this->availableProductsForPurchase as $index => $availableProduct) {
			if ($availableProduct->code() === $productCode) {
				unset($this->availableProductsForPurchase[$index]);
				return $availableProduct;
			}
		}

		throw new NoProductAvailable($productCode);
	}

	public function supplyProduct(Product $product) {
		$this->availableProductsForPurchase[] = $product;
	}
}

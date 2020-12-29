<?php

namespace VendingMachine\Product;

class Coke implements Product {

	public function priceInCents(): int {
		return 25;
	}

	public function code(): string {
		return 'coke';
	}
}

<?php

namespace VendingMachine\Product;

class Coke implements Product {

	public function price(): float {
		return 0.25;
	}

	public function priceInCents(): int {
		return 25;
	}

	public function code(): string {
		return 'coke';
	}
}

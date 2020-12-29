<?php

namespace VendingMachine\Product;

class Pepsi implements Product {

	public function price(): float {
		return 0.35;
	}

	public function priceInCents(): int {
		return 35;
	}

	public function code(): string {
		return 'pepsi';
	}
}

<?php

namespace VendingMachine\Product;

class Soda implements Product {

	public function priceInCents(): int {
		return 45;
	}

	public function code(): string {
		return 'soda';
	}
}

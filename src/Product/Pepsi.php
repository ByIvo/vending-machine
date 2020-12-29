<?php

namespace VendingMachine\Product;

class Pepsi implements Product {

	public function priceInCents(): int {
		return 35;
	}

	public function code(): string {
		return 'pepsi';
	}
}

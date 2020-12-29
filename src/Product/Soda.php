<?php

namespace VendingMachine\Product;

class Soda implements Product {

	public function price(): float {
		return 0.45;
	}

	public function code(): string {
		return 'soda';
	}
}

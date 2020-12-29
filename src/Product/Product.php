<?php

namespace VendingMachine\Product;

interface Product {

	public function priceInCents(): int;
	public function code(): string;

}

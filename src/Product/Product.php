<?php

namespace VendingMachine\Product;

interface Product {

	/** @deprecated */
	public function price(): float;
	public function priceInCents(): int;
	public function code(): string;

}

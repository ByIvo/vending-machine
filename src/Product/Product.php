<?php

namespace VendingMachine\Product;

interface Product {

	public function price(): float;
	public function code(): string;

}

<?php

namespace VendingMachine\Coin;

interface Coin {

	public const PENNY = 0.01;
	public const NICKEL = 0.05;
	public const DIME = 0.10;
	public const QUARTER = 0.25;

	public function valueOf(): float;
}

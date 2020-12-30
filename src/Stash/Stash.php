<?php

namespace VendingMachine\Stash;

interface Stash {

	public function pickCoinsForAmount(int $amount): array;
}

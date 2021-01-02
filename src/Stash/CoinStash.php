<?php

namespace VendingMachine\Stash;

interface CoinStash {

	public function pickCoinsForAmount(int $amount): array;
}

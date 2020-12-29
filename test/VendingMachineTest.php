<?php

namespace Test\VendingMachine;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use VendingMachine\Coin\Coin;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\VendingMachine;

class VendingMachineTest extends TestCase {

	public function provideAcceptableCoins(): iterable {
		yield 'penny' => [new Penny(), 0.01];
		yield 'nickle' => [new Nickle(), 0.05];
		yield 'dime' => [new Dime(), 0.10];
		yield 'quarter' => [new Quarter(), 0.25];
	}

	/**
	 * @test @dataProvider provideAcceptableCoins
	 */
	public function shouldAcceptCoins(Coin $coin, float $expectedValue): void {
		$vendingMachine = new VendingMachine();

		$vendingMachine->depositCoin($coin);

		Assert::assertEquals($expectedValue, $vendingMachine->depositedAmount());
	}

	/** @test */
	public function shouldAccumulateDepositedCoins(): void {
		$vendingMachine = new VendingMachine();

		$vendingMachine->depositCoin(new Nickle());
		Assert::assertEquals(.05, $vendingMachine->depositedAmount());

		$vendingMachine->depositCoin(new Quarter());
		Assert::assertEquals(.30, $vendingMachine->depositedAmount());

		$vendingMachine->depositCoin(new Penny());
		Assert::assertEquals(.31, $vendingMachine->depositedAmount());
	}

}

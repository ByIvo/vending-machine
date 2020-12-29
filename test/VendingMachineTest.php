<?php

namespace Test\VendingMachine;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use VendingMachine\Coin\Coin;
use VendingMachine\Coin\Dime;
use VendingMachine\Coin\Nickle;
use VendingMachine\Coin\Penny;
use VendingMachine\Coin\Quarter;
use VendingMachine\NotEnoughMoneyIntoTheMachine;
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

		$vendingMachine->putCoinInto($coin);

		Assert::assertEquals($expectedValue, $vendingMachine->depositedAmount());
	}

	/** @test */
	public function shouldAccumulateDepositedCoins(): void {
		$vendingMachine = new VendingMachine();

		$vendingMachine->putCoinInto(new Nickle());
		Assert::assertEquals(.05, $vendingMachine->depositedAmount());

		$vendingMachine->putCoinInto(new Quarter());
		Assert::assertEquals(.30, $vendingMachine->depositedAmount());

		$vendingMachine->putCoinInto(new Penny());
		Assert::assertEquals(.31, $vendingMachine->depositedAmount());
	}

	/** @test */
	public function shouldAllowCustomerToBuyACoke(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());

		$vendingMachineOutput = $vendingMachine->buy('coke');

		Assert::assertEquals('coke', $vendingMachineOutput->product());
		Assert::assertEmpty($vendingMachineOutput->changes());
	}

	/** @test */
	public function shouldAllowCustomerToBuyAPepsi(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Dime());

		$vendingMachineOutput = $vendingMachine->buy('pepsi');

		Assert::assertEquals('pepsi', $vendingMachineOutput->product());
		Assert::assertEmpty($vendingMachineOutput->changes());
	}

	/** @test */
	public function shouldAllowCustomerToBuyASoda(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Nickle());

		$vendingMachineOutput = $vendingMachine->buy('soda');

		Assert::assertEquals('soda', $vendingMachineOutput->product());
		Assert::assertEmpty($vendingMachineOutput->changes());
	}

	/** @test */
	public function shouldNotSellCokeIfThereIsNotEnoughMoney(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Dime());

		$this->expectException(NotEnoughMoneyIntoTheMachine::class);
		$this->expectExceptionMessage('There is not enough money to buy it');
		$vendingMachine->buy('coke');
	}

	/** @test */
	public function shouldNotSellPepsiIfThereIsNotEnoughMoney(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Nickle());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Penny());

		$this->expectException(NotEnoughMoneyIntoTheMachine::class);
		$this->expectExceptionMessage('There is not enough money to buy it');
		$vendingMachine->buy('pepsi');
	}

	/** @test */
	public function shouldNotSellSodaIfThereIsNotEnoughMoney(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Dime());
		$vendingMachine->putCoinInto(new Nickle());

		$this->expectException(NotEnoughMoneyIntoTheMachine::class);
		$this->expectExceptionMessage('There is not enough money to buy it');
		$vendingMachine->buy('soda');
	}
}

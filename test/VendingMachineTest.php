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
use VendingMachine\Product\Coke;
use VendingMachine\Product\Pepsi;
use VendingMachine\Product\Soda;
use VendingMachine\VendingMachine;

class VendingMachineTest extends TestCase {

	public function provideAcceptableCoins(): iterable {
		yield 'penny' => [new Penny(), 1];
		yield 'nickle' => [new Nickle(), 5];
		yield 'dime' => [new Dime(), 10];
		yield 'quarter' => [new Quarter(), 25];
	}

	/**
	 * @test @dataProvider provideAcceptableCoins
	 */
	public function shouldAcceptCoins(Coin $coin, float $expectedCents): void {
		$vendingMachine = new VendingMachine();

		$vendingMachine->putCoinInto($coin);

		Assert::assertEquals($expectedCents, $vendingMachine->depositedAmount());
	}

	/** @test */
	public function shouldAccumulateDepositedCoins(): void {
		$vendingMachine = new VendingMachine();

		$vendingMachine->putCoinInto(new Nickle());
		Assert::assertEquals(5, $vendingMachine->depositedAmount());

		$vendingMachine->putCoinInto(new Quarter());
		Assert::assertEquals(30, $vendingMachine->depositedAmount());

		$vendingMachine->putCoinInto(new Penny());
		Assert::assertEquals(31, $vendingMachine->depositedAmount());
	}

	/** @test */
	public function shouldAllowCustomerToBuyACoke(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());

		$vendingMachineOutput = $vendingMachine->buy('coke');

		Assert::assertEquals(new Coke(), $vendingMachineOutput->product());
		Assert::assertEmpty($vendingMachineOutput->changes());
	}

	/** @test */
	public function shouldAllowCustomerToBuyAPepsi(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Dime());

		$vendingMachineOutput = $vendingMachine->buy('pepsi');

		Assert::assertEquals(new Pepsi(), $vendingMachineOutput->product());
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

		Assert::assertEquals(new Soda(), $vendingMachineOutput->product());
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

	/** @test */
	public function shouldClearDepositedAmountAfterBuyingSomeProduct(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->buy('coke');

		$this->expectException(NotEnoughMoneyIntoTheMachine::class);
		$vendingMachine->buy('coke');
	}

	/** @test */
	public function shouldOutputTheProductWithChanges_WhenAmountOverflowTheProductPrice(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Quarter());

		$vendingMachineOutput = $vendingMachine->buy('soda');
		Assert::assertEquals([new Nickle()], $vendingMachineOutput->changes());

		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Nickle());

		$vendingMachineOutput = $vendingMachine->buy('coke');
		Assert::assertEquals([new Nickle(), new Penny(), new Penny()], $vendingMachineOutput->changes());
	}

	/** @test */
	public function shouldAllowCustomerToRequestARefundBeforeBuyingAProduct(): void {
		$vendingMachine = new VendingMachine();
		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Quarter());

		Assert::assertEquals([new Quarter(), new Quarter()], $vendingMachine->giveUpOnBuying());
		Assert::assertEquals(0, $vendingMachine->depositedAmount());

		$vendingMachine->putCoinInto(new Quarter());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Penny());
		$vendingMachine->putCoinInto(new Nickle());

		Assert::assertEquals([new Quarter(), new Penny(), new Penny(), new Nickle()], $vendingMachine->giveUpOnBuying());
		Assert::assertEquals(0, $vendingMachine->depositedAmount());
	}
}
